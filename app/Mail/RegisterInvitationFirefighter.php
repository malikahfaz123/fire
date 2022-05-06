<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterInvitationFirefighter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject, $host, $firefighter;
    public function __construct($host,$firefighter)
    {
        $this->subject = 'You have received an invitation';
        $this->host = $host;
        $this->firefighter = $firefighter;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('talha@cms.com', 'Mailtrap')
            ->subject($this->subject)
            ->markdown('emails.register-invitation-firefighter');
    }
}
