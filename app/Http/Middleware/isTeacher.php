<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == 'teacher') {
            return $next($request);
        }
        //jika bukan admin dan blom login, maka akan diarahkan ke halaman home dengan
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini');
    }
}
