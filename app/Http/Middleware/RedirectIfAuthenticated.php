<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $checkRole = Auth::user()->role;

                if ($checkRole == 'admin') {
                    return redirect()->intended('/home');
                } else if ($checkRole == 'lecturer') {
                    return redirect()->intended('/chat');
                } else if ($checkRole == 'student') {
                    return redirect()->intended('/users');
                }                
            }
        }

        return $next($request);
    }
}
