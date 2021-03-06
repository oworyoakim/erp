<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\GatewayController;
use App\Models\Module;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\View;

class SpmsController extends GatewayController
{
    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_APP_URL') . '/v1';
        $this->middleware(function ($request, $next) {
            $user = Sentinel::getUser();
            $service = $user->current_module;
            if ($service != 'spms' || !$user->canAccessModule($service))
            {
                User::where(['id' => $user->id])->update(['current_module' => 'hrms']);
                $request->session()->put('service', 'hrms');
                $request->session()->save();
                return redirect()->route("hrms.dashboard");
            }
            return $next($request);
        });
        $data = [
            'modules' => Module::all(['id','name','slug','description']),
        ];
        View::share($data);
    }


    public function indexSpms()
    {
        settings()->createOutputBasedReport();
        //dd(request());
        return view('spms.dashboard');
    }

    public function plan()
    {
        $startOfNFF = $this->getNextFinancialYearStartDate();
        return view('spms.plans.index',['startOfNextFinancialYear' => $startOfNFF->toDateString()]);
    }

    public function workPlan()
    {
        return view('spms.plans.execute_work_plan');
    }

    public function performanceCapture()
    {
        return view('spms.plans.execute_performance_capture');
    }

    public function resolutionsAndDirectives()
    {
        return view('spms.plans.execute_resolutions_and_directives');
    }

    public function monitorStrategySummary()
    {
        return view('spms.plans.monitor_strategy_summary');
    }

    public function monitorStrategyDetailed()
    {
        return view('spms.plans.monitor_strategy_detailed');
    }

    public function monitorActivity()
    {
        return view('spms.plans.monitor_activity');
    }
    public function monitorDirectivesAndResolutions()
    {
        return view('spms.plans.monitor_directives_and_resolutions');
    }

    public function objectiveDetails($id)
    {
        $startOfNFF = $this->getNextFinancialYearStartDate();
        return view('spms.objectives.show', ['objectiveId' => $id,'startOfNextFinancialYear' => $startOfNFF->toDateString()]);
    }

    public function keyResultAreaDetails($id)
    {
        $startOfNFF = $this->getNextFinancialYearStartDate();
        return view('spms.key-result-areas.show', ['keyResultAreaId' => $id,'startOfNextFinancialYear' => $startOfNFF->toDateString()]);
    }

    /**
     * @return Carbon
     */
    private function getNextFinancialYearStartDate(){
        $today = Carbon::today();
        $endOfFF = Carbon::parse("30-06-{$today->year}");
        if ($today->lessThan($endOfFF))
        {
            $startOfNFF = $endOfFF->addDays(1);
        } else
        {
            $startOfNFF = $endOfFF->addMonths(12)->addDays(1);
        }
        return $startOfNFF;
    }
}
