<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\LeavePolicy;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Exception;

class LeaveSettingsController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings']))
            {
                throw  new Exception('Permission Denied!');
            }
            return view('settings.leave');
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('dashboard');
        }
    }

}
