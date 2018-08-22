<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Imagen extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
    protected $table='imagens';
    protected $primarykey= 'id';
    protected $fillable = ['path','descripcion','estado','complejo_id'];
   //crea campos created_add y update_date 
    public $timestamps = true; 


 public function complejo()
 {
    return $this->belongsTo(Complejo::class,'complejo_id','id');
 }
}
