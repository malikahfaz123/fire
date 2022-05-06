<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $link,$f_name,$subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link,$f_name)
    {
        $this->link = $link;
        $this->f_name = $f_name;
        $this->subject = 'Confirmation email';
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
            ->markdown('emails.confirm-email');
    }
}
