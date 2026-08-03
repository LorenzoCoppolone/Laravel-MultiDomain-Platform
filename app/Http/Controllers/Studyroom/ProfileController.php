<?php

namespace App\Http\Controllers\Studyroom;

use App\Models\studyroom\Studente;
use App\Models\studyroom\Amministratore;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Show the form for editing the user's profile.
    */
    public function edit(Request $request): View
    {
        $user = Auth::user();
        return view('studyroom.profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // 1. Capiamo in quale tabella dobbiamo controllare l'univocità dell'email
        $modelClass = $user instanceof Studente ? Studente::class : Amministratore::class;

        // 2. Validiamo i dati. ATTENZIONE: Usa 'immagine' come nel form HTML
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cognome' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255', 
                Rule::unique($modelClass)->ignore($user->id),
                'ends_with:@univaq.it,@student.univaq.it'
            ],
            'username' => [
                'required', 'string', 'max:255', 
                Rule::unique($modelClass)->ignore($user->id)
            ],
            'immagine' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        // 3. Assegniamo i campi testuali
        $user->nome = $validated['nome'];
        $user->cognome = $validated['cognome'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 4. Gestione File per salvataggio BLOB nel Database
        if ($request->hasFile('immagine')) {
            $file = $request->file('immagine');
            
            // Leggiamo i dati binari del file
            $user->immagine_profilo = file_get_contents($file->getRealPath());
            
            // Leggiamo il tipo MIME (es. 'image/png' o 'image/jpeg')
            $user->immagine_profilo_mime = $file->getClientMimeType();
        }

        $user->save();

        // REINDIRIZZA INDIETRO (al form) CON MESSAGGIO DI SUCCESSO
        return Redirect::back()->with('status', 'Profilo aggiornato con successo!');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Capiamo chi sta cercando di cancellare l'account
        $guard = Auth::guard('amministratore')->check() ? 'amministratore' : 'studente';

        // 2. Diciamo alla regola 'current_password' di usare il guard corretto
        $request->validateWithBag('userDeletion', [
            'password' => ['required', "current_password:{$guard}"],
        ]);

        $user = $request->user();

        // 3. Facciamo il logout dal guard specifico
        Auth::guard($guard)->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 4. Reindirizziamo alla home del modulo
        return Redirect::to('home')->with('status', 'account-deleted');
    }
}