<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
Route::post('/mahasiswa', [MahasiswaController::class, 'store']);

Route::get('/mahasiswa/{mahasiswa}/edit', [MahasiswaController::class, 'edit']);
Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update']);
