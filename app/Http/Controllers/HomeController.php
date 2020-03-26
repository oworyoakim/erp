<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;
use stdClass;

class HomeController extends Controller
{
    public function indexHrms()
    {
        return view('hrms.dashboard');
    }

    public function indexSpms()
    {
        return view('spms.dashboard');
    }

    public function test()
    {
        try
        {
            dd('Ok');
        } catch (Exception $ex)
        {
            dd($ex->getTraceAsString());
        }
    }
}
