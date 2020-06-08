<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class LeaveTypesGateway extends GatewayController
{
    use ValidatesHttpRequests;

    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_APP_URL') . '/v1/leaves/types';
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

    public function activate(Request $request)
    {
        try
        {
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->patch("{$this->urlEndpoint}/activate", $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function deactivate(Request $request)
    {
        try
        {
            $data = $request->all();
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->patch("{$this->urlEndpoint}/deactivate", $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
