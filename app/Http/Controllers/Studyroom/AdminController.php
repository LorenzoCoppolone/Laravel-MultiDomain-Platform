<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use App\Models\Studyroom\Segnalazione;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Repositories\Studyroom\AdminRepository;
class AdminController extends Controller
{
    public function dashboard(): View {
        $segnalazioni = AdminRepository::trovaSegnalazioni();
        return view('studyroom.admin.dashboard', compact('segnalazioni'));
    }

    public function gestisciSegnalazione(): RedirectResponse {
        
        return redirect()->route('studyroom.admin.dashboard');
    }
}
