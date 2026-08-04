<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\studyroom\Studente;
use Illuminate\Auth\Notifications\ResetPassword;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Istruiamo Laravel su come costruire il link per il reset della password
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            
            // Se l'utente che sta resettando la password è uno Studente del modulo StudyRoom
            if ($notifiable instanceof Studente) {
                return route('studyroom.password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ]);
            }
        });
    }
}
