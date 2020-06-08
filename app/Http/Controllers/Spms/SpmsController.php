<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\GatewayController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Exception;

class SpmsController extends GatewayController
{
    public function __construct()
    {
        $this->urlEndpoint = env('SPMS_APP_URL') . '/v1';
    }


    public function indexSpms()
    {
        return view('spms.dashboard');
    }

    public function plan()
    {
        $startOfNFF = $this->getNextFinancialYearStartDate();
        return view('spms.plans.index',['startOfNextFinancialYear' => $startOfNFF->toDateString()]);
    }

    public function execute()
    {
        return view('spms.plans.execute');
    }

    public function monitor()
    {
        return view('spms.plans.monitor');
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
