<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->route('user'); // Obtener el ID del usuario de la ruta
        $emailRules = [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($userId), // Ignorar el email actual al actualizar
        ];

        // Verificar si el método de solicitud es PUT o PATCH y si 'data[email]' está presente
        if ($this->isMethod('put', 'patch') && $this->input('data.email')) {
            return [
                'data.email' => $emailRules,
            ];
        }

        return [
            'name' => 'required',
            'email' => $emailRules,
            // Otras reglas de validación aquí
        ];
    }
}
