<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fundraiser;
use App\Http\Resources\FundraiserResource;
use Illuminate\Support\Facades\Storage;
use App\User;
use Illuminate\Support\Facades\Auth;

class FundraiserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fundraisers = Fundraiser::all();
        return FundraiserResource::collection($fundraisers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(User::where('email',  $request->email)->exists()){
            $find_user = User::where('email',  $request->email)->firstOrFail();
        }else{
            $find_user = new User;
            $find_user->name = $request->name;
            $find_user->email = $request->email;
            $find_user->save();
        }

        $fundraiser = $request->isMethod('put') ? Fundraiser::findOrFail($request->fundraiser_id) : new Fundraiser;
        if(! \File::exists(public_path('img/'.$request->get('cover_img'))))
        {
            $image = $request->get('cover_img');
            $image_name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('cover_img'))->save(public_path('img/').$image_name);
        }
        $fundraiser->title = $request->title;
        $fundraiser->target_amount = $request->target_amount;
        $fundraiser->body = $request->body ? $request->body : '';
        $fundraiser->status = $request->status;
        if ($find_user->id)
            $fundraiser->user_id = $find_user->id;
        else
            $fundraiser->user_id = 1;
        if(! \File::exists(public_path('img/'.$request->get('cover_img'))))
            $fundraiser->cover_img = $image_name;
        $fundraiser->save();
        return new FundraiserResource($fundraiser);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new FundraiserResource(Fundraiser::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fundraiser = Fundraiser::findOrFail($id);
        if($fundraiser->delete()) {
            return new FundraiserResource($fundraiser);
        }
    }

    public function fundraisersActive(){
        $fundraisers = Fundraiser::where('status', 1)->get();
        return FundraiserResource::collection($fundraisers);
    }
}
