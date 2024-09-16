<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstudioImagen;
use Illuminate\Http\Request;

class EstudioImagenesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($estudioId, $userCitaId, $ConsultaAsignado)
    {
        $image = null;
        return view('administracion.consulta.formImagenesEstudio', compact('ConsultaAsignado', 'userCitaId',  'estudioId', 'image'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        EstudioImagen::saveImage($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($estudioId, $userCitaId, $ConsultaAsignado)
    {
        $query = EstudioImagen::where('estudio_id', $estudioId)->get();
        return view('administracion.consulta.listadoImagenesEstudio', compact('ConsultaAsignado', 'userCitaId', 'query', 'estudioId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($imagenId, $estudioId, $userCitaId, $ConsultaAsignado)
    {
        $image = EstudioImagen::find($imagenId);
        return view('administracion.consulta.formImagenesEstudio', compact('ConsultaAsignado', 'userCitaId',  'estudioId', 'imagenId', 'image'));
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
        EstudioImagen::deleteImage($id);
    }
}
