<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $request->user()->authorizeRoles(['administrator', 'member']);
        return view('home.home');
    }

    public function posts(Request $request)
    {
        // $request->user()->authorizeRoles(['administrator']);
        return view('home.posts');
    }

    public function showPost($id)
    {
        // $request->user()->authorizeRoles(['administrator']);
        return view('home.post');
    }

    public function courses(Request $request)
    {
        // $request->user()->authorizeRoles(['administrator']);
        return view('home.courses');
    }

    public function registerCourse(){
        return view('home.registerCourse');
    }

    public function fundraisers(){
        return view('home.fundraisers');
    }

    public function donate($id){
        return view('home.donate');
    }

    public function profile($id){
        return view('home.profile');
    }

    public function fundraiserCreate()
    {
        return view('home.fundraiserCreate');
    }
}
