<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LligaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('layouts.app');
});

Route::get('/lligues', [LligaController::class, 'index'])->name('lligues.index');
Route::get('/api/lligues', [LligaController::class, 'getLligues'])->name('lligues.api');

