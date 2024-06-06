<?php

use App\Http\Controllers\KaryawanController;
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
// });

require __DIR__ . '/auth.php';
