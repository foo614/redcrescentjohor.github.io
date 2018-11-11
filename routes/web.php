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
//home
Route::get('/', 'HomeController@index');

//posts
Route::get('news-stories', 'HomeController@posts');
Route::get('news-stories/{id}', 'HomeController@showPost');

//course register
Route::get('course-registration', 'HomeController@courses');
Route::get('course-registration/{id}/register', 'HomeController@registerCourse');

//fundraise
Route::get('fundraisers-campaign', 'HomeController@fundraisers');
Route::get('fundraisers-campaign/create', 'HomeController@fundraiserCreate');
Route::get('fundraisers-campaign/donate/{id}', 'HomeController@donate');

Route::view('social/login', 'home.social_login');

//socialite login
Route::get ( 'redirect/{service}', 'SocialAuthController@redirect' );
Route::get ( 'callback/{service}', 'SocialAuthController@callback' );

Route::group(['middleware' => ['auth']], function(){
    Route::get('profile/{id}', 'HomeController@profile');
});

// Route::group(['middleware' => ['role:administrator']], function(){
Route::group(['middleware' => ['auth', 'role:administrator']], function(){
    //donor
    Route::resource('donors', 'DonorController');

    //settings
    Route::resource('bloodTypes', 'BloodTypeController');
    Route::resource('postCategories', 'PostCategoryController');
    Route::resource('roles', 'RoleController');
    Route::resource('membershipTypes', 'MembershipTypeController');

    //post route
    Route::get('posts/calendar', 'PostController@viewCalendar')->name('posts.calendar');
    Route::resource('posts', 'PostController');

    //fundraise route
    Route::resource('fundraisers', 'FundraiserController');

    //user
    Route::resource('users', 'UserController');
    //user profile home

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
    Route::get('mail/send/course_registered', 'Api\RegisterCourseController@sendMail');

    //hospital route
    Route::resource('hospitals', 'HospitalController');

    //branches route
    Route::resource('branches', 'BranchController');

    //courses route
    Route::resource('courses', 'CourseController');

    //blood donation route
    Route::resource('bloodDonationRecords', 'BloodDonationRecordController');

    Route::get('logout', 'LoginController@logout');

    //payment = transaction
    Route::get('transactions', 'PaymentController@index');
});
Auth::routes();