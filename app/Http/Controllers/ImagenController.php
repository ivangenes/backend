<?php

namespace App\Http\Controllers;

use App\Imagen;
use Illuminate\Http\Request;
use App\Http\Requests\ImagenRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;


class ImagenController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->except(['store','traerImagen']);
    }

     /**
     * @return en json todos las imagenes, como un selec * from
     */
    public function index()
    {
        //todo las imagenes que tenes registrado.. es como el select pero objeto
        $imagens = Imagen::all();
        return $this->showAllResponse($imagens);//tranforma el objeto a json
    }

    /**
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
    public function store(ImagenRequest $request)
    {
        Imagen::create([
            'path'=>$request->path->store(''),
            'descripcion'=>$request['descripcion'],
            'estado'=>$request['estado'],
            'complejo_id'=>$request['complejo_id']
            
            ]);
    }

    

    /**
     * @param $id de la imagen 
     * Trae la imagen y los transforma a json
     */
    public function show(Imagen $imagen)
    {
       // $imagen = Imagen::findOrFail($id);
        return $this->showOneResponse($imagen);//
    }



    /**
     
     */
    public function edit($id)
    {
        //
    }

    

    /**
     * @param  $request
     * @param  int  $id
     * Actualizacion, para put
     */
    public function update(ImagenRequest $request,Imagen $imagen)
    {
        //$imagen=Imagen::findOrFail($id);//
        $imagen-> fill([
                'path'=>$request['path'],
                'descripcion'=>$request['descripcion'],
                'estado'=>$request['estado'],
                'complejo_id'=>$request['complejo_id']
                
        ]);
        $imagen->save();
    }

    /**
    *@param imagen /id
    *@return response, eliminacion de la imagen
     */
    public function destroy(Imagen $imagen)
    {
        Storage::delete($imagen->path);
       $imagen->delete();
        return $this->showOneResponse($imagen);
    }
    public function traerImagen()
    {
        $imagen=Imagen::With('complejo')->get();
        return $this->showAllResponse($imagen);
    }
}
