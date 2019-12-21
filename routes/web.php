<?php
use App\Http\Middleware\ApiAuthMiddleware;
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




Route::post('/api/register','UserController@register');
Route::post('/api/login','UserController@login');
Route::put('/api/user/update','UserController@Update');

Route::post('/api/user/upload', 'UserController@Upload')->middleware(ApiAuthMiddleware::class);

Route::get('api/user/avatar/{filename}','UserController@getImage');

Route::get('api/user/detail/{id}','UserController@detail');


//Categorias
Route::resource('/api/category', 'CategoryController');

Route::resource('api/post', 'PostController');

Route::post('/api/post/upload', 'PostController@Upload');

