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
Route::apiResource('posts', 'Api\PostController');
// bllod types api route
Route::apiResource('bloodTypes', 'Api\BloodTypeController');
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