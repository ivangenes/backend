<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ImagenRequest extends FormRequest
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
            'path'=> 'required',
            'descripcion'=> 'required',
            'estado'=> 'required',
            'complejo_id'=> 'required|exists:complejos,id'
        ];
    }
    public function messages()
    {
        return [
            'path.required'=>'La ruta es requerida',            
            'descripcion.required' => 'El :attribute es requerido',
            'estado.required'=>'El :attribute es requerido',
            'complejo_id.exists'=>'El :attribute no existe',
            'complejo_id.required'=>'El :attribute es requerido'
        ];
    }
}
