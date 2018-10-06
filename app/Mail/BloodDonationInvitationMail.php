<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BloodDonationInvitationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $invitation_content;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->invitation_content = $invitation_content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->view('mails.blood_donation_invitation_mail')->text('mails.blood_donation_invitation_mail_plain');
    }
}
