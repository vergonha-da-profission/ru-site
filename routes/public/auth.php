<?php

use Illuminate\Support\Facades\Route;

Route::get('/entrar', function () {
    return view('user.login');
});

