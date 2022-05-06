<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FirefighterCertificateSupply extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $admin_name,$firefighter_email,$firefighter_f_name,$firefighter_m_name,$firefighter_l_name,$cell_phone,$certificate,$subject;
    public function __construct($admin_name,$firefighter_email,$firefighter_f_name,$firefighter_m_name,$firefighter_l_name,$cell_phone,$certificate,$subject)
    {
        $this->admin_name         =  $admin_name;
        $this->firefighter_email  =  $firefighter_email;
        $this->firefighter_f_name =  $firefighter_f_name;
        $this->firefighter_m_name =  $firefighter_m_name;
        $this->firefighter_l_name =  $firefighter_l_name;
        $this->cell_phone         =  $cell_phone;
        $this->certificate        =  $certificate;
        $this->subject            =  $subject;
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
        ->markdown('emails.firefighter-certification-supply');
    }
}
