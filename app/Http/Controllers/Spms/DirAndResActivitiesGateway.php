<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\GatewayController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class DirAndResActivitiesGateway extends GatewayController
{

    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_APP_URL') . '/v1/directives-and-resolutions/activities';
    }

    public function index(Request $request)
    {
        try
        {
            $params = $request->only(['workPlanId', 'directiveAndResolutionId']);

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
            $user = Sentinel::getUser();
            $data['userId'] = $user->getUserId();

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
            $user = Sentinel::getUser();
            $data['userId'] = $user->getUserId();

            $responseData = $this->put($this->urlEndpoint, $data);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function updateStatus(Request $request)
    {
        try
        {
            $data = $request->all();
            if (!Sentinel::hasAccess("activities.{$data['action']}"))
            {
                throw new Exception("Permission Denied!");
            }
            $user = Sentinel::getUser();
            $data['userId'] = $user->getUserId();

            $responseData = $this->patch($this->urlEndpoint, $data);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function complete(Request $request)
    {
        try
        {
            $data = $request->all();
            if (!Sentinel::hasAccess("activities.complete"))
            {
                throw new Exception("Permission Denied!");
            }
            $user = Sentinel::getUser();
            $data['userId'] = $user->getUserId();

            $responseData = $this->patch("{$this->urlEndpoint}/complete", $data);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
