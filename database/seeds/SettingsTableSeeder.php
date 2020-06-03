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
        settings()->set('reset_password_email_subject','Complete Your {companyName} Account Password Reset');
        settings()->set('reset_password_email_template','
                <p>Hello&nbsp;<b>{userFullName}</b>,&nbsp;</p>
                <p>We have received a  request to reset password for the <b>{companyName}</b>  account registered with email address <b>{emailAddress}</b>.</p>
                <p>Please complete your request by clicking on the button below or ignore if this request was not initiated by you.</p>
                <p><a href="{passwordResetLink}" target="_blank" style="font-size: 16px;color: #fff;padding: 10px 20px;border-radius: 3px;display: inline-block;text-decoration: none;background-color: #33adff;"> Reset Your Account Password</a></p>
                <p><b>Important: </b>If your request is not completed within 24 hours, it will be deleted.</p><p>Warm Regards<br></p>');
    }

}


