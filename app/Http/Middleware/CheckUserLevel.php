<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$level)
    {
        if (Auth::check()) {
            $userLevel = Auth::user()->level;
    
            // Jika level tidak sesuai
            if (!in_array($userLevel, $level)) {
                // Cegah redirect loop: jika sudah di dashboard yang sesuai, biarkan lewat
                $currentPath = $request->path();
    
                switch ($userLevel) {
                    case 'admin':
                        if ($currentPath !== 'admin') return redirect('/admin');
                        break;
                    case 'bendahara':
                        if ($currentPath !== 'bendahara') return redirect('/bendahara');
                        break;
                    case 'owner':
                        if ($currentPath !== 'owner') return redirect('/owner');
                        break;
                    case 'pelanggan':
                        if ($currentPath !== 'pelanggan') return redirect('/pelanggan');
                        break;
                    default:
                        if ($currentPath !== 'home') return redirect('/home');
                }
            }
        } else {
            // Jika belum login, redirect ke login
            return redirect()->route('login');
        }
    
        return $next($request);
    }                            
}