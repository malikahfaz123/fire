<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FirefighterCourses;
use App\Setting;
use App\Course;
use App\CoursePrerequisites;
use App\CompletedCourse;
use App\Http\Helpers\Helper;
use \DB;
use App\User;
use App\Semester;
use App\Firefighter;
use App\Jobs\FirefighterCourseEnrollementJob;

class FirefighterCoursesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // check already exist
        $enroll_courses_count = FirefighterCourses::where('semester_id',$request->semester_id)->where('course_id', $request->course_id)->where('firefighter_id',$request->firefighter_id)->where('status','applied')->count();
        if($enroll_courses_count > 0){
            return response()->json(['status'=> false,'msg'=>'You already Applied for this Course.' ]);
        }

        $enroll_courses_count = FirefighterCourses::where('semester_id',$request->semester_id)->where('course_id', $request->course_id)->where('firefighter_id',$request->firefighter_id)->where('status','enrolled')->count();
        if($enroll_courses_count > 0){
            return response()->json(['status'=> false,'msg'=>'You already Enrolled for this Course.' ]);
        }
     
        // Enrollment validation
        $enrollment_limit        =  Helper::enrollment_limit();
        $enroll_courses_count    =  FirefighterCourses::where('status','applied')->where('semester_id',$request->semester_id)->where('firefighter_id',$request->firefighter_id)->count();
        if($enroll_courses_count >= $enrollment_limit){
            return response()->json(['status'=> false,'msg'=>'Your Enrollment Limit Exceeded.' ]);
        }

        // Maximum student validation
        $course_maximum_students    =  Course::where('id',$request->course_id)->pluck('maximum_students');
        $course_maximum_students    =  (int) $course_maximum_students[0];

        // $enrolled_courses_count     =  FirefighterCourses::where('status','enrolled')->where('semester_id',$request->semester_id)->where('course_id', $request->course_id)->count();
        $enrolled_courses_count     =  FirefighterCourses::where('status','applied')->where('semester_id',$request->semester_id)->where('course_id', $request->course_id)->count();
        if( $enrolled_courses_count >= $course_maximum_students ){
            return response()->json(['status'=> false,'msg'=> "There is no seat available." ]);
        }

        $course_prerequisites = CoursePrerequisites::where('course_id', $request->course_id)->count();

        if($course_prerequisites > 0){

            $course_prerequisites_ids  =  CoursePrerequisites::where('course_id', $request->course_id)->pluck('preq_course_id')->toArray();
            $completed_course          =  CompletedCourse::where('firefighter_id',$request->firefighter_id)->whereIn('course_id', $course_prerequisites_ids)->pluck('course_id')->toArray();

            if($course_prerequisites_ids != $completed_course ){
                $pre_req_courses = CoursePrerequisites::select('course_prerequisites.id','course_prerequisites.course_id','course_prerequisites.preq_course_id','courses.course_name as course_name')
                ->leftJoin('courses','courses.id','=','course_prerequisites.preq_course_id')
                ->whereIn('course_prerequisites.preq_course_id',$course_prerequisites_ids)
                ->groupBy('course_prerequisites.preq_course_id')
                ->pluck('courses.course_name')->toArray();

                return response()->json(['status'=> false,'msg' => "You should have to do this Prerequisite Course First: ".implode(', ',$pre_req_courses) ]);
            }
        }

        $firefighter_course = new FirefighterCourses();
        $firefighter_course->semester_id     =  $request->semester_id;
        $firefighter_course->course_id       =  $request->course_id;
        $firefighter_course->firefighter_id  =  $request->firefighter_id;
        $firefighter_course->status          =  "applied";

        if(!$firefighter_course->save()){
            return response()->json(['status'=> false,'msg'=>'Failed to save course. Please try again.']);
        }

        $user           =  User::where('role_id',1)->select('name','email')->first();
        $semester       =  Semester::where('id',$request->semester_id)->select('semester','year')->first();
        $course         =  Course::where('id',$request->course_id)->select('course_name')->first();
        $firefighter    =  Firefighter::where('id',$request->firefighter_id)->select('email','f_name','m_name','l_name','cell_phone')->first();

        // Sending Email to Admin
        dispatch(new FirefighterCourseEnrollementJob($user->name,$user->email,$firefighter->email,$firefighter->f_name,$firefighter->m_name,$firefighter->l_name,$firefighter->cell_phone,$semester->semester,$semester->year,$course->course_name,"Request for Course Enrollment ".ucfirst($course->course_name)));

        return response()->json(['status'=>true,'msg'=>'Applied Successfully !']);
    }
}
