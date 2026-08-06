<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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
        // Gestione dinamica dei redirect per gli utenti non loggati
        $middleware->redirectGuestsTo(function (Request $request) {
            
            // Se l'utente sta navigando nel modulo studyroom, rimandalo al SUO login
            if ($request->is('studyroom') || $request->is('studyroom/*')) {
                return route('studyroom.login');
            }

            // Fallback globale
            return route('login'); 
        });

    })->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'banned' => \App\Http\Middleware\CheckBanned::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
