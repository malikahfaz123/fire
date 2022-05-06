<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificationResheduleFailed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $firefighter_name,$certification,$subject;
    public function __construct($firefighter_name,$certification,$subject)
    {
        $this->firefighter_name  =  $firefighter_name;
        $this->certification     =  $certification;
        $this->subject           =  $subject;
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
        ->markdown('firefighter-setting.certification-reshedule-failed');
    }
}
