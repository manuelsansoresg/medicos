<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogPrice;
use App\Models\ItemPackage;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Package::with('items.catalogPrice')->orderBy('id', 'DESC')->get();
        return view('administracion.paquete.list', compact('query'));
    }

    public function getPackages(Request $request)
    {
        $isValidateCedula = $request->query('isValidateCedula', null);
        
        // Filtrar paquetes según el valor de isValidateCedula
        $packages = Package::where('status', 1)
                          ->where('isValidateCedula', $isValidateCedula)
                          ->with(['items.catalogPrice'])
                          ->get();
        
        // Retornar la vista parcial con los paquetes
        
        return view('packages.package_cards', compact('packages'))->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = null;
        $query = null;
        $elementos = CatalogPrice::where('status', 1)->get();
        $myElement = null;
        $elementosGuardados = null;
        return view('administracion.paquete.frm', compact('id', 'query', 'elementosGuardados', 'elementos', 'myElement'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Package::saveEdit($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $query = Package::find($id);
        $elementos = CatalogPrice::where('status', 1)->get();

        // Obtener los elementos ya guardados en ItemPackage para este Package
        $elementosGuardados = ItemPackage::where('package_id', $id)
                            ->pluck('catalog_price_id')
                            ->toArray(); // Convertir en un array de IDs
        
        // Obtener los valores máximos
        $elementosMaximos = [];
        if ($id) {
            $itemsPackage = ItemPackage::where('package_id', $id)->get();
            foreach ($itemsPackage as $item) {
                $elementosMaximos[$item->catalog_price_id] = $item->max;
            }
        }

        return view('administracion.paquete.frm', compact('id', 'query', 'elementos', 'elementosGuardados', 'elementosMaximos'));
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
        Package::saveEdit($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Package::where('id', $id)->delete();
    }
}
