<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use stdClass;

class HomeController extends Controller
{

    public function index(){
        return view('acl.users.index');
    }

    public function getFormSelectionsOptions(Request $request)
    {
        try
        {
            $data = array();
            $data['roles'] = Role::query()->whereIn('type',['system','both'])->get(['id', 'name']);
            $data['usernames'] = User::query()->pluck('username')->all();
            return response()->json($data);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function settings()
    {
        try
        {
            $settings = new stdClass();
            $settings->companyName = null;
            $settings->companyPhone = null;
            $settings->companyEmail = null;
            $settings->companyWebsite = null;
            $settings->companyAddress = null;
            $settings->companyLogo = null;
            $settings->resetPasswordEmailSubject = null;
            $settings->resetPasswordEmailTemplate = null;
            $settings->defaultLeaveApplicationVerifier = null;
            $settings->defaultLeaveApplicationGranter = null;

            foreach (Setting::all() as $setting){
                if($setting->key == 'company_name'){
                    $settings->companyName = $setting->value;
                }
                if($setting->key == 'company_phone'){
                    $settings->companyPhone = $setting->value;
                }

                if($setting->key == 'company_email'){
                    $settings->companyEmail = $setting->value;
                }

                if($setting->key == 'company_website'){
                    $settings->companyWebsite = $setting->value;
                }
                if($setting->key == 'company_address'){
                    $settings->companyAddress = $setting->value;
                }

                if($setting->key == 'company_logo'){
                    $settings->companyLogo = $setting->value;
                }

                if($setting->key == 'reset_password_email_subject'){
                    $settings->resetPasswordEmailSubject = $setting->value;
                }

                if($setting->key == 'reset_password_email_template'){
                    $settings->resetPasswordEmailTemplate = $setting->value;
                }

                if($setting->key == 'default_leave_application_verifier'){
                    $settings->defaultLeaveApplicationVerifier = $setting->value;
                }

                if($setting->key == 'default_leave_application_granter'){
                    $settings->defaultLeaveApplicationGranter = $setting->value;
                }
            }
            return response()->json($settings);
        } catch (Exception $ex)
        {
            response()->json($ex->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function test()
    {
        try
        {
            dd('Ok');
        } catch (Exception $ex)
        {
            dd($ex->getTraceAsString());
        }
    }
}
