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
    public function edit(Request $request)
    {
        $user = Auth::user();
        return view('studyroom.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // 1. Capiamo in quale tabella dobbiamo controllare l'univocità dell'email
        $modelClass = $user instanceof Studente ? Studente::class : Amministratore::class;

        // 2. Validiamo i dati direttamente qui, abbandonando il ProfileUpdateRequest di default
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255', 
                Rule::unique($modelClass)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 3. Reindirizziamo alla rotta corretta col prefisso!
        return Redirect::route('studyroom.profile.edit')->with('status', 'profile-updated');
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