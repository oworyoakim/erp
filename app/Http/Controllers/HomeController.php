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

    public function index(){
        return view('acl.users.index');
    }

    public function getFormSelectionsOptions(Request $request)
    {
        try
        {
            $data = array();
            $data['roles'] = Role::query()->whereIn('type',['system','both'])->get(['id', 'name']);
            $data['usernames'] = User::query()->pluck('username')->all();
            return response()->json($data);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
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
