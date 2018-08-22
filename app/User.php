<?php

namespace App;
use App\user_complejo;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;
      const USUARIO_VERIFICADO = 1;
       const USUARIO_NO_VERIFICADO = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'dni','name','email', 'password','telefono','domicilio','rol_id','estado','verification_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token',
    ];
     
     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     *///relacion de muchos a 1 ejemplo.. no quire decir que sea asi
   public function rol()
   {
       return $this->belongsTo(Rol::class,'rol_id','id' );
   
   }
   /**
     * relacion de reserva y usuario.
     * @return la clase reserva con hasmany
     */
   public function reserva()
   {
      return $this->hasMany(Reserva::class);
   }







    public function complejos()
   {
      return $this->belongsToMany(Complejo::class,'user_complejo','user_id','complejo_id');
   }
  




  /**
     *
     * @param $valor
     * realiza la asignacion
     */
  public function setName($valor)
  {
    $this->attributes['name'] = strtolower($valor);
  }

  /**
     *
     * @param $valor
     * @return trae el dato de nombre(realiza el get)
     */
  public function getName($valor)
  {
    return ucwords($valor);
  }

  /**
     *
     * @param $valor
     * realiza la asignacion del mail /minuscula
     */
  public function setEmail($valor)
  {
    $this->attributes['email'] = strtolower($valor);
  }
  /**
     *
     * @param $valor
     * @return trae el dato de email (realiza el get)
     */
   public function getEmail($valor)
  {
    return ucwords($valor);
  }

/**
     *
     * @return es verificado
     * 
     */
  public function esVerificado()
  {
    return $this->verified == User::USUARIO_VERIFICADO;
  }

  /**
     *
     * @return  no es verificado
     * 
     */
  public function NoEsVerificado()
  {
    return $this->verified == User::USUARIO_NO_VERIFICADO;
  }

  /**
     *
     * @return genera un token de verificacion de usuario
     */
  public static function generarVerificationToken()
  {
    return str_random(40);
  }



  
}
