<?php
use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register' , 'App\Http\Controllers\AuthController@register');
Route::post('/login' , 'App\Http\Controllers\AuthController@login');



Route::middleware('auth:api')->group(function (){

    Route::resource('/posts' , 'App\Http\Controllers\PostController');
    Route::get('/posts/user/{id}' , 'App\Http\Controllers\PostController@userPosts');
});
