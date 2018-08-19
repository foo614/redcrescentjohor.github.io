<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Branch;
use App\Http\Resources\BranchResource;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::all();
        return BranchResource::collection($branches);
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
        $branch->contact = $request->contact;
        $branch->email = $request->email;
        $branch->fax = $request->fax;
        $branch->map_lat = $request->map_lat;
        $branch->map_lng = $request->map_lng;

        if($branch->save()) {
            return new BranchResource($branch);
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
        $branch = Branch::findOrFail($id);
        return new BranchResource($branch);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        if($branch->delete()){
            return new BranchResource($branch);
        }
    }
}
