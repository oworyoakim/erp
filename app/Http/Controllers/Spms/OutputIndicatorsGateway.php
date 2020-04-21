<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\Controller;
use App\Traits\MakesRemoteHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class OutputIndicatorsGateway extends Controller
{
    use MakesRemoteHttpRequests;

    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_URL') . '/v1/output-indicators';
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
