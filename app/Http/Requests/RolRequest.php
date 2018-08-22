<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class RolRequest extends FormRequest
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
        return [
            'descripcion' => 'required'
            //
        ];
    }
    public function messages()
    {
        return [
            'descripcion.required' => 'El :attribute es requerido'
        ];
    }
}
