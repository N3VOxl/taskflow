<?php

use Illuminate\Support\Facades\Route;

// Când intri pe site, te trimite la fișierul HTML din public
Route::get('/', function () {
    return redirect('/login.html');
});