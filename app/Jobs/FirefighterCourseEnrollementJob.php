<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\FirefighterCourseEnrollement;
use Mail;
use Carbon\Carbon;

class FirefighterCourseEnrollementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $admin_name,$admin_email,$firefighter_email,$firefighter_f_name,$firefighter_m_name,$firefighter_l_name,$cell_phone,$semester_semester,$semester_year,$course_name,$subject;
    public function __construct($admin_name,$admin_email,$firefighter_email,$firefighter_f_name,$firefighter_m_name,$firefighter_l_name,$cell_phone,$semester_semester,$semester_year,$course_name,$subject)
    {
        $this->admin_name         =  $admin_name;
        $this->admin_email        =  $admin_email;
        $this->firefighter_email  =  $firefighter_email;
        $this->firefighter_f_name =  $firefighter_f_name;
        $this->firefighter_m_name =  $firefighter_m_name;
        $this->firefighter_l_name =  $firefighter_l_name;
        $this->cell_phone         =  $cell_phone;
        $this->semester_semester  =  $semester_semester;
        $this->semester_year      =  $semester_year;
        $this->course_name        =  $course_name;
        $this->subject            =  $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = (new FirefighterCourseEnrollement($this->admin_name,$this->firefighter_email,$this->firefighter_f_name,$this->firefighter_m_name,$this->firefighter_l_name,$this->cell_phone,$this->semester_semester,$this->semester_year,$this->course_name,$this->subject))->delay(Carbon::now()->addSeconds(3));
        Mail::to($this->admin_email)->send($email);
    }
}
