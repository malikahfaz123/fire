<?php

namespace App\Http\Controllers;

use App\CompletedCourse;
use App\Firefighter;
use App\Http\Helpers\Helper;
use App\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompletedCourseController extends Controller
{

    public function index($firefighter_id)
    {
        $completed_courses = CompletedCourse::select(DB::raw('COUNT(id) as count'))->where('firefighter_id',$firefighter_id)->whereNull('is_archive')->first();

        $firefighter = Firefighter::find($firefighter_id);

        return view('firefighter.completed-course')->with('title','View Completed Courses')->with('firefighter',$firefighter)->with('completed_courses',$completed_courses);
    }

    public function mark_completed(Request $request, $firefighter_id){

        foreach ($request->courses['semester_id'] as $key => $semester_id){
            $course_id = $request->courses['course_id'][$key];

            // Validate if course is already completed
            $is_course_completed = \App\Http\Helpers\FirefighterHelper::is_course_completed($firefighter_id,$semester_id,$course_id);

            // Validate if candidate meets minimum attendance
            $total_classes = \App\Http\Helpers\Helper::total_classes($semester_id,$course_id,$firefighter_id);
            $attended_classes = \App\Http\Helpers\Helper::get_attended_classes($semester_id,$course_id,$firefighter_id);
            $min_attendance = \App\Http\Helpers\Helper::get_min_attendance_perc();
            $attendance = $total_classes && $attended_classes ? number_format(($attended_classes/$total_classes)*100,0) : 0;
            
            // Validate if is semester completed
            $semester = Semester::find($semester_id);
            $is_semester_completed = \App\Http\Helpers\Helper::is_semester_completed($semester->semester,$semester->year);
    
            if(!$is_course_completed && $attendance > $min_attendance && $is_semester_completed){
                $completed_course = new CompletedCourse();
                $completed_course->firefighter_id = $firefighter_id;
                $completed_course->semester_id = $semester_id;
                $completed_course->course_id = $course_id;
                if(!$completed_course->save()){
                    return response()->json(['status'=>false,'msg'=> 'Something went wrong. Please try again!']);
                }
            }
        }
        return response()->json(['status'=>true,'msg'=> 'Marked Completed !']);
    }

    public function paginate(Request $request,$firefighter_id){
        $per_page = Helper::per_page();
        $query = CompletedCourse::select('completed_courses.*','courses.prefix_id','courses.course_name')->leftJoin('courses','completed_courses.course_id','=','courses.id')->where('completed_courses.firefighter_id',$firefighter_id);

        if($request->is_archive){
            $query = $query->where('completed_courses.is_archive',1);
        }else{
            $query = $query->whereNull('completed_courses.is_archive');
        }

        if($request->course_id){
            $query = $query->where('courses.prefix_id',$request->course_id);
        }

        if($request->course_name){
            $query = $query->where('courses.course_name','like',"%{$request->course_name}%");
        }

        if($request->created_at){
            $query = $query->where('completed_courses.created_at','like',"{$request->created_at}%");
        }

        $completed_courses = $query->orderBy('completed_courses.created_at','desc')->paginate($per_page)->appends(request()->query());
        return view('firefighter.paginate-completed-courses')->with('completed_courses',$completed_courses);
    }

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        CompletedCourse::where('id',$request->archive)->update(['is_archive'=>1]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));
    }

    public function archive($firefighter_id){
        $completed_courses = CompletedCourse::select(DB::raw('COUNT(id) as count'))->where('is_archive',1)->first();
        $firefighter = Firefighter::find($firefighter_id);
        return view('firefighter.archive-completed-courses')->with('title','Archived Completed Courses')->with('firefighter',$firefighter)->with('completed_courses',$completed_courses);
    }

    public function unarchive(Request $request){
        CompletedCourse::where('id',$request->archive)->update(['is_archive'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }
}
