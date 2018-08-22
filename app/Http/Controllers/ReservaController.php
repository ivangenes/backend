<?php

namespace App\Http\Controllers;

use App\Cancha;
use App\Reserva;
use App\Complejo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReservaRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;
class ReservaController extends ApiController
{

  public function __construct()
  {
    //$this->middleware('client.credentials')->only(['show']);
   // $this->middleware('auth:api')->except(['show']);
  }
    /**
     * @return todas las reservas que tenes registrado.. es como el select pero objeto
     */
    public function index()
    {

        $reservas = reserva::all();
        return $this->showAllResponse($reservas);//tranforma el objeto a json
    }

    /**
    *
     */
    public function create()
    {
        //
    }

    /**
     * Este metodo utilizo para realizar un ruta post ala bd 
     */

    public function store(ReservaRequest $request)
    {
        

        $reserva=Reserva::create([
            'fecha'=>$request['fecha'],
            'duracion'=>$request['duracion'],
            'horaDesde'=>$request['horaDesde'],
            'horaHasta'=>$request['horaHasta'],
            'cancha_id'=>$request['cancha_id'],
            'user_id'=>$request['user_id'],
            'estado'=>$request['estado']
        ]);
        return $this->showOneResponse($reserva);
    }

    /**
     *@param modelo $reserva 
     *@return trae el registro con el parametro $Id
     * con este metodo busca el registro $id y que no tenga errores, devuelve un json
     */
    public function show(Reserva $reserva)
    {
       // $reserva = Reserva::findOrFail($id);
        return $this->showOneResponse($reserva);

    }

    /**
     * @param reserva/id 
     * @return eliminancion de la id
     */
    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return $this->showOneResponse($reserva);
    }



    /**
     * busca que notenga errores, la id, realiza una modificacion 
     */
    public function update(ReservaRequest $request,Reserva $reserva)
    {
        //$reserva=Reserva::findOrFail($id);
        $reserva-> fill([
            'fecha'=>$request['fecha'],
            'duracion'=>$request['duracion'],
            'horaDesde'=>$request['horaDesde'],
            'horaHasta'=>$request['horaHasta'],
            'cancha_id'=>$request['cancha_id'],
            'user_id'=>$request['user_id'],
            'estado'=>$request['estado']
            

        ]);
        $reserva->save();
        return $this->showOneResponse($reserva);
    }

    /**
     * @return todas las reservas con su cancha complejo y usuario 
     */
    public function all_reservas()
    {
        $reservas = Reserva::With('cancha.complejo','user')->get();
        return $this->showAllResponse($reservas);
    }

    public function reservasDeUnaCancha(Request $request)
    {
        $parametro = $request->all();
        $id=$parametro['id'];
        $reservas = Reserva::With('cancha.complejo')->where('id',$id)->get();
        return $this->showAllResponse($reservas);
    }

    public function reservasDeUnaCanchaFecha(Request $request)
    {
        $parametro = $request->all();
       // $id=$parametro['id'];
        $fecha=$parametro['fecha'];

        $reservas = Reserva::With('cancha.complejo')->where('fecha',$fecha)->get();
        return $this->showAllResponse($reservas);
    }


    /**
    * @param Se le pasa el parametro por Frond-end de fecha y horaDesde
    * Metodo que trae las canchas disponibles al horario ingresado, en este caso estatico por API
    * @return canchas disponibles, o canchas que no tienen reserva
    */
    public function reservaid(Request $request)
    {
      $parametro = $request->all();
      
       $fecha = $parametro['fecha'];
       $horaDesde = $parametro['horaDesde'];
       $horaHasta = $parametro['horaHasta'];
       if (isset($fecha) && isset($horaDesde) && isset($horaHasta)) {
         # code...
       
        $prueba=[];

        $cancha=Cancha::whereDoesntHave('reserva',function($query) use ($fecha, $horaDesde,$horaHasta){
            $query->where('fecha', [$fecha])
            ->where('horaHasta','>',$horaDesde)
            ->where('horaDesde','<', $horaHasta);
        })->get(); 

        foreach ($cancha as  $value) {  
           // $cancha1=$value->complejo;
            $prueba[]= array('complejo'=>$value->complejo->nombre,
             'descripcion'=>$value->complejo->descripcion,
             'telefono'=>$value->complejo->telefono,
             'domicilio'=>$value->complejo->domicilio,
             'cancha'=>$value->descripcion,
             'precio'=>$value->precio,
             'id'=>$value->id,
             'horaHasta'=>$value->horaHasta);
            //dd($value->complejo->nombre);
        }   
         
                
        return $this->showAllResponse(Collection::make($prueba));
        }
        else
        {
          return $this->errorResponse('debe completar los campos requeridos',405);
        }
    }

 public function reservaLibresdeUnComplejo(Request $request)
    {
      $parametro = $request->all();
      $id = $parametro['id'];
      $fecha = $parametro['fecha'];
      $horaDesde = $parametro['horaDesde'];
      $horaHasta = $parametro['horaHasta'];
       if (isset($fecha) && isset($horaDesde) && isset($horaHasta)) {
         # code...
       
        $prueba=[];

        $cancha=Cancha::whereDoesntHave('reserva',function($query) use ($fecha, $horaDesde, $horaHasta){
            $query->where('fecha', [$fecha])
             ->where('horaHasta','>',$horaDesde)
            ->where('horaDesde','<', $horaHasta);

           
        })->get(); 

       // $canchas=Cancha::whereDoesntHave('reservas', function($query) use ($fecha, $horaDesde,$horaHasta, $idComp){
       //                $query->where('fecha',  [$fecha])
       //                      //->where('complejo_id', $idComp)
       //                      ->where('hora_hasta', '>', $horaDesde)
       //                      ->where('hora_desde', '<', $horaHasta)
       //                      ;
        foreach ($cancha as  $value) {  
            //$cancha1=$value->complejo;
            if ($value->complejo->id==$id)
            {
                $prueba[]=array('cancha'=>$value->descripcion,
                              'id'=>$value->id,
                              'precio'=>$value->precio); 
               
            }

        }   
        
        return $this->showAllResponse(Collection::make($prueba));
        }
        else
        {
          return $this->errorResponse('Debe completar los campos requeridos',405);
        }
    }


    public function reservaPorFecha(Request $request)
    {
      $parametro = $request->all();
      $id = $parametro['id'];
      $fecha = $parametro['fecha'];
      
       if (isset($fecha)) {
         # code...
       
        $prueba=[];

        $cancha=Cancha::whereDoesntHave('reserva',function($query) use ($fecha){
            $query->where('fecha', [$fecha]);
        })->get(); 

        foreach ($cancha as  $value) {  
            $cancha1=$value->complejo;
            if ($value->complejo->id==$id)
            {
                $prueba[]= array('cancha'=>$value->descripcion,
                                 'precio'=>$value->precio); 
            }
        }   
        return $this->showAllResponse(Collection::make($prueba));
        }
        else
        {
          return $this->errorResponse('Debe completar los campos requeridos',405);
        }
    }


    public function reservaUser(Request $request)
    {
        $date = Carbon::now();
        $parametro = $request->all();
    
        $id=$parametro['user_id'];

        $fechaHoy = $date->format('Y-m-d');
        $horaHoy = date( 'H:i', strtotime( '+1 hour' ) );
        $resultado;

       
        $reserva = Reserva::with('cancha.complejo','user')->where('user_id',$id) 
                                            ->where('fecha','>',$fechaHoy)
                                            ->orWhere('fecha','=',$fechaHoy)
                                            ->where('horadesde','>',$horaHoy)
                                            ->where('user_id',$id)->get();


        return response()->json(Collection::make(['data'=>$reserva]));
    }


    /**
    * @param 
    * @return horario mas reservado en el mes
    */
    public function cantDeReservasPorCancha(Request $request,Complejo $complejo )
    {
       
      $id=$request['id'];
       $cancha=Cancha::with('reserva')->where('complejo_id',$id)->get();
         
          foreach ($cancha as $value) 
                    { 

                     $idCancha[]= ['id_cancha'=>$value->id,
                                   'Nombre'=>$value->descripcion,
                                   'cantidad_reservas'=>$value->reserva->count()];
                    
                    } 



      return response()->json(Collection::make(['data'=>$idCancha]));
    }

    public function cantidadMaxReserva(Request $request)
    {

         $id=$request['id'];
       $cancha=Cancha::with('reserva')->where('deleted_at','=', null)
                                        ->where('complejo_id',$id)->get();
         
        foreach ($cancha as $value) 
             { 

             $idCancha[]= ['id Cancha:'=>$value->id,
                         'Nombre'=>$value->descripcion,
     
                         'hora_09'=>$value->reserva->where('horaDesde','09:00:00')->count(),
                         'hora_10'=>$value->reserva->where('horaDesde','10:00:00')->count(),
                         'hora_11'=>$value->reserva->where('horaDesde','11:00:00')->count(),
                         'hora_12'=>$value->reserva->where('horaDesde','12:00:00')->count(),
                         'hora_13'=>$value->reserva->where('horaDesde','13:00:00')->count(),
                         'hora_14'=>$value->reserva->where('horaDesde','14:00:00')->count(),
                         'hora_15'=>$value->reserva->where('horaDesde','15:00:00')->count(),
                         'hora_16'=>$value->reserva->where('horaDesde','16:00:00')->count(),
                         'hora_17'=>$value->reserva->where('horaDesde','17:00:00')->count(),
                         'hora_18'=>$value->reserva->where('horaDesde','18:00:00')->count(),
                         'hora_19'=>$value->reserva->where('horaDesde','19:00:00')->count(),
                         'hora_20'=>$value->reserva->where('horaDesde','20:00:00')->count(),
                         'hora_21'=>$value->reserva->where('horaDesde','21:00:00')->count(),
                         'hora_22'=>$value->reserva->where('horaDesde','22:00:00')->count(),
                         'hora_23'=>$value->reserva->where('horaDesde','23:00:00')->count(),
                         'hora_00'=>$value->reserva->where('horaDesde','00:00:00')->count()];
                    
             } 

           return response()->json(Collection::make(['data'=>$idCancha]));

    }
}