<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se è loggato ed è bannato...
        if (Auth::guard('studente')->check() && Auth::guard('studente')->user()->is_banned) {
            
            // Lo reindirizziamo alla pagina di ban
           return redirect()->route('studyroom.auth.banned');
        }
        // Altrimenti, lo facciamo passare normalmente
        return $next($request);
    }
}
