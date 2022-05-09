<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

use App\Mail\RoleManagerAcknowledgement;

use App\User;

class RoleManagerAcknowledgementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $firefighter_email, $assigned_by, $assigned_to, $message, $subject;

    public function __construct($firefighter_email, $assigned_by, $assigned_to, $message, $subject)
    {
        $this->firefighter_email    =   $firefighter_email;
        $this->assigned_by          =   $assigned_by;
        $this->assigned_to          =   $assigned_to;
        $this->message              =   $message;
        $this->subject              =   $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $admins = User::where('email', '!=', $this->firefighter_email)->get();
        if(!empty($admins))
        {
            foreach ($admins as $admin) {
                $email = (new RoleManagerAcknowledgement($admin->name, $admin->email, $this->assigned_by, $this->assigned_to,$this->message, $this->subject))->delay(Carbon::now()->addSeconds(4));
                Mail::to($admin->email)->send($email);
            }
        }
    }
}
