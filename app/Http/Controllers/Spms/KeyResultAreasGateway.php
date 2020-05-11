<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\GatewayController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class KeyResultAreasGateway extends GatewayController
{

    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_URL') . '/v1/key-result-areas';
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

    public function getKeyResultAreaDetails(Request $request)
    {
        try
        {
            $params = $request->only(['keyResultAreaId']);

            if (empty($params['keyResultAreaId']))
            {
                throw new Exception("Key result area id required!");
            }

            $responseData = $this->get("{$this->urlEndpoint}/show", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }
}
