<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use App\Models\Role;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HrmsController extends GatewayController
{

    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_APP_URL') . '/v1';
    }

    public function indexHrms()
    {
        request()->session()->put('service', 'hrms');
        $user = Sentinel::getUser();
        if ($user->user_type == 'employee')
        {
            return redirect()->route('hrms.employee-profile-view');
        }
        return view('hrms.dashboard');
    }

    public function executiveDirector()
    {
        return view('hrms.executive-director.index');
    }

    public function directorates()
    {
        return view('hrms.directorates.index');
    }

    public function directorateDetails($id)
    {
        if (empty($id))
        {
            return redirect()->route('hrms.directorates.list')->with(['error' => "Directorate ID required!"]);
        }
        return view('hrms.directorates.show', ['id' => $id]);
    }

    public function departments()
    {
        return view('hrms.departments.index');
    }

    public function departmentDetails(Request $request, $id)
    {
        if (empty($id))
        {
            return redirect()->route('hrms.departments.list')->with(['error' => "Department ID required!"]);
        }
        $data = [
            'id' => $id,
            'scope' => $request->get('scope'),
        ];
        return view('hrms.departments.show', $data);
    }

    public function divisions()
    {
        return view('hrms.divisions.index');
    }

    public function divisionDetails(Request $request, $id)
    {
        if (empty($id))
        {
            return redirect()->route('hrms.divisions.list')->with(['error' => "Division ID required!"]);
        }
        $data = [
            'id' => $id,
            'scope' => $request->get('scope'),
        ];
        return view('hrms.divisions.show', $data);
    }

    public function sections()
    {
        return view('hrms.sections.index');
    }

    public function designations()
    {
        return view('hrms.designations.index');
    }

    public function salaryScales()
    {
        return view('hrms.salary-scales.index');
    }

    public function delegations()
    {
        return view('hrms.delegations.index');
    }

    public function documents()
    {
        return view('hrms.documents.index');
    }

    public function employees()
    {
        if(!Sentinel::hasAnyAccess(['employees'])){
            session()->flash('error', 'Permission Denied!');
            return redirect()->to('/');
        }
        return view('hrms.employees.index');
    }

    public function employeeProfile($username)
    {
        if (empty($username))
        {
            return redirect()->route('hrms.employees.list')->with(['error' => "Employee username required!"]);
        }
        return view('hrms.employees.profile', ['username' => $username]);
    }

    public function createEmployee()
    {
        return view('hrms.employees.create');
    }

    public function leaves()
    {
        return view('hrms.leaves.index');
    }

    public function leaveApplications()
    {
        return view('hrms.leaves.applications');
    }

    public function leaveSettings()
    {
        return view('hrms.settings.leave');
    }

    public function approvalsSettings()
    {
        return view('hrms.settings.approvals');
    }

    public function getFormSelectionsOptions(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/form-selections-options", $params);
            $responseData['roles'] = Role::query()->whereIn('type', ['employee', 'both'])->get(['id', 'name']);
            $users = User::all();
            $responseData['usernames'] = $users->pluck('username')->all();
            $responseData['emails'] = $users->filter(function ($user) {
                return !empty($user->email);
            })->pluck('email')->all();

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function getDashboardStatistics(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/dashboard-statistics", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function employeeProfileView(){
        $user = Sentinel::getUser();
        return view("hrms.employee-profile", ['username' => $user->username]);
    }

}
