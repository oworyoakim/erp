<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Http;
use stdClass;

class OutputIndicatorTargetsGateway extends Controller
{
    /**
     * @var string
     */
    protected $urlEndpoint;

    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_URL') . '/v1/output-indicator-targets';
    }

    public function store(Request $request)
    {
        try
        {
            $data = $request->all();
            $user = Sentinel::getUser();
            $data['userId'] = $user->getUserId();
            $response = Http::post($this->urlEndpoint, $data);
            if (!$response->ok())
            {
                throw new Exception($response->body());
            }
            $data = $response->json();
            return response()->json($data);
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
            $response = Http::put($this->urlEndpoint, $data);
            if (!$response->ok())
            {
                throw new Exception($response->body());
            }
            $data = $response->json();
            return response()->json($data);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
