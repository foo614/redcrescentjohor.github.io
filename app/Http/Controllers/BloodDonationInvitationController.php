<?php

namespace App\Http\Controllers;

use App\Mail\BloodDonationInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Donor;
use App\User;
use App\Hospital;
use App\BloodType;

class BloodDonationInvitationController extends Controller
{
    public function send(Request $request){
        $get_donor = Donor::with('bloodType')->findOrFail($request->donor_id);
        $get_hospital = Hospital::findOrFail($request->hospital_id);
        $donor_info = User::findOrFail($get_donor->user_id);

        $letter_content = new \stdClass();
        $letter_content->donor_blood_type = $get_donor->bloodType->name;
        $letter_content->donor_name = $donor_info->name;
        $letter_content->donor_email = $donor_info->email;
        $letter_content->hospital_name = $get_hospital->name;
        $letter_content->hospital_address = $get_hospital->address;
        $letter_content->hospital_email = $get_hospital->email;
        $letter_content->hospital_contact = $get_hospital->contact;

        $letter_content->sender = 'Red Crescent Johor';
        $letter_content->receiver = $donor_info->name;
        
        Mail::to($donor_info->email)->send(new BloodDonationInvitationMail($letter_content));

    }

    public function sendAll(Request $request){
        
    }
}
