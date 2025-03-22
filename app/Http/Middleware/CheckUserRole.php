<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //return $next($request);
        // Cek apakah user terautentikasi dan memiliki role yang diinginkan
        if (Auth::user()->name === 'administrator') {
            return $next($request);
        }

        // Jika tidak, arahkan ke halaman yang diinginkan
        return redirect('/home');
    }
}
