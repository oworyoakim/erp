<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HrmsController extends GatewayController
{

    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_URL') . '/v1';
    }

    public function indexHrms()
    {
        return view('hrms.dashboard');
    }

    public function executiveSecretary()
    {
        return view('hrms.executive-secretary.index');
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

    public function getFormSelectionsOptions(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/form-selections-options", $params);
            $responseData['roles'] = Role::query()->get(['id', 'name']);
            $responseData['usernames'] = User::query()->pluck('username')->all();

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


}
