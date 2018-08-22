<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Reserva extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
    protected $table='reservas';
    protected $primarykey= 'id';
    protected $fillable = ['fecha','duracion','horaDesde','horaHasta','cancha_id','user_id','estado'];
   //crea campos created_add y update_date 
    public $timestamps = true; 

 //relacion de muchos a 1 ejemplo.. no quire decir que sea asi
 public function user()
 {
     return $this->belongsTo(User::class,'user_id','id' );
 }
 public function cancha()
 {
     return $this->belongsTo(Cancha::class,'cancha_id','id' );
 }

}
