<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class user_complejo extends Model
{
   use SoftDeletes;
	protected $dates = ['deleted_at'];
    protected $table='user_complejo';
    protected $primarykey= 'id';
    protected $fillable = ['user_id','complejo_id'];
   //crea campos created_add y update_date 
    public $timestamps = true; 


public function users()
{
    return $this->belongsTo(User::class,'user_id','id');
}
public function complejos()
{
    return $this->belongsTo(Complejo::class,'complejo_id','id');
}
}