<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\FirefighterCertificateAwarded;
use Mail;
use Carbon\Carbon;
use PDF;
use App\User;

class FirefighterCertificateAwardedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $firefighter,$certificate,$data,$issue_date,$subject;
    public function __construct($firefighter,$certificate,$data,$issue_date,$subject)
    {
        $this->firefighter = $firefighter;
        $this->certificate = $certificate;
        $this->issue_date  = $issue_date;
        $this->data        = $data;
        $this->subject     = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user  =  User::where('role_id',1)->select('name','email')->first();
        $pdf = PDF::loadView('firefighter.awarded-certificate', $this->data);
        $attachment = $pdf->output();
        $email = (new FirefighterCertificateAwarded($user,$this->firefighter,$this->certificate,$this->issue_date,$attachment,$this->subject))->delay(Carbon::now()->addSeconds(3));
        Mail::to($user->email)->send($email);
    }
}
