<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RoleManagerAcknowledgementToFirefighter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $firefighter_name, $assigned_by, $msgForFirefighter, $heading, $username, $password, $subject;
    public function __construct($firefighter_name, $assigned_by, $msgForFirefighter, $heading, $username, $password, $subject)
    {
        $this->firefighter_name= $firefighter_name;
        $this->assigned_by = $assigned_by;
        $this->msgForFirefighter = $msgForFirefighter;
        $this->heading = $heading;
        $this->username = $username;
        $this->password = $password;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject($this->subject)
            ->markdown('emails.role-manager-acknowledgement-mail-to-firefighter');
    }
}
