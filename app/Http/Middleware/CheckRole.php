<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (! auth()->check()) {
            abort(403);
        }

        if (! in_array(auth()->user()->type, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
