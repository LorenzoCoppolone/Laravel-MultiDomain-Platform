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
use App\Http\Controllers\Studyroom\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Studyroom\MaterialeController;
// ==========================================
// 1. ROTTE DI VERIFICA (Nomi globali obbligatori per Laravel)
// ==========================================
Route::prefix('studyroom')->middleware(['auth:studente,amministratore'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// ==========================================
// 2. TUTTE LE ALTRE ROTTE DEL MODULO STUDYROOM
// ==========================================
Route::prefix('studyroom')->name('studyroom.')->group(function () {

    Route::get("/", [HomeController::class, 'index'])->name('home');      

    // GUEST (Non loggati)
    Route::middleware('guest:studente,amministratore')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    // AUTH (Loggati)
    Route::middleware('auth:studente,amministratore')->group(function () {
        
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // DASHBOARD
        Route::get('dashboard', function () {
            return view('studyroom.layouts.home');
        })->middleware('verified')->name('dashboard');

        // PROFILO
        Route::get('profile', function () { return view('studyroom.profile.index'); })->name('profile.index')->middleware('verified:verification.notice');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('verified:verification.notice');
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('verified:verification.notice');
        Route::delete('profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('verified:verification.notice');
        Route::get('profile/cambia-password', [PasswordController::class, 'index'])->name('profile.password.change')->middleware('verified:verification.notice');
        

        // Materiali
        Route::get('carica-materiale', [MaterialeController::class, 'show'])->name('materiali.show')->middleware('verified:verification.notice');
        Route::post('carica-materiale/salva', [MaterialeController::class, 'store'])->name('materiali.salva')->middleware('verified:verification.notice');
        Route::get('materiali/popolari', [MaterialeController::class, 'popolari'])->name('materiali.popolari');
        Route::get('materiali/ricerca', [MaterialeController::class, 'ricerca'])->name('materiali.ricerca');
        Route::get('materiali/filtra', [MaterialeController::class, 'filtra'])->name('materiali.filtra');
        Route::get('materiale/dettagli/{id}', [MaterialeController::class, 'dettagli'])->name('materiale.dettagli');
        Route::get('materiale/recensioni/{id}', [MaterialeController::class, 'recensioni'])->name('materiale.recensioni');
        Route::post('materiale/salva-recensione', [MaterialeController::class, 'salvaRecensione'])->name('materiale.salva-recensione');
        Route::post('materiale/elimina-recensione', [MaterialeController::class, 'eliminaRecensione'])->name('materiale.elimina-recensione');
        Route::get('materiale/download/{id}', [MaterialeController::class, 'download'])->name('materiale.download');
    });

});