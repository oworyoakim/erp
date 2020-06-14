<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\GatewayController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class StrategicObjectivesGateway extends GatewayController
{

    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_APP_URL') . '/v1/objectives';
    }

    public function index(Request $request)
    {
        try
        {
            $params = $request->only(['planId']);
            if (empty($params['planId']))
            {
                throw new Exception("Strategic plan id required!");
            }

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

    public function getObjectiveDetails(Request $request)
    {
        try
        {
            $params = $request->only(['objectiveId']);
            if (empty($params['objectiveId']))
            {
                throw new Exception("Strategic objective id required!");
            }

            $responseData = $this->get("{$this->urlEndpoint}/show", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function getOutputAchievements(Request $request)
    {
        try
        {
            $params = $request->only(['objectiveId','reportPeriodId']);
            if (empty($params['objectiveId']))
            {
                throw new Exception("Strategic objective id required!");
            }
            if (empty($params['reportPeriodId']))
            {
                throw new Exception("Report period required!");
            }

            $responseData = $this->get("{$this->urlEndpoint}/achievements", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function storeOutputAchievements(Request $request)
    {
        try
        {
            $data = $request->all();
            $user = Sentinel::getUser();
            $data['userId'] = $user->getUserId();

            $responseData = $this->post("{$this->urlEndpoint}/achievements", $data);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }
}
