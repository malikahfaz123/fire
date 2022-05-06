<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CourseEnrollement;
use Mail;
use Carbon\Carbon;


class CourseEnrollmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $firefighter_email,$firefighter,$course,$semester,$subject;
    public function __construct($firefighter_email,$firefighter,$course,$semester,$subject)
    {

        $this->firefighter =  $firefighter;
        $this->firefighter_email =  $firefighter_email;
        $this->course      =  $course;
        $this->semester    =  $semester;
        $this->subject     =  $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(3));
        $email = (new CourseEnrollement($this->firefighter,$this->course,$this->semester,$this->subject))->delay(Carbon::now()->addSeconds(3));
        Mail::to($this->firefighter_email)->send($email);
    }
}
