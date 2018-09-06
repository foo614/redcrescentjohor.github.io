<?php

namespace App\Http\Controllers;

use Auth;
use App\Mail\DonatorRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\Donor;
use App\User;
use App\BloodType;
use App\Hospital;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    private $blockFor = 90;

    /**
    * Validation rules for hospital model
    *
    * @var Array
    */
    public $rules = [
        'name' => 'required|max:120',
        'email'=>'required|email|unique:users',
        // 'dob' => 'required|date|before:-18 years',
        'address' => 'required',
        'map_lat' => 'required',
        'map_lng' => 'required',
        'ic' => 'required|digits:12',
        'contact' => 'required',
        'blood_type' => 'required',
    ];

    public $messages = [
        // 'dob.before' => 'Your age must be atleast 18 years',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $donors = Donor::with('user')->with('bloodType')->get();
        // $donors = $get_donors->map(function($donor){
        //     return collect($donor->toArray())
        //     ->only(['dob','blood_type_id', 'user'])
        //     ->all();
        // });
        $totalUsers = count($donors);
        // dd($donors);
        //return response()->json($donors);
        return view('donors.index', compact(['donors','totalUsers']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blood_types = BloodType::all();
        return view('donors.create',compact('blood_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        if(!Auth::check()){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = '';
            $user->ic = $request->ic;        
            $user->contact = $request->contact;        
            $user->address = $request->address;
            $user->membership_type_id = 4;
            $user->save();
        }
        if($user){
            $donor = new Donor;
            $donor->map_lat = $request->map_lat;
            $donor->map_lng = $request->map_lng;
            $donor->blood_type_id = $request->blood_type;
            $donor->health_issues = $request->health_issues;
            $donor->user_id = $user->id;
            // $donor->user_id = Auth::check() ? Auth::user()->id : $user->id;
            $donor->save();
        }
        if($user->membership_type_id == 4){
            return $this->mail($user->name, $user->email);
        }
        // return redirect()->route('login');
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
        $donor = Donor::with('user')->with('bloodType')->find($id);
        $blood_types = BloodType::all();
        // dd(Auth::user());
        if (Auth::user()->isAdmin){
            // abort(403);
            $notification = array(
                'message' => 'Access denied',
                'alert-type' => 'warning'
            );
            return redirect('/donors')->with($notification);
        }
            

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
        $this->validate($request, $this->messages);

        $donor = Donor::find($id);

        // if (Auth::user()->id !== $donor->user->id)
        //     abort(403);

        // if ($request->hasFile('avatar')) {
        //     $donor->avatar = $this->imageStorageService->storeAvatar($request->file('avatar'));
        // }
        if($donor){
            $user = User::where('id', '=', $donor->user_id)->first();
        }
        $user->name = $request->name;
        $user->email = $request->email;
        // $user->ic = $request->ic;        
        $user->contact = $request->contact;        
        $user->address = $request->address;
        $user->save();

        // $donor->dob = $request->dob;
        $donor->map_lat = $request->map_lat;
        $donor->map_lng = $request->map_lng;
        $donor->blood_type_id = $request->blood_type;
        $donor->health_issues = $request->health_issues;
        $donor->save();
        //return response()->json($donor);
        $notification = array(
            'message' => $request->name.' is updated',
            'title' => 'Edit donator',
            'alert-type' => 'success'
        );
        return redirect('donors')->with($notification);
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
