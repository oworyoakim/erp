<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedAccessException;
use App\Models\Permission;
use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RolesController extends Controller
{

    public function index(Request $request)
    {
        $service = $request->session()->get('service');
        try
        {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                throw  new UnauthorizedAccessException('Permission Denied!');
            }
            return view('roles.index', compact('service'));
        } catch (UnauthorizedAccessException $ex)
        {
            $error = $ex->getMessage();
            return view('errors.permission_denied', compact('service', 'error'));
        } catch (Exception $ex)
        {
            $error = $ex->getMessage();
            return view('errors.general', compact('service', 'error'));
        }
    }

    public function getRoles(Request $request)
    {
        try
        {
            $permissions = [];
            $parents = Permission::query()->where('parent_id', 0)->get();
            foreach ($parents as $parent)
            {
                array_push($permissions, $parent->getDetails());
                $children = Permission::query()->where('parent_id', $parent->id)->get();
                foreach ($children as $child)
                {
                    array_push($permissions, $child->getDetails());
                }
            }
            $roles = Role::all()
                         ->map(function (Role $role) {
                             return $role->getDetails();
                         });
            $data = [
                'roles' => $roles,
                'permissions' => $permissions,
            ];
            return response()->json($data);
        } catch (Exception $ex)
        {
            Log::info("GET_ROLES: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function getPermissions(Request $request)
    {
        try
        {
            $role_id = $request->get('role_id');
            if (!$role_id)
            {
                throw new Exception('Role required!');
            }
            $role = Role::find($role_id);
            if (!$role)
            {
                throw new Exception('Role not found!');
            }

            $permissions = $role->permissions;

            return response()->json($permissions);

        } catch (Exception $ex)
        {
            Log::info("GET_PERMISSIONS: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                throw new Exception('Permission Denied!');
            }

            $name = $request->get('name');
            $slug = Str::slug($name);

            $oldRole = Role::whereSlug($slug)->first();

            if ($oldRole)
            {
                throw new Exception("Role {$name} already exists");
            }

            $role = new Role;
            $role->name = $name;
            $role->slug = $slug;
            $role->description = $request->get('description');
            $role->save();

            return response()->json("Role created!");
        } catch (Exception $ex)
        {
            Log::info("CREATE_ROLE: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function update(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                throw new Exception('Permission Denied!');
            }

            $id = $request->get('id');
            $role = Role::query()->find($id);

            if (!$role)
            {
                throw new Exception('Role not found!');
            }
            $name = $request->get('name');
            $slug = Str::slug($name);

            $oldRole = Role::whereSlug($slug)->first();

            if ($oldRole && $oldRole->id != $role->id)
            {
                throw new Exception("Role {$name} already exists");
            }

            $role->name = $name;
            $role->slug = $slug;
            $role->description = $request->get('description');
            $role->save();

            response()->json("Role updated!");
        } catch (Exception $ex)
        {
            Log::info("UPDATE_ROLE: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function updatePermissions(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.roles']))
            {
                throw new Exception('Permission Denied!');
            }
            $role_id = $request->get('role_id');
            if (!$role_id)
            {
                throw new Exception('Role required!');
            }
            $role = Role::query()->find($role_id);
            if (!$role)
            {
                throw new Exception('Role not found!');
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
            Log::info("UPDATE_PERMISSIONS: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
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

            if (!$role)
            {
                return response()->json('Role Not Found!', Response::HTTP_NOT_FOUND);
            }

            $role->users()->detach();

            $role->delete();

            return response()->json('Role deleted!');

        } catch (Exception $ex)
        {
            Log::error($ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
