@component('mail::message')
# Blood Donation Invitation

Good day {{ $invitation_content->receiver }},


**Urgent** need for **_{{$invitation_content->donor_blood_type}}_** blood types.

@component('mail::panel')
<table>
    <tr style="line-height: 2rem;"><td style="width: 30%;">HOSPITAL</td><td style="width: 70%;">{{ $invitation_content->hospital_name}}</td></tr>
    <tr style="line-height: 2rem;"><td style="width: 30%;">CONTACT</td><td style="width: 70%;">{{ $invitation_content->hospital_contact}}</td></tr>
    <tr style="line-height: 2rem;"><td style="width: 30%;">EMAIL</td><td style="width: 70%;">{{ $invitation_content->hospital_email}}</td></tr>
    <tr style="line-height: 2rem;"><td style="width: 30%;">ADDRESS</td><td style="width: 70%;">{{ $invitation_content->hospital_address}}</td></tr>
</table>

@endcomponent

@component('mail::button', ['url' => "https://www.google.com/maps?saddr=$invitation_content->donor_map_lat,$invitation_content->donor_map_lng&daddr=$invitation_content->hospital_map_lat,$invitation_content->hospital_map_lng"])
Show Directions
@endcomponent

Regards,<br>
**{{ $invitation_content->sender }}**
@endcomponent
