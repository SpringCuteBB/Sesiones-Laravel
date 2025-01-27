<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\SesionController;


Route::get('/', [SesionController::class, 'index']);
Route::post('/show/{id}', [SesionController::class, 'store'])->whereNumber('id');
Route::get('/show/{id}', [SesionController::class, 'show'])->name('show')->whereNumber('id');
Route::delete('/delete/{id}', [SesionController::class,'delete']);
Route::post('/add/{id}', [SesionController::class,'add'])->name('add');