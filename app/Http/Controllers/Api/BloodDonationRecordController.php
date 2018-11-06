<?php

namespace App\Http\Controllers\Api;

use App\BloodDonationRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BloodDonationRecordResource;

class BloodDonationRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bloodDonationRecords = BloodDonationRecord::all();
        return BloodDonationRecordResource::collection($bloodDonationRecords);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request   BloodDonationRecord
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->json() as $item) {
            $bloodDonationRecord = $request->isMethod('put') ? BloodDonationRecord::findOrFail($item['blood_donation_record_id']) : new BloodDonationRecord;
            $bloodDonationRecord->user_id = $item['user']['id'];
            $bloodDonationRecord->post_id = $item['post_id'];
            $bloodDonationRecord->donate_date = $item['donate_date'];
            $bloodDonationRecord->volume = $item['volume'];
            $bloodDonationRecord->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BloodDonationRecord  $bloodDonationRecord
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bloodDonationRecord = BloodDonationRecord::findOrFail($id);
        return new BloodDonationRecordResource($bloodDonationRecord);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BloodDonationRecord  $bloodDonationRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bloodDonationRecord = BloodDonationRecord::findOrFail($id);
        if($bloodDonationRecord->delete()) {
            return new BloodDonationRecordResource($bloodDonationRecord);
        } 
    }

    public function bloodDonationRecordsFromUser($id){
        $result = BloodDonationRecord::where('user_id', $id)->get();
        return BloodDonationRecordResource::collection($result);
    }
}
