<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        settings()->set('company_name','uneb');
        settings()->set('company_address','');
        settings()->set('company_email','');
        settings()->set('default_leave_application_verifier',null);
        settings()->set('default_leave_application_granter',null);
    }

}


