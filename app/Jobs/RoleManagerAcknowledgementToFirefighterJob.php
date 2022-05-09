<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

use App\Mail\RoleManagerAcknowledgementToFirefighter;

class RoleManagerAcknowledgementToFirefighterJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $firefighter_name, $firefighter_email, $assigned_by, $msgForFirefighter, $heading, $username, $password, $subject;

    public function __construct($firefighter_name, $firefighter_email, $assigned_by, $msgForFirefighter, $heading, $username, $password, $subject)
    {
        $this->firefighter_name = $firefighter_name;
        $this->firefighter_email = $firefighter_email;
        $this->assigned_by = $assigned_by;
        $this->msgForFirefighter = $msgForFirefighter;
        $this->heading = $heading;
        $this->username = $username;
        $this->password = $password;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = (new RoleManagerAcknowledgementToFirefighter( $this->firefighter_name, $this->assigned_by, $this->msgForFirefighter, $this->heading, $this->username, $this->password, $this->subject ))->delay(Carbon::now()->addSeconds(3));
        Mail::to($this->firefighter_email)->send($email);
    }
}
