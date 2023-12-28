<?php

namespace App\Http\Controllers;

use App\Models\ClinicaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function clinicaYConsultorio(Request $request)
    {
        // Verifica si la variable de sesión 'clinica' existe y tiene un valor
        if ($request->session()->has('clinica') && $request->session()->get('clinica') !== '') {

            // Verifica si la variable de sesión 'consultorio' existe y tiene un valor
            if ($request->session()->has('consultorio') && $request->session()->get('consultorio') !== '') {
                // Ambas variables de sesión existen y tienen valores
                $response = [
                    'status' => 200,
                    'message' => 'Las variables de sesión "clinica" y "consultorio" existen y tienen valores.'
                ];
            } else {
                // La variable de sesión 'consultorio' no existe o no tiene valor
                $response = [
                    'status' => 500,
                    'message' => 'La variable de sesión "consultorio" no existe o no tiene valor.'
                ];
            }
        } else {
            // La variable de sesión 'clinica' no existe o no tiene valor
            $response = [
                'status' => 500,
                'message' => 'La variable de sesión "clinica" no existe o no tiene valor.'
            ];
        }

        // Devuelve el resultado en formato JSON
        return response()->json($response);
    }

    public function viewClinicaYConsultorio()
    {
        $my_clinics = ClinicaUser::where('user_id', Auth::user()->id)->get();
        return view('CinicaYConsultorio', compact('my_clinics'));
    }
}
