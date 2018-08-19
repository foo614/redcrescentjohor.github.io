<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalResource;
use App\Hospital;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hospitals = Hospital::all();
        return new HospitalResource($hospitals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hospital = $request->isMethod('put') ? Hospital::findOrFail($request->hospital_id) : new Hospital;
        $hospital->name = $request->name;
        $hospital->address = $request->address;
        $hospital->email = $request->email;
        $hospital->contact = $request->contact;
        $hospital->map_lat = $request->map_lat;
        $hospital->map_lng = $request->map_lng;

        if($hospital->save()){
            return new HospitalResource($hospital);
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
        $hospital = Hospital::findOrFail($id);
        return new HospitalResource($hospital);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hospital = Hospital::findOrFail($id);
        if($hospital->delete()){
            return new HospitalResource($hospital);
        }
    }
}
