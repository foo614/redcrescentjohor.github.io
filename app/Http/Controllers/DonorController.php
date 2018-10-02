<?php

namespace App\Http\Controllers;

use Auth;
use App\Mail\DonatorRegisteredMail;
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
        $donors = User::with('blood_type')->get();
        return view('donors.index', compact(['donors']));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $donor = Donor::find($id);

        return view('donors.show',[
            'donor' => $donor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $donor = User::with('blood_type')->find($id);
        $blood_types = BloodType::all();

        return view('donors.edit', [
            'donor' => $donor,
            'blood_types' => $blood_types,
        ]);
        //return response()->json($donor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        $donors = Donor::with('bloodType', 'user')->get();
        $hospitals = Hospital::get();
        return response([$donors, $hospitals]);
    }

    /**
     * Return donor details
     */
    // public function hospital(Request $request){
    //     $hospitals = Hospital::get();
    //     return response($hospitals);
    // }

    /**
     * Search donors
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        // if ($request->user()->donor)
        //     abort(403);
        $blood_types = BloodType::all();
        $hospitals = Hospital::all();
        return view('donors.search', compact(['blood_types', 'hospitals']));
    }
    /**
     * send mail to registered donator
     */
    public function mail($name, $email){
        $content = new \stdClass();
        $content->name = $name;

        Mail::to($email)->send(new DonatorRegisteredMail( $content ));  
        $notification = array(
            'message' => 'Thank you for joined as donator! Please check email to get more details.',
            'title' => 'Donator account created',
            'alert-type' => 'success'
        );
        return redirect('/')
        ->with($notification);
    }
}
