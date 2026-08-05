<?php

namespace App\Http\Controllers\Studyroom\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function index(): View
    {
        return view('studyroom.profile.password.change');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::guard('studente')->user();
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['required', 'same:password']
        ]);

        if (Hash::check($validated['password'], $user->password)) {
            return redirect()->back()->withErrors(['password' => 'La nuova password non può essere uguale a quella attuale.']);
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('status', 'password-updated');
    }
}
