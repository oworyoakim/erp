<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\GatewayController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class SwotCategoriesGateway extends GatewayController
{

    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_APP_URL') . '/v1/swot-categories';
    }

    public function index(Request $request)
    {
        try
        {
            $responseData = $this->get($this->urlEndpoint);
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

}
