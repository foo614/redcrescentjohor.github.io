<?php

namespace App\Http\Controllers\Api;

use App\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::orderBy('created_at','DESC')->where('start_date', '>', Carbon::now())->get();
        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $course = $request->isMethod('put') ? Course::findOrFail($request->course_id) : new Course;
        $course->name = $request->name;
        $course->course_fee = $request->course_fee;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->start_time = $request->start_time;
        $course->end_time = $request->end_time;
        $course->available_seat = $request->available_seat;
        $course->venue = $request->venue;
        $course->info = $request->info;
        $course->description = $request->description;
        if($course->save()) {
            return new CourseResource($course);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return new CourseResource($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        if($course->delete()){
            return new CourseResource($course);
        }
    }
}
