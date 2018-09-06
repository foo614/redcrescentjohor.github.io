<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $totalUsers = count($hospitals);
        return view('hospitals.index',compact('hospitals','totalUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hospitals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hospital = new Hospital;
        $hospital->name = $request->name;
        $hospital->address = $request->address;
        $hospital->email = $request->email;
        $hospital->contact = $request->contact;
        $hospital->map_lat = $request->map_lat;
        $hospital->map_lng = $request->map_lng;
        $hospital->save();
        $notification = array(
            'message' => $hospital->name.' is created',
            'title' => 'Add hospital',
            'alert-type' => 'success'
        );
        return redirect()->route('hospitals.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hospital = Hospital::findOrFail($id);
        return view('hospitals.edit', compact('hospital'));
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
            'name'=>'required|max:100',
            'email'=>'nullable|email|unique:hospitals,email,'.$id,
        ]);

        $hospital = Hospital::findOrFail($id);
        $hospital->name = $request->input('name');
        $hospital->address = $request->input('address');
        $hospital->email = $request->input('email');
        $hospital->contact = $request->input('contact');
        // $hospital->reg_no = $request->input('reg_no');
        $hospital->map_lat = $request->input('map_lat');
        $hospital->map_lng = $request->input('map_lng');
        $hospital->save();
        $notification = array(
            'message' => $hospital->name.' is updated',
            'title' => 'Edit hospital',
            'alert-type' => 'success'
        );
        return redirect()->route('hospitals.index')->with($notification);
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
        $hospital->delete();
        $notification = array(
            'message' => $hospital->name.' is deleted',
            'title' => 'Delete hospital',
            'alert-type' => 'success'
        );
        return redirect()->route('hospitals.index')
            ->with($notification);
    }
}
