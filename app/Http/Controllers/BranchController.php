<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('branches.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $branch = $request->isMethod('put') ? Branch::findOrFail($request->branch_id) : new Branch;
        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->email = $request->email;
        $branch->contact = $request->contact;
        $branch->map_lat = $request->map_lat;
        $branch->map_lng = $request->map_lng;
        $branch->save();
        return redirect()->route('branches.index');
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
        $branch = Branch::findOrFail($id);
        return view('branches.edit', compact('branch'));
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
            'email'=>'nullable|email|unique:branches,email,'.$id,
        ]);

        $branch = Branch::findOrFail($id);
        $branch->name = $request->input('name');
        $branch->address = $request->input('address');
        $branch->email = $request->input('email');
        $branch->contact = $request->input('contact');
        $branch->map_lat = $request->input('map_lat');
        $branch->map_lng = $request->input('map_lng');
        $branch->save();
        return redirect()->route('branches.index');
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
