@component('mail::message')
# {{$content->course_name}}

Dear participant,

Welcome to Malaysian Reg Crescent Training Institute (HQ).<br>
Your registration was successful.

Given below is the details and address of the training venue, for your kind perusal.

@component('mail::panel')
<table style="width:100%">
    <tr><th colspan="2">Course Details</th></tr>
    <tr style="line-height: 2rem;"><td style="width: 15%;">Name</td><td style="width: 100%;">{{$content->course_name}}</td></tr>
    <tr style="line-height: 2rem;"><td style="width: 15%;">Date</td><td style="width: 100%;">{{$content->course_start_date}}</td></tr>
    <tr style="line-height: 2rem;"><td style="width: 15%;">Venue</td><td style="width: 100%;">{{$content->course_venue}}</td></tr>
    <tr style="line-height: 2rem;"><td style="width: 15%;">Notice</td><td style="width: 100%;">{{$content->course_info}}</td></tr>
</table>
@endcomponent

Confirm your personal information, <br>
Name  : {{$content->user_name}} <br>
Email : {{$content->user_email}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
