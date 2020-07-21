<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table    = "orders";
    protected $appends = ['trangthai'];
    protected $fillable = [
        'user_id',
        'message',
        'status',
        "amount",
        "vnp_BankCode",
        "vnp_CardType",
        "vnp_OrderInfo",
        "vnp_PayDate",
        "vnp_TransactionNo",
        "vnp_TxnRef",
        'type',
    ];

    public function orderUser()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function orderDetail()
    {
        return $this->hasMany('App\Order_detail', 'order_id', 'id');
    }

    public function getTypeAttribute($value){
        if($this->attributes['type'] == 1){
            return 'Offline';
        }else{
            return 'Online';
        }
    }

    public function getTrangthaiAttribute($value){
            return $this->attributes['status'];
    }

    public function getStatusAttribute($value){
        if($this->attributes['status'] == 0){
            return 'Chờ xử lí';
        }elseif($this->attributes['status'] == 1){
            return 'Đã xác nhận';
        }elseif($this->attributes['status'] == 2){
            return 'Đang giao hàng';
        }
        elseif($this->attributes['status'] == 3){
            return 'Đã thanh toán';
        }
        elseif($this->attributes['status'] == 4){
            return 'Khách hàng hủy';
        }
        elseif($this->attributes['status'] == 5){
            return 'Người bán hủy';
        }
        elseif($this->attributes['status'] == 5){
            return 'Giao hàng không thàng công';
        }
    }

    // public function products() {
    //     return $this->belongsToMany(Product::class, 'order_detail', 'order_id', 'product_id', 'id');
    // }

}
