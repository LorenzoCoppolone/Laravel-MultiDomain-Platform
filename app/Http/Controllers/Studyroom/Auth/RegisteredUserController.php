<?php

namespace App\Http\Controllers\Studyroom\Auth;

use App\Http\Controllers\Controller;
use App\Models\studyroom\Studente;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('studyroom.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cognome' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:'.Studente::class],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:'.Studente::class,
                'ends_with:@univaq.it,@student.univaq.it'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Studente::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'username'=> $request->username,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::guard('studente')->login($user);

        return redirect(route('studyroom.verification.notice', absolute: false));
    }
}
