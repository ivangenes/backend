<?php

namespace App\Http\Controllers;
use App\Cancha;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CanchaRequest;
use Illuminate\Database\Eloquent\Collection;

class CanchaController extends ApiController
{   
   
    /**
     * @return en json todos las canchas, como un selec * from
     */
    public function index()
    {
     $canchas = Cancha::all();
     return $this->showAllResponse($canchas); //response()->json(['data' => $canchas]);//tranforma el objeto a json
    }

    /**
          * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * @param  $request
     * @return el insert de cancha, para un post
     */
    public function store(CanchaRequest $request)
    {
        $cancha = Cancha::create([
            'descripcion'=>$request['descripcion'],
            'precio'=>$request['precio'],
            'horaDesde'=>$request['horaDesde'],
            'horaHasta'=>$request['horaHasta'],
            'estado'=>$request['estado'],
            'complejo_id'=>$request['complejo_id']
            

        ]);
        return $this->showOneResponse($cancha);
    } 

    /**
     *
     * @param  int  $id
     * @return devuelve en json el registro cancha especificado con el id
     */
    public function show(Cancha $cancha)
    {
       // $cancha = Cancha::findOrFail($id);
        return $this->showOneResponse($cancha);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * @param  $request
     * @param  int  $id
     * Actualizacion, para put
     */
    public function update(CanchaRequest $request, Cancha $cancha)
    {
      //  $cancha = Cancha::findOrFail($id);
        $cancha->fill([
                'descripcion'=>$request['descripcion'],
                'precio'=>$request['precio'],
                'horaDesde'=>$request['horaDesde'],
                'horaHasta'=>$request['horaHasta'],
                'estado'=>$request['estado'],
                'complejo_id'=>$request['complejo_id']

        ]);
        $cancha->save();
        return $this->showONeResponse($cancha);
    }

    /**
     * @param $cancha
     * @return response
     */
    public function destroy(Cancha $cancha)
    {
        $cancha->delete();
        return $this->showOneResponse($cancha);    
    }

    /**
     * @param id,
     * @return la cancha con su respectivo complejo..
     */
    public function show_one($id)
    {
        $cancha=Cancha::with('complejo')->where('id',$id)->get();
        return $this->showAllResponse($cancha);
    }

     public function reservasDeUnaCancha(Request $request)
    {
        $parametro = $request->all();
        $id=$parametro['id'];
        $reservas = Cancha::With('reserva')->where(['id',$id],['fecha',$fecha],['horaDesde',$horaDesde])->get();
        return $this->showAllResponse($reservas);
    }

}
