@if($service == 'facebook')
<div class="title m-b-md">
    Welcome {{ $details->user['name']}} ! <br> Your email is : {{
    $details->user['email'] }}
@endif