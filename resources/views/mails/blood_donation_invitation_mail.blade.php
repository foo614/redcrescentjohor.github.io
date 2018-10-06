<div>Good day {{ $invitation_content->receiver }},</div><br/>

<div>
<b>Urgent need</b> for {{$invitation_content->donor_blood_type}} blood types. Please come as soon as possible to donate blood.
<br/>
<div>HOSPITAL {{$invitation_content->hospital_name}}</div>
<div>CONTACT {{$invitation_content->hospital_contact}}</div>
<div>EMAIL {{$invitation_content->hospital_email}}</div>
<div>ADDRESS {{$invitation_content->hospital_address}}</div>



</div>
   <br/>
Thank You,
<br/>
{{ $invitation_content->sender }}
