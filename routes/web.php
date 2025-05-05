<?php

use App\Http\Controllers\Admin\FormularioConfigurationController;
use App\Http\Controllers\Admin\FormularioController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ClipPaymentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\Estudio;
use App\Models\UserCita;
use App\Models\FormularioEntry;
use App\Models\User;

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
Route::get('/registro-medico/salida', [App\Http\Controllers\HomeController::class, 'registroMedico']);

Route::get('/editProfile', [App\Http\Controllers\HomeController::class, 'editProfile'])->name('editProfile')->middleware('auth');

Route::get('/query/clinicaYConsultorio', [App\Http\Controllers\HomeController::class, 'clinicaYConsultorio'])->middleware('auth');
Route::get('/query/viewClinicaYConsultorio', [App\Http\Controllers\HomeController::class, 'viewClinicaYConsultorio'])->middleware('auth');
// Agregar esta ruta a tu archivo web.php
Route::get('/obtener-paquetes', '\App\Http\Controllers\Admin\PackageController@getPackages');

Route::group(['prefix' => 'admin'], function () {
    Route::resource('actividades', '\App\Http\Controllers\Admin\ActividadesController')->middleware('auth');
    
    Route::resource('citas', '\App\Http\Controllers\Admin\CitasController')->middleware('auth');
    Route::get('citas/{consultaAsignado}/{hora}/{fecha}/add', [App\Http\Controllers\Admin\CitasController::class, 'add'])->middleware('auth');
    Route::get('citas/{fecha}/{iddoctor}/{idconsultorio}/{idclinica}/set', [App\Http\Controllers\Admin\CitasController::class, 'setCita'])->middleware('auth');
    Route::get('citas/{consultaAsignado}/list', [App\Http\Controllers\Admin\CitasController::class, 'viewCitaConsultaAsignado'])->middleware('auth');
    Route::get('citas/{consultaAsignado}/consulta', [App\Http\Controllers\Admin\CitasController::class, 'consulta'])->middleware('auth');


    Route::resource('pacientes', '\App\Http\Controllers\Admin\PacientesController')->middleware('auth');
    Route::get('pacientes/get/search', [App\Http\Controllers\Admin\PacientesController::class, 'search'])->middleware('auth');
    Route::post('paciente/curp', [App\Http\Controllers\Admin\PacientesController::class, 'curp'])->middleware('auth');
    Route::post('paciente/vincular', [App\Http\Controllers\Admin\PacientesController::class, 'vincular'])->middleware('auth');
    Route::post('paciente/delete-vinculo', [App\Http\Controllers\Admin\PacientesController::class, 'deleteVinculo'])->middleware('auth');
    Route::post('paciente/share', [App\Http\Controllers\Admin\PacientesController::class, 'share'])->middleware('auth');
    
    Route::resource('pendientes', '\App\Http\Controllers\Admin\PendientesController')->middleware('auth');
    Route::resource('administracion', '\App\Http\Controllers\Admin\AdministracionController')->middleware('auth');
    
    Route::resource('clinica', '\App\Http\Controllers\Admin\ClinicaController')->middleware('auth');
    Route::get('/clinica/{clinica}/consultorio/get', [App\Http\Controllers\Admin\ClinicaController::class, 'consultorioGet'])->middleware('auth');
    Route::get('/clinica/consultorio/myConfiguration', [App\Http\Controllers\Admin\ClinicaController::class, 'consultorioGet'])->middleware('auth');
    Route::post('/clinica/consultorio/set', [App\Http\Controllers\Admin\ClinicaController::class, 'setClinicaConsultorio'])->middleware('auth');
    Route::get('/clinica/consultorio/get', [App\Http\Controllers\Admin\ClinicaController::class, 'getClinicaConsultorio'])->middleware('auth');
    Route::post('/clinica/vincular/store', [App\Http\Controllers\Admin\ClinicaController::class, 'storeVincular'])->middleware('auth');


    Route::resource('consultorio', '\App\Http\Controllers\Admin\ConsultoriosController')->middleware('auth');
    Route::get('/consultorio/{id}/{userId}/show', [App\Http\Controllers\Admin\ConsultoriosController::class, 'show'])->middleware('auth');
    Route::post('/consultorio/vincular/store', [App\Http\Controllers\Admin\ConsultoriosController::class, 'storeVincular'])->middleware('auth');

    Route::resource('usuarios', '\App\Http\Controllers\Admin\UserController')->middleware('auth');
    Route::get('usuarios/{pacientes}/permisos/get', [\App\Http\Controllers\Admin\UserController::class, 'permisosGet'])->middleware('auth');
    Route::post('/usuarios/vincular/store', [App\Http\Controllers\Admin\UserController::class, 'storeVincular'])->middleware('auth');

    Route::get('/usuarios/activar/{user_id}', [UserController::class, 'showActivationForm'])->name('users.activation.form');
    Route::post('/usuarios/activar/{user_id}', [UserController::class, 'activateUser'])->name('users.activation');
    Route::delete('/usuarios/ine/{type}/delete', [UserController::class, 'deleteIneImage'])->name('users.ine.delete');
    
    Route::get('/usuarios/{user_id}/consultorio/clinica/get', [UserController::class, 'getUsuarioConsultorioClinica'])->name('users.consultorio.get');

    Route::resource('catalogo', '\App\Http\Controllers\Admin\CatalogoController')->middleware('auth');
    Route::resource('paquete', '\App\Http\Controllers\Admin\PackageController')->middleware('auth');

    

    Route::resource('solicitudes', '\App\Http\Controllers\Admin\SolicitudController')->middleware('auth');
    Route::get('solicitudes/{solicitudId}/task/{task}', [\App\Http\Controllers\Admin\SolicitudController::class, 'taskSolicitud'])->middleware('auth');
    Route::post('solicitudes/{userId}/{solicitudId}/cedula/validate', [\App\Http\Controllers\Admin\SolicitudController::class, 'validateCedula'])->middleware('auth');
    Route::get('solicitudes/{solicitudId}/estatus/1/', [\App\Http\Controllers\Admin\SolicitudController::class, 'estatusSolicitudEspera'])->middleware('auth');

    /* pagos en solicitus tarjeta o transferencia */
    Route::post('payment/card/save', [\App\Http\Controllers\Admin\SolicitudController::class, 'paymentCardStore'])->middleware('auth');
    Route::post('payment/transfer/save', [\App\Http\Controllers\Admin\SolicitudController::class, 'paymentTransferStore'])->middleware('auth');

    Route::post('solicitudes/{solicitudId}/adjuntarComprobante', [\App\Http\Controllers\Admin\SolicitudController::class, 'adjuntarComprobante'])->middleware('auth')->name('SolicitudAdjuntarComprobante');
    Route::get('solicitudes/{solicitudId}/imagen/delete', [\App\Http\Controllers\Admin\SolicitudController::class, 'deleteImg'])->middleware('auth')->name('SolicitudDeleteImg');

    
    Route::post('solicitudes/{solicitudId}/comment/store', [\App\Http\Controllers\Admin\SolicitudController::class, 'storeSolicitudComment'])->middleware('auth');
    Route::get('solicitudes/{solicitudId}/reset', [\App\Http\Controllers\Admin\SolicitudController::class, 'resetSolicitud'])->middleware('auth');
    Route::get('solicitudes/{solicitudId}/renew/showData', [\App\Http\Controllers\Admin\SolicitudController::class, 'showDataRenew'])->middleware('auth');
    
    Route::post('solicitudes/action/renew/store', [\App\Http\Controllers\Admin\SolicitudController::class, 'storeSolicitudes'])->middleware('auth');
    

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
    Route::post('/admin/expedientes/descargar-archivos', [App\Http\Controllers\Admin\ExpedienteController::class, 'descargarArchivos']);

    Route::resource('estudio-imagenes', '\App\Http\Controllers\Admin\EstudioImagenesController')->middleware('auth');
    Route::get('estudio-imagenes/{estudioId}/{userCitaId}/{ConsultaAsignado}', [App\Http\Controllers\Admin\EstudioImagenesController::class, 'show'])->middleware('auth');
    Route::get('/estudio-imagenes/{estudioId}/{userCitaId}/{ConsultaAsignado}/create', [App\Http\Controllers\Admin\EstudioImagenesController::class, 'create'])->middleware('auth');
    Route::get('/estudio-imagenes/{imagenId}/{estudioId}/{userCitaId}/{ConsultaAsignado}/edit', [App\Http\Controllers\Admin\EstudioImagenesController::class, 'edit'])->middleware('auth');
    
    Route::resource('template-formulario', '\App\Http\Controllers\Admin\FormularioConfigurationController');
    Route::get('template-formulario/{configuration}/edit', [FormularioConfigurationController::class, 'edit'])->name('template-formulario.edit');
    Route::put('template-formulario/{configuration}', [FormularioConfigurationController::class, 'update'])->name('template-formulario.update');
    Route::get('template-formulario/{configuration}/delete', [FormularioConfigurationController::class, 'destroy'])->name('template-formulario.destroy');
    Route::get('template-formulario/{configuration}/activar', [FormularioConfigurationController::class, 'activar'])->name('template-formulario.activar');
    
    Route::get('template-formulario/{configurationId}/consulta/{consultaId}', [FormularioConfigurationController::class, 'showFormulario'])->name('template-formulario.showFormulario'); //mostrar por primera vez
    Route::post('template-formulario/{configurationId}/consulta/{consultaId}', [FormularioConfigurationController::class, 'storeFormulario'])->name('template-formulario.storeFormulario');
    
    Route::get('template-formulario/{configurationId}/{consultaId}/{userCitaId}/showTemplate', [FormularioConfigurationController::class, 'showTemplate']);
    
    Route::resource('configuracion-descargas', '\App\Http\Controllers\Admin\UserConfigDownload');

    
    Route::resource('setting', '\App\Http\Controllers\Admin\SettingController');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/ganancias', [App\Http\Controllers\HomeController::class, 'gananciapdf'])->name('home');

Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout.get');


///administracion formulario consulta

Route::get('formularios/{id}/{consultaId}', [FormularioController::class, 'show'])->name('formularios.show');
Route::post('formularios/{id}/{consultaId}', [FormularioController::class, 'store'])->name('formularios.store');
Route::put('formulario_entries/{entryId}', [FormularioController::class, 'updateFormularioConsulta'])->name('formulario_entries.update');


Route::get('formulario_entries/{entryId}/show_saved', [FormularioConfigurationController::class, 'showFormularioGuardado'])->name('formulario_entries.show_saved'); //mostrar lo que se guardo

Route::post('/delete-temp-file', function (\Illuminate\Http\Request $request) {
    $filePath = $request->input('filePath');

    if (File::exists($filePath)) {
        File::delete($filePath);
        return response()->json(['status' => 'success']);
    }

    return response()->json(['status' => 'file_not_found'], 404);
})->name('delete-temp-file');

Route::get('/developer/payment-links', [ClipPaymentController::class, 'showPaymentLinkGenerator'])
    ->name('developer.payment-links')
   ; // Asegura que solo usuarios autenticados accedan


   // Rutas para integración con Clip
/* Route::get('/payment/clip', [App\Http\Controllers\ClipPaymentController::class, 'showPaymentForm'])->name('clip.payment.form');
Route::post('/payment/clip/create', [App\Http\Controllers\ClipPaymentController::class, 'createPayment'])->name('clip.payment.create');
Route::get('/payment/clip/callback', [App\Http\Controllers\ClipPaymentController::class, 'handleCallback'])->name('clip.payment.callback');
Route::get('/payment/clip/cancel', [App\Http\Controllers\ClipPaymentController::class, 'handleCancellation'])->name('clip.payment.cancel'); */

Route::resource('payment', '\App\Http\Controllers\Admin\PaymentController');
Route::post('payment/transfer/save', '\App\Http\Controllers\Admin\PaymentController@storeTransfer');



// ... existing code ...
Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register']);

Route::get('/registro-exitoso', function () {
    return view('auth.registro-exitoso');
})->name('registro.exitoso');

Route::get('/registro-exitoso-transfer', function () {
    return view('auth.registro-exitoso-transfer');
})->name('registro.exitoso.transfer');

Route::get('solicitud/{solicitudId}/comprobante/adjuntar', [HomeController::class, 'adjuntarComprobante'])->name('formularios.show');
Route::post('solicitud/{solicitudId}/comprobante/adjuntar/store', [HomeController::class, 'storeComprobante'])->name('formulario-adjuntado.store');
Route::get('solicitud/{solicitudId}/comprobante/exitoso', [HomeController::class, 'salidaComprobanteExitoso'])->name('solicitud.comprobante.exitoso');

Route::post('/admin/expedientes/descargar-archivos', [App\Http\Controllers\Admin\ExpedienteController::class, 'descargarArchivos']);