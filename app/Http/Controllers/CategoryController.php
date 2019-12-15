<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth',['except'=>['index','show']]);
    }

    public function index(){
        $categories = Category::all();

        return response()->json([
            'code'=>200,
            'status'=>'success',
            'categories'=>$categories
        ]);
    }

    public function show($id){
        $category = Category::find($id);
        $data =[
            'code'=>404,
            'status'=>'error',
            'message'=>'La categoria no existe'
        ];
        if(is_object($category)){
            $data =[
                'code'=>200,
                'status'=>'success',
                'category'=>$category
            ];
        }
        return response()->json($data, $data['code']);

    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json, true);
        $data = [
            'code' => 400,
            'status' => 'error',
            'message' => 'No has envidado nada'
        ];

        if(!empty($params_array)) {
        $validate = \Validator::make($params_array,[
            'name' => 'required'
        ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'no se ha guardado la categoria'
                ];
            } else {
                $category = new Category;
                $category->name = $params_array['name'];
                $category->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'category' => $category
                ];
            }
        }

        return response()->json($data, $data['code']);

    }
}
