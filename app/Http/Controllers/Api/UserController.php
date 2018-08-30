<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\MembershipType;
use App\Branch;
use App\BloodType;
use App\Donor;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Return a paginated list of posts.
     *
     * @return UserResource
     */
    public function index()
    {
        // get all users
        $users = User::with('roles')->whereHas(
            'roles', function($q){
                $q->where('roles.id', '!=', 1);
            }
        )->get();
        return UserResource::collection($users);
        // return view('user.index')->with('users', $users);
        // return response()->json($users);
    }

    /**
     * Fetch and return the User.
     *
     * @param User $User
     * @return UserResource
     */
    public function show($id)
    {
        // return new PostResource($post);
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Validate and save a new signature to the database.
     *
     * @param Request $request
     * @return UserResource
     */
    public function store(Request $request)
    {
        $user = $request->isMethod('put') ? User::findOrFail($request->user_id) : new User;
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
            $imgToStore='generic.png';
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get article
        $user = User::findOrFail($id);
        if($user->delete()) {
            return new UserResource($user);
        }    
    }
}
