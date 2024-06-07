<?php

use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SubKriteriaController;
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



// Route::middleware('auth')->group(function () {
Route::get('/', function () {
    return view('master');
});
Route::resource('karyawan', KaryawanController::class);
Route::resource('kriteria', KriteriaController::class);
Route::resource('subkriteria', SubKriteriaController::class);

Route::resource('penilaian', PenilaianController::class);

// });

require __DIR__ . '/auth.php';
