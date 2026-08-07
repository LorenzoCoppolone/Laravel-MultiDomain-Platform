<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Studyroom\Preferito;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Repositories\Studyroom\StudenteRepository;
class PreferitoController extends Controller
{
   public function crea(Request $request)
    {
        $user = Auth::guard('studente')->user();
        
        // Leggiamo 'idMateriale' perché è così che si chiama nel form Blade
        $materialeId = $request->input('idMateriale');

        if (!$materialeId) {
            return redirect()->back()->with('error', 'ID materiale non valido.');
        }

        // Cerchiamo se il preferito esiste già per questo studente e questo materiale
        $preferito = Preferito::where('studente_id', $user->id)
                              ->where('materiale_id', $materialeId)
                              ->first();

        if (!$preferito) {
            // Se non esiste, lo creiamo
            Preferito::create([
                'studente_id' => $user->id,
                'materiale_id' => $materialeId
            ]);
        } else {
            // Se esiste già, lo rimuoviamo (toggle)
            $preferito->delete();
        }

        return redirect()->back();
    }

    public function preferitiUtente(): View
    {
        $user = Auth::guard('studente')->user();
        $materiali = StudenteRepository::trovaPreferiti($user->id);
        return view('studyroom.profile.risultati-ricerca-profilo', compact('user', 'materiali'));
    }

}
