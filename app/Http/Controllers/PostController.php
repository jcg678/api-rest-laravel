<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\JwtAuth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['index','show']]);
    }

    public function index(){
        $post = Post::all()->load('category');

        return response()->json([
           'code'=>200,
            'status'=>'success',
            'posts'=>$post
        ]);
    }

    public function show($id){
        $post = Post::find($id)->load('category');

        $data =[
            'code'=>404,
            'status'=>'error',
            'message'=>'No encontrado el post'
        ];
        if(is_object($post)){
            $data =[
              'code'=>200,
              'status'=>'success',
              'post' => $post
            ];
        }
        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){
            $user = $this->getIdentity($request);

            $validate = \Validator::make($params_array,[
               'title' => 'required',
                'content'=>'required',
                'category_id'=>'required',
                'image'=>'required'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status'=>'error',
                    'message'=>'No se ha guardado el post, faltan datos'
                ];
            }else{
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->content = $params->content;
                $post->title = $params->title;
                $post->image = $params->image;
                $post->save();

                $data = [
                    'code' => 200,
                    'status'=>'success',
                    'post'=>$post
                ];
            }

        }else{
            $data = [
                'code' => 400,
                'status'=>'error',
                'message'=>'Envia los datos correctamene'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function update($id, Request $request){


        $json = $request->input('json',null);
        $params_array = json_decode($json, true);

        $data = array(
            'code'=>400,
            'status'=>'error',
            'message'=>'datos incorrectos'
        );

        if(!empty($params_array)){
            $validate = \Validator::make($params_array,[
                'title'=>'required',
                'content'=>'required',
                'category_id'=>'required'
            ]);

            if($validate->fails()){
                $data['errors']=$validate->errors();
                return response()->json($data,data['code']);
            }

            unset($params_array['id']);
            unset($params_array['user_id']);
            unset($params_array['created_at']);
            unset($params_array['user']);

            $user = $this->getIdentity($request);

            $post = Post::where('id',$id)->where('user_id',$user->sub)->first();

            if(!empty($post) && is_object($post)){

                $post->update($params_array);

                $data = array(
                    'code'=>200,
                    'status'=>'success',
                    'post'=>$post,
                    'changes'=>$params_array
                );
            }
            /*$where =[
                'id'=>$id,
                'user_id'=>$user->sub
            ];
            $post = Post::updateOrCreate($where, $params_array);*/

        }

        return response()->json($data,$data['code']);
    }

    public function destroy($id,Request $request){
        $user = $this->getIdentity($request);

        $post = Post::where('id',$id)->where('user_id',$user->sub)->first();
        $data = array(
            'code'=>400,
            'status'=>'error',
            'message'=>'no existe',
        );
        if(!empty($post)){
        $post->delete();

        $data = array(
            'code'=>200,
            'status'=>'success',
            'post'=>$post,
        );
        }
        return response()->json($data,$data['code']);
    }

    private function getIdentity(Request $request){
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization',null);
        $user = $jwtAuth->checkToken($token, true);
        return $user;
    }
}
