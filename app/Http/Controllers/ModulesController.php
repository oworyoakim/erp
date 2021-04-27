<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use stdClass;

class ModulesController extends Controller
{
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
        try
        {
            $modules = Module::all()
                         ->map(function (Module $module) {
                             return $module->getDetails();
                         });
            $users = User::all()->map(function (User $user){
                $userData = new stdClass();
                $userData->id = $user->id;
                $userData->username = $user->username;
                $userData->fullName = $user->fullName();
                $userData->modules = $user->modules()->pluck('id')->all();
               return $userData;
            });
            return response()->json(compact('modules','users'));
        } catch (Exception $ex)
        {
            Log::info("GET_MODULES: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings.modules']))
            {
                throw new Exception('Permission Denied!');
            }

            $name = $request->get('name');
            $slug = Str::slug($name);

            $oldRole = Module::whereSlug($slug)->first();

            if ($oldRole)
            {
                throw new Exception("Module {$name} already exists");
            }

            $module = new Module;
            $module->name = $name;
            $module->slug = $slug;
            $module->description = $request->get('description');
            $module->save();

            return response()->json("Module created!");
        } catch (Exception $ex)
        {
            Log::info("CREATE_MODULE: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function updateModulesAccess(Request $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['settings.modules']))
            {
                throw new Exception('Permission Denied!');
            }
            $userId = $request->get('id');
            $user = User::query()->find($userId);
            if(!$user){
                throw new Exception('User not found!');
            }
            $modules = $request->get('modules') ?: [];
            $user->modules()->sync($modules);
            return response()->json("Modules access updated!");
        } catch (Exception $ex)
        {
            Log::info("UPDATE_MODULES_ACCESS: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }
}
