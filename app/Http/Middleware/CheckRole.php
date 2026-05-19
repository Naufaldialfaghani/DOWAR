<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth('api')->check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $userRole = auth('api')->user()->role;
        if (!in_array($userRole, $roles)) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke fitur ini.'], 403);
        }

        return $next($request);
    }
}
