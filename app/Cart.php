<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "carts";
    protected $fillable = [
        'user_id'
    ];
    public function cartUser() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function cartProduct() {
        return $this->belongsToMany('App\Product', 'cart_product', 'cart_id', 'product_id', 'id')->withTimestamps();
    }
}
