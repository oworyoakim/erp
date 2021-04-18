<?php

use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = Module::updateOrCreate(['slug' => 'hrms'], ['name' => 'HRMS',]);
        Module::updateOrCreate(['slug' => 'spms'], ['name' => 'SPMS',]);
        Module::updateOrCreate(['slug' => 'acl'], ['name' => 'ACL',]);
        // give every user hrms
        foreach (User::all() as $user){
            $user->modules()->sync([$module->id]);
        }
    }
}
