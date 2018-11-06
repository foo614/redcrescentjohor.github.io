<?php

namespace App\Http\Controllers;

use Auth;
use App\Mail\MemberRegistration;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\BloodType;
use App\Hospital;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('donors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('donors.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('donors.edit');
    }

    /**
     * Return user details
     * 
     */ 
    public function user(Request $request){
        $request->user()->donor;
        $request->user()->branch;
        
        return response()->json([
            'user' => $request->user()
        ]);
    }

    /**
     * Return donor details
     */
    public function donor(Request $request){
        $donors = User::with('blood_type')->get();
        $hospitals = Hospital::get();
        return response([$donors, $hospitals]);
    }

    /**
     * Search donors
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        return view('donors.search');
    }

    /**
     * send mail to registered donator
     */
    public function sendMail($name, $email){
        $content = new \stdClass();
        $content->name = $name;

        Mail::to($email)->send(new MemberRegistration( $content ));
    }
}
