<?php

namespace App\Http\Controllers;

use App\Firefighter;
use App\ForeignRelations;
use App\Http\Helpers\Helper;
use App\InstructorPrerequisites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorPrerequisitesController extends Controller
{
    public function index(){
        return view('instructor-level.index',['title'=>'Instructor levels']);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = InstructorPrerequisites::groupBy('instructor_level');
        if($request->instructor_level){
            $query = $query->where('instructor_level',$request->instructor_level);
        }
        $instructor_levels = $query->orderBy('instructor_level','asc')->paginate($per_page)->appends(request()->query());
        if($instructor_levels && $instructor_levels->count()){
            foreach ($instructor_levels as $key=>$instructor_level){
                $courses = InstructorPrerequisites::select('courses.prefix_id','courses.course_name')->leftJoin('courses','instructor_prerequisites.course_id','=','courses.id')->where('instructor_prerequisites.instructor_level',$instructor_level->instructor_level)->get();
                $arr = [];
                foreach ($courses as $course){
                    $arr[] = "{$course->course_name} ({$course->prefix_id})";
                }
                $instructor_levels[$key]->courses = $arr;
            }
        }
        return view('instructor-level.paginate',['title'=>'Instructor levels'])->with('instructor_levels',$instructor_levels);
    }

    public function create(){
        return view('instructor-level.create',['title'=>'Add level']);
    }

    public function store(Request $request){
        $rules = [
            'instructor_level'    =>  'required|numeric|unique:instructor_prerequisites',
            'courses'             =>  'required|array',
        ];

        $this->validate($request,$rules);

        foreach ($request->courses as $course){
            $instructor_lvl = new InstructorPrerequisites();
            $instructor_lvl->instructor_level = $request->instructor_level;
            $instructor_lvl->course_id = $course;
            if(!$instructor_lvl->save()){
                $this->reverse_store_process($request->instructor_level);
                return response()->json(['status'=>false,'msg'=>'Failed to save record. Please try again.']);
            }
        }
        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }

    private function reverse_store_process($instructor_level){
        return InstructorPrerequisites::where('instructor_level',$instructor_level)->delete();
    }

    public function edit($instructor_level){
        $courses = InstructorPrerequisites::where('instructor_level',$instructor_level)->get();
        if($courses && $courses->count()){
            return view('instructor-level.edit', ['title' => 'Edit Instructor level','instructor_level'=>$instructor_level,'courses'=>$courses]);
        }
        return view('404');
    }

    public function update(Request $request,$instructor_level){

        $rules = [
            'instructor_level'    =>  'required|numeric',
            'current_instructor_level'    =>  'required|numeric',
            'courses'             =>  'required|array',
        ];

        if( (int) $request->current_instructor_level !== (int) $request->instructor_level ){
            $exist = InstructorPrerequisites::select('id')->where('instructor_level',$request->instructor_level)->limit(1)->first();
            if(isset($exist) && $exist->id){
                $rules['instructor_level'].='|unique:instructor_prerequisites,instructor_level,'.$request->instructor_level;
            }
        }

        $this->validate($request,$rules);

        $courses = InstructorPrerequisites::where('instructor_level',$instructor_level)->get();
        if($courses && $courses->count()){
            $this->reverse_store_process($instructor_level);
            foreach ($request->courses as $course){
                $instructor_lvl = new InstructorPrerequisites();
                $instructor_lvl->instructor_level = $request->instructor_level;
                $instructor_lvl->course_id = $course;
                if(!$instructor_lvl->save()){
                    $this->reverse_store_process($request->instructor_level);
                    return response()->json(['status'=>false,'msg'=>'Failed to save record. Please try again.']);
                }
            }
            return response()->json(['status'=>true,'msg'=>'Updated Successfully !','instructor_level'=>$request->instructor_level]);
        }
        return response()->json(['status'=>false,'msg'=>'Invalid Request.']);
    }

    public function show($instructor_level){

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($instructor_level){
        if($this->reverse_store_process($instructor_level)){
            return response()->json(['status'=>true,'msg'=>'Deleted Successfully !']);
        }
        return response()->json(['status'=>false,'msg'=>'Delete failed.']);
    }
}