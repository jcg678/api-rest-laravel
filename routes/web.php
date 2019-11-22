<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','PostController@initApi');


//RUTAS API

Route::get('/usuario/pruebas','UserController@pruebas');

Route::get('/categoria/pruebas','CategoryController@pruebas');

Route::get('/entradas/pruebas','PostController@pruebas');



Route::post('/api/register','UserController@register');
Route::post('/api/login','UserController@login');
Route::post('/api/user/update','UserController@Update');