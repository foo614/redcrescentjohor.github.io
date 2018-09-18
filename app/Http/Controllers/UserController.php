<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Role;
use App\MembershipType;
use App\Branch;
use App\BloodType;
use App\Donor;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all users
        $users = User::with('roles')->whereHas(
            'roles', function($q){
                $q->where('roles.id', '!=', 1);
            }
        )->get();
        return view('users.index')->with('users', $users);
        // return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','id')->all();
        $membership_types = MembershipType::pluck('name','id')->all();
        $branches = Branch::pluck('name', 'id')->all();
        $blood_types = BloodType::pluck('name', 'id')->all();
        return view('users.create', ['roles'=>$roles, 'membership_types'=>$membership_types, 'branches'=>$branches, 'blood_types'=>$blood_types]);
        // dd($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'ic' => 'required',
            'password'=>'required|min:6|confirmed',
            'avatar'=>'image|nullable|max:8000'
        ]);
        //File Upload
        if($request->hasFile('avatar')){
            //get filename with extension
            $imgExt = $request->file('avatar')->getClientOriginalName();
            //get filename  
            $imgName = pathinfo($imgExt, PATHINFO_FILENAME);
            // just get ext
            $extension = $request->file('avatar')->getClientOriginalExtension();
            //img to store
            $imgToStore = $imgName.'_'.time().'.'.$extension;
            //upload
            $path = $request->file('avatar')->storeAs('public/img',$imgToStore);
        }else{
            $imgToStore=null;
        }
        if($user){
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request['password']);
            $user->contact = $request->contact;
            $user->ic = $request->ic;
            $user->address = $request->address;
            $user->detachment = $request->detachment;
            $user->avatar = $imgToStore;
            $user->map_lat = $request->map_lat;
            $user->map_lng = $request->map_lng;
            // $user->health_issues = $request->health_issues;

            $user->membership_type_id = $request->membership_type;
            $user->branch_id = $request->branch;
            $user->blood_type_id = $request->blood_type;
            $user->save();
            $roles = $request['roles'];
            if(isset($roles)){
                foreach($roles as $role){
                    $user->roles()->attach(Role::where('id', $role)->firstOrFail());
                }
            }
        }
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('profile.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name','id')->all();
        $membership_types = MembershipType::pluck('name','id')->all();
        $branches = Branch::pluck('name', 'id')->all();
        $blood_types = BloodType::pluck('name', 'id')->all();
        // return response()->json($user);
        return view('users.edit', compact('user', 'roles', 'membership_types', 'branches', 'blood_types'));
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
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            'ic' => 'required',
        ]);

        //File Upload
        if($request->hasFile('avatar')){
        //get filename with extension
        $imgExt = $request->file('avatar')->getClientOriginalName();
        //get filename  
        $imgName = pathinfo($imgExt, PATHINFO_FILENAME);
        // just get ext
        $extension = $request->file('avatar')->getClientOriginalExtension();
        //img to store
        $imgToStore = $imgName.'_'.time().'.'.$extension;
        //upload
        $path = $request->file('avatar')->storeAs('public/img',$imgToStore);
        }else{
            $imgToStore=null;
        }

        // $request['password'] = bcrypt($request['password']);
        // $input = $request->only(['name', 'email']); 
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->contact = $request->contact;
        $user->ic = $request->ic;
        $user->address = $request->address;
        $user->detachment = $request->detachment;
        $user->membership_type_id = $request->membership_type;
        $user->branch_id = $request->branch;
        $user->avatar = $imgToStore;
        $user->save();
        $roles = $request['roles'];
        if (isset($roles)) {        
            $user->roles()->sync($roles);  // sync the new role
        } else {
            $user->roles()->detach(); // detach the role related
        }

        return redirect()->route('users.index');
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
