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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// blood types api route
// Route::apiResource('bloodTypes', 'Api\BloodTypeController');
Route::get('bloodTypes', 'Api\BloodTypeController@index');
Route::get('bloodType/{id}', 'Api\BloodTypeController@show');
Route::post('bloodType', 'Api\BloodTypeController@store');
Route::put('bloodType', 'Api\BloodTypeController@store');
Route::delete('bloodType/{id}', 'Api\BloodTypeController@destroy');

// posts api route
Route::get('posts', 'Api\PostController@index');
Route::get('post/{id}', 'Api\PostController@show');
Route::post('post', 'Api\PostController@store');
Route::put('post', 'Api\PostController@store');
Route::delete('post/{id}', 'Api\PostController@destroy');

// post categories api route
Route::get('postCategories', 'Api\PostCategoryController@index');
Route::get('postCategory/{id}', 'Api\PostCategoryController@show');
Route::post('postCategory', 'Api\PostCategoryController@store');
Route::put('postCategory', 'Api\PostCategoryController@store');
Route::delete('postCategory/{id}', 'Api\PostCategoryController@destroy');

// roles api route
Route::get('roles', 'Api\RoleController@index');
Route::get('role/{id}', 'Api\RoleController@show');
Route::post('role', 'Api\RoleController@store');
Route::put('role', 'Api\RoleController@store');
Route::delete('role/{id}', 'Api\RoleController@destroy');

// membership types api route
// Route::apiResource('membershipTypes', 'Api\MembershipTypeController');
Route::get('membershipTypes', 'Api\MembershipTypeController@index');
Route::get('membershipType/{id}', 'Api\MembershipTypeController@show');
Route::post('membershipType', 'Api\MembershipTypeController@store');
Route::put('membershipType', 'Api\MembershipTypeController@store');
Route::delete('membershipType/{id}', 'Api\MembershipTypeController@destroy');

// branches api route
// Route::apiResource('branches', 'Api\BranchController');
Route::get('branches', 'Api\BranchController@index');
Route::get('branch/{id}', 'Api\BranchController@show');
Route::post('branch', 'Api\BranchController@store');
Route::put('branch', 'Api\BranchController@store');
Route::delete('branch/{id}', 'Api\BranchController@destroy');

// hospitals api route
Route::apiResource('hospitals', 'Api\HospitalController');

// users api route
// Route::apiResource('members', 'Api\UserController');
Route::get('members', 'Api\UserController@index');
Route::get('member/{id}', 'Api\UserController@show');
Route::post('member', 'Api\UserController@store');
Route::put('member', 'Api\UserController@store');
// Route::delete('member/{id}', 'Api\UserController@destroy');