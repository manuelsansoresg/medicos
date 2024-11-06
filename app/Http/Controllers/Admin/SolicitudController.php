<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogPrice;
use App\Models\Comment;
use App\Models\Solicitud;
use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $query = null;
        $catalogPrices = CatalogPrice::all();
        return view('administracion.solicitudes.frm', compact('query', 'catalogPrices'));
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $solicitud = Solicitud::select(
                        'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad', 'comprobante', 'fecha_vencimiento', 'name', 'vapellido', 'solicitudes.user_id', 'catalog_prices_id'
                        )
                        ->where('solicitudes.id',$id)
                        ->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
                        ->join('users', 'users.id', 'solicitudes.user_id')
                        ->first();
        $comments = Comment::where([
            'type' => 2,
            'idRel' => $id,
        ])->get();
        //obtener el paquete activo de uso de sistema
        $paqueteActivo = Solicitud::getPaqueteActivo($solicitud);
        
        $fecha_vencimiento = $solicitud->fecha_vencimiento != '' ? $solicitud->fecha_vencimiento : date('Y-m-d', strtotime('+1 year'));
        return view('administracion.solicitudes.solicitud', compact('solicitud', 'id', 'comments', 'fecha_vencimiento', 'paqueteActivo'));
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
                    'comprobante' => $nombreArchivo
                );
                if ($estatus != null) {
                    $dataSolicitud['estatus'] = $estatus;
                    if ($estatus == 1) {
                        $dataSolicitud['fecha_vencimiento'] = $fechaVencimiento;
                    }
                }
                $archivo->move($rutaDestino, $nombreArchivo);
                Solicitud::where('id', $solicitudId)->update($dataSolicitud);

                return back()->with('success', 'Comprobante adjuntado correctamente.');
            }

            return back()->with('error', 'Hubo un problema al cargar el archivo.');
        }

        // Si no hay archivo, solo se actualiza el estatus
        if ($estatus != null) {
            $dataSolicitud = array(
                'estatus' => $estatus,
            );
            if ($estatus == 1) {
                $dataSolicitud['fecha_vencimiento'] = $fechaVencimiento;
            }
            Solicitud::where('id', $solicitudId)->update($dataSolicitud);
        }

        return back()->with('success', 'Estatus actualizado correctamente.');
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
