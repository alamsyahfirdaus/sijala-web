<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak ditemukan.'
            ], 401);
        }

        $token = str_replace('Bearer ', '', $token);

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid.'
            ], 401);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}