<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeesGateway extends GatewayController
{
    use ValidatesHttpRequests;

    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_APP_URL') . '/v1/employees';
    }

    public function index(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get($this->urlEndpoint, $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function indexUnscoped(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/unscoped", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(Request $request)
    {
        try
        {
            $rules = [
                'username' => 'required|unique:users',
                'firstName' => 'required',
                'lastName' => 'required',
                'password' => 'required',
                'roleId' => 'required',
            ];
            // get all the form data
            $data = $request->all();
            // validate the data for users table
            $this->validateData($data, $rules);

            $loggedInUser = Sentinel::getUser();
            $data['createdBy'] = $loggedInUser->getUserId();

            $role_id = $request->get('roleId');
            $role = Sentinel::getRoleRepository()->findById($role_id);
            //TODO: Create the user first
            if (!$role)
            {
                throw new Exception('The selected role was not found!');
            }

            $credentials = [
                'username' => $data['username'],
                'password' => $data['password'],
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'avatar' => '/storage/images/avatar.png',
            ];
            DB::beginTransaction();
            // create the employee
            $user = Sentinel::registerAndActivate($credentials);
            if (!$user)
            {
                throw new Exception('Failed to create employee!');
            }
            $role->users()->attach($user);
            $attachedRole = $user->roles()->find($role->id);
            if (!$attachedRole)
            {
                throw new Exception('Failed to attach role to employee!');
            }

            $data['userId'] = $user->getUserId();
            // create the employee in the remote service
            $responseData = $this->post($this->urlEndpoint, $data);
            // success, commit
            DB::commit();
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            // rollback on any failure
            DB::rollBack();
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function update(Request $request)
    {
        try
        {
            $data = $request->all();
            $user = Sentinel::getUser();
            $data['updatedBy'] = $user->getUserId();

            //TODO: update application user associated with this employee (firstName,lastName,email,password,avatar)

            $responseData = $this->put($this->urlEndpoint, $data);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function profileData(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/profile", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function activate(Request $request)
    {
        try
        {
            $data = $request->all();

            $responseData = $this->patch("{$this->urlEndpoint}/activate", $data);

            $user = Sentinel::findById($responseData['userId']);
            if (!$user)
            {
                throw new Exception("Employee activation failed. Contact admin for help!");
            }

            $activation = Activation::exists($user);
            if ($activation)
            {
                $activation->complete($activation->code);
            } else
            {
                $activation = Activation::create($user);
                $activation->complete($activation->code);
            }
            return response()->json("Employee activated!");
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
