<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Studyroom\Materiale;
use Illuminate\Support\Facades\DB;
use App\Models\Studyroom\Download;
use illuminate\Support\Facades\Auth;
class DownloadController extends Controller {
    
    public function download(int $idMateriale)
    {
    $user = Auth::guard('studente')->user();

    $documento = DB::table('materiali')
        ->where('id', $idMateriale)
        ->select('file_Contenuto', 'file_mimeType', 'titolo')
        ->first();

    // 1. Se non esiste, 404
    if (!$documento) {
        abort(404, 'Il file richiesto non esiste.');
    }

    // 2. Estrazione sicura del BLOB
    $contenuto = $documento->file_Contenuto;
    
    // TRUCCO VITALE: Se il database restituisce il BLOB come "resource"
    if (is_resource($contenuto)) {
        $contenuto = stream_get_contents($contenuto);
    }

    // 3. Se dopo la lettura è ancora vuoto, il file è danneggiato
    if (empty($contenuto)) {
        abort(404, 'Il file richiesto è danneggiato o vuoto.');
    }

    $mimeType = $documento->file_mimeType ?? 'application/pdf';
    
    // 4. Puliamo il titolo per generare un nome file valido
    $safeTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $documento->titolo);
    
    // Aggiungiamo l'estensione PDF se non specificato altrimenti (aggiusta a seconda dei tipi che supporti)
    $filename = $safeTitle . '.pdf'; 

    // 5. Registriamo il download nel database (evitando duplicati se l'utente clicca due volte di fila)
    Download::firstOrCreate([
        'studente_id'  => $user->id,
        'materiale_id' => $idMateriale,
    ]);
    
    // 6. Restituiamo il BLOB direttamente in download senza salvare file fisici!
    return response()->streamDownload(function () use ($contenuto) {
        echo $contenuto;
    }, $filename, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'no-cache, must-revalidate',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"'
    ]);
    }

    public function downloadsUtente()
    {
       //
    }
}