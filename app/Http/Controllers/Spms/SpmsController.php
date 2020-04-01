<?php

namespace App\Http\Controllers\Spms;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use stdClass;

class SpmsController extends Controller
{

    public function plans()
    {
        return view('spms.plans.index');
    }

    public function objectiveDetails($id)
    {
        return view('spms.objectives.show', ['objectiveId' => $id]);
    }
}
