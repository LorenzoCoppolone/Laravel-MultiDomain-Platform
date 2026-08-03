<?php

namespace App\Http\Controllers\Studyroom\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        $studente = $request->user('studente');

        if (!$studente) {
            return redirect()->route('studyroom.login');
        }

        return $studente->hasVerifiedEmail()
                    ? redirect()->route('studyroom.dashboard')
                    : view('studyroom.auth.verify-email'); // Restituisce la vista corretta
    }
}
