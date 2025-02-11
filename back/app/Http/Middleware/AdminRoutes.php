<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoutes
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return \response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $userType = $user->type;

        if ($userType !== 'admin') {
            return \response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return $next($request);
    }
}
