<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Studyroom\Studente;
class StudenteController extends Controller
{
    public function bannaUtente(int $idStudente){
    Studente::where("id", $idStudente)->update(['is_banned' => 1]);
    return redirect()->back()->with('success', 'Utente bannato con successo');
    }
}
