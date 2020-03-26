<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::query()->truncate();
        Permission::query()->create([
            'name' => 'Manage Settings',
            'slug' => 'settings',
            'description' => 'Manage Settings',
        ]);
        Permission::query()->create([
            'name' => 'Dashboard',
            'slug' => 'dashboard',
            'description' => 'Dashboard',
        ]);

        // user management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage Users',
            'slug' => 'users',
            'description' => 'Manage Users',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create Users',
                'slug' => 'users.create',
                'description' => 'Create Users',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update Users',
                'slug' => 'users.update',
                'description' => 'Update Users',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View Users',
                'slug' => 'users.view',
                'description' => 'View Users',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete Users',
                'slug' => 'users.delete',
                'description' => 'Delete Users',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show Users',
                'slug' => 'users.show',
                'description' => 'Show Users',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Manage Roles',
                'slug' => 'users.roles',
                'description' => 'Manage Roles',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Manage Permissions',
                'slug' => 'users.permissions',
                'description' => 'Manage Permissions',
            ]);
        }

        // leaves management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage Leaves',
            'slug' => 'leaves',
            'description' => 'Manage Leaves',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create Leaves',
                'slug' => 'leaves.create',
                'description' => 'Create Leaves',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update Leaves',
                'slug' => 'leaves.update',
                'description' => 'Update Leaves',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View Leaves',
                'slug' => 'leaves.view',
                'description' => 'View Leaves',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete Leaves',
                'slug' => 'leaves.delete',
                'description' => 'Delete Leaves',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show Leaves',
                'slug' => 'leaves.show',
                'description' => 'Show Leaves',
            ]);


            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Apply for Leaves',
                'slug' => 'leaves.apply',
                'description' => 'Apply for Leaves',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Verify Leave Applications',
                'slug' => 'leaves.applications.verify',
                'description' => 'Verify Leave Applications',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Return Leave Applications',
                'slug' => 'leaves.applications.return',
                'description' => 'Return Leave Applications',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Approve Leave Applications',
                'slug' => 'leaves.applications.approve',
                'description' => 'Approve Leave Applications',
            ]);

            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Decline Leave Applications',
                'slug' => 'leaves.applications.decline',
                'description' => 'Decline Leave Applications',
            ]);

            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Grant Leave Applications',
                'slug' => 'leaves.applications.grant',
                'description' => 'Grant Leave Applications',
            ]);

            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Reject Leave Applications',
                'slug' => 'leaves.applications.reject',
                'description' => 'Reject Leave Applications',
            ]);

            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Expire Leave Applications',
                'slug' => 'leaves.applications.expire',
                'description' => 'Expire Leave Applications',
            ]);

            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete Leave Applications',
                'slug' => 'leaves.applications.delete',
                'description' => 'Delete Leave Applications',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Edit Leave Applications',
                'slug' => 'leaves.applications.edit',
                'description' => 'Edit Leave Applications',
            ]);
        }

    }
}
