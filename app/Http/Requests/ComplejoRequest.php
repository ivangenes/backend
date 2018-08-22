<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ComplejoRequest extends FormRequest
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
     * 
     */
    public function rules()
    {
        return [ 
            'id' => 'exists:complejos,id',
            'nombre' => 'required',//|exists:complejos,nombre',
            'descripcion'=> 'required',
            'telefono'=> 'required|numeric',
            'domicilio'=> 'required',
            'latitud'=> 'required|numeric',
            'longitud'=> 'required|numeric',
            'hora_apertura'=> 'required',
            'hora_cierre'=> 'required',
            'estado' => 'required',
            'path'=> 'required',
            
            
        ];
    }

    public function messages()
    {
        return[
            'nombre.exists'=>'Ese :attribute ya existe',
            'id.exists'=>"no existe",
            'nombre.required'=>'El :attribute es requerido',
            'descripcion.required'=>':attribute es requerido',
            'telefono.required'=>'El :attribute es requerido',
            'telefono.numeric'=>'El :attribute debe ser numerico',
            'domicilio.required'=>'El :attribute es requerido',
            'latitud.required'=>'El campo :attribute es requerido',
            'latitud.numeric'=>'El campo :attribute debe ser numerico',
            'longitud.required'=>'El campo :attribute es requerido',
            'longitud.numeric'=>'El campo :attribute debe ser numerico',
            'hora_apertura.required'=>'La :attribute es requerido',
            'hora_cierre.required'=>' :attribute es requerido',
            'estado.required'=>':attribute es requerido',
            'path.required'=>'La imagen es requerida',            
            
             ];
    }
}
