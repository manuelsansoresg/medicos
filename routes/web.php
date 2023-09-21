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


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');;
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');;

Route::group(['prefix' => 'admin'], function () {
    Route::resource('actividades', '\App\Http\Controllers\Admin\ActividadesController')->middleware('auth');
    Route::resource('citas', '\App\Http\Controllers\Admin\CitasController')->middleware('auth');
    Route::resource('pacientes', '\App\Http\Controllers\Admin\PacientesController')->middleware('auth');
    Route::resource('pendientes', '\App\Http\Controllers\Admin\PendientesController')->middleware('auth');
});
