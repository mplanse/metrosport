<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LligaController;
use App\Http\Controllers\EditarPerfilController;
use App\Http\Controllers\DiaHoraController;
use App\Http\Controllers\NotificacionsController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('principal');
});
Route::get('/lliga/{id}/detall', [LligaController::class, 'getLligaDetallada'])->name('lliga.detall');
// Rutas protegidas con middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('layouts.app');
    })->name('home');

    // Coloca la ruta estática para crear liga ANTES de la ruta dinámica
    Route::get('/lligues/crear', function () {
        return view('lligues.crear-lliga');
    })->name('crear-lliga');

    // Ruta para almacenar la nueva liga
    Route::post('/lligues/crear', [LligaController::class, 'store'])->name('lligues.store');
    // Ruta dinámica para mostrar una liga individual
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
    Route::post('/perfil/update', [EditarPerfilController::class, 'updatePerfil'])->name('perfil.update');

    // Añade esta ruta para manejar solicitudes GET a /perfil/update
    Route::get('/perfil/update', function() {
        return redirect()->route('editar-perfil')->with('error', 'Esta página solo se puede acceder mediante un formulario.');
    });

    // Ruta para ver las notificaciones
    Route::get('/notificacions', [NotificacionsController::class, 'index'])->name('notificacions.index');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    Route::post('/lligues/{id}/inscribirse', [LligaController::class, 'inscribirEquipo'])->name('lligues.inscribirse');
    Route::get('/chatMissatges', [ChatController::class, 'getMissatges'])->name('missatges');
    Route::post('/chatMissatges', [ChatController::class, 'storeMissatge']);
    Route::get('/lligues/{id}/classificacio', [LligaController::class, 'mostrarClassificacio'])->name('classificacio');
});

Route::get('/lliga/{id}/openrouter', [LligaController::class, 'callOpenRouter'])->name('lliga.openrouter');

// Rutas de autenticación
Route::get('/login', [UsuarioController::class, 'showLogin'])->name('login');
Route::post('/login', [UsuarioController::class, 'login']);


Route::get('/register', [UsuarioController::class, 'showRegister'])->name('register');
Route::post('/register1', [UsuarioController::class, 'register1'])->name('register1');

Route::get('/pepe', function () {
    return view('lligues.pepe');
});


