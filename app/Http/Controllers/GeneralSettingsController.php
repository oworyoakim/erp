<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedAccessException;
use App\Models\Module;
use App\Models\Setting;
use App\Models\User;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Exception;
use stdClass;

class GeneralSettingsController extends Controller
{
    use ValidatesHttpRequests;

    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            $user = Sentinel::getUser();
            $service = $user->current_module;
            if ($service != 'acl' || !$user->canAccessModule($service))
            {
                if($request->ajax()){
                    return response()->json("Sorry, you do not have access to the acl module!", Response::HTTP_FORBIDDEN);
                }
                User::where(['id' => $user->id])->update(['current_module' => 'hrms']);
                $request->session()->put('service', 'hrms');
                $request->session()->save();
                return redirect()->route("hrms.dashboard");
            }
            return $next($request);
        });
        $data = [
            'modules' => Module::all(['id','name','slug','description']),
        ];
        View::share($data);
    }

    public function index(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings']))
            {
                throw  new UnauthorizedAccessException('Permission Denied!');
            }
            return view('acl.settings.global');
        } catch (UnauthorizedAccessException|Exception $ex)
        {
            $error = $ex->getMessage();
            return view('errors.401', ['error' => $error]);
        }
    }

    public function getSettings(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings']))
            {
                throw  new Exception('Permission Denied!');
            }
            $settings = Setting::all()->map(function ($item) {
                $setting = new stdClass();
                $setting->id = $item->id;
                $setting->settingKey = $item->setting_key;
                $setting->settingValue = $item->setting_value;
                $setting->createdAt = $item->created_at->toDateTimeString();
                $setting->updatedAt = $item->updated_at->toDateTimeString();
                return $setting;
            });
            return response()->json($settings);
        } catch (Exception $ex)
        {
            Log::error('General Settings: ' . $ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function update(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings']))
            {
                throw  new UnauthorizedAccessException('Permission Denied!');
            }
            //dd($request->all());
            settings()->set('company_name', $request->get('company_name'));
            settings()->set('company_address', $request->get('company_address'));
            settings()->set('company_website', $request->get('company_website'));
            settings()->set('company_email', $request->get('company_email'));
            settings()->set('company_phone', $request->get('company_phone'));
            settings()->set('reset_password_email_subject', $request->get('reset_password_email_subject'));
            settings()->set('reset_password_email_template', $request->get('reset_password_email_template'));
            settings()->set('date_format',$request->get('date_format'));
            // upload logo

            if ($request->hasFile('company_logo'))
            {
                $logo = $request->file('company_logo');
                $file = array('company_logo' => $logo);
                $rules = array('company_logo' => 'required|image|mimes:jpeg,jpg,bmp,png');
                $this->validateData($file, $rules);
                $fileName = md5($logo->getClientOriginalName() . '_' . Sentinel::getUser()->getUserId() . '_' . now()) . '.png';
                $filePath = "/storage/images/{$fileName}";
                $image = Image::make($logo->getRealPath());
                // resize
                if ($image->width() > 140 || $image->height() > 140)
                {
                    $image->resize(140, 140);
                }
                $image->save(public_path($filePath));
                settings()->set('company_logo', $filePath);
            }

            session()->flash('success', "Settings Saved!");
            return redirect()->back();
        } catch (UnauthorizedAccessException|Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->back()->withInput()->with(['error' => $ex->getMessage()]);
        }
    }

    public function updateSettings(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings']))
            {
                throw  new Exception('Permission Denied!');
            }
            $settings = $request->get('settings');
            settings()->beginTransaction();
            foreach ($settings as $key => $value)
            {
                settings()->set($key, $value);
            }
            settings()->commitTransaction();
            return response()->json("Settings Saved!");
        } catch (Exception $ex)
        {
            settings()->rollbackTransaction();
            Log::error('General Settings Update: ' . $ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function modules(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings']))
            {
                throw  new UnauthorizedAccessException('Permission Denied!');
            }
            return view('acl.settings.module');
        } catch (UnauthorizedAccessException|Exception $ex)
        {
            $error = $ex->getMessage();
            return view('errors.401', ['error' => $error]);
        }
    }

}
