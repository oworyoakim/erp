<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\User;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\UnauthorizedAccessException;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UsersController extends Controller
{
    use ValidatesHttpRequests;

    public function __construct()
    {
        $this->middleware(function (Request $request, \Closure $next) {
            $user = Sentinel::getUser();
            $service = $user->current_module;
            if ($service != 'acl' || !$user->canAccessModule($service))
            {
                if($request->ajax()){
                    return response()->json("Sorry, you do not have access to the acl module!", Response::HTTP_FORBIDDEN);
                }
                User::where(['id' => $user->id])->update(['current_module' => 'hrms']);
                $request->session()->put('service', 'hrms');
                $request->session()->save();
                return redirect()->route("hrms.dashboard");
            }
            return $next($request);
        });
        $data = [
            'modules' => Module::all(['id','name','slug','description']),
        ];
        View::share($data);
    }

    public function index(Request $request)
    {
        $service = $request->session()->get('service');
        try
        {
            if (!Sentinel::hasAnyAccess(['users', 'users.create', 'users.update', 'users.delete', 'users.view']))
            {
                throw  new UnauthorizedAccessException('Permission Denied!');
            }
            return view('acl.users.index', compact('service'));
        } catch (UnauthorizedAccessException $ex)
        {
            $error = $ex->getMessage();
            return view('errors.401', compact('service', 'error'));
        } catch (Exception $ex)
        {
            $error = $ex->getMessage();
            return view('errors.500', compact('service', 'error'));
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
                'avatar' => '/storage/images/avatar.png',
            ];
            // Since users are
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

    public function activate(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.activate']))
            {
                throw  new Exception('Permission Denied!');
            }
            $rules = [
                'email' => 'required|email',
                'username' => 'required',
            ];

            $id = $request->get('id');
            $user = Sentinel::findById($id);
            if (!$user)
            {
                throw new Exception("User not found!");
            }
            $this->validateData($request->all(), $rules);

            settings()->beginTransaction();

            $user->activate();

            settings()->commitTransaction();
            return response()->json('User activated!');
        } catch (Exception $ex)
        {
            settings()->rollbackTransaction();
            Log::error("ACTIVATE_USER: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function deactivate(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.deactivate']))
            {
                throw  new Exception('Permission Denied!');
            }
            $rules = [
                'email' => 'required|email',
                'username' => 'required',
            ];

            $id = $request->get('id');
            $user = Sentinel::findById($id);
            if (!$user)
            {
                throw new Exception("User not found!");
            }
            $this->validateData($request->all(), $rules);

            settings()->beginTransaction();

            $user->deactivate();

            settings()->commitTransaction();
            return response()->json('User deactivated!');
        } catch (Exception $ex)
        {
            settings()->rollbackTransaction();
            Log::error("DEACTIVATE_USER: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function delete($id)
    {

    }
}
