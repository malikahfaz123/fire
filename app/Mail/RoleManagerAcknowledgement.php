<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RoleManagerAcknowledgement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name,$email,$assigned_by,$assigned_to,$message,$subject;
    public function __construct($name,$email,$assigned_by,$assigned_to,$message,$subject)
    {
        $this->name    =  $name;
        $this->email      =  $email;
        $this->assigned_by = $assigned_by;
        $this->assigned_to = $assigned_to;
        $this->message = $message;
        $this->subject        =  $subject;

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
            ->markdown('emails.role-manager-acknowledgement-mail');
    }
}
