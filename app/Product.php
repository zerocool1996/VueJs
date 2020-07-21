<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes;
    protected $table= "products";
    protected $dates = ['deleted_at'];
    protected $appends = ['image'];
	protected $fillable = [
        'name',
        'img',
        'price',
        'content',
        'status',
        'author_id',
        'slide',
        'saled'
    ];
    public function productCategory() {
        return $this->belongsToMany('App\Category', 'category_product', 'product_id', 'category_id')->withTimestamps();
    }

    public function productAuthor(){
        return $this->belongsTo('App\Author', 'author_id', 'id')->withDefault(['name' => 'Chưa biết']);
    }

    public function getImageAttribute()
    {
        if (isset($this->attributes['img'])) {
            return Storage::disk('public')->url(
                $this->attributes['img']
            );
        }
        return null;
    }

    public function orders() {
        return $this->belongsToMany(Order::class, 'order_detail');
    }
}
