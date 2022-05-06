<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCertificate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $firefighter,$certificate,$update_certificate_status,$attachment,$subject;
    public function __construct($firefighter,$certificate,$update_certificate_status,$attachment)
    {
        $this->firefighter = $firefighter;
        $this->certificate = $certificate;
        $this->update_certificate_status = $update_certificate_status;
        $this->attachment = $attachment;
        $this->subject = "Test Results for Credential ".ucfirst($certificate->title);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.send-certificate')
            ->attachData($this->attachment, "certificate-{$this->certificate->prefix_id}.pdf", [
            'mime' => 'application/pdf',
        ]);
    }
}
