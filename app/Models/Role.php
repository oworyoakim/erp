<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use stdClass;

class Role extends EloquentRole
{
    use SoftDeletes;

    protected static $usersModel = User::class;

    public function getDetails()
    {
        $role = new stdClass();
        $role->id = $this->id;
        $role->name = $this->name;
        $role->slug = $this->slug;
        $role->type = $this->type;
        $role->description = $this->description;
        $role->permissions = array_keys($this->getPermissions());
        return $role;
    }
}
