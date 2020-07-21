<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notification;
use App\Notifications\ConfirmOrder;
use App\Notifications\CancelOrder;
use App\Order;
use Carbon\Carbon;
use App\Product;
use App\User;


class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return response()->json([
            'orders' => $orders
        ]);
    }

    public function detail($id)
    {
        $order = Order::with(['orderUser', 'orderDetail' => function ($query) {
            $query->with('orderdetailProduct');
        }])->find($id);
        return response()->json([
            'data' => $order
        ]);
    }

    public function type($type)
    {
        $orders = Order::where('type', $type)->latest()->paginate(10);
        return response()->json([
            'orders' => $orders
        ]);
    }

    public function confirm($id)
    {
        $order = Order::find($id);
        $order->update([
            'status' => 1
        ]);
        return response()->json([
            'message' => 'Xác nhân đơn hàng thành công'
        ]);
    }

    public function cancel($id, $userid)
    {
        if ($this->user->can('update', Order::class)) {
            $order = $this->order->find($id);
            if ($order->orderUser->id == $userid && ($order->status == 0 || $order->status == 1)) {
                $order->update([
                    'status' => 5
                ]);
                $user = $this->user->find($userid);
                $user->notify(new CancelOrder($order));
                return redirect()->back()->with('success_msg', 'Đơn hàng đã hủy !');
            } else {
                return redirect()->back()->withErrors('Có gì đó không đúng !');
            }
        } else {
            return redirect()->back()->withErrors('Bạn không đủ thẩm quyền !');
        }
    }

    public function status(Request $request)
    {
        if ($this->user->can('view', Order::class)) {
            if ($request->ajax()) {
                switch ($request->type) {
                    case 0:
                        $orders = $this->order->where("status", 0)->paginate(15);
                        return view('admin.ajax-view.order', compact('orders'))->render();
                        break;
                    case 1:
                        $orders = $this->order->where("status", 1)->paginate(15);
                        return view('admin.ajax-view.order', compact('orders'))->render();
                        break;
                    case 2:
                        $orders = $this->order->where("status", 2)->paginate(15);
                        return view('admin.ajax-view.order', compact('orders'))->render();
                        break;
                    case 3:
                        $orders = $this->order->where("status", 3)->paginate(15);
                        return view('admin.ajax-view.order', compact('orders'))->render();
                        break;
                    case 4:
                        $orders = $this->order->where("status", 4)->paginate(15);
                        return view('admin.ajax-view.order', compact('orders'))->render();
                        break;
                    case 5:
                        $orders = $this->order->where("status", 5)->paginate(15);
                        return view('admin.ajax-view.order', compact('orders'))->render();
                        break;
                    case 6:
                        $orders = $this->order->where("status", 6)->paginate(15);
                        return view('admin.ajax-view.order', compact('orders'))->render();
                        break;
                    default:
                        $orders = $this->order->paginate(15);
                        return view('admin.ajax-view.order', compact('orders'))->render();
                }
            }
        } else {
            return redirect()->back()->withErrors('Bạn không đủ thẩm quyền !');
        }
    }

    public function transport($id, $userid)
    {
        if ($this->user->can('update', Order::class)) {
            $order = $this->order->find($id);
            if ($order->orderUser->id == $userid && $order->status == 1) {
                $order->status = 2;
                $order->save();
                return redirect()->back()->with('success_msg', 'Cập nhật đơn hàng thành công !');
            } else {
                return redirect()->back()->withErrors('Có gì đó không đúng !');
            }
        } else {
            return redirect()->back()->withErrors('Bạn không đủ thẩm quyền !');
        }
    }
    public function transportFail($id, $userid)
    {
        if ($this->user->can('update', Order::class)) {
            $order = $this->order->find($id);
            if ($order->orderUser->id == $userid && $order->status == 2) {
                $order->update([
                    "status" => 6
                ]);
                return redirect()->back()->with('success_msg', 'Cập nhật đơn hàng thành công !');
            } else {
                return redirect()->back()->withErrors('Có gì đó không đúng !');
            }
        } else {
            return redirect()->back()->withErrors('Bạn không đủ thẩm quyền !');
        }
    }
    public function pay($id, $userid)
    {
        if ($this->user->can('update', Order::class)) {
            $order = $this->order->find($id);
            if ($order->orderUser->id == $userid && $order->status == 2) {
                $order->update([
                    "status" => 3
                ]);
                return redirect()->back()->with('success_msg', 'Cập nhật đơn hàng thành công !');
            } else {
                return redirect()->back()->withErrors('Có gì đó không đúng !');
            }
        } else {
            return redirect()->back()->withErrors('Bạn không đủ thẩm quyền !');
        }
    }
}
