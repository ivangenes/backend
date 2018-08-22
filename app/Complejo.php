<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Complejo extends Model
{
   use SoftDeletes;
	protected $dates = ['deleted_at'];
    //relacion entre tablas por ejemplo.. de 1 a muchos..
 protected $table='complejos';
    protected $primarykey= 'id';
    protected $fillable = ['nombre','descripcion','telefono','domicilio','latitud','longitud','hora_apertura','hora_cierre','estado'];
   //crea campos created_add y update_date 
    public $timestamps = true; 

 public function canchas()
     {
        return $this->hasMany(Cancha::class);
     }

 public function imagens()
 {
    return $this->hasMany(Imagen::class);
 }

 /**
     * relacion de reserva y usuario.
     * @return la clase reserva con hasmany
     */
    public function users()
   {
      return $this->belongsToMany(User::class,'user_complejo','user_id','complejo_id');
   }

} 

