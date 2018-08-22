<?php

namespace App\Http\Controllers;
use App\User;
use App\Imagen;
use App\Complejo;
use App\user_complejo;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;

class UserController extends ApiController
{
    public function __construct()
    {
       //$this->middleware('client.credentials')->except(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->showAllResponse($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 'name', 'email', 'password','telefono','domicilio','rol_id'
     */
    public function store(UserRequest $request)
    {


        $emailVacio= $request->has('email');
        //dd($emailVacio);
        //$dnis=$request->has('dni');
        if(($emailVacio))
        {

            $ver=User::generarVerificationToken();
            $usuario1 = User::create ([
                'dni'=>$request['dni'],
                'name'=>$request['name'],
                'email'=>$request['email'],
                'password'=>bcrypt($request['password']),
                'telefono'=>$request['telefono'],
                'domicilio'=>$request['domicilio'],
                'rol_id'=>$request['rol_id'],
                'estado'=>$request['estado'],
                'verification_token'=>$ver,
            ]);
            return $this->showOneResponse($usuario1);
        }
        else
        {
            return $this->errorResponse('el Email es requerido',405);
        }
    }   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
       // $user= User::findOrFail($id);
       return $this->showOneResponse($user);
   }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {


        if ($request->has('email') && $user->email != $request->email) {
            $ver=User::generarVerificationToken();
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = $ver;
            $user->email = $request->email;

        }

        $rules = [
            'dni'=>'numeric|min:8',
            'name'=>'required',
            'telefono'=>'numeric|min:8',
            'domicilio'=>'required'
        ];


      $this->validate($request, $rules);

             $user->fill([
            'dni'=>$request['dni'],
            'name'=>$request['name'],
            //'email'=>$request['email'], desactivar el mail a
           // 'password'=>$request['password'],
            'telefono'=>$request['telefono'],
            'domicilio'=>$request['domicilio'],
            'rol_id'=>$request['rol_id'],
            'estado'=>$request['estado'],
            //'verification_token'=>$ver
        ]);
        $user->save();
        return $this->showOneResponse($user);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response y eliminacion
     */
    public function destroy(User $user)
    {
     $user->delete();
     return $this->showOneResponse($user);
 }


    /**
    * Este metodo trae el rol del usuario especificado
    * @param $token
    * @return listado de usuario con su rol 
    */
    public function show_oneUsuario($id)
    {
        $user=User::with('Rol')->where('id',$id)->get();
        return $this->showAllResponse($user);
    }

    /**
    * Este metodo devuelve todos los metodos
    * @return todos los usuarios con sus roles
    */
    public function allUser()
    {
     $users=User::with('Rol')->get();
     return $this->showAllResponse($users);
 }

    public function buscarUsuario($email)
      {  

        $buscar = User::with('rol')->get();
        if (isset($email))
        {
            $buscar=User::with('rol')->where('email','like','%'.$email.'%')->get();
                                     // ->where('rol_id',3)->get();
            return $this->showAllResponse($buscar);
        }
        return $this->showAllResponse($buscar);
    
       }    




    /**
    * @param User, el usuario que desea saber
    * @return devuelve todas las reservas que posee un usuario
    */
    public function ReservasDeUsuario(User $user)
    {
        //$reservas = User::with('Reserva')->get(); 
        $reservas = $user->reserva;
        return $this->showAllResponse($reservas);
    }

    /**
    * @param $name
    * @return metodo que reliza un like, busqueda del nombre 
    */
    public function buscar($name)
    {
        $buscar=User::where('name','like',$name.'%')->get();
        return $this->showAllResponse($buscar);

    }
    /**
    * Este metodo verifica la existencia del usuario con el token especificado
    * @param $token
    * @return mensaje de verificacion 
    */
    public function verify($token)
    {
        $user = User::where('verification_token',$token)->firstOrFail();
        $user->verified=User::USUARIO_VERIFICADO;
        $user->verification_token=null;

        $user->save();

        return $this->showMessage('La cuenta ha sido verificada');

    }


    /**
    * Este metodo reenvia el mail en el caso que no sea verificado .
    * @param User
    * @return reenvia el mail en el caso que el usuario no se haya verificado 
    */
    public function resend(User $user)
    {
        if ($user->esVerificado())
        {
            return $this->errorResponse('Este usuario ya ha sido verificado.', 409);
        }
        retry(5,function() use ($user)
        {
          Mail::to($user)->send(new UserCreated($user));  
      },100);
        
           // Mail::to($user)->send(new UserCreated($user));
        
        return $this->showMessage('El correo de verificación se ha reenviado');
    }



/**
    * Este metodo reenvia el mail en el caso que no sea verificado .
    * @param User
    * @return reenvia el mail en el caso que el usuario no se haya verificado 
    */
     public function rolEmail($email)
    {
        $user=User::with('Rol')->where('email',$email)->get();
        return $this->showAllResponse($user);
    }

/**
    * Este metodo reenvia el mail en el caso que no sea verificado .
    * @param User
    * @return reenvia el mail en el caso que el usuario no se haya verificado 
    */
    public function modificarRol(Request $request,User $user,$email)
    {
        $user->fill(['rol_id'=>$request['rol_id']])->where('email',$email);
        $user->save();
        return $this->showOneResponse($user);

    }

    /**
    * Este metodo reenvia el mail en el caso que no sea verificado .
    * @param request
    * @return asigna a un usuario admin un complejo
    */
    public function asignacionComplejo(Request $request,User $user)
    {
        
        $parametro = $request->all();
         $complejo_id=$request['complejo_id'];
         $user_id=$request['user_id'];
        // dd($parametro);
        $user->fill([
            //'dni'=>$request['dni'],
            'name'=>$request['name'],
            //'email'=>$request['email'], desactivar el mail a
           // 'password'=>$request['password'],
            'telefono'=>$request['telefono'],
            'domicilio'=>$request['domicilio'],
            'rol_id'=>$request['rol_id'],
            'estado'=>$request['estado']
            //'verification_token'=>$ver
        ]);
        $user->save();

        if($complejo_id != null)
        {
         $user_complejo =  user_complejo::create([
             'user_id'=>$user_id,
             'complejo_id'=>$complejo_id,
            
         ]);
        $user_complejo->save();
        }
        // else{
        //     return $this->errorResponse('No se asigno ni un complejo',500);
           
        // }
        return $this->showOneResponse($user);

    }


    /**
    * Este metodo reenvia el mail en el caso que no sea verificado .
    * @param User
    * @return traer datos de usuario admin con su complejo 
    */
    public function adminComplejo(Request $request)
    {

        $parametro = $request->all();

        $id = $parametro['id'];

        $user = User::find($id);

        //             foreach ($user->complejos as $complejo) {

        //                 $id = $complejo->id;     
        //                 $buscar = Complejo::with('imagens')->where('id',$id)->get();
        //                 $prueba[]= array($buscar);
        //             }
        // return $this->showAllResponse(Collection::make($prueba));
    $consulta1 = DB::table('user_complejo AS a')
    ->leftjoin('users AS m', 'm.id','=','a.user_id')
    ->leftjoin('complejos AS n', 'n.id','=','a.complejo_id')
    ->select('m.name AS Nombre','n.nombre AS Complejo','n.id AS IDcomplejo')
        
     ->where('m.rol_id','=',2 )
     ->Where('m.id','=',$id )
     ->Where('n.deleted_at','=', null)
    ->get();
    return $this->showAllResponse(Collection::make($consulta1));
  }  


  public function cantidadUsuarioMes()
  {
    $enero = '2018-01-01';
    $febrero= '2018-02-01';
    $marzo= '2018-03-01';
    $abril= '2018-04-01';
    $mayo= '2018-05-01';
    $junio= '2018-06-01';
    $julio= '2018-07-01';
    $agosto= '2018-08-01';
    $septiembre= '2018-09-01';
    $obtubre= '2018-10-01';
    $noviembre= '2018-11-01';
    $diciembre= '2018-12-01';

    $ene = DB::table('users')->whereBetween('created_at',[$enero,$febrero])->count();
      $feb = DB::table('users')->whereBetween('created_at',[$febrero,$marzo])->count();
       $mar = DB::table('users')->whereBetween('created_at',[$marzo,$abril])->count();
        $abr = DB::table('users')->whereBetween('created_at',[$abril,$mayo])->count();
         $may = DB::table('users')->whereBetween('created_at',[$mayo,$junio])->count();
          $jun = DB::table('users')->whereBetween('created_at',[$junio,$julio])->count();
           $jul = DB::table('users')->whereBetween('created_at',[$julio,$agosto])->count();
            $ago = DB::table('users')->whereBetween('created_at',[$agosto,$septiembre])->count();
             $sep = DB::table('users')->whereBetween('created_at',[$septiembre,$obtubre])->count();
              $obt = DB::table('users')->whereBetween('created_at',[$obtubre,$noviembre])->count();
               $nov = DB::table('users')->whereBetween('created_at',[$noviembre,$diciembre])->count();
                $dic = DB::table('users')->whereBetween('created_at',[$diciembre,$enero])->count();

             $ano = DB::table('users')->whereBetween('created_at',[$enero,$diciembre])->count();
             $total = DB::table('users')->count();
    //$a = $user->count();
                
                $prueba[]= array('Enero'=>$ene,
                                 'Febrero'=>$feb,
                                 'Marzo'=>$mar,
                                 'Abri'=>$abr,
                                 'Mayo'=>$may,
                                 'Junio'=>$jun,
                                 'Julio'=>$jul,
                                 'Agosto'=>$ago,
                                 'Septiembre'=>$sep,
                                 'Obtubre'=>$obt,
                                 'Noviembre'=>$nov,
                                 'Diciembre'=>$dic,
                                 'Total_anio'=>$ano,
                                 'Total_registrados'=>$total,
                                ); 


     return $this->showAllResponse(Collection::make($prueba));






  }

}
