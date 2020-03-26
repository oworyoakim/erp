<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Str;

class CreateRolesController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                throw  new Exception('Permission Denied!');
            }
            $data = array();
            $permissions = Permission::where('parent_id', 0)->get();
            foreach ($permissions as $permission) {
                array_push($data, $permission);
                $subs = Permission::where('parent_id', $permission->id)->get();
                foreach ($subs as $sub) {
                    array_push($data, $sub);
                }
            }
            return view('users.roles.create', compact('data'));
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('dashboard');
        }
    }

}
