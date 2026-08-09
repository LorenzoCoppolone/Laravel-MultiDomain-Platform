<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Studyroom\Recensione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Studyroom\StudenteRepository;
use Illuminate\View\View;
class RecensioneController extends Controller
{
   public function salvaRecensione(Request $request, int $idMateriale) : RedirectResponse
   {
    // 1. Validazione (meglio usare 'integer' invece di 'numeric' per ID e voti)
    $request->validate([
        'commento'    => ['nullable', 'string', 'max:255'], 
        'voto'        => ['required', 'integer', 'min:1', 'max:5'],
    ]);

    $studenteId = Auth::guard('studente')->id();

    // 2. Controllo duplicato (Corretto: idMateriale invece di id_materiale)
    $recensioneEsistente = Recensione::where([
        'studente_id'  => $studenteId,
        'materiale_id' => $idMateriale,
    ])->exists();

    // 3. Se esiste già, blocchiamo subito l'esecuzione (Early Return)
    if ($recensioneEsistente) {
        return redirect()->back()->with('error', 'Hai già inserito una recensione per questo materiale.');
    }

    // 4. Altrimenti, procediamo con la creazione
    Recensione::create([
        'materiale_id' => $idMateriale,
        'studente_id'  => $studenteId,
        'voto'         => $request->voto,
        'commento'     => $request->commento,
    ]);

    return redirect()->back()->with('success', 'Recensione inserita con successo!');
    }
    public function eliminaRecensione(int $idRecensione): RedirectResponse{
        Recensione::where('id', $idRecensione)->delete();
        return redirect()->back()->with('success', 'Recensione eliminata con successo!');
    }

    public function modificaRecensione(Request $request, int $idRecensione) : redirectResponse{
        $request->validate([
            'commento'   => ['nullable', 'string', 'max:255'],
            'voto'       => ['required', 'numeric', 'min:1', 'max:5'],
        ]);
        Recensione::where('id', $idRecensione)->update([
            'voto'         => $request->voto,
            'commento'     => $request->commento,
        ]);
        return redirect()->back()->with('success', 'Recensione modificata con successo!');
    }

    public function recensioniMateriale(int $idMateriale){
        $user = null;
        if(Auth::guard('studente')->check()){ $user = Auth::guard('studente')->user(); }
        $recensioni = Recensione::where('materiale_id', $idMateriale)->join('studenti', 'recensioni.studente_id', '=', 'studenti.id')->simplePaginate(10);
        return view('studyroom.materiale.recensioni', compact('user','recensioni', 'idMateriale'));
    }

    public function recensioniUtente(): View
    {
        $user = Auth::guard('studente')->user();
        $recensioni = StudenteRepository::trovaRecensioni($user->id);        
        return view('studyroom.profile.recensioni-utente', compact('user', 'recensioni'));
    }
}
