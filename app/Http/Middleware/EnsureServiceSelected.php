<?php

namespace App\Http\Middleware;

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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $service = $request->session()->get('service');
        if ($service)
        {
            return $next($request);
        }
        if ($request->isXmlHttpRequest())
        {
            return response()->json('Select a service!', Response::HTTP_BAD_REQUEST);
        }

        return redirect()->route('service');
    }
}
