<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Mail\DonatorRegisteredMail;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\BloodType;
use App\Hospital;
use App\Http\Resources\DonorResource;

class DonorController extends Controller
{
    /**
     * Return a paginated list of posts.
     *
     * @return DonorResource
     */
    public function index()
    {
        // get all donors
        $donors = User::with('blood_type')->where("membership_type_id",4)->get();
        return DonorResource::collection($donors);
    }

    /**
     * Fetch and return the User.
     *
     * @param User $User
     * @return DonorResource
     */
    public function show($id)
    {
        // return new PostResource($post);
        $user = User::findOrFail($id);
        return new DonorResource($user);
    }

    /**
     * Validate and save a new signature to the database.
     *
     * @param Request $request
     * @return DonorResource
     */
    public function store(Request $request)
    {
        $user = $request->isMethod('put') ? User::findOrFail($request->user_id) : new User;
        if($request->get('avatar'))
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
            if($request->get('avatar'))
                $user->avatar = $name;
            $user->map_lat = $request->map_lat;
            $user->map_lng = $request->map_lng;
            $user->membership_type_id = $request->membership_type_id;
            $user->branch_id = $request->branch_id;
            $user->blood_type_id = $request->blood_type_id;
            $user->save();
            $roles = $request['roles'];
            if (isset($roles)) {        
                $user->roles()->sync($roles);  // sync the new role
            }else{
                $user->roles()->detach(); // detach the role related
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
            return new DonorResource($user);
        }    
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
