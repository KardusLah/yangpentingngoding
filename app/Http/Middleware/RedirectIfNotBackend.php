<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotBackend
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $level = Auth::user()->level;
        if (!in_array($level, ['admin', 'bendahara', 'pemilik'])) {
            return redirect('/');
        }

        return $next($request);
    }
}