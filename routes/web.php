<?php

use App\Http\Controllers\FreelinkController;
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

Route::get('/documentation', function () {
    return view('documentation');
});

Route::get('/detailreversement', function () {
    return view('detailreversement');
});

Route::get('/detailtransaction', function () {
    return view('detailtransaction');
});
Route::any("/lienunique", [FreelinkController::class, 'Generer']);
Route::post("/lienunique", [FreelinkController::class, 'Liens']);



