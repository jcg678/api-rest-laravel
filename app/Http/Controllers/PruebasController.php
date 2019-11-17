<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;


class PruebasController extends Controller
{
    public function index(){
    	$animales = ['perro','libro'];

    	return view('pruebas.index', array(
    		'animales' => $animales
    	));
    }


    public function testOrm(){
    	//$posts = Post::all();

    	//var_dump($posts);

    	/*foreach ($posts as $post ) {
    		echo '<h3>'.$post->title.'</h3>';
    		echo "<span>{$post->user->name}</span>";
    		echo '<p>'.$post->content.'</p>';
    	}*/

    	$categories = Category::all();
    	var_dump($categories);

    	foreach ($categories as $category ) {
    		echo "<h1>{$category->name}</h1>";

    		 foreach ($category->posts as $post ) {
    			echo '<h3>'.$post->title.'</h3>';
    			echo "<span>{$post->user->name}</span>";
    			echo '<p>'.$post->content.'</p>';
    		}
    	}

    	die();
    }

}