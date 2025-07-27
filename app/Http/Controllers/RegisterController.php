<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'vapellido' => ['required', 'string', 'max:255'],
            'segundo_apellido' => ['required', 'string', 'max:255'],
            'ttelefono' => ['required', 'int'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $rol = 'medico';
            $tipo_establecimiento = $request->tipo_establecimiento == 'clinica' ? 1 : 2;
            $user = User::create([
                'name' => $request->name,
                'vapellido' => $request->vapellido,
                'segundo_apellido' => $request->segundo_apellido,
                'ttelefono' => $request->ttelefono,
                'tdireccion' => $request->tdireccion,
                'vcedula' => $request->vcedula,
                'tipo_establecimiento' => $tipo_establecimiento,
                'RFC' => $request->RFC,
                'especialidad' => $request->especialidad,
                'email' => $request->email,
                'status' => 0,
                'accepted_terms' => $request->accepted_terms === 'true' ? true : false,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($rol);
            $codeUser = User::createCode($user->id);
            
            User::where('id', $user->id)->update([
                'codigo_paciente' => $codeUser
            ]);
            
            
            session(['user_id' => $user->id]);
            return response()->json([
                'status' => 'success',
                'user' => User::find($user->id),
                'message' => 'Usuario registrado exitosamente'
            ], 201);

            

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al registrar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
