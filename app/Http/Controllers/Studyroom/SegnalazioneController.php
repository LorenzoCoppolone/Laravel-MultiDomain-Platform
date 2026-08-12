<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Studyroom\Segnalazione;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Paginator;

class SegnalazioneController extends Controller
{
    public function salvaSegnalazione(Request $request, int $idMateriale) : RedirectResponse{
    $user = Auth::guard("studente")->user();

    $request->validate([
        "motivo"=> ["required","string","max:255"]
        ]);
    
    $segnalazione = Segnalazione::where([
        "materiale_segnalato_id" => $idMateriale,
        "segnalante_id" => $user->id,
        ])->exists();

    if($segnalazione){return redirect()->back()->with("error","Hai già segnalato questo materiale");}
    
    $datiSegnalazione = [
        "materiale_segnalato_id" => $idMateriale,
        "segnalante_id" => $user->id,
        "motivo" => $request->motivo,
        "amministratore_id"=> 1,
    ];

    Segnalazione::Create($datiSegnalazione);

    return redirect()->back()->with("success","Segnalazione inviata con successo!");
    }

    public  function gestisciSegnalazione(int $idMateriale) : View {
        $materiale = DB::table("materiali")->select("id AS idMateriale", "titolo")->where(["id"=>$idMateriale])->first();
        $utente = DB::table("materiali")->join("studenti", "materiali.studente_id", "=", "studenti.id")->where(['materiali.id'=>$idMateriale])->first();
        return view("studyroom.admin.gestisciSegnalazione", compact("materiale" ,"utente"));
    }

    public function visualizzaMotivi(int $idMateriale) : View {
        $motivi = Segnalazione::select("motivo", "studenti.username AS username", 'segnalazioni.created_at')
        ->where("materiale_segnalato_id", $idMateriale)
        ->join("studenti", "segnalazioni.segnalante_id", "=", "studenti.id")->simplePaginate(10);
        $materiale = DB::table("materiali")->select("id AS idMateriale", "titolo")->where("id", $idMateriale)->first();
        return view("studyroom.admin.motiviSegnalazione", compact("motivi", "materiale"));
    }

    public function eliminaSegnalazioni(int $idMateriale) : redirectResponse {
        Segnalazione::where("materiale_segnalato_id", $idMateriale)->delete();
        return redirect()->route("studyroom.admin.dashboard")->with("success","Segnalazioni eliminate con successo!");
    }
}
