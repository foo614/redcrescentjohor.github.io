<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MembershipType;
use App\Http\Resources\MembershipTypeResource;

class MembershipTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $membership_types = MembershipType::latest('created_at')->get();
        return new MembershipTypeResource($membership_types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $membership_type = $request->isMethod('put') ? MembershipType::findOrFail($request->membershipType_id) : new MembershipType;
        $membership_type->name = $request->name;
        if($membership_type->save()){
            return new MembershipTypeResource($membership_type);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $membership_type = MembershipType::findOrFail($id);
        return new MembershipTypeResource($membership_type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $membership_type = MembershipType::findOrFail($id);
        if($membership_type->delete()){
            return new MembershipTypeResource($membership_type);
        }
    }
}
