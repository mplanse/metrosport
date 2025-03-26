<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LligaController;
use App\Http\Controllers\EditarPerfilController;
use App\Http\Controllers\DiaHoraController;

Route::get('/', function () {
    return view('principal');
});

// Rutas protegidas con middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('layouts.app');
    })->name('home');

    Route::get('/lligues/{id}', function ($id) {
        return view('lligues.lliga', ['id' => $id]);
    })->name('lliga.show');

    Route::get('/preguntes', function () {
        return view('preguntes.preguntes');
    })->name('preguntes');

    Route::get('/lligues', [LligaController::class, 'index'])->name('lligues.index');
    Route::get('/editar-perfil',[EditarPerfilController::class, 'showEditarPerfil'])->name('editar-perfil');

    Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');
    Route::post('/dia-hora/store', [DiaHoraController::class, 'store'])->name('dia_hora.store');

});

// Rutas de autenticación
Route::get('/login', [UsuarioController::class, 'showLogin'])->name('login');
Route::post('/login', [UsuarioController::class, 'login']);

Route::get('/register', [UsuarioController::class, 'showRegister'])->name('register');
Route::post('/register1', [UsuarioController::class, 'register1'])->name('register1');


Route::get('/pepe', function () {
    return view('lligues.pepe');
});
