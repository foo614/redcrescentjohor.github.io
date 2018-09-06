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
// posts api route
// Route::apiResource('posts', 'Api\PostController');
Route::get('posts', 'Api\PostController@index');
Route::get('post/{id}', 'Api\PostController@show');
Route::post('post', 'Api\PostController@store');
Route::put('post', 'Api\PostController@store');
Route::delete('post/{id}', 'Api\PostController@destroy');

// bllod types api route
// Route::apiResource('bloodTypes', 'Api\BloodTypeController');
Route::get('bloodTypes', 'Api\BloodTypeController@index');
Route::get('bloodType/{id}', 'Api\BloodTypeController@show');
Route::post('bloodType', 'Api\BloodTypeController@store');
Route::put('bloodType', 'Api\BloodTypeController@store');
Route::delete('bloodType/{id}', 'Api\BloodTypeController@destroy');

// membership types api route
Route::apiResource('membershipTypes', 'Api\MembershipTypeController');
// branches api route
Route::apiResource('branches', 'Api\BranchController');
// hospitals api route
Route::apiResource('hospitals', 'Api\HospitalController');
// post categories api route
Route::apiResource('postCategories', 'Api\PostCategoryController');
// users api route
Route::apiResource('users', 'Api\UserController');
