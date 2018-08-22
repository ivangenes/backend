<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class UserRequest extends FormRequest
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
    { //dd($this->id);
        return [
            
            'dni'=>'min:8',
           // 'email'=>'required',
            //'name'=>'required',
            'email'=>'email|unique:users,email|required' . $this->id,
            'password'=>'min:5|confirmed',
            'telefono'=>'min:8',//required
           // 'domicilio'=>'required',//required
            'rol_id'=>'exists:roles,id',//required
           // 'estado'=>'required'
        ];
    }
    public function messages()
    {
        return[
            'password.confirmed'=> "Confirmacion de :attribute es incorrecto",
            'email.required'=>'El :attribute es requerido',
            'name.required'=>'El :attribute es requerido',
            'dni.unique'=>'El :attribute ya existe',
            'email.email'=>'El valor :attribute no tiene el formato correcto.',
            'email.unique'=>':attribute no puede repetirse',
            'telefono.required'=>'El :attribute es requerido',
            'password.required'=>'El :attribute es requerido',
            'telefono.numeric'=>'El :attribute debe ser numerico',
            'telefono.min'=>'El :attribute debe ser un minimo de :min caracteres',
            'telefono.required'=>'El :attribute debe ser requerido',
            'domicilio.required'=>'El :attribute es requerido',
            'estado.required'=>'El :attribute es requerido',
            'rol_id.required'=>'El :attribute es requerido',
            'rol_id.exists'=>'El :attribute no existe',
            'domicilio.required'=>'El :attribute no existe',
            'estado.required'=>'El :attribute es requerido',
            'password.min'=>'El minimo numero de caracteres: 5 ',
            
        ];
    }
}
