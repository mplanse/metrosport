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

    Route::post('/equip/ubicacio', [EditarPerfilController::class, 'guardarUbicacio'])->name('equip.setUbicacio');


});

// Rutas de autenticación
Route::get('/login', [UsuarioController::class, 'showLogin'])->name('login');
Route::post('/login', [UsuarioController::class, 'login']);

Route::get('/register', [UsuarioController::class, 'showRegister'])->name('register');
Route::post('/register1', [UsuarioController::class, 'register1'])->name('register1');


Route::get('/pepe', function () {
    return view('lligues.pepe');
});

Route::get('/api/equip-usuari', function () {
    $usuari_id = 2;

    $equip = DB::table('equip')
        ->leftJoin('ubicacio_camp', 'equip.usuari_id_usuari', '=', 'ubicacio_camp.equip_usuari_id_usuari')
        ->select('equip.nom_equip', 'equip.url_imagen', 'ubicacio_camp.nom_ubicacio')
        ->where('equip.usuari_id_usuari', $usuari_id)
        ->first();

    return response()->json($equip);
});
