<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplicationSetting;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;

class ApprovalsSettingsController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings']))
            {
                throw  new Exception('Permission Denied!');
            }
            return view('settings.approvals');
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('dashboard');
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
            $designationId = $request->get('designation_id');
            if (!$designationId)
            {
                throw  new Exception('Designation ID is required!');
            }
            $leaveApplicationSetting = LeaveApplicationSetting::firstOrNew([
                'designation_id' => $designationId,
            ]);
            $leaveApplicationSetting->verified_by = $request->get('verified_by');
            $leaveApplicationSetting->approved_by = $request->get('approved_by');
            $leaveApplicationSetting->granted_by = $request->get('granted_by');
            $leaveApplicationSetting->save();
            return response()->json('Changes Applied!');
        } catch (Exception $ex)
        {
            Log::error('Approval Settings Update: ' . $ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }
}
