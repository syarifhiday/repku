<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHasProfile
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->profile && !$request->routeIs('onboarding.*', 'logout')) {
            return redirect()->route('onboarding.create');
        }
        return $next($request);
    }
}
