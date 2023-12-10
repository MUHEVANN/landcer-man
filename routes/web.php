<?php

use App\Http\Controllers\DataUserController;
use App\Http\Controllers\DataUserKeluar;
use App\Http\Controllers\PenanggungJawabController;
use Illuminate\Support\Facades\Route;

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

Route::get('/input-data-page', [DataUserController::class, 'index']);
Route::get('/data-user', [DataUserController::class, 'data_user']);
Route::post('/purposes', [DataUserController::class, 'create']);
Route::get('/purposes/{id}/edit', [DataUserController::class, 'edit']);
Route::post('/purposes/{id}', [DataUserController::class, 'update']);
Route::delete('/purposes/{id}', [DataUserController::class, 'delete']);
Route::post('/purposes/end/{id}', [DataUserController::class, 'end']);
Route::get('/purposes/keluar-page', [DataUserKeluar::class, 'index']);
Route::get('/data-user-keluar', [DataUserKeluar::class, 'data_user']);
Route::get('/penanggung-jawab', [PenanggungJawabController::class, 'index']);
Route::get('/data-penanggung', [PenanggungJawabController::class, 'data_penaggung']);
Route::post('/penanggung-jawab', [PenanggungJawabController::class, 'store']);
Route::delete('/penanggung-jawab/{id}', [PenanggungJawabController::class, 'delete']);
Route::get('/penanggung-jawab/{id}', [PenanggungJawabController::class, 'edit']);
Route::post('/penanggung/{id}', [PenanggungJawabController::class, 'update']);
