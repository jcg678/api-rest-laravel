<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function pruebas(Request $request){
    	return "Accion de pruebas de UserController";
    }

    public function register(Request $request){

    	$name = $request->input('name');

    	return "Accion registro usuario: $name";
    }

    public function login(Request $request){
    	return 'Accion login usuarios';
    }

}
