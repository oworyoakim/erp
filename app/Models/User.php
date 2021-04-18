<?php

namespace App\Models;

use App\Traits\Addressable;
use App\Traits\Contactable;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use stdClass;

class User extends EloquentUser
{
    use SoftDeletes, Contactable, Addressable;

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
        'avatar',
    ];

    protected $loginNames = ['email', 'username'];
    protected $dates = ['last_login'];

    protected static $rolesModel = Role::class;

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_users');
    }

    public function fullName()
    {
        return "{$this->first_name}  {$this->last_name}";
    }

    public function getDetails()
    {
        $user = new stdClass();
        $user->id = $this->id;
        $user->firstName = $this->first_name;
        $user->lastName = $this->last_name;
        $user->fullName = $this->fullName();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = null;
        $user->avatar = $this->avatar;
        $role = $this->roles()->first();
        if ($role)
        {
            $user->roleId = $role->id;
            $user->role = $role->getDetails();
        } else
        {
            $user->roleId = null;
            $user->role = null;
        }
        $user->permissions = array_keys($this->getPermissions());
        if ($this->last_login)
        {
            $user->lastLogin = $this->last_login->toDateTimeString();
        } else
        {
            $user->lastLogin = null;
        }
        $user->active = Activation::completed($this);
        return $user;
    }

    public function activate()
    {
        $activation = Activation::create($this);

        return Activation::complete($this, $activation->code);
    }

    public function deactivate()
    {
        // first delete all active sessions for this user
        $this->persistences()->delete();
        // deactivate the user
        return $this->activations()->delete();
    }

    public function getPasswordResetReminderCode($refresh = false)
    {
        $reminder = $this->reminders()
                         ->where('completed', false)
                         ->whereNull('completed_at')
                         ->first();
        if (!$reminder)
        {
            if (!$refresh)
            {
                return null;
            }
            $reminder = Reminder::create($this);
        }
        return $reminder->code;
    }

    /**
     * @param string $module
     *
     * @return bool
     */
    public function canAccessModule(string $module)
    {
        return $this->modules()->where(['slug' => $module])->count() > 0;
    }

}
