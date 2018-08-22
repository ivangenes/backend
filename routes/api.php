<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|-------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/
//---------------------------------------------------------------------------------------
// Route::middleware('auth:api')->get('/user', function (Request $request)
//  {
//     return $request->user();
// });


//---------------------------------------------------------------------------------------
//rutas user
Route::post('user','UserController@store');
Route::get('user','UserController@index');
Route::get('user/{user}', 'UserController@show');//
Route::put('user/{user}','UserController@update');
Route::put('modificarRol/{email}','UserController@modificarRol');

Route::delete('user/{user}','UserController@destroy');//eliminacion logica
Route::get('ReservasDeUsuario/{user}','UserController@ReservasDeUsuario');//reserva de usuarios
Route::name('verify')->get('users/verify/{token}','UserController@verify');//coreo de verificacion
Route::name('resend')->get('users/{user}/resend','UserController@resend');//reenvio de mail
Route::get('show_oneUsuario/{id}', 'UserController@show_oneUsuario');//usuario con rol
Route::get('allUser', 'UserController@allUser');//todos los usuarios con sus roles
Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
Route::get('buscarUsuario/{email}','UserController@buscarUsuario',
function($nombre = null)
{
	return $email;//Route::get('buscar','ComplejoController@traerImagen');
});

//---------------------------------------------------------------------------------------
//inicio sesion

Route::group(['namespace' => 'api'], function () {
    Route::post('/login', 'UserController@login');
    Route::post('/logout', 'UserController@logout')->middleware('auth:api');;
    Route::post('/details', 'UserController@details')->middleware('auth:api');
});



//---------------------------------------------------------------------------------------
//rutas complejo

Route::get('complejo','ComplejoController@index'); //select
Route::get('complejo/{complejo}','ComplejoController@show');//recibe un parametro el {id}
Route::post('complejo','ComplejoController@store'); //insert
Route::put('complejo/{complejo}','ComplejoController@update'); //modif
Route::get('all','ComplejoController@all');//complejos con sus canchas
//Route::get('allReservasComplejos/{id}','ComplejoController@allReservasComplejos');
Route::delete('complejo/{complejo}','ComplejoController@destroy');
Route::get('traerImagen','ComplejoController@traerImagen');//metodo filtrar complejo

Route::get('buscar/{nombre?}','ComplejoController@buscar',
function($nombre = null)
{
	return $nombre;//Route::get('buscar','ComplejoController@traerImagen');
});
Route::get('CanchasDeUnComplejo/{id}','ComplejoController@CanchasDeUnComplejo');
Route::post('traerComplejoImagen','ComplejoController@traerComplejoImagen');
//--traerComplejoImagen-------------------------------------------------------------------------------------


//rutas canchas
Route::post('cancha','CanchaController@store');
Route::get('cancha','CanchaController@index');
Route::get('cancha/{cancha}','CanchaController@show');//
Route::put('cancha/{cancha}','CanchaController@update');
Route::get('show_one/{id}','CanchaController@show_one');//ruta de cancha con complejo para inner join
Route::delete('cancha/{cancha}','CanchaController@destroy');

//---------------------------------------------------------------------------------------
//rutas rol
Route::post('rol','RolController@store');
Route::get('rol','RolController@index');
Route::get('rol/{rol}','RolController@show');//
Route::put('rol/{rol}','RolController@update');
Route::delete('rol/{rol}','RolController@destroy');
Route::get('rolEmail/{email}','UserController@rolEmail');
//---------------------------------------------------------------------------------------
//rutas imagen
Route::post('imagen','ImagenController@store');
Route::get('imagen','ImagenController@index');
Route::get('imagen/{imagen}','ImagenController@show');//
Route::put('imagen/{imagen}','ImagenController@update');
Route::delete('imagen/{imagen}','ImagenController@destroy');
//Route::get('traerImagen','ImagenController@traerImagen');

//---------------------------------------------------------------------------------------
//rutas reserva
Route::post('reserva','ReservaController@store');
Route::get('reserva','ReservaController@index');
Route::get('reserva/{reserva}','ReservaController@show');//
Route::put('reserva/{reserva}','ReservaController@update');
Route::get('all_reservas','ReservaController@all_reservas');//listado de las reservas con cancha, complejo y users
Route::post('reservaid','ReservaController@reservaid');
Route::delete('reserva/{reserva}','ReservaController@destroy');
Route::post('reservaLibre','ReservaController@ReservaLibresdeUnComplejo');
Route::post('reservaPorFecha','ReservaController@reservaPorFecha');
Route::post('reservasDeUnaCancha','ComplejoController@reservasDeUnaCancha');
Route::post('reservasDeUnaCanchaFecha','ReservaController@reservasDeUnaCanchaFecha');
Route::post('allReservasComplejos','ComplejoController@allReservasComplejos');
route::post('reservaUser','ReservaController@reservaUser');


Route::put('asignacionComplejo/{user}','UserController@asignacionComplejo');
Route::post('adminComplejo','UserController@adminComplejo');



//------------------------------------
//estadisticas
Route::get('cantidadUsuarioMes','UserController@cantidadUsuarioMes');
Route::get('cantidadComplejoMes','ComplejoController@cantidadComplejoMes');
Route::post('cantDeReservasPorCancha','ReservaController@cantDeReservasPorCancha');	
Route::post('cantidadMaxReserva','ReservaController@cantidadMaxReserva');