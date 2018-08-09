<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::resource('posts', 'Api\PostController')->only(['index', 'store', 'show', 'delete']);

// List posts
Route::get('posts', 'Api\PostController@index');
// List single post
Route::get('post/{id}', 'Api\PostController@show');
// Create new post
Route::post('post', 'Api\PostController@store');
// Update post
Route::put('post', 'Api\PostController@store');
// Delete post
Route::delete('post/{id}', 'Api\PostController@destroy');

