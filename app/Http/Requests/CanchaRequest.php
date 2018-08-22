<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class CanchaRequest extends FormRequest
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
            
            'descripcion'=> 'required',
            'precio'=> 'required|numeric',
            'horaDesde'=> 'required',
            'horaHasta'=> 'required',
            'estado'=>'required',
            'complejo_id'=>'required|exists:complejos,id'
        ];
    }
    public function messages()
    {
        return[
            'descripcion.required'=>':attribute es requerido',
            'precio.required'=>'El :attribute es requerido',
            'precio.numeric'=>'El :attribute debe ser numerico',
            'horaDesde.required'=>'El campo :attribute es requerido',
          //  'horaDesde.time'=>'El campo :attribute esta mal definido',
            'horaHasta.required'=>'El campo :attribute es requerido',
          //  'horaHasta.time'=>'El campo :attribute esta mal definido',
            'estado.required'=>'El  campo :attribute es requerido',
            'complejo_id.exists'=>'El complejo no existe',
            'complejo_id.required'=>'El campo :attribute es requerido'
              ];
    }   
    
    

}
