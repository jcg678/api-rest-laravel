<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function pruebas(Request $request){
    	return "Accion de pruebas de POstController";
    }


    public function initApi(Request $request){
    	return 'Welcome api beta';
    }
}
