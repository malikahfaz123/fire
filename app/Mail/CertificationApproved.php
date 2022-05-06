<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificationApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $firefighter,$certification,$test_date,$test_time,$subject;
    public function __construct($firefighter,$certification,$test_date,$test_time,$subject)
    {
        $this->firefighter    =  $firefighter;
        $this->certification  =  $certification;
        $this->test_date      =  $test_date;
        $this->test_time      =  $test_time;
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
        ->markdown('firefighter-setting.certification-approved');
    }
}
