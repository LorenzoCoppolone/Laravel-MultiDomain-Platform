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
use App\Models\Studyroom\Recensione;
use App\Http\Controllers\Studyroom\RecensioneController;
use App\Http\Controllers\Studyroom\SegnalazioneController;
use App\Http\Controllers\Studyroom\DownloadController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Studyroom\AdminController;
use App\Http\Controllers\Studyroom\StudenteController;
// 1. ROTTE DI VERIFICA EMAIL SI USA QUELLA DEL FRAMEWORK, NON QUELLA DI STUDYROOM
Route::prefix('studyroom')->middleware(['auth:studente'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});




// Rotte ADMIN
Route::prefix('studyroom/admin')->name('studyroom.admin.')->middleware(['auth:amministratore'])->group(function () {
    Route::get('dashboard',[AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('gestisci-segnalazione/{idMateriale}', [SegnalazioneController::class, 'gestisciSegnalazione'])->name('gestisci-segnalazione');
    Route::post('banna-utente/{idUtente}', [StudenteController::class,'bannaUtente'])->name('utente.banna-utente');
    Route::get('segnalazioni/motivi/{idMateriale}', [SegnalazioneController::class,'visualizzaMotivi'])->name('segnalazioni.motivi');
    Route::post('elimina-materiale/{idMateriale}', [MaterialeController::class,'eliminaMateriale'])->name('materiale.elimina');
    Route::post('elimina-segnalazioni/{idMateriale}', [SegnalazioneController::class,'eliminaSegnalazioni'])->name('segnalazioni.annulla');
    
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
    Route::get('materiale/recensioni/{idMateriale}', [RecensioneController::class, 'recensioniMateriale'])->name('materiale.recensioni');


    Route::get('chi-siamo', function () {
        $user = Auth::guard('studente')->user(); 
        return view('studyroom.static.chiSiamo', compact('user')); })->name('chi-siamo');

    Route::get('supporto', function () {
        $user = Auth::guard('studente')->user();
        return view('studyroom.static.supporto', compact('user')); })->name('supporto');

    Route::get('FAQ', function () {
        $user = Auth::guard('studente')->user();
        return view('studyroom.static.faq', compact('user')); })->name('FAQ');

    Route::get('termini-utilizzo', function () {
        $user = Auth::guard('studente')->user();
        return view('studyroom.static.termini', compact('user')); })->name('termini');


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




    Route::middleware(['auth:studente,amministratore'])->group(function () {
        Route::get('banned', function(){
            $user = Auth::guard('studente')->user();
            return view('studyroom.banned.banned', compact('user'));})->name('banned.banned');
        
        Route::get('banned/assistenza', function () {
            $user = Auth::guard('studente')->user();
            return view('studyroom.banned.assistenza', compact('user')); })->name('banned.assistenza');
    });


    // AUTH (Loggati)
    Route::middleware(['auth:studente,amministratore', 'verified:verification.notice', 'banned:studente'])->group(function () {
        


        // Rotte per la gestione della password e del logout
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');



        // PROFILO
        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('profile/cambia-password', [PasswordController::class, 'index'])->name('profile.password.change');
        Route::post('profile/cambia-password/successo', [PasswordController::class, 'update'])->name('profile.password.update');
        Route::get('profile/preferiti', [PreferitoController::class, 'preferitiUtente'])->name('profile.preferiti');    
        Route::get('profile/recensioni', [RecensioneController::class, 'recensioniUtente'])->name('profile.recensioni');
        Route::get('profile/downloads', [DownloadController::class, 'downloadsUtente'])->name('profile.downloads');
        Route::get('profile/caricati', [ProfileController::class, 'caricatiUtente'])->name('profile.caricati');


        // Materiali per gli utenti loggati (studente o amministratore)
        Route::get('carica-materiale', [MaterialeController::class, 'show'])->name('materiali.show');
        Route::post('carica-materiale/salva', [MaterialeController::class, 'store'])->name('materiali.salva');
        Route::post('materiale/salva-recensione/{idMateriale}', [RecensioneController::class, 'salvaRecensione'])->name('materiale.salva-recensione');
        Route::delete('materiale/elimina-recensione/{idRecensione}', [RecensioneController::class, 'eliminaRecensione'])->name('materiale.elimina-recensione');
        Route::put('materiale/modifica-recensione/{idRecensione}', [RecensioneController::class,'modificaRecensione'])->name('materiale.modifica-recensione');
        Route::get('materiale/download/{idMateriale}', [DownloadController::class, 'download'])->name('materiale.download');
        Route::post('materiale/preferiti', [PreferitoController::class, 'crea'])->name('materiale.preferiti');
        Route::post('materiale/aggiungi-segnalazione/{idMateriale}', [SegnalazioneController::class, 'salvaSegnalazione'])->name('materiale.aggiungi-segnalazione');   
    });
});