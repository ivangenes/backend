<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Rol extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='roles';
    protected $primarykey= 'id';
    protected $fillable = ['descripcion'];
   //crea campos created_add y update_date 
    public $timestamps = true; 


    //relacion de muchos a 1 ejemplo.. no quire decir que sea asi
    public function users()
    {
       return $this->hasMany(User::class);
    }
}
