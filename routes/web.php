<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboardApp');
})->name('dashboard');

Route::get('/info', function () {
    return view('info');
})->name('info');
