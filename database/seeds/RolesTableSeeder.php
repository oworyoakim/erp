<?php

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Role::query()->truncate();

        $permissions = [
            "profile.others" => true,
            "profile.own" => true,
            "profile.edit_own" => true,
            "dashboard" => true,
            "leaves" => true,
            "leaves.apply" => true,
        ];

        Role::query()->updateOrCreate(['slug' => 'admin'], [
            'name' => 'Admin',
            'permissions' => [
                "profile.others" => true,
                "profile.own" => true,
                "profile.edit_own" => true,
                "users" => true,
                "users.view" => true,
                "users.create" => true,
                "users.update" => true,
                "users.delete" => true,
                "users.show" => true,
                "users.roles" => true,
                "settings" => true,
                "dashboard" => true,
                "leaves" => true,
                "leaves.create" => true,
            ],
        ]);
        Role::query()->updateOrCreate(['slug' => 'employee'], [
            'name' => 'Employee',
            'permissions' => $permissions,
        ]);

        Role::query()->updateOrCreate(['slug' => 'executive-secretary'], [
            'name' => 'Executive Secretary',
            'permissions' => $permissions,
        ]);

        Role::query()->updateOrCreate(['slug' => 'director'], [
            'name' => 'Director',
            'permissions' => $permissions,
        ]);

        Role::query()->updateOrCreate(['slug' => 'manager'], [
            'name' => 'Manager',
            'permissions' => $permissions,
        ]);

        Role::query()->updateOrCreate(['slug' => 'principal'], [
            'name' => 'Principal',
            'permissions' => $permissions,
        ]);

        Role::query()->updateOrCreate(['slug' => 'senior'], [
            'name' => 'Senior',
            'permissions' => $permissions,
        ]);
    }
}
