<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class LeaveApplicationsGateway extends GatewayController
{
    use ValidatesHttpRequests;

    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_APP_URL') . '/v1/leaves/applications';
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

    public function store(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves.apply']))
            {
                throw new Exception('Permission Denied!');
            }
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->post($this->urlEndpoint, $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function update(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves.applications.update']))
            {
                throw new Exception('Permission Denied!');
            }
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->put($this->urlEndpoint, $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function verify(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves.applications.verify']))
            {
                throw new Exception('Permission Denied!');
            }
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->patch("{$this->urlEndpoint}/verify", $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function returnApplication(Request $request)
    {
        try
        {
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->patch("{$this->urlEndpoint}/return", $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function approve(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves.applications.approve']))
            {
                throw new Exception('Permission Denied!');
            }
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->patch("{$this->urlEndpoint}/approve", $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function decline(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves.applications.decline']))
            {
                throw new Exception('Permission Denied!');
            }
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->patch("{$this->urlEndpoint}/decline", $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function grant(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves.applications.grant']))
            {
                throw new Exception('Permission Denied!');
            }
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->patch("{$this->urlEndpoint}/grant", $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function reject(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves.applications.reject']))
            {
                throw new Exception('Permission Denied!');
            }
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->patch("{$this->urlEndpoint}/reject", $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
