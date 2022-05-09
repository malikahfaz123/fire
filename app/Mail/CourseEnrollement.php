<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;



class CourseEnrollement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $firefighter,$course,$semester,$subject;
    public function __construct($firefighter,$course,$semester,$subject)
    {
        $this->firefighter =  $firefighter;
        $this->course      =  $course;
        $this->semester    =  $semester;
        $this->subject     =  $subject;
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
        ->markdown('firefighter-setting.course-enrollment');
    }
}
