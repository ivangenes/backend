<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;
use App\Http\Controllers\ApiController;
use App\Http\Requests\RolRequest;

class RolController extends ApiController
{
    // public function __construct()
    // {
    //     parent::__construct();
    // }
    /**
     * @return todos los roles 
     */
    public function index()
    {
        $rol = Rol::all();
        return $this->showAllResponse($rol);//tranforma el objeto a json
    }

    /**
    *
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolRequest $request)
    {
        Rol::create([
            'descripcion'=>$request['descripcion']
        ]);   //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        // $rol=Rol::findOrFail($id);
         return $this->showOneResponse($rol);

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
    public function update(RolRequest $request, Rol $rol)
    {
       // $rol=Rol::findOrFail($id);
        $rol-> fill([
        'descripcion'=>$request['descripcion']
        ]);
        $rol->save();
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();
        return $this->showOneResponse($rol);
    }
}
