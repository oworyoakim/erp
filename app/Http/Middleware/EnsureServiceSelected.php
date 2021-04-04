<?php

namespace App\Http\Middleware;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureServiceSelected
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Sentinel::getUser();
        $service = $user->current_module;
        if (!$service || !$user->canAccessModule($service))
        {
            User::where(['id' => $user->id])->update(['current_module' => 'hrms']);
            $request->session()->put('service', 'hrms');
            return $next($request);
        }
        if(!$request->is([$service,"$service/*"]) && !$request->ajax()){
            $route = "{$service}.dashboard";
            return redirect()->route($route);
        }
        return $next($request);
    }
}
