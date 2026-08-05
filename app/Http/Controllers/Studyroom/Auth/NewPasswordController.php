<?php

namespace App\Http\Controllers\Studyroom\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('studyroom.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        // 1. Usiamo il broker 'studenti' configurato in auth.php per resettare la password tramite token
        $status = Password::broker('studenti')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            
            function ($studente) use ($request) {
                $studente->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($studente));
            }
        );

        // 2. Se tutto va bene, reindirizziamo alla login corretta del modulo con il messaggio di successo
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('studyroom.login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
