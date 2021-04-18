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
            'name' => 'Manage Leave Applications Approval Settings',
            'slug' => 'settings.leaves.applications.approvals',
            'description' => 'Manage Leave Applications Approval Settings',
        ]);
        Permission::query()->create([
            'name' => 'Manage Modules Access Settings',
            'slug' => 'settings.modules',
            'description' => 'Manage Modules Access Settings',
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
                'name' => 'Activate Users',
                'slug' => 'users.activate',
                'description' => 'Activate Users',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Deactivate Users',
                'slug' => 'users.deactivate',
                'description' => 'Deactivate Users',
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
                'name' => 'Recall Leaves',
                'slug' => 'leaves.recall',
                'description' => 'Recall Leaves',
            ]);
             Permission::query()->create([
                        'parent_id' => $perm->id,
                        'name' => 'Leaves history',
                        'slug' => 'leaves.history',
                        'description' => 'Leaves history',
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

        // leave types management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage leave types',
            'slug' => 'leaves.types',
            'description' => 'Manage leave types',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create leave types',
                'slug' => 'leaves.types.create',
                'description' => 'Create leave types',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update leave types',
                'slug' => 'leaves.types.update',
                'description' => 'Update leave types',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View leave types',
                'slug' => 'leaves.types.view',
                'description' => 'View leave types',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete leave types',
                'slug' => 'leaves.types.delete',
                'description' => 'Delete leave types',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show leave types',
                'slug' => 'leaves.types.show',
                'description' => 'Show leave types',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Activate leave types',
                'slug' => 'leaves.types.activate',
                'description' => 'Activate leave types',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Deactivate leave types',
                'slug' => 'leaves.types.deactivate',
                'description' => 'Deactivate leave types',
            ]);
        }

        // leave policies management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage leave policies',
            'slug' => 'leaves.policies',
            'description' => 'Manage leave policies',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create leave policies',
                'slug' => 'leaves.policies.create',
                'description' => 'Create leave policies',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update leave policies',
                'slug' => 'leaves.policies.update',
                'description' => 'Update leave policies',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View leave policies',
                'slug' => 'leaves.policies.view',
                'description' => 'View leave policies',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete leave policies',
                'slug' => 'leaves.policies.delete',
                'description' => 'Delete leave policies',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show leave policies',
                'slug' => 'leaves.policies.show',
                'description' => 'Show leave policies',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Activate leave policies',
                'slug' => 'leaves.policies.activate',
                'description' => 'Activate leave policies',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Deactivate leave policies',
                'slug' => 'leaves.policies.deactivate',
                'description' => 'Deactivate leave policies',
            ]);
        }

        // salary_scales management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage salary scales',
            'slug' => 'salary_scales',
            'description' => 'Manage salary scales',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create salary scales',
                'slug' => 'salary_scales.create',
                'description' => 'Create salary scales',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update salary scales',
                'slug' => 'salary_scales.update',
                'description' => 'Update salary scales',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View salary scales',
                'slug' => 'salary_scales.view',
                'description' => 'View salary scales',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete salary scales',
                'slug' => 'salary_scales.delete',
                'description' => 'Delete salary scales',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show salary scales',
                'slug' => 'salary_scales.show',
                'description' => 'Show salary scales',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Activate  salary scales',
                'slug' => 'salary_scales.activate',
                'description' => 'Activate salary scales',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Deactivate salary scales',
                'slug' => 'salary_scales.deactivate',
                'description' => 'Deactivate salary scales',
            ]);
        }

        // activities management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage Activities',
            'slug' => 'activities',
            'description' => 'Manage Activities',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create activities',
                'slug' => 'activities.create',
                'description' => 'Create activities',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update activities',
                'slug' => 'activities.update',
                'description' => 'Update activities',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View activities',
                'slug' => 'activities.view',
                'description' => 'View activities',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete activities',
                'slug' => 'activities.delete',
                'description' => 'Delete activities',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Approve activities',
                'slug' => 'activities.approve',
                'description' => 'Approve activities',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Decline activities',
                'slug' => 'activities.decline',
                'description' => 'Decline activities',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Start activities',
                'slug' => 'activities.start',
                'description' => 'Start activities',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Put activities on hold',
                'slug' => 'activities.hold',
                'description' => 'Put activities on hold',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Complete activities',
                'slug' => 'activities.complete',
                'description' => 'Complete activities',
            ]);
        }


        // employee management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage Employees',
            'slug' => 'employees',
            'description' => 'Manage Employees',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create employees',
                'slug' => 'employees.create',
                'description' => 'Create employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update employees',
                'slug' => 'employees.update',
                'description' => 'Update employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View employees',
                'slug' => 'employees.view',
                'description' => 'View employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete employees',
                'slug' => 'employees.delete',
                'description' => 'Delete employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show employees',
                'slug' => 'employees.show',
                'description' => 'Show employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Activate employees',
                'slug' => 'employees.activate',
                'description' => 'Activate employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Deactivate employees',
                'slug' => 'employees.deactivate',
                'description' => 'Deactivate employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Demote employees',
                'slug' => 'employees.demote',
                'description' => 'Demote employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Promote employees',
                'slug' => 'employees.promote',
                'description' => 'Promote employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Retire employees',
                'slug' => 'employees.retire',
                'description' => 'Retire employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Suspend employees',
                'slug' => 'employees.suspend',
                'description' => 'Suspend employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delegate employees',
                'slug' => 'employees.delegate',
                'description' => 'Delegate employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Dismiss employees',
                'slug' => 'employees.dismiss',
                'description' => 'Dismiss employees',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Manage employees profile',
                'slug' => 'employees.manage_profile',
                'description' => 'Manage employees profile',
            ]);

            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Manage employees work experience',
                'slug' => 'employees.manage_work_experience',
                'description' => 'Manage employees work experience',
            ]);

            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Manage employees education info',
                'slug' => 'employees.manage_education_info',
                'description' => 'Manage employees education info',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Manage employees bank info',
                'slug' => 'employees.manage_bank_info',
                'description' => 'Manage employees bank info',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Manage employees related persons info',
                'slug' => 'employees.manage_related_persons_info',
                'description' => 'Manage employees related persons info',
            ]);

            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Manage employees documents',
                'slug' => 'employees.manage_documents',
                'description' => 'Manage employees documents',
            ]);
        }

        // directorate management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage directorates',
            'slug' => 'directorates',
            'description' => 'Manage directorates',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create directorates',
                'slug' => 'directorates.create',
                'description' => 'Create directorates',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update directorates',
                'slug' => 'directorates.update',
                'description' => 'Update directorates',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View directorates',
                'slug' => 'directorates.view',
                'description' => 'View directorates',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete directorates',
                'slug' => 'directorates.delete',
                'description' => 'Delete directorates',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show directorates',
                'slug' => 'directorates.show',
                'description' => 'Show directorates',
            ]);
        }

        // departments management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage departments',
            'slug' => 'departments',
            'description' => 'Manage departments',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create departments',
                'slug' => 'departments.create',
                'description' => 'Create departments',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update departments',
                'slug' => 'departments.update',
                'description' => 'Update departments',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View departments',
                'slug' => 'departments.view',
                'description' => 'View departments',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete departments',
                'slug' => 'departments.delete',
                'description' => 'Delete departments',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show departments',
                'slug' => 'departments.show',
                'description' => 'Show departments',
            ]);
        }

        // divisions management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage divisions',
            'slug' => 'divisions',
            'description' => 'Manage divisions',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create divisions',
                'slug' => 'divisions.create',
                'description' => 'Create divisions',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update divisions',
                'slug' => 'divisions.update',
                'description' => 'Update divisions',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View divisions',
                'slug' => 'divisions.view',
                'description' => 'View divisions',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete divisions',
                'slug' => 'divisions.delete',
                'description' => 'Delete divisions',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show divisions',
                'slug' => 'divisions.show',
                'description' => 'Show divisions',
            ]);
        }

        // sections management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage sections',
            'slug' => 'sections',
            'description' => 'Manage sections',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create sections',
                'slug' => 'sections.create',
                'description' => 'Create sections',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update sections',
                'slug' => 'sections.update',
                'description' => 'Update sections',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View sections',
                'slug' => 'sections.view',
                'description' => 'View sections',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete sections',
                'slug' => 'sections.delete',
                'description' => 'Delete sections',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show sections',
                'slug' => 'sections.show',
                'description' => 'Show sections',
            ]);
        }

        // designations management permissions
        if ($perm = Permission::query()->create([
            'name' => 'Manage designations',
            'slug' => 'designations',
            'description' => 'Manage designations',
        ]))
        {
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Create designations',
                'slug' => 'designations.create',
                'description' => 'Create designations',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Update designations',
                'slug' => 'designations.update',
                'description' => 'Update designations',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'View designations',
                'slug' => 'designations.view',
                'description' => 'View designations',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Delete designations',
                'slug' => 'designations.delete',
                'description' => 'Delete designations',
            ]);
            Permission::query()->create([
                'parent_id' => $perm->id,
                'name' => 'Show designations',
                'slug' => 'designations.show',
                'description' => 'Show designations',
            ]);
        }
    }
}
