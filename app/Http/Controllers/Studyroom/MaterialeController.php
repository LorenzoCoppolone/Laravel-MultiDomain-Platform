<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Studyroom\CorsoDiLaurea;
use App\Models\Studyroom\Insegnamento;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Studyroom\Materiale;
use App\Models\Studyroom\Appunto;
use App\Models\Studyroom\Esame;
class MaterialeController extends Controller
{
 public function show(){
    $studente = Auth::guard('studente')->user();
    $corsi = CorsoDiLaurea::all();
    $insegnamenti = Insegnamento::all();
    return view('studyroom.materiale.carica-materiale', compact('studente', 'corsi','insegnamenti'));
 }

 public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $studente = Auth::guard('studente')->user();

        // Validazione rigorosa di tutti i campi provenienti dal form
        $request->validate([
            'file'         => ['required', 'file', 'mimes:pdf', 'max:10240'], // max 10MB
            'tipo'         => ['required', 'in:appunto,esame'],
            'cdl'          => ['required'],
            'insegnamento' => ['required'],
            'titolo'       => ['required', 'string', 'max:255'],
            'tag'          => ['required_if:tipo,appunto', 'nullable', 'string'],
            'terms'        => ['required', 'accepted'],
        ]);

        // Lettura del file binario per il salvataggio diretto nel campo BLOB del database
        $fileContent = file_get_contents($request->file('file')->getRealPath());

        // Array dei dati comuni condivisi nella tabella unica 'materiale'
        $datiMateriale = [
            'titolo'          => $request->titolo,
            'tipo'            => $request->tipo,
            'file_Contenuto'  => $fileContent,
            'file_mimeType'   => $request->file('file')->getMimeType(),
            'insegnamento_id' => $request->insegnamento,
            'studente_id'     => $studente->id,
        ];

        // Discriminazione basata sul tipo scelto dall'utente
        if ($request->tipo === 'appunto') {
            $datiMateriale['tag'] = strtoupper($request->tag);
            Appunto::create($datiMateriale);
        } else {
            // L'esame non prevede il tag
            Esame::create($datiMateriale);
        }

        return redirect()->back()->with('status', 'Materiale caricato con successo!');
    }

    public function popolari(): View
    {
        $studente = Auth::guard('studente')->user();
       
        $materiali = Materiale::trovaMaterialiPopolari();
        $corsi = CorsoDiLaurea::all();
        $insegnamenti = Insegnamento::all();
        return view('studyroom.materiale.risultati-ricerca', compact('materiali', 'corsi', 'insegnamenti', 'studente'));
    }

    public function filtra(Request $request): View
    {
    $studente = Auth::guard('studente')->user();
    // Raccogliamo i filtri inviati dal form
    $filtri = $request->only(['titolo', 'cdl', 'insegnamento', 'tipologia', 'criterio']);
    // Gestione della sessione per mantenere il titolo attivo
   // Gestione pulita e reattiva del titolo
    if ($request->has('titolo')) {
        $filtri['titolo'] = trim($request->input('titolo'));
        
        if (!empty($filtri['titolo'])) {
            // Se c'è un titolo, lo aggiorniamo in sessione
            session(['ricerca_titolo' => $filtri['titolo']]);
        } else {
            // Se l'utente ha svuotato il campo, rimuoviamo il titolo dalla sessione
            session()->forget('ricerca_titolo');
        }
    } else {
        // Se la richiesta non passa il parametro, recuperiamo dalla sessione (se esiste)
        $filtri['titolo'] = session('ricerca_titolo', '');
    }

    // Eseguiamo la ricerca filtrata
    $materiali = Materiale::ricercaFiltrata($filtri);
    $corsi = CorsoDiLaurea::all();
    $insegnamenti = Insegnamento::all();
    // Variabili di supporto per la vista
    $ordinamento = $filtri['criterio'] ?? '';
    $queryCorrente = $filtri['titolo'] ?? '';
    // Manteniamo i valori visibili nelle combobox dopo il filtraggio
    $selectedCdl = $filtri['cdl'] ?? '';
    $selectedCdlNome = $selectedCdl ? CorsoDiLaurea::where('codice_corso', $selectedCdl)->value('nome_corso') : '';
    $selectedIns = $filtri['insegnamento'] ?? '';
    $selectedInsNome = $selectedIns ? Insegnamento::where('id', $selectedIns)->value('nome_insegnamento') : '';
    return view('studyroom.materiale.risultati-ricerca', compact(
        'materiali', 
        'corsi', 
        'insegnamenti', 
        'studente', 
        'filtri', 
        'ordinamento', 
        'queryCorrente',
        'selectedCdl',
        'selectedCdlNome',
        'selectedIns',
        'selectedInsNome'
    ));
    }
}