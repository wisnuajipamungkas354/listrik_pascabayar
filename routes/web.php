<?php

use App\Http\Controllers\TagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tagihan', [TagihanController::class, 'index']);