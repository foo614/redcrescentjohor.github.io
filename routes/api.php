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

//List bloodTypes
Route::get('bloodTypes', 'Api\BloodTypeController@index');
// List single bloodType
Route::get('bloodType/{id}', 'Api\BloodTypeController@show');
// Create new bloodType
Route::post('bloodType', 'Api\BloodTypeController@store');
// Update bloodType
Route::put('bloodType', 'Api\BloodTypeController@store');
// Delete bloodType
Route::delete('bloodType/{id}', 'Api\BloodTypeController@destroy');

//List branches
Route::get('branches', 'Api\BranchController@index');
// List single branch
Route::get('branch/{id}', 'Api\BranchController@show');
// Create new branch
Route::post('branch', 'Api\BranchController@store');
// Update branch
Route::put('branch', 'Api\BranchController@store');
// Delete branch
Route::delete('branch/{id}', 'Api\BranchController@destroy');

//List membershipTypes
Route::get('membershipTypes', 'Api\MembershipTypeController@index');
// List single membershipType
Route::get('membershipType/{id}', 'Api\MembershipTypeController@show');
// Create new membershipType
Route::post('membershipType', 'Api\MembershipTypeController@store');
// Update membershipType
Route::put('membershipType', 'Api\MembershipTypeController@store');
// Delete membershipType
Route::delete('membershipType/{id}', 'Api\MembershipTypeController@destroy');

//List hospitals
Route::get('hospitals', 'Api\HospitalController@index');
// List single hospital
Route::get('hospital/{id}', 'Api\HospitalController@show');
// Create new hospital
Route::post('hospital', 'Api\HospitalController@store');
// Update hospital
Route::put('hospital', 'Api\HospitalController@store');
// Delete hospital
Route::delete('hospital/{id}', 'Api\HospitalController@destroy');

//List post categories
Route::get('postCategories', 'Api\PostCategoryController@index');
// List single post category
Route::get('postCategory/{id}', 'Api\PostCategoryController@show');
// Create new post category
Route::post('postCategory', 'Api\PostCategoryController@store');
// Update post category
Route::put('postCategory', 'Api\PostCategoryController@store');
// Delete post category
Route::delete('postCategory/{id}', 'Api\PostCategoryController@destroy');
