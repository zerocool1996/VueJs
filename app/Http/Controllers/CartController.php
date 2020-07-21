<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Cart;
use App\Cart_Product;
use App\Product;
use App\Http\Requests\CartRequest;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cart, $cart_product, $user;
    public function __construct(Cart $cart, Cart_product $cart_Product)
    {
        $this->user = Auth::user();
        $this->cart = $cart;
        $this->cart_product = $cart_Product;
    }

    public function init(Request $request)
    {
        $user_cart = $this->user->userCart;
        if($request->cart != null){
            $cart = $request->cart;
            foreach($cart as $item){
                $user_cart->cartProduct()->syncWithoutDetaching([
                    $item['id'] => ['quantity' => $item['quantity']]
                ]);
            }
        }
        return response()->json([
            'cart' => Cart_Product::with('product')->where('cart_id', $user_cart->id)->get()
        ]);
    }

    public function addProduct($id){
        $product = $this->cart_product->where(['cart_id' => $this->user->userCart->id, 'product_id' => $id])->first();
        $num = isset($product) ? $product->count() : 0;
        if($num == 0){
            $this->user->userCart->cartProduct()->attach([ $id => ['quantity' => 1] ]);
            return response()->json([
                'message' => 'Thêm mới sản phẩm thành công !',
                'code'    => 1
            ]);
        }else{
            $product->update([
                'quantity' => $product->quantity +1
            ]);
            return response()->json([
                'message' => 'Thêm sản phẩm thành công !'
            ]);

        }

    }

    public function deleteProduct($id){
        $cart_products = $this->user->userCart->cartProduct()->detach($id);
        return response()->json([
            'message' => 'Xóa sản phẩm thành công !'
        ]);
    }

    public function reset(){
        $this->user->userCart->cartProduct()->sync([]);
        return response()->json([
            'message' => 'Reset giỏ hàng thành công !'
        ]);
    }

    public function plus($id, $quantity){
        $cart = $this->user->userCart->cartProduct()->updateExistingPivot($id, [
            'quantity' => $quantity
        ]);
        return response()->json([
            'message' => 'Tăng số lượng sản phẩm thành công !'
        ]);
    }

    public function minus($id, $quantity){
        $cart = $this->user->userCart->cartProduct()->updateExistingPivot($id, [
            'quantity' => $quantity
        ]);
        return response()->json([
            'message' => 'Giảm số lượng sản phẩm thành công !'
        ]);
    }
}
