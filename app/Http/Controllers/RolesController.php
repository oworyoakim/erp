<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                throw  new Exception('Permission Denied!');
            }
            return view('roles.index');
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('dashboard');
        }
    }

    public function getRoles(Request $request)
    {
        try
        {
            $perms = [];
            $permissions = Permission::where('parent_id', 0)->get();
            foreach ($permissions as $permission) {
                array_push($perms, $permission);
                $subs = Permission::where('parent_id', $permission->id)->get();
                foreach ($subs as $sub) {
                    array_push($perms, $sub);
                }
            }
            $data = [
                'roles' => Role::all()->transform(function (Role $role){
                    $role->perms = array_keys($role->getPermissions());
                    return $role;
                }),
                'permissions' => $perms,
            ];
            return response()->json($data);
        } catch (Exception $ex)
        {
            Log::error($ex->getMessage());
            return response()->json($ex->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPermissions(Request $request)
    {
        try
        {
            $role_id = $request->get('role_id');
            if (!$role_id)
            {
                return response()->json('Role Required!', Response::HTTP_FORBIDDEN);
            }
            $role = Role::find($role_id);
            if(!$role){
                return response()->json('Role Not Found!', Response::HTTP_NOT_FOUND);
            }

            $permissions = $role->permissions;

            return response()->json($permissions);

        } catch (Exception $ex)
        {
            Log::error($ex->getMessage());
            return response()->json($ex->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                return response()->json('Permission Denied!', Response::HTTP_UNAUTHORIZED);
            }

            $role_id = $request->get('role_id');
            if (!$role_id)
            {
                return response()->json('Role Required!', Response::HTTP_FORBIDDEN);
            }
            $role = Role::find($role_id);

            if(!$role){
                return response()->json('Role Not Found!', Response::HTTP_NOT_FOUND);
            }

            $role->users()->detach();

            $role->delete();

            return response()->json('Role deleted!');

        } catch (Exception $ex)
        {
            Log::error($ex->getMessage());
            return response()->json($ex->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
