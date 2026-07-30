<?php

use Illuminate\Support\Facades\Route;

Route::get("/studyroom", function () {
    return view("studyroom.home");
});