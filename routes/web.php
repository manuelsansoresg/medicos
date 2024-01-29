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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/query/clinicaYConsultorio', [App\Http\Controllers\HomeController::class, 'clinicaYConsultorio'])->middleware('auth');
Route::get('/query/viewClinicaYConsultorio', [App\Http\Controllers\HomeController::class, 'viewClinicaYConsultorio'])->middleware('auth');

Route::group(['prefix' => 'admin'], function () {
    Route::resource('actividades', '\App\Http\Controllers\Admin\ActividadesController')->middleware('auth');
    Route::resource('citas', '\App\Http\Controllers\Admin\CitasController')->middleware('auth');
    Route::resource('pacientes', '\App\Http\Controllers\Admin\PacientesController')->middleware('auth');
    Route::resource('pendientes', '\App\Http\Controllers\Admin\PendientesController')->middleware('auth');
    Route::resource('administracion', '\App\Http\Controllers\Admin\AdministracionController')->middleware('auth');
    
    Route::resource('clinica', '\App\Http\Controllers\Admin\ClinicaController')->middleware('auth');
    Route::get('/clinica/{clinica}/consultorio/get', [App\Http\Controllers\Admin\ClinicaController::class, 'consultorioGet'])->middleware('auth');
    Route::get('/clinica/consultorio/myConfiguration', [App\Http\Controllers\Admin\ClinicaController::class, 'consultorioGet'])->middleware('auth');
    Route::post('/clinica/consultorio/set', [App\Http\Controllers\Admin\ClinicaController::class, 'setClinicaConsultorio'])->middleware('auth');

    Route::resource('consultorio', '\App\Http\Controllers\Admin\ConsultoriosController')->middleware('auth');
    Route::resource('usuarios', '\App\Http\Controllers\Admin\UserController')->middleware('auth');
    Route::resource('sin_citas', '\App\Http\Controllers\Admin\SinCitasController')->middleware('auth');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
