<?php

namespace App;
use App\Notification;
use App\Notifications\UserOrder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;
    protected $table = "users";
    protected $guard = "admin";
    protected $dates = ['deleted_at'];
    protected $appends  = ['fullname','sex'];
	protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'address',
        'tel',
        'gender_id',
        'img',
        'role',
        'role_group_id'
    ];
    protected $hidden = [
        'password'
    ];
    public function AdminGender() {
        return $this->belongsTo('App\Gender', 'gender_id');
    }

    public function userCart(){
        return $this->hasOne('App\Cart', 'user_id', 'id');
    }

    public function userOrder(){
        return $this->hasMany('App\Order', 'user_id', 'id');
    }

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isSuperUser(){
        return (boolean) $this->super_user;
    }

    public function userRoleGroup(){
        return $this->belongsTo('App\RoleGroup', 'role_group_id', 'id')->withDefault(['roles_id' => [0] ]);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getImgAttribute(){
        if (isset($this->attributes['img'])) {
            return Storage::disk('public')->url(
                $this->attributes['img']
            );
        }
        return null;
    }

    public function getFullnameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }

    public function getSexAttribute(){
        if($this->attributes['gender_id'] == 1) {
            return 'Nam';
        }elseif ($this->attributes['gender_id'] == 2) {
            return 'Nữ';
        }else {
            return 'Chưa biết';
        }
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
