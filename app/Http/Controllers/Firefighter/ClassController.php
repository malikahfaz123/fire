<?php

namespace App\Http\Controllers\Firefighter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes;
use App\CourseClass;
use App\Course;
use App\Firefighter;
use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
   
    public function index($course_id){

        $per_page = Helper::per_page();

        $firefighter_classes_count = CourseClass::whereNull('classes.is_archive')->where('course_classes.course_id',$course_id)->where('firefighter_id',Auth::guard('firefighters')->user()->id)
        ->leftJoin('classes','classes.id','=','course_classes.class_id')
        ->count();

        $course = Course::where('id',$course_id)->select('prefix_id')->first();

        return view('firefighter-frontend.class.index')->with('course',$course)->with('course_id',$course_id)->with('firefighter_classes_count',$firefighter_classes_count);
    }

    public function paginate(Request $request,$course_id){

        $per_page = Helper::per_page();

        $query = CourseClass::whereNull('classes.is_archive')->where('course_classes.course_id',$course_id)->where('firefighter_id',Auth::guard('firefighters')->user()->id)
        ->leftJoin('classes','classes.id','=','course_classes.class_id')
        ->leftJoin('firefighters','firefighters.id','=','classes.instructor_id')
        ->leftJoin('semesters','semesters.id','=','classes.semester_id')
        ->select('classes.start_date','classes.end_date','classes.id','classes.start_time','classes.instructor_id','firefighters.f_name as instructor_f_name','firefighters.m_name as instructor_m_name','firefighters.l_name as instructor_l_name','semesters.id as semester_id');

        if($request->start_date){
            $query = $query->where('classes.start_date',$request->start_date);
        }
        
        if($request->end_date){
            $query = $query->where('classes.end_date',$request->end_date);
        }

        $firefighter_classes = $query->paginate($per_page)->appends(request()->query());

        return view('firefighter-frontend.class.paginate')->with('classes',$firefighter_classes);
    }
    
}
