<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:[ 
        __DIR__.'/../routes/web.php',
        __DIR__.'/../routes/studyroom.php',
        // aggiungere altre rotte
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
      // 1. Definisci gli alias
        $middleware->alias([
            'banned' => \App\Http\Middleware\CheckBanned::class,
        ]);

        // 2. Definisci il redirect per i non loggati (tutto dentro lo stesso blocco!)
        $middleware->redirectGuestsTo(function (Request $request) {
            if($request->is('studyroom/*')) {return route('studyroom.login');}

            return route('login');
        });
    })
   
    ->withExceptions(function (Exceptions $exceptions): void {
        // CATTURA L'ERRORE 405 (Method Not Allowed)
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if (! $request->expectsJson()) {
                
                // Se la rotta rifiutata apparteneva al modulo StudyRoom
                if ($request->is('studyroom*')) {
                    // Tenta di tornare indietro se esiste un referrer valido, 
                    // altrimenti rimandalo espressamente alla Home di StudyRoom
                    return redirect()->back()->getTargetUrl() !== url('/')
                        ? redirect()->back()
                        : redirect()->route('studyroom.home');
                }

                // Fallback di default per il resto della piattaforma
                return redirect('/');
            }
        });

        // 2. GESTIONE ERRORE 404 (Not Found - NUOVO)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (! $request->expectsJson()) {
                // Se si trova dentro il modulo StudyRoom
                if ($request->is('studyroom*')) {
                    // Restituisce una vista Blade personalizzata per StudyRoom con il codice HTTP 404
                    return redirect()->back()->getTargetUrl() !== url('/')
                        ? redirect()->back()
                        : redirect()->route('studyroom.home');
                }
                // Se non è in studyroom, Laravel mostrerà il suo 404 standard
            }
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
