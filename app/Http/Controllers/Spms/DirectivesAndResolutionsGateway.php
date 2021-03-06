<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\GatewayController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class DirectivesAndResolutionsGateway extends GatewayController
{

    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_APP_URL') . '/v1/directives-and-resolutions';
    }

    public function index(Request $request)
    {
        try
        {
            $params = $request->only(['workPlanId', 'planId']);

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

    public function getSelectionOptions(Request $request)
    {
        try
        {
            $params = $request->only(['directiveAndResolutionId','directorateId']);
            if(empty($params['directiveAndResolutionId']) || empty($params['directorateId'])){
                throw new Exception("Directive and resolution is required!");
            }
            // get directive and resolution activities
            $activities = $this->get("{$this->urlEndpoint}/activities", $params);
            // get directorate employees
            $urlEndpoint = env('HRMS_APP_URL') . '/v1/directorates/employees';
            $employees = $this->get($urlEndpoint, $params);

            $data = [
                'activities' => $activities,
                'employees' => $employees,
            ];

            return response()->json($data);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
