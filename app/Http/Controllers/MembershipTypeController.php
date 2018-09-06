<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MembershipType;

class MembershipTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $membership_types = MembershipType::all();
        return view('membership_types.index')->with('membership_types', $membership_types);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('membership_types.create');
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
        $membership_type = new MembershipType;
        $membership_type->name = $request['name'];
        $membership_type->save();
        return redirect()->route('membership_types.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('membership_types');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $membership = MembershipType::findOrFail($id);
        return view('membership_types.edit', compact('membership'));
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
        $membership = MembershipType::findOrFail($id);
        $this->validate($request, [
            'membership_type'=>'required|max:40',
        ]);
        $input = $request->all();
        $membership->fill($input)->save();
        return redirect()->route('membership_types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $membership = MembershipType::findOrFail($id);
        $membership->delete();
        return redirect()->route('membership_types.index');
    }
}
