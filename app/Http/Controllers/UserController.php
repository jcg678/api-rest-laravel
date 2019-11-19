<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function pruebas(Request $request){
    	return "Accion de pruebas de UserController";
    }

    public function register(Request $request){
    	//recoger datos
    	$json = $request->input('json',null);
    	$params = json_decode($json);
    	$params_array = json_decode($json,true);


    	if(!empty($params) && !empty($params_array)){
	    	//validar datos
	    	$params_array = array_map('trim', $params_array);
	    	$validate = \Validator::make($params_array,[
	    		'name'=>'required|alpha',
	    		'surname'=>'required|alpha',
	    		'email'=>'required|email',
	    		'password'=>'required'
	    	]);



	    	if($validate->fails()){
		    	$data = array(
		    		'status'=>'error',
		    		'code' => 404,
		    		'message' => 'El usuario no se ha creado',
		    		'errors'=> $validate->errors()
		    	);

	    	
	    	}else{
	    		$data = array(
		    		'status'=>'success',
		    		'code' => 200,
		    		'message' => 'El usuario se ha creado'
		    	);
	    	}
	    }else{
	    		$data = array(
		    		'status'=>'error',
		    		'code' => 400,
		    		'message' => 'Los datos enviados no son correctos'
		    	);
	    }	
    	//cifrar contraseña

    	//comprobar si el usuario existe
    	//crear el usuario




    	return response()->json($data, $data['code']);

    }

    public function login(Request $request){
    	return 'Accion login usuarios';
    }

}
