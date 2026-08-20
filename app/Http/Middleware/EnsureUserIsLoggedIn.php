<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = session('user_id');

        if (! $userId || ! User::find($userId)) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}