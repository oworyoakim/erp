<?php

namespace App\Models;

use App\Traits\Addressable;
use App\Traits\Contactable;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends EloquentUser
{
    use SoftDeletes,Contactable,Addressable;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'username',
        'password',
        'permissions',
    ];

    protected $loginNames = ['email','username'];

    protected static $rolesModel = Role::class;

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

}
