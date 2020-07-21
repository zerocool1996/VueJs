<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_Product extends Model
{
    //
    protected $table = "category_product";
    protected $fillable = [
        'product_id',
        'catalog_id'
    ];
}
