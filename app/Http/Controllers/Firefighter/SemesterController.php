<?php

namespace App\Http\Controllers\FireFighter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes;
use App\Course;
use App\History;
use App\Semester;
use App\SemesterCourse;
use App\Http\Helpers\Helper;


class SemesterController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $semesters = Semester::select(DB::raw('COUNT(id) as count'))->whereNull('is_archive')->first();
        return view('firefighter-frontend.semester.index')->with('title','Semester')->with('semesters',$semesters);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = $request->is_archive ? Semester::where('is_archive',1) : Semester::whereNull('is_archive');
        $query = Helper::filter('semesters',$request->all(),$query,['is_archive','created_by','created_at','updated_at']);
        if($query){
            $semesters = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $semesters = Semester::orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }
        if($request->is_archive){
            return view('semester.paginate-archive')->with('semesters',$semesters);
        }
        return view('firefighter-frontend.semester.paginate')->with('semesters',$semesters);
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $semester = Semester::find($id);
        if($semester && $semester->count()){
            $last_updated = Helper::get_last_updated('semesters',$id);
            $semester_courses = SemesterCourse::where('semester_id',$id)->get();

            $semester_courses = SemesterCourse::where('semester_id', $id)
                ->leftJoin('courses', 'courses.id', '=', 'semester_courses.course_id')
                ->leftJoin('semesters', 'semesters.id', '=', 'semester_courses.semester_id')
                ->select('semesters.semester as semester_name','semesters.start_date','semesters.end_date','semester_courses.semester_id as semester_id','courses.id as course_id','courses.prefix_id','courses.course_name','courses.course_hours')
                ->get();
            
            $edit_courses = Classes::select('semester_id')->where('semester_id',$id)->limit(1)->first();
            $edit_courses = isset($edit_courses->semester_id) && $edit_courses->semester_id ? false : true;
            return view('firefighter-frontend.semester.show', ['title' => ucfirst($semester->semester).' Semester '.$semester->year,'semester'=>$semester,'semester_courses'=>$semester_courses,'last_updated'=>$last_updated,'edit_courses'=>$edit_courses ]);
        }else{
            return view('404');
        }
    }

}
