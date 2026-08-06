<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Studyroom\Recensione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RecensioneController extends Controller
{
   public function salvaRecensione(Request $request) : RedirectResponse
{
    $request->validate([
        'commento'   => ['nullable', 'string', 'max:255'], // Messo nullable se il commento è opzionale, oppure 'required' se obbligatorio
        'voto'       => ['required', 'numeric', 'min:1', 'max:5'],
        'idMateriale'=> ['required', 'numeric'],
    ]);

    // Recuperiamo l'ID dello studente loggato
    $studenteId = Auth::guard('studente')->id();

    Recensione::create([
        'materiale_id' => $request->idMateriale,
        'studente_id'  => $studenteId,
        'voto'         => $request->voto,
        'commento'     => $request->commento,
    ]);

    return redirect()->back()->with('success', 'Recensione inserita con successo!');
}
    public function eliminaRecensions(Request $request){}

}
