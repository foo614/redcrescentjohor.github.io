@component('mail::message')
# {{$content->course_name}}

Dear participant,

Welcome to Malaysian Reg Crescent Training Institute (HQ).<br>
Your registration was successful.

Given below is the details and address of the training venue, for your kind perusal.

@component('mail::panel')
<table style="width:100%">
    <tr><th colspan="2">Course Details</th></tr>
    <tr style="line-height: 2rem;"><td>Name     </td><td>{{$content->course_name}}</td></tr>
    <tr style="line-height: 2rem;"><td>Date     </td><td>{{$content->course_start_date}}</td></tr>
    <tr style="line-height: 2rem;"><td>Venue    </td><td>{{$content->course_venue}}</td></tr>
    <tr style="line-height: 2rem;"><td>Notice   </td><td>{{$content->course_info}}</td></tr>
</table>
1p@endcomponentnb0111111111111111
lpk
Conj    0firm your personal information, <br>
Name  : {{$content->user_name}} <br>
Email : {{$content->user_email}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
