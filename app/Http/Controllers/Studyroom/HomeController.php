<?php

namespace App\Http\Controllers\Studyroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
    public function index(): View {
    $user = null;

    if(Auth::guard('studente')->check()) $user = Auth::guard('studente')->user();
    return view('studyroom.layouts.home', compact('user'));
    }
}