<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\Cart;
use App\Notification;
use App\Notifications\UserOrder;
use App\Notifications\UserCancelOrder;
use App\Http\Requests\OrderRequest;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $order, $product, $cart;
    public function __construct(Order $order, Product $product, Cart $cart)
    {
        $this->cart     = $cart;
        $this->product  = $product;
        $this->order    = $order;
    }



    public function ordered()
    {
        $user = Auth::user();
        $orders = $user->userOrder()->latest()->paginate(15);
        return response()->json([
            'orders' => $orders
        ]);
    }

    public function store(OrderRequest $request)
    {
        $user  = Auth::user();
        $order = $this->order->create([
            'user_id' => $user->id,
            'type'      => $request->type
        ]);
        if($request->type == 2){
        // DB::beginTransaction();

        // try {

            $order->update([
                'status'        => 3,
                'vnp_TxnRef'    => $request->order['vnp_TxnRef'],
                'amount'        => $request->order['vnp_Amount'],
                'message'       => $request->order['vnp_OrderInfo'],
                'vnp_BankCode'  => $request->order['vnp_BankCode'],
                'vnp_CardType'  => $request->order['vnp_CardType'],
                'vnp_TransactionNo' => $request->order['vnp_TransactionNo'],
                'vnp_PayDate'   => $request->order['vnp_PayDate'],
            ]);

        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'message' => "luu don hang ko thanh cong"
        //     ]);
        // }
        }else{
            $order->update([
                'status'        => 0,
                'amount'        => $request->amount,
                'message'       => $request->message,
            ]);
        }
        foreach ($request->cart as $cart) {
            $order->orderDetail()->create([
                'product_id' => $cart['id'],
                'price'      => $cart['price'],
                'quantity'   => $cart['quantity']
            ]);
            $product = $this->product->find($cart['id']);
            $product->saled = $product->saled + $cart['quantity'];
            $product->save();
        }
        return response()->json([
            'message' => "luu don hang thanh cong"
        ]);
    }

    public function detail($id)
    {
        $data = Order::with(['orderDetail' => function ($query) {
            $query->with('orderdetailProduct');
        }])->find($id);
        return response()->json([
            'data' => $data
        ]);
    }

    public function cancel($id)
    {
        $user  = Auth::user();
        $order = $user->userOrder()->find($id);
        if ($order) {
            $order->update([
                'status' => 4
            ]);
            $user->notify(new UserCancelOrder($order));
            return redirect()->back();
        } else {
            return redirect()->back()->with('error_message', 'Có gì đó lỗi !');
        }
    }

    public function vnpay(Request $request)
    {
        //Tải code demo tại: https://goo.gl/4mjkd2

        $vnp_TmnCode = "C5VGU62I";
        $vnp_HashSecret = "DNCYYHGFJJXPNDFIFZZFDNDCJNASPXAH";
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_ReturnUrl = "http://localhost/day2006/public/index#/return";

        $vnp_TxnRef = date('YmdHis');
        $vnp_OrderInfo = $request->input('content');
        $vnp_Amount = $request->input('total') * 100;
        $vnp_IpAddr = $request->ip();
        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => 'vn',
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }
        return response()->json([
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        ]);
    }
}
