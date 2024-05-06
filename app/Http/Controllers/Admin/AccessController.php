<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Access::getAll();
        return view('administracion.access.list', compact('query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $acces_id = null;
        $access = null;
        $users = User::getUsers();
        return view('administracion.access.frm', compact('access', 'acces_id', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
     public function store(Request $request)
     {
         $accessId = $request->input('acces_id');
         $userId = $request->input('data.user_id'); // Obtener el user_id del formulario
         
         $rules = [
             'data.user_id' => 'required|unique:access,user_id', // Regla unique básica
         ];
     
         if ($accessId != null) {
             // Escenario de actualización: ignorar el usuario actual solo si se ha cambiado el user_id
             $rules['data.user_id'] .= ',' . $accessId . ',id';
         }
         
         $messages = [
             'unique' => 'El valor del campo ya está en uso.', // Mensaje personalizado genérico
         ];
     
         $request->validate($rules, $messages);
     
         Access::saveEdit($request);
     }
     

     
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $access = Access::find($id);
        $users = User::getMyUsers($access->user_id);
        
        return view('administracion.access.show', compact('access', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $acces_id = $id;
        $access = Access::find($acces_id);
        $users = User::getUsers();
        return view('administracion.access.frm', compact('access', 'acces_id', 'users'));
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
