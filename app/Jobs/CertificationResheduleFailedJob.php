<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CertificationResheduleFailed;
use Mail;
use Carbon\Carbon;

class CertificationResheduleFailedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $firefighter_email,$firefighter_name,$certification,$subject;
    public function __construct($firefighter_email,$firefighter_name,$certification,$subject)
    {
        $this->firefighter_email =  $firefighter_email;
        $this->firefighter_name  =  $firefighter_name;
        $this->certification     =  $certification;
        $this->subject           =  $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = (new CertificationResheduleFailed($this->firefighter_name,$this->certification,$this->subject))->delay(Carbon::now()->addSeconds(3));
        Mail::to($this->firefighter_email)->send($email);
    }
}
