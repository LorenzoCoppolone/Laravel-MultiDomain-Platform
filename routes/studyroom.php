<?php

use App\Http\Controllers\Studyroom\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Studyroom\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Studyroom\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Studyroom\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Studyroom\Auth\NewPasswordController;
use App\Http\Controllers\Studyroom\Auth\PasswordController;
use App\Http\Controllers\Studyroom\Auth\PasswordResetLinkController;
use App\Http\Controllers\Studyroom\Auth\RegisteredUserController;
use App\Http\Controllers\Studyroom\Auth\VerifyEmailController;
use App\Http\Controllers\Studyroom\ProfileController;
use Illuminate\Support\Facades\Route;

// Applico il prefisso URL e il prefisso del nome a TUTTO il modulo
Route::prefix('studyroom')->name('studyroom.')->group(function () {

    // URL reale: /studyroom
    Route::get("/", function () {
        return view("studyroom.layouts.home");
    })->name('home');

    // ROTTE PER UTENTI NON LOGGATI (GUEST)
    // Nota: specifichiamo i guard per evitare che un utente loggato veda il login
    Route::middleware('guest:studente,amministratore')->group(function () {
        
        // URL reale: /studyroom/register | Nome: route('studyroom.register')
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    // ROTTE PER UTENTI LOGGATI (AUTH)
    // Nota: diciamo a Laravel di cercare negli specifici Guard
    Route::middleware('auth:studente,amministratore')->group(function () {
        
        Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
        Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // DASHBOARD
        Route::get('dashboard', function () {
            return view('studyroom.layouts.home');
        })->middleware('verified')->name('dashboard');

        // PROFILO
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});