<?php

namespace App\Http\Controllers;

use App\Classes;
use App\Course;
use App\History;
use App\Http\Helpers\Helper;
use App\Semester;
use App\SemesterCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return view('semester.index')->with('title','Semester')->with('semesters',$semesters);
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
        return view('semester.paginate')->with('semesters',$semesters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('semester.create', ['title' => 'Add Semester']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'semester'    =>  'required',
            'year'    =>  'required',
            'id'    =>  'required',
            'start_date'    =>  'required|date',
            'end_date'    =>  'required|date|after:start_date',
        ];

        if($request->year){
            if($request->start_date){
                $year = explode('-',$request->start_date)[0];
                if((int) $request->year !== (int) $year){
                    $rules['start_date'].= '|max:1';
                    $message['start_date.max'] = "Date's year should be same as year of semester.";
                }
            }
            if($request->end_date){
                $year = explode('-',$request->end_date)[0];
                if((int) $request->year !== (int) $year){
                    $rules['end_date'].=  '|max:1';
                    $message['end_date.max'] = "Date's year should be same as year of semester.";
                }
            }
        }

        $message['id.required'] = 'Semester course is required.';

        $this->validate($request,$rules,$message);

        // Validate if semester already exist.
        $semester = Semester::select(DB::raw('COUNT(id) as count'))->where('semester',$request->semester)->where('year',$request->year)->limit(1)->first();
        if(isset($semester->count) && $semester->count)
            return response()->json(['status'=>false,'msg'=>ucfirst($request->semester)." Semester {$request->year} already exist."]);

        // Validate if semester date range is unique
        $semester_exist = Helper::semester_unique_date_range($request->start_date,$request->end_date,$request->year);
        if(!$semester_exist['status']){
            return response()->json($semester_exist);
        }













        

        $semester = new Semester();
        $semester->semester = strtolower($request->semester);
        $semester->start_date = $request->start_date;
        $semester->end_date = $request->end_date;
        $semester->year = $request->year;
        $semester->comment = $request->comment;
        $semester->created_by = Auth::user()->id;

        if(!$semester->save()){
            return response()->json(['status'=>false,'msg'=>"Failed to save semester, please try again."]);
        }

        foreach ($request->id as $course_id){
            $semester_course = new SemesterCourse();
            $semester_course->semester_id = $semester->id;
            $semester_course->course_id = (int) $course_id;
            if(!$semester_course->save()){
                $this->reverse_store_process($semester->id);
                return response()->json(['status'=>false,'msg'=>"Failed to save semester courses, please try again."]);
            }
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }

    public function reverse_store_process($id){
        try{
            $response = Semester::where('id',$id)->delete();
        }catch (\Exception $error){
            return false;
        }
        if($response){
            SemesterCourse::where('semester_id',$id)->delete();
            return true;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // dd($id);
        $semester = Semester::find($id);
        if($semester && $semester->count()){
            $last_updated = Helper::get_last_updated('semesters',$id);
            $semester_courses = SemesterCourse::where('semester_id',$id)->get();
            $edit_courses = Classes::select('semester_id')->where('semester_id',$id)->limit(1)->first();
            $edit_courses = isset($edit_courses->semester_id) && $edit_courses->semester_id ? false : true;
            return view('semester.show', ['title' => ucfirst($semester->semester).' Semester '.$semester->year,'semester'=>$semester,'semester_courses'=>$semester_courses,'last_updated'=>$last_updated,'edit_courses'=>$edit_courses]);
        }else{
            return view('404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $semester = Semester::find($id);
        if($semester && $semester->count()){
            $semester_courses = SemesterCourse::where('semester_id',$id)->get();
            $edit_courses = Classes::select('semester_id')->where('semester_id',$id)->limit(1)->first();
            $edit_courses = isset($edit_courses->semester_id) && $edit_courses->semester_id ? false : true;
            return view('semester.edit', ['title' => 'Edit Semester','semester'=>$semester,'semester_courses'=>$semester_courses,'edit_courses'=>$edit_courses]);
        }else{
            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'semester'    =>  'required',
            'year'    =>  'required',
            'start_date'    =>  'required|date',
            'end_date'    =>  'required|date|after:start_date',
        ];

        if($request->year){
            if($request->start_date){
                $year = explode('-',$request->start_date)[0];
                if((int) $request->year !== (int) $year){
                    $rules['start_date'].= '|max:1';
                    $message['start_date.max'] = "Date's year should be same as year of semester.";
                }
            }
            if($request->end_date){
                $year = explode('-',$request->end_date)[0];
                if((int) $request->year !== (int) $year){
                    $rules['end_date'].=  '|max:1';
                    $message['end_date.max'] = "Date's year should be same as year of semester.";
                }
            }
        }

        // Check if classes are scheduled
        $edit_courses = Classes::select('semester_id')->where('semester_id',$id)->limit(1)->first();
        $edit_courses = isset($edit_courses->semester_id) && $edit_courses->semester_id ? false : true;

        $message = [];
        if($edit_courses){
            $rules['id'] = 'required';
            $message = ['id.required'=>'Semester course is required.'];
        }

        $this->validate($request,$rules,$message);

        // Validate if semester already exist.
        $semester = Semester::select(DB::raw('COUNT(id) as count'))->where('id','!=',$id)->where('semester',$request->semester)->where('year',$request->year)->limit(1)->first();
        if(isset($semester->count) && $semester->count)
            return response()->json(['status'=>false,'msg'=>ucfirst($request->semester)." Semester {$request->year} already exist."]);

        // Validate if semester date range is unique
        $semester_exist = Helper::semester_unique_date_range($request->start_date,$request->end_date,$request->year,$id);
        if(!$semester_exist['status']){
            return response()->json($semester_exist);
        }

        $error = '';
        $additional_changes = [];
        $semester = Semester::find($id);
        $semester->semester = $request->semester;
        $semester->year = (int) $request->year;
        $semester->start_date = $request->start_date;
        $semester->end_date = $request->end_date;
        $semester->comment = $request->comment;

        $prev_object = $semester->getOriginal();
        $new_object = $semester->getAttributes();

        if(!$semester->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save courses. Please try again.']);
        }

        // Detect type change and update
        if($edit_courses){
            $foreign_relations = SemesterCourse::where('semester_id',$id)->get();
            $semester_course_ids = [];
            $semester_courses = [];
            foreach ($foreign_relations as $foreign_relation){
                array_push($semester_course_ids,$foreign_relation->id);
                array_push($semester_courses,$foreign_relation->course_id);
            }
            if( (sizeof($semester_courses)!==sizeof($request->id)) || sizeof(array_diff($semester_courses,$request->id))){
                $additional_changes[] = [
                    'label'  =>  'semester_courses',
                    'prev'   =>  $semester_courses,
                    'new'    =>  $request->id,
                ];

                foreach ($request->id as $course){
                    $foreign_relation               =   new SemesterCourse();
                    $foreign_relation->semester_id   =   $id;
                    $foreign_relation->course_id       = (int) $course;
                    if(!$foreign_relation->save()){
                        $error.="<li>Failed to save types metadata.</li>";
                    }
                }
                foreach ($semester_course_ids as $semester_course_id){
                    SemesterCourse::where('id',$semester_course_id)->delete();
                }
            }
        }

        // Create History
        $response = Helper::create_history($prev_object,$new_object,$semester->id,'semesters',null,$additional_changes);
        if(!$response){
            $error.="<li>Failed to create update course history</li>";
        }

        if($error){
            $msg = '<p>Semester Updated. Some errors occurred are stated:</p>';
            return response()->json(array('status'=>false,'msg'=>"{$msg}<ul class='pl-4'>{$error}</ul>"));
        }else{
            return response()->json(array('status'=>true,'msg'=>'Updated Successfully !'));
        }
    }

    public function is_deletable(){
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$semester = Semester::select('semester','year')->limit(1)->first();
        if(!$this->reverse_store_process($id)){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        History::where('foreign_id',$id)->where('module','semesters')->delete();
        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }

    public function search_courses(Request $request){
        $per_page = Helper::per_page();
        $courses = Course::whereNull('is_archive')->where(function ($query) use($request,$per_page) {
            $query->where('prefix_id','like',"%{$request->search}%")
                ->orWhere('course_name','like',"%{$request->search}%")
                ->limit($per_page);
        })->get();

        return response()->json($courses);
    }

    public function history($id) {
        $histories = History::where('foreign_id',$id)->where('module','semesters')->orderBy('created_at','desc')->get();
        if($histories && $histories->count()){
            foreach ($histories as $key=>$history){
                $array = json_decode($history->data,true);
                foreach ($array as $key_2=>$data){
                    $label = strtolower($data['label']);
                    $prev = null;
                    $new = null;
                    if($label === 'semester_courses'){
                        $prev = Course::select('course_name')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('course_name')->toArray() : 'N/A';
                        $new = Course::select('course_name')->whereIn('id',$data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('course_name')->toArray() : 'N/A';
                    }
                    if(isset($prev) && isset($new)){
                        $array[$key_2]['prev'] = $prev;
                        $array[$key_2]['new'] = $new;
                    }
                    $histories[$key]->data = $array;
                }
            }
            return view('partials.update-history')->with('histories',$histories);
        }
    }

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        if(!$this->is_deletable())
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);

        Semester::where('id',$request->archive)->update(['is_archive'=>1,'archived_at'=>date('Y-m-d H:i:s'),'archived_by'=>Auth::user()->id]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));

    }

    public function unarchive(Request $request){
        Semester::where('id',$request->archive)->update(['is_archive'=>null,'archived_at'=>null,'archived_by'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }

    public function archive(){
        $semesters = Semester::select(DB::raw('COUNT(id) as count'))->where('is_archive',1)->first();
        return view('semester.archive')->with('title','Semester')->with('semesters',$semesters);
    }
}
