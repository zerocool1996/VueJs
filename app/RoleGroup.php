<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleGroup extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_group';
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'roles_id',
        'description',
    ];

    protected $dateFormat = 'U';

    protected $casts = [
        'roles_id' => 'array',
    ];
}
