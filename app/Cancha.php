<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cancha extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
    protected $table='canchas';
    protected $primarykey= 'id';
    protected $fillable = ['descripcion','precio','horaDesde','horaHasta','estado','complejo_id'];
   //crea campos created_add y update_date 
    public $timestamps = true; 
 //relacion de muchos a 1 ejemplo.. no quire decir que sea asi
public function complejo()
{
    return $this->belongsTo(Complejo::class,'complejo_id','id');
}
public function reserva()
{
   return $this->hasMany(Reserva::class);
}
}
