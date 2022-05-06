<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FirefighterCertificateAwarded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user,$firefighter,$certificate,$issue_date,$attachment,$subject;
    public function __construct($user,$firefighter,$certificate,$issue_date,$attachment,$subject)
    {
        $this->user        = $user;
        $this->firefighter = $firefighter;
        $this->certificate = $certificate;
        $this->issue_date  = $issue_date;
        $this->attachment  = $attachment;
        $this->subject     = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.firefighter-certificate-awarded')->attachData($this->attachment, "certificate-{$this->certificate->prefix_id}.pdf", [
            'mime' => 'application/pdf',
        ]);
    }
}
