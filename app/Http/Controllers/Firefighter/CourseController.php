<?php

namespace App\Http\Controllers\FireFighter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes;
use App\Course;
use App\CreditType;
use App\ForeignRelations;
use App\History;
use App\Http\Helpers\CourseHelper;
use App\Http\Helpers\Helper;
use App\FirefighterCourses;
use App\CoursePrerequisites;
use App\SemesterCourse;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $courses = Course::select(DB::raw('COUNT(id) as count'))->whereNull('is_archive')->first();
        return view('firefighter-frontend.course.index')->with('title','Course')->with('courses',$courses);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = $request->is_archive ? Course::where('is_archive',1) : Course::whereNull('is_archive');
        $query = Helper::filter('courses',$request->all(),$query,['is_archive','created_by','created_at','updated_at']);
        if($query){
            $courses = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $courses = Course::orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }

        if($request->is_archive){
            return view('course.paginate-archive')->with('courses',$courses);
        }
        return view('firefighter-frontend.course.paginate')->with('courses',$courses);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $course = Course::find($id);
        if($course && $course->count()){
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp){
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($credit_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','courses')->where('name','credit_types')->get();

                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description']." ({$credit_types[$temp->value]['prefix_id']})";
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types,true) : '';
            }
            $last_updated = Helper::get_last_updated('courses',$id);
            return view('firefighter-frontend.course.show', ['title' => 'View Course','course'=>$course,'credit_types'=>$credit_types,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'last_updated'=>$last_updated]);
        }else{
            return view('404');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function my_courses_index()
    {
        $firefighter_courses_count = FirefighterCourses::where('firefighter_id',Auth::guard('firefighters')->user()->id)
        ->leftJoin('courses','courses.id','=','firefighter_courses.course_id')
        ->leftJoin('firefighters','firefighters.id','=','firefighter_courses.firefighter_id')
        ->leftJoin('semesters','semesters.id','=','firefighter_courses.semester_id')
        ->select('firefighter_courses.id','firefighter_courses.course_id','semesters.semester','semesters.year','courses.prefix_id','courses.course_name','courses.course_hours','firefighters.f_name','firefighters.m_name','firefighters.l_name','firefighter_courses.status')
        ->orderBy('firefighter_courses.created_at','DESC')
        ->count();

        return view('firefighter-frontend.my-courses.index')->with('title','My Courses')->with('firefighter_courses_count',$firefighter_courses_count);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function my_courses_paginate(Request $request)
    {
        $per_page = Helper::per_page();

        $query = FirefighterCourses::where('firefighter_id',Auth::guard('firefighters')->user()->id)
            ->leftJoin('courses','courses.id','=','firefighter_courses.course_id')
            ->leftJoin('firefighters','firefighters.id','=','firefighter_courses.firefighter_id')
            ->leftJoin('semesters','semesters.id','=','firefighter_courses.semester_id')
            ->select('firefighter_courses.id','firefighter_courses.course_id','firefighter_courses.semester_id','semesters.semester','semesters.year','courses.prefix_id','courses.course_name','courses.course_hours','firefighters.f_name','firefighters.m_name','firefighters.l_name','firefighter_courses.status')
            ->orderBy('firefighter_courses.created_at','DESC');

            if($request->course_name){
                $query = $query->where('courses.course_name',$request->course_name);
            }

            if($request->prefix_id){
                $query = $query->having('prefix_id','like',"%{$request->prefix_id}%");
            }

            if($request->type){
                $query = $query->where('firefighter_courses.status',$request->type);
            }
            
            $firefighter_courses = $query->paginate($per_page)->appends(request()->query());

        return view('firefighter-frontend.my-courses.paginate')->with('firefighter_courses',$firefighter_courses);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */

    public function my_courses_show($id){
        $course = Course::find($id);
        if($course && $course->count()){
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp){
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($credit_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','courses')->where('name','credit_types')->get();

                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description']." ({$credit_types[$temp->value]['prefix_id']})";
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types,true) : '';
            }

            $pre_req_courses = CoursePrerequisites::select('course_prerequisites.id','course_prerequisites.course_id','course_prerequisites.preq_course_id','courses.course_name as course_name','courses.prefix_id as prefix_id')
            ->leftJoin('courses','courses.id','=','course_prerequisites.preq_course_id')
            ->where('course_prerequisites.course_id',$id)
            ->get();

            // status and reason in detail
            $firefighter_courses = FirefighterCourses::where('firefighter_id', Auth::guard('firefighters')->user()->id)
            ->where('firefighter_courses.course_id',$id)
            ->leftJoin('rejected_reasons','rejected_reasons.firefighter_courses_id','=','firefighter_courses.id')
            ->select('rejected_reasons.reason','firefighter_courses.status')
            ->first();

            $prereq_courses = [];
            if($pre_req_courses && $pre_req_courses->count()){
                foreach ($pre_req_courses as $pre_req_course){
                    $prereq_courses[] = "{$pre_req_course->course_name} ($pre_req_course->prefix_id)";
                }
            }

            $last_updated = Helper::get_last_updated('courses',$id);
            return view('firefighter-frontend.my-courses.show', ['title' => 'View Course','course'=>$course,'credit_types'=>$credit_types,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'last_updated'=>$last_updated,'prereq_courses'=>$prereq_courses,'pre_req_courses' => $pre_req_courses ,'firefighter_courses' => $firefighter_courses ]);
        }
    }
}
