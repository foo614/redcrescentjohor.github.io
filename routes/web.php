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
Route::get('/', 'HomeController@index')->name('home');
Route::get('news-stories', 'HomeController@posts');
Route::get('news-stories/{id}', 'HomeController@showPost');
Route::resource('donors', 'DonorController');
Route::get('/course_registration', 'HomeController@courses');

Route::group(['middleware' => ['auth']], function () {
    //blank template
    Route::get('dashboard', 'DashboardController@index');
    //settings
    Route::resource('bloodTypes', 'BloodTypeController');
    Route::resource('postCategories', 'PostCategoryController');
    Route::resource('roles', 'RoleController');
    Route::resource('membershipTypes', 'MembershipTypeController');

    //post route
    Route::get('posts/calendar', 'PostController@viewCalendar')->name('posts.calendar');
    Route::resource('posts', 'PostController');

    //user
    Route::resource('users', 'UserController');

    //donor 
    Route::get('user', 'DonorController@user');
    
    Route::get('search', [
        'as' => 'donors.search',
        'uses' => 'DonorController@search'
    ]);
    Route::get('donor', [
        'uses' => 'DonorController@donor'
    ]);

    // send mail
    Route::get('mail/send', 'BloodDonationInvitationController@send');
    Route::get('mail/register', 'DonorController@mail');
    Route::get('mail/sendAll', 'BloodDonationInvitationController@sendAll');

    //hospital route
    Route::resource('hospitals', 'HospitalController');

    //branches route
    Route::resource('branches', 'BranchController');

    //courses route
    Route::resource('courses', 'CourseController');

    //blood donation route
    Route::resource('bloodDonationRecords', 'BloodDonationRecordController');
});

Auth::routes();
