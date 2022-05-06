<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTranscript extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $firefighter,$course,$attachment,$subject;
    public function __construct($firefighter,$course,$attachment)
    {
        $this->firefighter = $firefighter;
        $this->course = $course;
        $this->attachment = $attachment;
        $this->subject = "Transcript for Course {$course->prefix_id}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.send-transcript')->attachData($this->attachment, "transcript-course-{$this->course->prefix_id}.pdf", [
            'mime' => 'application/pdf',
        ]);
    }
}
