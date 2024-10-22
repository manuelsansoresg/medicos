<?php

use App\Http\Controllers\Admin\FormularioConfigurationController;
use App\Http\Controllers\Admin\FormularioController;
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
Route::get('/editProfile', [App\Http\Controllers\HomeController::class, 'editProfile'])->name('editProfile')->middleware('auth');

Route::get('/query/clinicaYConsultorio', [App\Http\Controllers\HomeController::class, 'clinicaYConsultorio'])->middleware('auth');
Route::get('/query/viewClinicaYConsultorio', [App\Http\Controllers\HomeController::class, 'viewClinicaYConsultorio'])->middleware('auth');

Route::group(['prefix' => 'admin'], function () {
    Route::resource('actividades', '\App\Http\Controllers\Admin\ActividadesController')->middleware('auth');
    
    Route::resource('citas', '\App\Http\Controllers\Admin\CitasController')->middleware('auth');
    Route::get('citas/{consultaAsignado}/{hora}/{fecha}/add', [App\Http\Controllers\Admin\CitasController::class, 'add'])->middleware('auth');
    Route::get('citas/{fecha}/{iddoctor}/set', [App\Http\Controllers\Admin\CitasController::class, 'setCita'])->middleware('auth');
    Route::get('citas/{consultaAsignado}/list', [App\Http\Controllers\Admin\CitasController::class, 'viewCitaConsultaAsignado'])->middleware('auth');


    Route::resource('pacientes', '\App\Http\Controllers\Admin\PacientesController')->middleware('auth');
    Route::get('pacientes/get/search', [App\Http\Controllers\Admin\PacientesController::class, 'search'])->middleware('auth');
    Route::resource('pendientes', '\App\Http\Controllers\Admin\PendientesController')->middleware('auth');
    Route::resource('administracion', '\App\Http\Controllers\Admin\AdministracionController')->middleware('auth');
    
    Route::resource('clinica', '\App\Http\Controllers\Admin\ClinicaController')->middleware('auth');
    Route::get('/clinica/{clinica}/consultorio/get', [App\Http\Controllers\Admin\ClinicaController::class, 'consultorioGet'])->middleware('auth');
    Route::get('/clinica/consultorio/myConfiguration', [App\Http\Controllers\Admin\ClinicaController::class, 'consultorioGet'])->middleware('auth');
    Route::post('/clinica/consultorio/set', [App\Http\Controllers\Admin\ClinicaController::class, 'setClinicaConsultorio'])->middleware('auth');
    Route::get('/clinica/consultorio/get', [App\Http\Controllers\Admin\ClinicaController::class, 'getClinicaConsultorio'])->middleware('auth');

    Route::resource('consultorio', '\App\Http\Controllers\Admin\ConsultoriosController')->middleware('auth');
    Route::get('/consultorio/{id}/{userId}/show', [App\Http\Controllers\Admin\ConsultoriosController::class, 'show'])->middleware('auth');

    Route::resource('usuarios', '\App\Http\Controllers\Admin\UserController')->middleware('auth');
    

    Route::resource('sin_citas', '\App\Http\Controllers\Admin\SinCitasController')->middleware('auth');
    Route::resource('consulta-asignado', '\App\Http\Controllers\Admin\ConsultasignadoController')->middleware('auth');
    Route::get('/consulta-asignado/{user}/create', [App\Http\Controllers\Admin\ConsultasignadoController::class, 'create'])->middleware('auth');
    Route::get('/consulta-asignado/{userId}/{idConsultorio}/edit', [App\Http\Controllers\Admin\ConsultasignadoController::class, 'edit'])->middleware('auth');
    Route::delete('/consulta-asignado/{userId}/{idConsultorio}/delete', [App\Http\Controllers\Admin\ConsultasignadoController::class, 'destroy'])->middleware('auth');

    Route::resource('acceso', '\App\Http\Controllers\Admin\AccessController')->middleware('auth');

    Route::resource('consulta', '\App\Http\Controllers\Admin\ConsultaController')->middleware('auth');
    Route::get('consulta/{userCitaId}/{consultaAsignadoId}/registro', [App\Http\Controllers\Admin\ConsultaController::class, 'registroConsulta'])->middleware('auth');
    Route::get('consulta/{consulta}/{type}/generate/pdf', [App\Http\Controllers\Admin\ConsultaController::class, 'recetaPdf'])->middleware('auth');

    Route::resource('estudio', '\App\Http\Controllers\Admin\EstudioController')->middleware('auth');
    Route::get('estudio/{estudio}/generate/pdf', [App\Http\Controllers\Admin\EstudioController::class, 'estudioPdf'])->middleware('auth');

    Route::resource('expedientes', '\App\Http\Controllers\Admin\ExpedienteController')->middleware('auth');
    Route::post('expedientes/select/download', [App\Http\Controllers\Admin\ExpedienteController::class, 'downloadExpedient'])->middleware('auth');

    Route::resource('estudio-imagenes', '\App\Http\Controllers\Admin\EstudioImagenesController')->middleware('auth');
    Route::get('estudio-imagenes/{estudioId}/{userCitaId}/{ConsultaAsignado}', [App\Http\Controllers\Admin\EstudioImagenesController::class, 'show'])->middleware('auth');
    Route::get('/estudio-imagenes/{estudioId}/{userCitaId}/{ConsultaAsignado}/create', [App\Http\Controllers\Admin\EstudioImagenesController::class, 'create'])->middleware('auth');
    Route::get('/estudio-imagenes/{imagenId}/{estudioId}/{userCitaId}/{ConsultaAsignado}/edit', [App\Http\Controllers\Admin\EstudioImagenesController::class, 'edit'])->middleware('auth');
    
    Route::resource('template-formulario', '\App\Http\Controllers\Admin\FormularioConfigurationController');
    Route::get('template-formulario/{configuration}/edit', [FormularioConfigurationController::class, 'edit'])->name('template-formulario.edit');
    Route::put('template-formulario/{configuration}', [FormularioConfigurationController::class, 'update'])->name('template-formulario.update');
    Route::get('template-formulario/{configuration}/delete', [FormularioConfigurationController::class, 'destroy'])->name('template-formulario.destroy');
    
    Route::get('template-formulario/{configurationId}/consulta/{consultaId}', [FormularioConfigurationController::class, 'showFormulario'])->name('template-formulario.showFormulario'); //mostrar por primera vez
    Route::post('template-formulario/{configurationId}/consulta/{consultaId}', [FormularioConfigurationController::class, 'storeFormulario'])->name('template-formulario.storeFormulario');
    
    Route::get('template-formulario/{configurationId}/{consultaId}/{userCitaId}/showTemplate', [FormularioConfigurationController::class, 'showTemplate']);
    
    Route::resource('configuracion-descargas', '\App\Http\Controllers\Admin\UserConfigDownload');

    


});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout.get');


///administracion formulario consulta

Route::get('formularios/{id}/{consultaId}', [FormularioController::class, 'show'])->name('formularios.show');
Route::post('formularios/{id}/{consultaId}', [FormularioController::class, 'store'])->name('formularios.store');
Route::put('formulario_entries/{entryId}', [FormularioController::class, 'updateFormularioConsulta'])->name('formulario_entries.update');


Route::get('formulario_entries/{entryId}/show_saved', [FormularioConfigurationController::class, 'showFormularioGuardado'])->name('formulario_entries.show_saved'); //mostrar lo que se guardo
