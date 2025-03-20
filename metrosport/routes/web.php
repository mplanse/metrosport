<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LligaController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas protegidas con middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('layouts.app');
    })->name('home');

    Route::get('/lligues/{id}', function ($id) {
        return view('lligues.lliga', ['id' => $id]);
    })->name('lliga.show');
    

    Route::get('/lligues', [LligaController::class, 'index'])->name('lligues.index');

    Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');
});

// Rutas de autenticación
Route::get('/login', [UsuarioController::class, 'showLogin'])->name('login');
Route::post('/login', [UsuarioController::class, 'login']);

Route::get('/register', [UsuarioController::class, 'showRegister'])->name('register');
Route::post('/register', [UsuarioController::class, 'register']);


Route::get('/pepe', function () {
    return view('lligues.pepe');
});
