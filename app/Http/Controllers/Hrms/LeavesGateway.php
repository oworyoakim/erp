<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class LeavesGateway extends GatewayController
{
    use ValidatesHttpRequests;

    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_APP_URL') . '/v1/leaves';
    }

    public function index(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves','leaves.view','leaves.recall']))
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

    public function history(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['leaves.history']))
            {
                throw new Exception('Permission Denied!');
            }
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/history", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
