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
        if($request->get('avatar') && file_exists(public_path($request->get('avatar'))))
        {
            $image = $request->get('avatar');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('avatar'))->save(public_path('img/').$name);
        }

        if($user){
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if($request->isMethod('post'))
                $user->password = bcrypt($request['password']);
            $user->contact = $request->contact;
            $user->ic = $request->ic;
            $user->address = $request->address;
            $user->detachment = $request->detachment;
            if($request->get('avatar') && file_exists(public_path($request->get('avatar'))))
                $user->avatar = $name;
            $user->map_lat = $request->map_lat;
            $user->map_lng = $request->map_lng;
            // $user->health_issues = $request->health_issues;

            $user->membership_type_id = $request->membership_type_id;
            $user->branch_id = $request->branch_id;
            $user->blood_type_id = $request->blood_type_id;
            $user->save();
            $roles = $request['roles'];
            // if(isset($roles)){
            //     foreach($roles as $role){
            //         $user->roles()->sync($role['id']); //dont delete old entry = false
            //         $user->roles()->attach(Role::where('id', $role->id)->firstOrFail());
            //     }
            // }
            if (isset($roles)) {        
                $user->roles()->sync($roles);  // sync the new role
            }else{
                $user->roles()->detach(); // detach the role related
            }
        }
        // return response()->json(['success' => 'You have successfully uploaded an image'], 200);
        return new UserResource($user);
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
