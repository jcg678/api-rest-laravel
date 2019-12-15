<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
}
