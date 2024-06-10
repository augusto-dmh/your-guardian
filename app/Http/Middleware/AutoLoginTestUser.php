<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginTestUser
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('local') && !Auth::check()) {
            $testUserEmail = 'augustodemelohenriques@gmail.com';
            $user = User::where('email', $testUserEmail)->first();

            if ($user) {
                Auth::login($user, true);
            }
        }

        return $next($request);
    }
}
