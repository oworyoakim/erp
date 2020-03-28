<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\UnauthorizedAccessException;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $service = $request->session()->get('service');
        try
        {
            if (!Sentinel::hasAnyAccess(['users', 'users.create', 'users.update', 'users.delete', 'users.view']))
            {
                throw  new UnauthorizedAccessException('Permission Denied!');
            }
            return view('users.index', compact('service'));
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

    public function getUsers(Request $request)
    {
        try
        {
            $roleId = $request->get('role_id');
            $users = User::all()->map(function (User $user) {
                return $user->getDetails();
            });
            if ($roleId)
            {
                $users = $users->filter(function ($user) use ($roleId) {
                    return $user->roleId == $roleId;
                });
            }
            return response()->json($users);
        } catch (Exception $ex)
        {
            Log::error("GET_USERS: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.create']))
            {
                throw  new Exception('Permission Denied!');
            }
            $rules = [
                'firstName' => 'required',
                'lastName' => 'required',
                'roleId' => 'required',
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $errors = Collection::make();
                foreach ($validator->errors()->messages() as $key => $messages)
                {
                    $field = ucfirst($key);
                    $message = implode(', ', $messages);
                    $error = "{$field}: {$message}";
                    $errors->push($error);
                }
                throw new Exception($errors->implode('<br>'));
            }
            $role_id = $request->get('roleId');
            $role = Sentinel::getRoleRepository()->findById($role_id);

            if (!$role)
            {
                throw new Exception('A valid role is required!');
            }
            settings()->beginTransaction();
            $credentials = [
                'first_name' => $request->get('firstName'),
                'last_name' => $request->get('lastName'),
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'avatar' => '/images/avatar.png',
            ];
            $user = Sentinel::registerAndActivate($credentials);
            $role->users()->attach($user);
            settings()->commitTransaction();
            return response()->json('User created!');
        } catch (Exception $ex)
        {
            settings()->rollbackTransaction();
            Log::error("CREATE_USER: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function update(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.update']))
            {
                throw  new Exception('Permission Denied!');
            }
            $rules = [
                'firstName' => 'required',
                'lastName' => 'required',
            ];

            $id = $request->get('id');
            $user = Sentinel::findById($id);
            if (!$user)
            {
                throw new Exception("User not found!");
            }
            $username = $request->get('username');
            $email = $request->get('email');

            if ($user->username != $username)
            {
                $rules['username'] = 'required|unique:users';
            }

            if ($user->email != $email)
            {
                $rules['email'] = 'required|email|unique:users';
            }
            $role_id = $request->get('roleId');
            $role = Sentinel::findRoleById($role_id);

            if (!$role)
            {
                throw new Exception('A valid role is required!');
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                $errors = Collection::make();
                foreach ($validator->errors()->messages() as $key => $messages)
                {
                    $field = ucfirst($key);
                    $message = implode(', ', $messages);
                    $error = "{$field}: {$message}";
                    $errors->push($error);
                }
                throw new Exception($errors->implode('<br>'));
            }
            $userRole = $user->roles()->first();
            settings()->beginTransaction();
            if ($userRole)
            {
                if ($userRole->id != $role->id)
                {
                    $user->roles()->detach();
                    $user->roles()->attach($role);
                }
            }
            $password = $request->get('password');
            $credentials = [
                'first_name' => $request->get('firstName'),
                'last_name' => $request->get('lastName'),
                'username' => $username,
                'email' => $email,
            ];
            if (!!$password)
            {
                $credentials['password'] = $password;
            }
            $user = Sentinel::update($user, $credentials);
            settings()->commitTransaction();
            return response()->json('User updated!');
        } catch (Exception $ex)
        {
            settings()->rollbackTransaction();
            Log::error("UPDATE_USER: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function delete($id)
    {

    }
}
