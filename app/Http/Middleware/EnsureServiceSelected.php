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
            $action = $request->route()->getAction();
            if (!empty($action['prefix']))
            {
                $prefixes = explode('/', trim($action['prefix'], '/'));

                $prefix = $prefixes[0];

                if ($prefix != $service && !$request->isXmlHttpRequest())
                {
                    $request->session()->remove('service');

                    return redirect()->route('service');
                }
            }
            return $next($request);
        } else
        {
            if ($request->isXmlHttpRequest())
            {
                return response()->json('Select a service!', Response::HTTP_BAD_REQUEST);
            }

            return redirect()->route('service');
        }
    }
}
