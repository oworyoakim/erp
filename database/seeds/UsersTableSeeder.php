<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ($role = Sentinel::findRoleBySlug('admin'))
        {
            // seed admin user
            $credentials = [
                'first_name' => 'Kim',
                'last_name' => 'Dev',
                'email' => 'admin@erp.kim',
                'username' => 'admin',
                'password' => 'admin',
                'avatar' => '/storage/images/avatar.png',
            ];

            $user = Sentinel::registerAndActivate($credentials);

            $role->users()->attach($user);
        }
    }
}
