<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LligaController;
use App\Http\Controllers\DiaHoraController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/lligues', [LligaController::class, 'getLligues'])->name('lligues.api');
Route::get('/lligues/{id}', [LligaController::class, 'getLligaInfo'])->name('lligues.info');

// Endpoint públic o autenticat segons la teva configuració:
Route::get('/lliga/{id}/openrouter', [LligaController::class, 'callOpenRouter'])->name('lliga.openrouter');

Route::get('/lligues/{id}/verificar-compatibilidad', [LligaController::class, 'verificarCompatibilidadUnirse'])->name('lligues.verificar-compatibilidad');

Route::get('/lliga/{id}/disponibilitat-ia', [LligaController::class, 'disponibilitatIA'])->name('lliga.disponibilitat-ia');


