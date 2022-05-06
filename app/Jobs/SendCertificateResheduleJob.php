<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendCertificateReshedule;
use Mail;
use Carbon\Carbon;
use PDF;

class SendCertificateResheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $firefighter,$certificate,$data,$update_certificate_status,$subject;
    public function __construct($firefighter,$certificate,$data,$update_certificate_status,$subject)
    {
        $this->firefighter = $firefighter;
        $this->certificate = $certificate;
        $this->data        = $data;
        $this->update_certificate_status  = $update_certificate_status;
        $this->subject     = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pdf = PDF::loadView('firefighter.awarded-certificate', $this->data);
        $attachment = $pdf->output();
        $email = (new SendCertificateReshedule($this->firefighter,$this->certificate,$this->update_certificate_status,$attachment,$this->subject))->delay(Carbon::now()->addSeconds(3));
        Mail::to($this->firefighter->email)->send($email);
    }
}
