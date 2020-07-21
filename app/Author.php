<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Author extends Model
{
    use SoftDeletes;
    protected $table= "authors";
    protected $dates = ['deleted_at'];
	protected $fillable = [
        'name',
        'img',
        'story',
        'des'
    ];
    public function AuthorProduct() {
        return $this->hasMany('App\Product', 'author_id', 'id');
    }
    public function getImgAttribute()
    {
        if (isset($this->attributes['img'])) {
            return Storage::disk('public')->url(
                $this->attributes['img']
            );
        }
        return null;
    }
}
