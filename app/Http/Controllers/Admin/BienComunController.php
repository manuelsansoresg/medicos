<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BienComun;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BienComunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::GetListUsers();
        $bienComunes = BienComun::with(['user', 'userRegistra'])
            ->where('idusrregistra', User::getMyUserPrincipal())
            ->orderBy('date', 'asc')
            ->orderBy('hour', 'asc')
            ->get();
        
        return view('administracion.bien_comun.index', compact('users', 'bienComunes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'hour' => 'required',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $bienComun = BienComun::create([
            'name' => $request->name,
            'date' => $request->date,
            'hour' => $request->hour,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'idusrregistra' => Auth::user()->id,
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bien común creado exitosamente',
            'data' => $bienComun->load(['user', 'userRegistra'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'date' => 'required|date',
                'hour' => 'required',
                'description' => 'nullable|string',
                'user_id' => 'required|exists:users,id'
            ]);

            $bienComun = BienComun::findOrFail($id);
            
            // Verificar que el usuario tenga permisos para editar
            if ($bienComun->idusrregistra !== auth()->id() && !auth()->user()->hasRole(['admin', 'doctor'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para editar este registro'
                ], 403);
            }

            $bienComun->update([
                'name' => $request->name,
                'date' => $request->date,
                'hour' => $request->hour,
                'description' => $request->description,
                'user_id' => $request->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bien común actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el bien común: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $bienComun = BienComun::findOrFail($id);
            
            // Verificar que el usuario tenga permisos para eliminar
            if ($bienComun->idusrregistra != User::getMyUserPrincipal() && !Auth::user()->hasRole('administrador')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para eliminar este registro'
                ], 403);
            }
            
            $bienComun->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Bien común eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el bien común'
            ], 500);
        }
    }
}
