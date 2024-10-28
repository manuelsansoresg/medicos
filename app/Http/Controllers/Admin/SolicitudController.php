<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogPrice;
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
                        'solicitudes.id', 'catalog_prices.nombre', 'catalog_prices.precio', 'cantidad'
                        )
                        ->where('solicitudes.id',$id)
                        ->join('catalog_prices', 'catalog_prices.id', 'solicitudes.catalog_prices_id')
                        ->first();
        return view('administracion.solicitudes.solicitud', compact('solicitud'));
    }

    public function adjuntarComprobante(Request $request)
    {
        // Definir las reglas de validación
        $validator = Validator::make($request->all(), [
            'comprobante' => 'required|mimes:jpeg,png,jpg,pdf|max:1024', // Máximo 1MB
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

            $archivo->move($rutaDestino, $nombreArchivo);

            // Aquí podrías guardar información en la base de datos si es necesario
            return back()->with('success', 'Comprobante adjuntado correctamente.');
        }

        return back()->with('error', 'Hubo un problema al cargar el archivo.');
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
