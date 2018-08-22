<?php

namespace App\Http\Controllers;
use App\Cancha;
use App\Imagen;
use App\Complejo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ComplejoRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;


class ComplejoController extends ApiController
{
public function __construct()
    {
       // $this->middleware('client.credentials')->except(['store']);
    }


    /**
     * @return en json todos los complejos, como uun selec * from
     */
    public function index()
    {
        //todo los complejos que tenes registrado.. es como el select pero objeto
        $complejos = Complejo::all();
        return $this->showAllResponse($complejos);//tranforma el objeto a json
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
     * @param  $request
     * @return el insert de complejo, para un post
     */
    public function store(ComplejoRequest $request)
    {

       $buscar = Complejo::where('nombre','like',$request->nombre)->first();
       //dd(count($buscar));
       if(count($buscar) > 0)
       {
           return $this->showMessage("Error, ya existe este complejo");
       }
       else{
        $imagen64 = $request->path;
        $imagen64=base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagen64));

        $complejo = Complejo::create([
            'nombre'=>$request['nombre'],
            'descripcion'=>$request['descripcion'],
            'telefono'=>$request['telefono'],
            'domicilio'=>$request['domicilio'],
            'latitud'=>$request['latitud'],
            'longitud'=>$request['longitud'],
            'hora_apertura'=>$request['hora_apertura'],
            'hora_cierre'=>$request['hora_cierre'],
            'estado'=>$request['estado']

        ]);
         $nombreIMG=str_random(40);
         $nombreIMG=$nombreIMG.'.jpeg';
         file_put_contents(public_path('img/').$nombreIMG, $imagen64);
         $imagen =  Imagen::create([
            'path'=>$nombreIMG,
            'descripcion'=>$request->descripcionima,
            'estado'=>$request->estadoima,
            'complejo_id'=>$complejo->id,
            
        ]);


         //$prueba=$complejo+" "+$imagen;

        return $this->showOneResponse($complejo);
    }
 }

    /**
     * @param $id del complejo 
     * @return trae el complejo en json
     * Trae el complejo y los transforma a json
     */
public function show(Complejo $complejo)
    {
       // $complejo = Complejo::findOrFail($complejo);
        return $this->showOneResponse($complejo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     *
     * @param  $request
     * @param  int  $id
     * Actualizacion, para put
     */
public function update(ComplejoRequest $request, Complejo $complejo, Imagen $imagen)
    {
      //  $complejo = Complejo:: findOrFail($id);//

        $complejo->fill([
            'nombre' =>$request['nombre'],
            'descripcion'=>$request['descripcion'],
            'telefono'=>$request['telefono'],
            'domicilio'=>$request['domicilio'],
            'latitud'=>$request['latitud'],
            'longitud'=>$request['longitud'],
            'hora_apertura'=>$request['hora_apertura'],
            'hora_cierre'=>$request['hora_cierre'],
            'estado'=>$request['estado']
        ]);

    if($request->path <> "10")
        {  
         $imagen64 = $request->path;
         $imagen64=base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagen64));
         $nombreIMG=str_random(40);
         $nombreIMG=$nombreIMG.'.jpeg';
         file_put_contents(public_path('img/').$nombreIMG, $imagen64);


          $id=$request['idima'];

        
        $imagen=Imagen::find($id);
         
        $imagen->fill([
                    
                      'path'=>$nombreIMG,
                      'descripcion'=>$request['descripcionima'],
                      'estado'=>$request['estadoima'],
                      'complejo_id'=>$complejo->id
                
        ]);

        $imagen->save();
        $complejo->save();
        return $this->showOneResponse($imagen);
        }
        
        $complejo->save();
         return $this->showOneResponse($complejo);
       

        
    }

    /**
     * Destroy
     */
    public function destroy(Complejo $complejo,Cancha $can)
    {
               
        $id= $complejo->id;
       
        $comp=Complejo::with('canchas')->where('id',$id)->get();
       
        foreach ($comp as  $value) {  
                $canchas=$value->canchas;
                foreach ($canchas as $value2) {
                         $idCancha= $value2->id;
                         $can=Cancha::find($idCancha);    
                         //$can->fill(['deleted_at'=>$date]);
                          $can->delete();
                         // $can->save();
                        }
                        
            }  
              $complejo->delete();
       
      
        //$complejo->save();
        return $this->showMessage("Se dio de baja Correctamente.");
    }
    

    
    /**
     * @param ninguno
     * @return json innerjoin de complejo con cancha
     */
    public function all()
    {
        $complejos = Complejo::With('canchas')->get();
        return $this->showAllResponse($complejos);
    }

    public function allReservasComplejos (Request $request)
    {
        $parametro = $request->all();
        $fecha = $parametro['fecha'];
        $id = $parametro['id'];
        $complejo=Complejo::with('canchas.reserva.user')->where('id',$id)->get();
        $prueba=[];



         foreach ($complejo as  $value) {  
            $canchas= $value->canchas;
            foreach($canchas as $value2)
            {
                $reserva=$value2->reserva;
                foreach($reserva as $value3)
                {
                   // $value3=$value3::where('fecha','like',$fecha.'%')->get();
                   // $publicacion = Publicacion::where('updated_at', 'like', $updated_at.'%')->get();
                   if($value3->fecha ==$fecha)
                    $prueba[]=array('reserva'=>$value3,$value2);

                }
                

                //$prueba[]=array('fecha'=>$reserva->fecha,
                    //            'horaDesde'=>$reserva->horaDesde);
            }

         }

        return $this->showAllResponse(Collection::make($prueba));
    }
    /**
    * @param $nombre para filtrar
    * @return el complejo buscado 
    */
    public function buscar($nombre)
    {  

        $buscar = Complejo::with('imagens')->get();
        if (isset($nombre))
        {
            $buscar=Complejo::with('imagens')->where('nombre','like','%'.$nombre.'%')->get();
            return $this->showAllResponse($buscar);
        }
        return $this->showAllResponse($buscar);
    
    }
       


    public function traerImagen()
    {
       $buscar = Complejo::with('imagens')->get();
       return $this->showAllResponse($buscar);
    }

    public function traerComplejoImagen(Request $request)
    {   
        $id = $request->id;
         
       $buscar = Complejo::with('imagens')->where('id',$id)->get();
       return $this->showAllResponse($buscar);
    }
    


    public function CanchasDeUnComplejo($id)
    {
        $complejo = Complejo::with('canchas')->where('id',$id)->get();
        foreach ($complejo as  $value) {  
            $prueba[]= array($value->canchas);
        }
        return $this->showAllResponse(Collection::make($prueba));
    }


    public function cantidadComplejoMes()
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

    $ene = DB::table('complejos')->whereBetween('created_at',[$enero,$febrero])->count();
      $feb = DB::table('complejos')->whereBetween('created_at',[$febrero,$marzo])->count();
       $mar = DB::table('complejos')->whereBetween('created_at',[$marzo,$abril])->count();
        $abr = DB::table('complejos')->whereBetween('created_at',[$abril,$mayo])->count();
         $may = DB::table('complejos')->whereBetween('created_at',[$mayo,$junio])->count();
          $jun = DB::table('complejos')->whereBetween('created_at',[$junio,$julio])->count();
           $jul = DB::table('complejos')->whereBetween('created_at',[$julio,$agosto])->count();
            $ago = DB::table('complejos')->whereBetween('created_at',[$agosto,$septiembre])->count();
             $sep = DB::table('complejos')->whereBetween('created_at',[$septiembre,$obtubre])->count();
              $obt = DB::table('complejos')->whereBetween('created_at',[$obtubre,$noviembre])->count();
               $nov = DB::table('complejos')->whereBetween('created_at',[$noviembre,$diciembre])->count();
                $dic = DB::table('complejos')->whereBetween('created_at',[$diciembre,$enero])->count();

             $ano = DB::table('complejos')->whereBetween('created_at',[$enero,$diciembre])->count();
             $total = DB::table('complejos')->count();
    //$a = $user->count();
                
                $prueba[]= array('anio'=>$ano,
                                 'Enero'=>$ene,
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
                                 'Total_registrados'=>$total,
                                ); 

               

     return $this->showAllResponse(Collection::make($prueba));
  }
    

}
