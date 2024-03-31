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
    Route::get('citas/{consultaAsignado}/{hora}/{fecha}/add', [App\Http\Controllers\Admin\CitasController::class, 'add'])->middleware('auth');
    Route::resource('pacientes', '\App\Http\Controllers\Admin\PacientesController')->middleware('auth');
    Route::get('pacientes/get/search', [App\Http\Controllers\Admin\PacientesController::class, 'search'])->middleware('auth');
    Route::resource('pendientes', '\App\Http\Controllers\Admin\PendientesController')->middleware('auth');
    Route::resource('administracion', '\App\Http\Controllers\Admin\AdministracionController')->middleware('auth');
    
    Route::resource('clinica', '\App\Http\Controllers\Admin\ClinicaController')->middleware('auth');
    Route::get('/clinica/{clinica}/consultorio/get', [App\Http\Controllers\Admin\ClinicaController::class, 'consultorioGet'])->middleware('auth');
    Route::get('/clinica/consultorio/myConfiguration', [App\Http\Controllers\Admin\ClinicaController::class, 'consultorioGet'])->middleware('auth');
    Route::post('/clinica/consultorio/set', [App\Http\Controllers\Admin\ClinicaController::class, 'setClinicaConsultorio'])->middleware('auth');

    Route::resource('consultorio', '\App\Http\Controllers\Admin\ConsultoriosController')->middleware('auth');

    Route::resource('usuarios', '\App\Http\Controllers\Admin\UserController')->middleware('auth');
    Route::resource('sin_citas', '\App\Http\Controllers\Admin\SinCitasController')->middleware('auth');
    Route::resource('consulta-asignado', '\App\Http\Controllers\Admin\ConsultasignadoController')->middleware('auth');
    Route::get('/consulta-asignado/{user}/create', [App\Http\Controllers\Admin\ConsultasignadoController::class, 'create'])->middleware('auth');

    Route::resource('acceso', '\App\Http\Controllers\Admin\AccessController')->middleware('auth');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
