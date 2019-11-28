<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\Response;

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
	    		'email'=>'required|email|unique:users',
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

	    		$pwd =hash('sha256',$params->password);

	    		$user = new User();
	    		$user->name = $params_array['name'];
	    		$user->surname = $params_array['surname'];
	    		$user->email = $params_array['email'];
	    		$user->password = $pwd;
	    		$user->role = 'ROLE_USER';

	    		$user->save();

	    		$data = array(
		    		'status'=>'success',
		    		'code' => 200,
		    		'message' => 'El usuario se ha creado',
		    		'user'=> $user
		    	);
	    	}

	    }else{
	    		$data = array(
		    		'status'=>'error',
		    		'code' => 400,
		    		'message' => 'Los datos enviados no son correctos'
		    	);
	    }	


    	return response()->json($data, $data['code']);

    }

    public function login(Request $request){

		$json = $request->input('json',null);
		$params = json_decode($json);
		$params_array =json_decode($json,true);

		$validate = \Validator::make($params_array,[
			'email'=>'required|email',
			'password'=>'required'
		]);



		if($validate->fails()){
			$signup = array(
				'status'=>'error',
				'code' => 404,
				'message' => 'El usuario no se podido loguear',
				'errors'=> $validate->errors()
			);
		}else{
			$pwd =hash('sha256',$params->password);
			$jwtAuth = new \JwtAuth();
			$signup = $jwtAuth->signup($params->email,$pwd);

			if(!empty($params->gettoken)){
				$signup = $jwtAuth->signup($params->email,$pwd, true);
			}
		}



    	return  response()->json($signup, 200);
    }

	public function update(Request $request){

		$token = $request->header('Authorization');
		$jwtAuth = new \JwtAuth();
		$checkToken = $jwtAuth->checkToken($token);

		$json = $request->input('json', null);
		$params_array = json_decode($json, true);

		if($checkToken && !empty($params_array)){

			$user = $jwtAuth->checkToken($token ,true);

			$validate = \Validator::make($params_array,[
				'name'=>'required|alpha',
				'surname'=>'required|alpha',
				'email'=>'required|email|unique:users'.$user->sub
			]);


			unset($params_array['id']);
			unset($params_array['role']);
			unset($params_array['password']);
			unset($params_array['created_at']);
			unset($params_array['remenber_token']);

			$user_update = User::where('id', $user->sub)->update($params_array);

			$data = array(
				'code'=>200,
				'status'=>'success',
				'user'=> $user,
				'changes'=> $params_array
			);

		}else{
			$data= array(
				'code'=>404,
				'status'=>'error',
				'message'=> 'El usuario no esta identicado'
			);
		}
		return response()->json($data,$data['code']);
	}


	public function upload(Request $resuest){
		$image = $resuest->file('file0');

		$validate = \Validator::make($resuest->all(),[
			'file0'=>'required|image|mimes:jpg,jpeg,png,gif'
		]);

		if(!$image || $validate->fails()){
			$data= array(
				'code'=>404,
				'status'=>'error',
				'message'=> 'Error al subir imagen'
			);

		}else{
			$image_name = time().$image->getClientOriginalName();
			\Storage::disk('users')->put($image_name, \File::get($image));

			$data = array(
				'code'=>200,
				'status'=>'success',
				'image'=>$image_name
			);
		}

		return response()->json($data, $data['code']);
	}

	public function getImage($filename){
		$isset = \Storage::disk('users')->exists($filename);
		if($isset){
			$file = \Storage::disk('users')->get($filename);
			return new Response($file, 200);
		}else{
			$data = array(
				'code'=>400,
				'status'=>'error',
				'message'=>'La imagen no existe'
			);

			return response()->json($data, $data['code']);
		}
	}
}
