@component('mail::message')
# Thank you joined as donator and please take attention on few details.

<div>
    Hi, {{ $content->name }}.
</div>
<div>
We use your personal information:<br>
<ul>
    <li>to work out when and where to invite you to donate blood;</li>
    <li>to communicate with you via post, email, phone call, mobile messages and SMS in relation to:</li>
    <ul style="list-style-type:decimal;">
        <li>sending session invitations</li>
        <li>and preparing for donation appointments</li>
        <li>letting you know how we have used the blood you have donated</li>
        <li>information about other donation matters</li>
        <li>developments in our services</li>
        <li>opportunities to volunteer for research projects</li>
        <li>encouraging you to continue to donate.</li>
    </ul>
</ul>
We do our best to contact you in the way you prefer about the subjects you prefer. If you want us to change the way we contact you, please let us know by contacting our helpline on 07 2371544, or email ibupejabat@redcrescentjohor.org.my.
</div>


Regards,<br>
{{ config('app.name') }}
@endcomponent
