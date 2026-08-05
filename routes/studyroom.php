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
use App\Http\Controllers\Studyroom\PreferitoController;

// 1. ROTTE DI VERIFICA EMAIL SI USA QUELLA DEL FRAMEWORK, NON QUELLA DI STUDYROOM
Route::prefix('studyroom')->middleware(['auth:studente,amministratore'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});








// 2. TUTTE LE ALTRE ROTTE DEL MODULO STUDYROOM
Route::prefix('studyroom')->name('studyroom.')->group(function () {

    // Rotta principale del modulo StudyRoom, riporta alla home page del modulo
    Route::get("/", [HomeController::class, 'index'])->name('home');      

    // rotte pubbliche per la visualizzazione dei materiali senza login e cambio password
    Route::get('materiali/popolari', [MaterialeController::class, 'popolari'])->name('materiali.popolari');
    Route::get('materiali/ricerca', [MaterialeController::class, 'ricerca'])->name('materiali.ricerca');
    Route::get('materiali/filtra', [MaterialeController::class, 'filtra'])->name('materiali.filtra');
    Route::get('materiale/dettagli/{id}', [MaterialeController::class, 'dettagli'])->name('materiale.dettagli');
    Route::get('materiale/stream/{id}', [MaterialeController::class, 'stream'])->name('materiale.stream');



    // GUEST (Non loggati)
    Route::middleware('guest:studente,amministratore')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    });






    // AUTH (Loggati)
    Route::middleware('auth:studente,amministratore')->group(function () {
        


        // Rotte per la gestione della password e del logout
        Route::put('password', [PasswordController::class, 'update'])->name('password.update')->middleware('verified:verification.notice');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('verified:verification.notice');



        // PROFILO
        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index')->middleware('verified:verification.notice');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('verified:verification.notice');
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('verified:verification.notice');
        Route::delete('profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('verified:verification.notice');
        Route::get('profile/cambia-password', [PasswordController::class, 'index'])->name('profile.password.change')->middleware('verified:verification.notice');
        Route::post('profile/cambia-password/successo', [PasswordController::class, 'update'])->name('profile.password.update')->middleware('verified:verification.notice');
        Route::get('profile/preferiti', [PreferitoController::class, 'preferitiUtente'])->name('profile.preferiti')->middleware('verified:verification.notice');    



        // Materiali per gli utenti loggati (studente o amministratore)
        Route::get('carica-materiale', [MaterialeController::class, 'show'])->name('materiali.show')->middleware('verified:verification.notice');
        Route::post('carica-materiale/salva', [MaterialeController::class, 'store'])->name('materiali.salva')->middleware('verified:verification.notice');
        Route::get('materiale/recensioni/{id}', [MaterialeController::class, 'recensioni'])->name('materiale.recensioni')->middleware('verified:verification.notice');
        Route::post('materiale/salva-recensione', [MaterialeController::class, 'salvaRecensione'])->name('materiale.salva-recensione')->middleware('verified:verification.notice');
        Route::post('materiale/elimina-recensione', [MaterialeController::class, 'eliminaRecensione'])->name('materiale.elimina-recensione')->middleware('verified:verification.notice');
        Route::get('materiale/download/{id}', [MaterialeController::class, 'download'])->name('materiale.download')->middleware('verified:verification.notice');
        Route::post('materiale/preferiti', [PreferitoController::class, 'crea'])->name('materiale.preferiti')->middleware('verified:verification.notice');
        Route::post('materiale/aggiungi-segnalazione', [MaterialeController::class, 'aggiungiSegnalazione'])->name('materiale.aggiungi-segnalazione')->middleware('verified:verification.notice');   
    });
});