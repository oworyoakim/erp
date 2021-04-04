<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class DirectoratesGateway extends GatewayController
{
    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_APP_URL') . '/v1/directorates';
    }

    public function index(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['directorates','directorates.view','directorates.create','directorates.update']))
            {
                throw new Exception('Permission Denied!');
            }

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
            if (!Sentinel::hasAnyAccess(['directorates.create']))
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
            if (!Sentinel::hasAnyAccess(['directorates.update']))
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

    public function show(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['directorates.view']))
            {
                throw new Exception('Permission Denied!');
            }

            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/show", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
