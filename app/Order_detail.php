<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    protected $table    = "order_detail";
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity'
    ];
    public function orderdetailProduct(){
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
