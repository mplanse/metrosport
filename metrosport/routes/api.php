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


Route::get('/lligues', [LligaController::class, 'getLligues'])->name('lligues.api');
Route::get('/lliga/{id}', [LligaController::class, 'getLligaInfo']);

Route::post('/dia-hora/store', [DiaHoraController::class, 'store'])->name('dia_hora.store');


