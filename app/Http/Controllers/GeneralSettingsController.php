<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Setting;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Exception;
use stdClass;

class GeneralSettingsController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings']))
            {
                throw  new Exception('Permission Denied!');
            }
            $designations = [];
            foreach (Designation::all() as $item)
            {
                $designations[$item->id] = $item->title;
            };
            $data = [
                'designations' => $designations,
            ];
            // dd($designations);
            return view('settings.global', $data);
        } catch (Exception $ex)
        {
            Log::error("Settings: {$ex->getMessage()}");
            session()->flash('error', $ex->getMessage());
            return redirect()->route('dashboard');
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
                throw  new Exception('Permission Denied!');
            }
//            dd($request->all());
            settings()->set('company_name', $request->get('company_name'));
            settings()->set('company_address', $request->get('company_address'));
            settings()->set('company_website', $request->get('company_website'));
            settings()->set('company_email', $request->get('company_email'));
            settings()->set('company_phone', $request->get('company_phone'));
            settings()->set('default_leave_application_verifier', $request->get('default_leave_application_verifier'));
            settings()->set('default_leave_application_granter', $request->get('default_leave_application_granter'));
            // upload logo
            if ($request->hasFile('company_logo'))
            {
                $logo = $request->file('company_logo');
                $file = array('company_logo' => $logo);
                $rules = array('company_logo' => 'required|mimes:jpeg,jpg,bmp,png');
                $validator = Validator::make($file, $rules);
                if ($validator->fails())
                {
                    session()->flash('warning', trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else
                {
                    $fileName = md5($logo->getClientOriginalName() . '_' . now()) . '.png';
                    $filePath = "images/{$fileName}";
                    $image = Image::make($logo->getRealPath());
                    // resize
                    if ($image->width() > 215 || $image->height() > 215)
                    {
                        $image->resize(215, 215);
                    }
                    $image->save($filePath);
                    settings()->set('company_logo', "/{$filePath}");
                }
            }
            session()->flash('success', "Settings Saved!");
            return redirect()->route('settings.general');
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->back()->withInput();
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

}
