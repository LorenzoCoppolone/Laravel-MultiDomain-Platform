<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Studyroom\CorsoDiLaurea;
use App\Models\Studyroom\Insegnamento;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Studyroom\Appunto;
use App\Models\Studyroom\Esame;
use Illuminate\Support\Facades\DB;
use App\Repositories\Studyroom\MaterialeRepository;
use Illuminate\Http\RedirectResponse;
use App\Models\Studyroom\Preferito;
class MaterialeController extends Controller
{
 public function show(){
    $user = Auth::guard('studente')->user();
    $corsi = CorsoDiLaurea::all();
    $insegnamenti = Insegnamento::all();
    return view('studyroom.materiale.carica-materiale', compact('user', 'corsi','insegnamenti'));
 }

 public function store(Request $request): RedirectResponse
    {
        $user = Auth::guard('studente')->user();

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
            'studente_id'     => $user->id,
        ];

        // Discriminazione basata sul tipo scelto dall'utente
        if ($request->tipo === 'appunto') {
            $datiMateriale['tag'] = strtoupper($request->tag);
            Appunto::create($datiMateriale);
        } else {
            // L'esame non prevede il tag
            Esame::create($datiMateriale);
        }

        return redirect()->back()->with('success', 'Materiale caricato con successo!');
    }

    public function popolari(): View
    {
        $user = Auth::guard('studente')->user();
       
        $materiali = MaterialeRepository::trovaMaterialiPopolari();
        $corsi = CorsoDiLaurea::all();
        $insegnamenti = Insegnamento::all();
        return view('studyroom.materiale.risultati-ricerca', compact('materiali', 'corsi', 'insegnamenti', 'user'));
    }

    public function filtra(Request $request): View
    {
    $user = Auth::guard('studente')->user();
    // Raccogliamo i filtri inviati dal form
    $filtri = $request->only(['titolo', 'cdl', 'insegnamento', 'tipologia', 'criterio']);
    // Gestione della sessione per mantenere il titolo attivo
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
    $materiali = MaterialeRepository::ricercaFiltrata($filtri);
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
        'user', 
        'filtri', 
        'ordinamento', 
        'queryCorrente',
        'selectedCdl',
        'selectedCdlNome',
        'selectedIns',
        'selectedInsNome'
    ));
    }

    public function dettagli(int $id): View|RedirectResponse
    {
        $user = Auth::guard('studente')->user();
        $materiale = MaterialeRepository::dettagliMateriale($id);
        if(!$materiale) {
            return redirect()->back();
        }

        if(isset($user->id)) {
        $preferito = Preferito::where([
            'studente_id' => $user->id,
            'materiale_id' => $id,
        ])->exists();
        }else{
            $preferito = false;
        }
        return view('studyroom.materiale.dettagli', compact('materiale', 'user', 'preferito'));
    }


public function stream(int $id)
{
    // 1. Estraiamo SOLO il contenuto del file, il tipo e il titolo dal DB
    $documento = DB::table('materiali')
        ->where('id', $id)
        ->select('file_Contenuto', 'file_mimeType', 'titolo')
        ->first();

    // 2. Se non esiste, 404
    if (!$documento) {
        abort(404, 'Il file richiesto non esiste.');
    }

    // 3. Estrazione sicura del BLOB
    $contenuto = $documento->file_Contenuto;
    
    // TRUCCO VITALE: Se il database restituisce il BLOB come "resource", lo leggiamo come stringa
    if (is_resource($contenuto)) {
        $contenuto = stream_get_contents($contenuto);
    }

    // Se dopo la lettura è ancora vuoto, il file è danneggiato
    if (empty($contenuto)) {
        abort(404, 'Il file richiesto è danneggiato o vuoto.');
    }

    // Fallback nel caso il mimeType non sia salvato correttamente
    $mimeType = $documento->file_mimeType ?? 'application/pdf';
    
    // Puliamo il titolo per usarlo come nome file temporaneo
    $safeTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $documento->titolo);

    // 4. Restituiamo il file
    return response($contenuto)
        ->header('Content-Type', $mimeType)
        ->header('Content-Disposition', 'inline; filename="' . $safeTitle . '.pdf"');
}

public function eliminaMateriale(int $idMateriale): RedirectResponse{
    DB::table('materiali')->where('id', $idMateriale)->delete();
    return redirect()->route('studyroom.admin.dashboard')->with("success","Materiale eliminato con successo!");
}
    
}