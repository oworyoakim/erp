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

class StoreRoleController extends Controller
{
    public function __invoke(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                return response()->json('Permission Denied!', Response::HTTP_UNAUTHORIZED);
            }

            $name = $request->get('name');
            $slug = Str::slug($name);

            $oldRole = Role::whereSlug($slug)->first();

            if ($oldRole)
            {
                return response()->json("Role {$name} already exists", Response::HTTP_FORBIDDEN);
            }

            $role = new Role;
            $role->name = $name;
            $role->slug = $slug;
            $role->description = $request->get('description');
            $role->save();

            return response()->json("Record Saved!");
        } catch (Exception $ex)
        {
            Log::error($ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
