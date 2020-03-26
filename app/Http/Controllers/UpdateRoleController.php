<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UpdateRoleController extends Controller
{
    public function __invoke(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                return response()->json('Permission Denied!', Response::HTTP_UNAUTHORIZED);
            }

            $id = $request->get('id');

            if (!$id)
            {
                return response()->json('Role Required!', Response::HTTP_FORBIDDEN);
            }

            $role = Role::find($id);

            if (!$role)
            {
                return response()->json('Role Not Found!', Response::HTTP_NOT_FOUND);
            }
            $name = $request->get('name');
            $slug = Str::slug($name);

            $oldRole = Role::whereSlug($slug)->first();

            if ($oldRole && $oldRole->id != $role->id)
            {
                return response()->json("Role {$name} already exists", Response::HTTP_FORBIDDEN);
            }

            $role->name = $name;
            $role->slug = $slug;
            $role->description = $request->get('description');
            $role->save();

            response()->json("Record Saved!");
        } catch (Exception $ex)
        {
            Log::info($ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePermissions(Request $request)
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
            if (!$role)
            {
                return response()->json('Role Not Found!', Response::HTTP_NOT_FOUND);
            }
            $permissions = $request->get('permissions');

            $role->permissions = array();

            foreach ($permissions as $permission)
            {
                $role->addPermission($permission);
            }

            $role->save();

            return response()->json("Role Permissions Updated!");
        } catch (Exception $ex)
        {
            Log::info($ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
