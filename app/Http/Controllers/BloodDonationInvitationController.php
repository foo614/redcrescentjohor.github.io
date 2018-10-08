<?php

namespace App\Http\Controllers;

use App\Mail\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\User;
use App\Hospital;

class BloodDonationInvitationController extends Controller
{
    public function send(Request $request){
        $get_hospital = Hospital::findOrFail($request->hospital_id);
        $donor_info = User::with('blood_type')->findOrFail($request->donor_id);

        $invitation_content = new \stdClass();
        $invitation_content->donor_blood_type = $donor_info->blood_type['name'];
        $invitation_content->donor_name = $donor_info->name;
        $invitation_content->donor_email = $donor_info->email;
        $invitation_content->donor_map_lat = $donor_info->map_lat;
        $invitation_content->donor_map_lng = $donor_info->map_lng;
        $invitation_content->hospital_name = $get_hospital->name;
        $invitation_content->hospital_address = $get_hospital->address;
        $invitation_content->hospital_email = $get_hospital->email;
        $invitation_content->hospital_map_lat = $get_hospital->map_lat;
        $invitation_content->hospital_map_lng = $get_hospital->map_lng;
        $invitation_content->hospital_contact = $get_hospital->contact;

        $invitation_content->sender = 'Red Crescent Johor';
        $invitation_content->receiver = $donor_info->name;
        
        Mail::to($donor_info->email)->send(new Invitation($invitation_content));

    }

    public function sendAll(Request $request){
        
    }
}
