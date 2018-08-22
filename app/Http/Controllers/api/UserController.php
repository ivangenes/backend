<?php

namespace App\Http\Controllers\api;

use App\User;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
     public function __construct()
     	{
       		 $this->content = array();
    	}
    public function login()
    	{
        	if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
        	$user = Auth::user();
        	$this->content['token'] =  $user->createToken('SoccerApp')->accessToken;
        	$status = 200;
   	    }
    else
    	{
        	$this->content['error'] = "Email y/o contraseña no son validos";
         	$status = 401;
   		 }
    return response()->json($this->content, $status);    
	}

	public function details()
	{
        return response()->json(['user' => Auth::user()]);
    }

    public function logout() {
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();
        return response()->json("Cerro Sesion", 200);
    }
}
