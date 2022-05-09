<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CertificationApproved;
use Mail;
use Carbon\Carbon;

class CertificationApprovedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $firefighter_email,$firefighter,$certification,$test_date,$test_time,$subject;
    public function __construct($firefighter_email,$firefighter,$certification,$test_date,$test_time,$subject)
    {
        $this->firefighter_email =  $firefighter_email;
        $this->firefighter       =  $firefighter;
        $this->certification     =  $certification;
        $this->test_date         =  $test_date;
        $this->test_time         =  $test_time;
        $this->subject           =  $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = (new CertificationApproved($this->firefighter,$this->certification,$this->test_date,$this->test_time,$this->subject))->delay(Carbon::now()->addSeconds(3));
        Mail::to($this->firefighter_email)->send($email);
    }
}
