<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\RegisterCourseResource;
use App\Http\Controllers\Controller;
use Mail;
use App\User;
use App\Course;
use App\Mail\CourseRegistered;
use Carbon\Carbon;;

class RegisterCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Course::orderBy('created_at','DESC')->where('start_date', '>', Carbon::now())->get();
        return RegisterCourseResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $find_user = User::where('email', $request->email)->firstOrFail();
        $course = Course::where('id', $request->course_id)->firstOrFail();
        if($find_user){
            if (isset($request->course_id)) {
                $find_user->courses()->sync($request->course_id);  // sync 
            }
        }else{
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact = $request->contact;
            $user->address = $request->address;
            //$user->save();
            if (isset($request->course_id)) {        
                $user->courses()->sync($request->course_id);  // sync
            }
        }
        $result = $find_user ? $find_user : $user;
        $this->sendMail($result->name, $result->email, $request->selectedCourse['start_date'], $request->selectedCourse['venue'], $request->selectedCourse['info'], $request->selectedCourse['name']);
        return new RegisterCourseResource($result);
    }

    /**
     * send mail to registered donator
     */
    public function sendMail($name, $email, $course_start_date, $course_venue, $course_info, $course_name){
        $content = new \stdClass();
        $content->user_email = $email;
        $content->user_name = $name;
        $content->course_start_date = $course_start_date;
        $content->course_venue = $course_venue;
        $content->course_info = $course_info;
        $content->course_name = $course_name;


        Mail::to($email)->send(new CourseRegistered( $content ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
