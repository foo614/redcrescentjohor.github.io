<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BloodResource;
use App\BloodType;

class BloodTypeController extends Controller
{
     /**
     * Return a paginated list of blood types.
     *
     * @return BloodResource
     */
    public function index()
    {
        $blood_types = BloodType::all();
        return BloodResource::collection($blood_types);
    }


    /**
     * Validate and save a new signature to the database.
     *
     * @param Request $request
     * @return BloodResource
     */
    public function store(Request $request)
    {
        $blood_type = $request->isMethod('put') ? BloodType::findOrFail($request->blood_type_id) : new BloodType;
        $blood_type->name = $request->name;

        if($blood_type->save()) {
            return new BloodResource($blood_type);
        }
    }

    /**
     * Fetch and return the Post.
     *
     * @param BloodType $blood_type
     * @return BloodResource
     */
    public function show($id)
    {
        $blood_type = BloodType::findOrFail($id);
        return new BloodResource($blood_type);
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
        if($blood_type->delete()) {
            return new BloodResource($blood_type);
        }    
    }
}
