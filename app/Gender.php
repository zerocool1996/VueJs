<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = "gender";
    protected $fillable = [
        'name'
    ];
    public function GenderAdmin(){
        return $this->hasMany('App\Admin','gender_id');
    }
}
