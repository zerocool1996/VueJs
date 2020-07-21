<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart_Product extends Model
{
    protected $table = "cart_product";
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity'
    ];

    public function product(){
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
