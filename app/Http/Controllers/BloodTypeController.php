<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BloodType;

class BloodTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blood_types = BloodType::all();
        return view('settings.bloodType')->with('blood_types', $blood_types);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blood_types.create');
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
            'name'=>'required|max:40',
        ]);
        $blood_type = new BloodType;
        $blood_type->name = $request['name'];
        $blood_type->save();
        $notification = array(
            'message' =>  $blood_type->name.' is created',
            'title' => 'Add blood type',
            'alert-type' => 'success'
        );
        return redirect()->route('blood_types.index')
            ->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('blood_types');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blood_type = BloodType::findOrFail($id);
        return view('blood_types.edit', compact('blood_type'));
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
        $blood_type = BloodType::findOrFail($id);
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
        $input = $request->all();
        $blood_type->fill($input)->save();
        return redirect()->route('blood_types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blood_type = BloodType::findOrFail($id);
        $blood_type->delete();
        return redirect()->route('blood_types.index');
    }
}
