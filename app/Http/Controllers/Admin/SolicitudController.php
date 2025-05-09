<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\NotificationUser;
use App\Models\CatalogPrice;
use App\Models\Clinica;
use App\Models\ClinicaUser;
use App\Models\Comment;
use App\Models\Consultorio;
use App\Models\LogSystem;
use App\Models\Setting;
use App\Models\Solicitud;
use App\Models\SolicitudPaciente;
use App\Models\SolicitudUsuario;
use App\Models\User;
use App\Models\VinculacionSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function taskSolicitud($solicitudId, $task)
    {
        $solicitud = Solicitud::select(
            'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'is_cedula_valid', 'estatus', 'comprobante', 'fecha_vencimiento', 'name', 'vapellido', 'segundo_apellido', 'vcedula', 'clinica', 'tdireccion', 'solicitudes.user_id', 'solicitud_origin_id'
            )
            ->where('solicitudes.id', $solicitudId)
            ->join('catalog_prices', 'catalog_prices.id', 'solicitudes.solicitud_origin_id')
            ->join('users', 'users.id', 'solicitudes.user_id')
            ->first();
        
        $fecha_vencimiento = $solicitud->fecha_vencimiento != '' ? $solicitud->fecha_vencimiento : date('Y-m-d', strtotime('+1 year'));
        if ($task == 1) {
            return view('administracion.solicitudes.tarea1', compact('solicitud', 'fecha_vencimiento'));
            # code...
        } else {
            return view('administracion.solicitudes.tarea2', compact('solicitud', 'fecha_vencimiento'));
            # code...
        }
        
    }

    public static function validateCedula($userId, $solicitudId, Request $request)
    {
        $validate = User::validateCedula($userId, $solicitudId, $request);
        return response()->json($validate);
    }

    public static function estatusSolicitudEspera($solicitudId)
    {
        return view('espera');
    }

    public static function paymentCardStore(Request $request)
    {
        $validate = Solicitud::paymentCardStore($request);
        return response()->json($validate);
    }

    public static function paymentTransferStore(Request $request)
    {
        $validate = Solicitud::paymentTransferStore($request);
        return response()->json($validate);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $query = null;
        $catalogPrices = CatalogPrice::all();
        $users = null;
        
        if (Auth::user()->hasRole('administrador')) {
            $users = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['medico', 'auxiliar', 'secretario']);
            })->get();
        }   
        return view('administracion.solicitudes.frm', compact('query', 'catalogPrices', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $solicitud = Solicitud::saveEdit($request);
        return response()->json($solicitud);
    }

    public function resetSolicitud($solicitudId)
    {
       $solicitud = Solicitud::reset($solicitudId);
       return response()->json($solicitud);
    }

    public function showDataRenew($solicitudId)
    {
        $title = 'Elije un elemento para activar';
        $solicitudes = Solicitud::select('cantidad', 'solicitud_origin_id', 'cantidad')->where('id', $solicitudId)->first();
        $cantidad = $solicitudes->cantidad;
        $users = null;
        $cons = null;
        if ($solicitudes->solicitud_origin_id == 2) { //usuarios
            $users        = User::GetListUsers(null, 0);
            
        }
        if ($solicitudes->solicitud_origin_id == 3) { //consultorios
            $cons              = Consultorio::getAll(null, 0);
            
        }
        $view = \View::make('listado_solicitud_renovacion', compact('solicitudes', 'users', 'cantidad', 'solicitudId', 'cons'))->render();
        $data = array(
            'title' => $title,
            'content' => $view
        );
        return response()->json($data);
        //return view('listado_solicitud_renovacion', compact('solicitudes', 'users', 'cantidad'));
    }

    public function storeSolicitudes(Request $request)
    {
        SolicitudUsuario::saveRenew($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $settings = Setting::where('id', 1)->first();
        $solicitudSourceId = Solicitud::select('solicitudes.*', 'users.name as name', 'users.vapellido as vapellido', 
                    'users.segundo_apellido as segundo_apellido', 'users.email', 'ttelefono')
                    ->join('users', 'users.id', 'solicitudes.user_id')
                    ->where('solicitudes.id', $id)->first();

        if ($solicitudSourceId != null && $solicitudSourceId->source_id == 0) {
            $solicitud = Solicitud::select('solicitudes.*', 'users.name as name', 'users.vapellido as vapellido', 
                    'users.segundo_apellido as segundo_apellido', 'users.email', 'ttelefono')
            ->join('users', 'users.id', 'solicitudes.user_id')
            ->where('solicitudes.id', $id)
            ->join('packages', 'packages.id', '=', 'solicitudes.solicitud_origin_id')
                ->addSelect('packages.nombre as package_nombre', 'precio')
                ->with(['package.items.catalogPrice'])->first();
        } else {
            $solicitud = Solicitud::select('solicitudes.*', 'users.name as name', 'users.vapellido as vapellido', 
            'users.segundo_apellido as segundo_apellido', 'users.email', 'ttelefono', 'catalog_prices.nombre as package_nombre', 'catalog_prices.precio as precio')
            ->join('users', 'users.id', 'solicitudes.user_id')
            ->join('catalog_prices', 'catalog_prices.id', 'solicitudes.solicitud_origin_id')
            ->where('solicitudes.id', $id)->first();
        }
        $comments = Comment::where([
            'type' => 2,
            'idRel' => $id,
        ])->get();
        //obtener el paquete activo de uso de sistema
        $pacientes = SolicitudPaciente::where('solicitud_id', $id) ->with('paciente')->get();
        $clinicas   = Clinica::getAll();
        $my_clinics = ClinicaUser::where('user_id', $solicitud->user_id)->get();
        $roles = array('administrador', 'medico', 'auxiliar', 'secretario');
        $clinicasVincular = Clinica::select('clinica.idclinica as id', 'clinica.tnombre as nombre')
                            ->where('idusrregistra', $solicitud->user_id)->get();
        $consultorioVincular = Consultorio::where('idusrregistra', $solicitud->user_id)->get();
        $usuarioVincular = User::where('usuario_principal', $solicitud->user_id)
                            ->whereHas('roles', function ($q) use($roles) {
                                $q->whereIn('name', $roles);
                                })
                            ->get();
        //$getVinculacion = VinculacionSolicitud::getMyVinculacion($id);
        $fecha_vencimiento = $solicitud->fecha_vencimiento != '' ? $solicitud->fecha_vencimiento : date('Y-m-d', strtotime('+1 year'));
        return view('administracion.solicitudes.solicitud', compact('solicitud', 'usuarioVincular',  'consultorioVincular', 'id', 'clinicasVincular', 'comments', 'fecha_vencimiento', 'my_clinics',  'pacientes', 'clinicas', 'settings'));
    }

    public function adjuntarComprobante(Request $request)
    {
        $solicitudId = $request->solicitudId;
        $estatus = isset($request->estatus) ? $request->estatus : null;
        
        $fechaVencimiento = isset($request->fecha_vencimiento) ? $request->fecha_vencimiento : null;
        // Si el archivo "comprobante" está presente, aplica validación y procesamiento
        
        if ($request->hasFile('comprobante')) {
            // Validar el archivo de comprobante
            $validator = Validator::make($request->all(), [
                'comprobante' => 'mimes:jpeg,png,jpg,pdf|max:1024' // Máximo 1MB
            ]);

            // Si la validación falla, redireccionar con errores
            if ($validator->fails()) {
                
                return back()->withErrors($validator)->withInput();
            }

            // Procesar y guardar el archivo si la validación es exitosa
            if ($request->file('comprobante')->isValid()) {
                
                $archivo = $request->file('comprobante');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $rutaDestino = public_path('comprobante');

                $dataSolicitud = array(
                    'comprobante' => $nombreArchivo,
                    'estatus' => 2 //en revision
                );
                if ($estatus != null) {
                    $dataSolicitud['estatus'] = $estatus;
                    if ($estatus == 1) {
                        
                        $dataSolicitud['fecha_vencimiento'] = $fechaVencimiento;
                        $dataSolicitud['fecha_activacion'] = date('Y-m-d');
                        $dataSolicitud['precio_total'] = $request->precio_total;
                        $dataSolicitud['status'] = 1; //activar la solicitud porque la verificacion es exitosa
                        
                    }

                   
                }
                $archivo->move($rutaDestino, $nombreArchivo);
                Solicitud::where('id', $solicitudId)->update($dataSolicitud);

                //notificar si el usuario es doctor 
                if (Auth::user()->hasRole('medico')) {
                    $notification = new NotificationUser();
                    $notification->requestRegistration(Auth::user()->id, $solicitudId, 'transfer');
                }
                return redirect('/admin/solicitudes/'.$solicitudId);
            }
            return back()->with('error', 'Hubo un problema al cargar el archivo.');
            
        }

        // Si no hay archivo, solo se actualiza el estatus
        
        if ($estatus != null) {
            $dataSolicitud = array(
                'estatus' => $estatus,
            );
            $getSolicitud = Solicitud::find($solicitudId);
            $userNameAdmin = Auth::user()->name;

            if ($getSolicitud != null) {
                $titleSolicitud = Solicitud::$titleSolicitud[$getSolicitud->solicitud_origin_id];
                $statusSolicitud = Solicitud::$statusSolicitud[$estatus];
            }
            //dd($getSolicitud->solicitud_origin_id);
            if ($estatus == 1) { // paquete uso del sistema
                $dataSolicitud['fecha_vencimiento'] = $fechaVencimiento;
                $dataSolicitud['precio_total'] = $request->precio_total;
                $dataSolicitud['fecha_activacion'] = date('Y-m-d');

                if ($request->isNotify == 1) {
                    $notification =  new NotificationUser();
                    $notification->activatesSystem($solicitudId);
                }
                
                $userId = $getSolicitud != null ? $getSolicitud->user_id : null;

                if ($userId != null) {
                    $statusPackages = Solicitud::getUsedStatusPackages();
                    if (count($statusPackages) > 0) {
                        $solicitudId = $statusPackages['totalUsuariosSistema']['solicitudId'];
                        VinculacionSolicitud::saveVinculacion($userId, $solicitudId); 
                    }
                   
                    User::where('id', $userId)->update(['status' => 1]);
                }
                
            }
            LogSystem::createLog(Auth::user()->id, $titleSolicitud, 'Usuario administrador '.$userNameAdmin.' cambio el estatus a '.$statusSolicitud.' de la solicitud ID-'.$solicitudId);
            Solicitud::where('id', $solicitudId)->update($dataSolicitud);
            
        }

        return redirect('/home')->with('success', 'Solicitud actualizada correctamente');
        
    }


    public function storeSolicitudComment($solicitudId, Request $request)
    {
        Comment::commentSolicitud($solicitudId, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function deleteImg($solicitudId)
    {
        $pathComprobante = env('PATH_COMPROBANTE');
        $getSolicitud = Solicitud::find($solicitudId);
        unlink($pathComprobante.'/'.$getSolicitud->comprobante);
        
        Solicitud::where('id', $solicitudId)->update([
            'comprobante' => null
        ]);
        return redirect ('admin/solicitudes/'.$solicitudId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
