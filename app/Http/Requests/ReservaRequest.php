<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ReservaRequest extends FormRequest
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
     * rules son las reglas que necesitan cada campo para validaciones
     * @return array
     */
    public function rules()
    {
        return [
            'fecha'=> 'required|date',
            'duracion'=> 'required',
            'horaDesde'=> 'required',
            'horaHasta'=> 'required',
            'cancha_id'=> 'required',//|exists:canchas,id',
            'user_id'=> 'required|exists:users,id',
            'estado'=> 'required'

        ];
    }

    public function messages()
    {
        return[
                        'fecha.required'=>'El campo :attribute es requerido',
                        'duracion.required'=>'El campo :attribute es requerido',
                   // 'duracion.numeric'=>'La :attribute esta mal definido',
                        'horaDesde.required'=>'El campo :attribute es requerido',
                    //'horaDesde.numeric'=>'El campo :attribute esta mal definido',
                        'horaHasta.required'=>'El campo :attribute es requerido',
                  //  'horaHasta.numeric'=>'El campo :attribute esta mal definido',
                        'cancha_id.required'=>'El :attribute es requerido',
                        //'cancha_id.exists'=>'la cancha no existe',
                        'user_id.exists'=>'el usuario no existe',
                        'user_id.required'=>'El campo :attribute es requerido',
                        'estado.required'=>'El campo :attribute es requerido',
                        'fecha.date'=>'El campo :attribute no es valido'
            
        ];
    }
}
