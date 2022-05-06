<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes;
use App\Course;
use App\CreditType;
use App\GroupCreditType;
use App\ForeignRelations;
use App\History;
use App\Http\Helpers\CourseHelper;
use App\Http\Helpers\Helper;
use App\CoursePrerequisites;
use App\FirefighterCourses;
use App\RejectedReasons;
use App\SemesterCourse;
use App\Firefighter;
use App\Semester;
use App\Jobs\CourseEnrollmentJob;

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
        return view('course.index')->with('title','Course')->with('courses',$courses);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();

        $query = $request->is_archive ? Course::where('is_archive',1) : Course::leftJoin('semester_courses','semester_courses.course_id','=','courses.id')->leftJoin('semesters','semesters.id','=','semester_courses.semester_id')->select('courses.*','semester_courses.course_id','semester_courses.semester_id','semesters.semester as semester','semesters.year as semester_year','semesters.start_date','semesters.end_date',DB::raw("(SELECT count(firefighter_courses.course_id) FROM firefighter_courses WHERE firefighter_courses.course_id = courses.id AND firefighter_courses.semester_id = semester_courses.semester_id AND firefighter_courses.status = 'applied') AS course_request_count"));

        // $query = Helper::filter('courses',$request->all(),$query,['is_archive','created_by',' created_at','updated_at']);

        if($request->prefix_id){
            $query = $query->having('prefix_id','like',"%{$request->prefix_id}%");
        }

        if($request->course_name){
            $query = $query->where('courses.course_name',$request->course_name);
        }

        if($request->instructor_level){
            $query = $query->where('courses.instructor_level',$request->instructor_level);
        }

        if($request->search){
            $query = $query->orWhereRaw("concat(semesters.semester, ' ',semesters.year, ' ',courses.course_name, ' ',courses.prefix_id, ' ',courses.course_hours) like '%{$request->search}%' ");
        }

        if($request->is_archive == null){
            $courses = $query->orderByRaw("CASE WHEN course_request_count > 0 THEN 1 ELSE 2 END ASC")->orderBy('course_request_count','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $courses = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }

        if($request->is_archive){
            return view('course.paginate-archive')->with('courses',$courses);
        }
        return view('course.paginate')->with('courses',$courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $credit_types = CreditType::select('id','prefix_id','description')->get();

        $query = GroupCreditType::groupBy('credit_code');
        $group_credit_types = $query->orderBy('credit_code','asc')->get();

        if($group_credit_types && $group_credit_types->count()){
            foreach ($group_credit_types as $key=>$group_credit_type){
                $g_credit_types = GroupCreditType::select('credit_types.id','credit_types.description')->leftJoin('credit_types','group_credit_types.credit_type_id','=','credit_types.id')->where('group_credit_types.credit_code',$group_credit_type->credit_code)->get();
                $arr = [];
                foreach ($g_credit_types as $credit_type){
                    $arr[] = "{$credit_type->description}";
                }
                $group_credit_types[$key]->g_credit_types  = $arr;
            }
        }

        $courses = Course::select('id','prefix_id','course_name')->get();

        return view('course.create', ['title' => 'Add Course','credit_types' => $credit_types,'courses' => $courses ,'group_credit_types' => $group_credit_types ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $rules = [
            'course_name'    =>  'required|max:40',
            'course_hours'    =>  'required|numeric|min:1',
            // 'no_of_credit_types'    =>  'required|numeric',
            // 'credit_types'    =>  'required|array',
            'instructor_level'    =>  'required',
            'maximum_students' =>   'required|numeric|min:1',
        ];

        $this->validate($request,$rules);

        // Validation No of credit_types is equal credit_types value.
        if($request->no_of_credit_types){
            $no_of_credit_types = count(array_filter($request->credit_types));
            if($no_of_credit_types != $request->no_of_credit_types){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'credit_types' => ['The Credit Type field is required.']
                ]);
            }
        }

        // Validation for unique credit types
        $credit_types = [];
        if(!empty($request->no_of_credit_types)){
            foreach ($request->credit_types as $credit_type){
                if(in_array($credit_type,$credit_types)){
                    return response()->json(['status'=> false,'msg'=>'Credit type selection must be unique']);
                }else{
                    $credit_types[] = $credit_type;
                }
            }
        }

        $ids = GroupCreditType::where('credit_code',$request->group_credit_types)->pluck('credit_type_id')->toArray();

        if(empty($request->credit_types) && empty($ids) ){
            throw \Illuminate\Validation\ValidationException::withMessages([
                'no_of_credit_types' => ['The Credit Type field is required.']
            ]);
        }

        $course = new Course();
        $course->fema_course = $request->fema_course;
        $course->course_name = $request->course_name;
        $course->nfpa_std = $request->nfpa_std;
        $course->course_hours = $request->course_hours;
        $course->no_of_credit_types = $request->no_of_credit_types;
        $course->maximum_students = (int) $request->maximum_students;
        $course->instructor_level = $request->instructor_level;
        $course->comment          = $request->comment;
        $course->created_by = Auth::user()->id;

        if(!$course->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save course. Please try again.']);
        }

        // Create Prefix ID
        $response = Course::where('id', $course->id)->update(['prefix_id' => CourseHelper::prefix_id($course->id)]);
        if (!$response) {
            $this->reverse_store_process($course->id);
            return response()->json(array('status' => false, 'msg' => 'Something went wrong while updating prefix id.'));
        }

        $credit_types =  $request->credit_types;

        if(!empty($request->credit_types) && !empty($ids) ){

            $check_same_value = array_intersect($request->credit_types,$ids);
            // if(!empty($check_same_value)){
            //     // dd("Match found");
            //     return response()->json(['status'=> false,'msg'=>'Credit type selection must be unique']);
            // }

            $credit_types = array_unique(array_merge($ids,$request->credit_types));
        }

        if(empty($request->credit_types) && !empty($ids)){
            $credit_types = $ids;
        }

        // no_of_credit_types
        $response = Course::where('id', $course->id)->update(['no_of_credit_types' => count($credit_types) ]);
        // if (!$response) {
        //     return response()->json(array('status' => false, 'msg' => 'Something went wrong while updating no_of_credit_types id.'));
        // }

        foreach ($credit_types as $credit_type){
            $foreign_relation               =   new ForeignRelations();
            $foreign_relation->foreign_id   =   $course->id;
            $foreign_relation->module       =   'courses';
            $foreign_relation->name         =   'credit_types';
            $foreign_relation->value        =   $credit_type;
            if(!$foreign_relation->save()){
                $this->reverse_store_process($course->id);
                return response()->json(['status'=>false,'msg'=>'Failed to save credit types metadata. Please try again.']);
            }
        }
        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }

    public function reverse_store_process($id){
        try{
            $response = Course::where('id',$id)->delete();
        }catch (\Exception $error){
            return false;
        }
        if($response){
            ForeignRelations::where('foreign_id',$id)->where('module','courses')->delete();
            return true;
        }
        return false;
    }

    public function course_show($id)
    {
        // dd($semester_id);
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

            $pre_reqcourses = CoursePrerequisites::select('course_prerequisites.id','course_prerequisites.course_id','course_prerequisites.preq_course_id','courses.course_name as course_name','courses.prefix_id as prefix_id')
            ->leftJoin('courses','courses.id','=','course_prerequisites.preq_course_id')
            ->where('course_prerequisites.course_id',$id)
            ->get();

            $pre_req_courses = [];
            if($pre_reqcourses && $pre_reqcourses->count()){
                foreach ($pre_reqcourses as $pre_reqcourse){
                    $pre_req_courses[$pre_reqcourse->preq_course_id] = "$pre_reqcourse->course_name ($pre_reqcourse->prefix_id)";
                }
            }

            // show credit type list
            $all_credit_types = CreditType::select('id','prefix_id','description')->get();

            // show courses list
            $all_courses = Course::select('id','prefix_id','course_name')->get();

            $last_updated = Helper::get_last_updated('courses',$id);

            $query = GroupCreditType::groupBy('credit_code');
            $group_credit_types = $query->orderBy('credit_code','asc')->get();

            if($group_credit_types && $group_credit_types->count()){
                foreach ($group_credit_types as $key=>$group_credit_type){
                    $g_credit_types = GroupCreditType::select('credit_types.id','credit_types.description')->leftJoin('credit_types','group_credit_types.credit_type_id','=','credit_types.id')->where('group_credit_types.credit_code',$group_credit_type->credit_code)->get();
                    $arr = [];
                    foreach ($g_credit_types as $credit_type){
                        $arr[] = "{$credit_type->description}";
                    }
                    $group_credit_types[$key]->g_credit_types  = $arr;
                }
            }

            return view('course.show', ['group_credit_types' => $group_credit_types, 'title' => 'View Course','course'=>$course,'credit_types'=>$credit_types,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'last_updated'=>$last_updated,'all_courses'=>$all_courses,'pre_req_courses' => $pre_req_courses, 'semester_id' => '' , 'all_credit_types' => $all_credit_types ]);
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
    public function show($semester_id,$id)
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

            $pre_reqcourses = CoursePrerequisites::select('course_prerequisites.id','course_prerequisites.course_id','course_prerequisites.preq_course_id','courses.course_name as course_name','courses.prefix_id as prefix_id')
            ->leftJoin('courses','courses.id','=','course_prerequisites.preq_course_id')
            ->where('course_prerequisites.course_id',$id)
            ->get();

            $pre_req_courses = [];
            if($pre_reqcourses && $pre_reqcourses->count()){
                foreach ($pre_reqcourses as $pre_reqcourse){
                    $pre_req_courses[$pre_reqcourse->preq_course_id] = "$pre_reqcourse->course_name ($pre_reqcourse->prefix_id)";
                }
            }

            // show courses list
            $all_courses = Course::select('id','prefix_id','course_name')->get();

            // show credit type list
            $all_credit_types = CreditType::select('id','prefix_id','description')->get();
            $last_updated = Helper::get_last_updated('courses',$id);

            $query = GroupCreditType::groupBy('credit_code');
            $group_credit_types = $query->orderBy('credit_code','asc')->get();

            if($group_credit_types && $group_credit_types->count()){
                foreach ($group_credit_types as $key=>$group_credit_type){
                    $g_credit_types = GroupCreditType::select('credit_types.id','credit_types.description')->leftJoin('credit_types','group_credit_types.credit_type_id','=','credit_types.id')->where('group_credit_types.credit_code',$group_credit_type->credit_code)->get();
                    $arr = [];
                    foreach ($g_credit_types as $credit_type){
                        $arr[] = "{$credit_type->description}";
                    }
                    $group_credit_types[$key]->g_credit_types  = $arr;
                }
            }

            return view('course.show', ['title' => 'View Course','course'=>$course,'credit_types'=>$credit_types,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'last_updated'=>$last_updated,'all_courses'=>$all_courses,'pre_req_courses' => $pre_req_courses, 'semester_id' => $semester_id, 'all_credit_types' => $all_credit_types, 'group_credit_types' => $group_credit_types]);
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
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types,true) : '';
            }

            $pre_reqcourses = CoursePrerequisites::select('course_prerequisites.id','course_prerequisites.course_id','course_prerequisites.preq_course_id','courses.course_name as course_name','courses.prefix_id as prefix_id')
            ->leftJoin('courses','courses.id','=','course_prerequisites.preq_course_id')
            ->where('course_prerequisites.course_id',$id)
            ->get();

            $pre_req_courses = [];
            if($pre_reqcourses && $pre_reqcourses->count()){
                foreach ($pre_reqcourses as $pre_reqcourse){
                    $pre_req_courses[$pre_reqcourse->preq_course_id] = "$pre_reqcourse->course_name ($pre_reqcourse->prefix_id)";
                }
            }
            // show credit type list
            $all_credit_types = CreditType::select('id','prefix_id','description')->get();

            // show courses list
            $all_courses = Course::select('id','prefix_id','course_name')->get();

            $query = GroupCreditType::groupBy('credit_code');
            $group_credit_types = $query->orderBy('credit_code','asc')->get();

            if($group_credit_types && $group_credit_types->count()){
                foreach ($group_credit_types as $key=>$group_credit_type){
                    $g_credit_types = GroupCreditType::select('credit_types.id','credit_types.description')->leftJoin('credit_types','group_credit_types.credit_type_id','=','credit_types.id')->where('group_credit_types.credit_code',$group_credit_type->credit_code)->get();
                    $arr = [];
                    foreach ($g_credit_types as $credit_type){
                        $arr[] = "{$credit_type->description}";
                    }
                    $group_credit_types[$key]->g_credit_types  = $arr;
                }
            }

            return view('course.edit', ['title' => 'Edit Course','course'=>$course,'credit_types'=>$credit_types,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'pre_req_courses'=>$pre_req_courses,'all_credit_types'=>$all_credit_types, 'all_courses' => $all_courses, 'group_credit_types' => $group_credit_types ]);
        }
        else{
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
            'course_name'    =>  'required|max:40',
            'course_hours'    =>  'required|numeric|min:1',
            // 'no_of_credit_types'    =>  'required|numeric',
            // 'credit_types'    =>  'required|array',
            'instructor_level'    =>  'required',
            'maximum_students' =>   'required|numeric|min:1',
        ];
//        if(!$request->admin_ceu && !$request->tech_ceu){
//            $rules['tech_ceu'] = 'required';
//        }

        // if( $request->no_of_credit_types ){
        //     $rules['credit_types'].='|size:'.$request->no_of_credit_types;
        // }

        // if( $request->no_of_pre_req_course ){
        //     $rules['pre_req_courses'] = 'required';
        //     $rules['pre_req_courses'].='|size:'.$request->no_of_pre_req_course;
        // }

        // $messages = [
        //     'pre_req_courses.required' => 'The Pre-requisite Courses field is required.',
        //     'pre_req_courses.size' => 'The Pre-requisite Courses field is required.',
        // ];

        $this->validate($request,$rules);

        // Validation No of credit_types is equal credit_types value.
        if($request->no_of_credit_types){
            $no_of_credit_types = count(array_filter($request->credit_types));
            if($no_of_credit_types != $request->no_of_credit_types){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'no_of_credit_types' => ['Wrong No. of Credit type Selection.']
                ]);
            }
        }

        // Validation No of courses is equal courses value.
//        if($request->no_of_pre_req_course){
//            $no_of_pre_req_course_count = count(array_filter($request->pre_req_courses));
//            if($no_of_pre_req_course_count != $request->no_of_pre_req_course){
//                throw \Illuminate\Validation\ValidationException::withMessages([
//                    'no_of_pre_req_course' => [' Wrong No. of Pre-requisite Course(s) Selection.']
//                ]);
//                // return response()->json(['status'=> false,'msg'=>'Please Select Correct No. of Pre-requisite Course(s).']);
//            }
//        }

        // Validation for unique credit types
        $credit_types = [];
        if(!empty($request->credit_types)){
            foreach ($request->credit_types as $credit_type){
                if(in_array($credit_type,$credit_types)){
                    return response()->json(['status'=> false,'msg'=>'Credit type selection must be unique']);
                }else{
                    $credit_types[] = $credit_type;
                }
            }
        }

//        if(!empty($request->no_of_pre_req_course)){
//            if(in_array($id,$request->pre_req_courses)){
//                return response()->json(['status'=> false,'msg'=>'Wrong Pre-requisite selection']);
//            }
//        }

        // Validation for unique course
//        if(!empty($request->no_of_pre_req_course)){
//            $pre_req_courses = [];
//            foreach ($request->pre_req_courses as $pre_req_course){
//                if(in_array($pre_req_course,$pre_req_courses)){
//                    return response()->json(['status'=> false,'msg'=>'Course selection must be unique']);
//                }else{
//                    $pre_req_courses[] = $pre_req_course;
//                }
//            }
//        }

        $ids = GroupCreditType::where('credit_code',$request->group_credit_types)->pluck('credit_type_id')->toArray();
        if(empty($request->credit_types) && empty($ids) ){
            throw \Illuminate\Validation\ValidationException::withMessages([
                'no_of_credit_types' => ['The Credit Type field is required.']
            ]);
        }

        $error = '';
        $additional_changes = [];
        $course = Course::find($id);
        $course->fema_course = $request->fema_course;
        $course->course_name = $request->course_name;
        $course->nfpa_std = $request->nfpa_std ? (float) $request->nfpa_std : null;
//        $course->admin_ceu = (float) $request->admin_ceu;
//        $course->tech_ceu = (float) $request->tech_ceu;
        $course->course_hours = (float) $request->course_hours;
        $course->no_of_credit_types = (int) $request->no_of_credit_types;
//        $course->no_of_pre_req_course = !empty($request->no_of_pre_req_course) ? (int) $request->no_of_pre_req_course : '';
        $course->maximum_students = (int) $request->maximum_students;
        $course->instructor_level = (int) $request->instructor_level;
        $course->comment          = $request->comment;

        $prev_object = $course->getOriginal();
        $new_object = $course->getAttributes();

        if(!$course->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save courses. Please try again.']);
        }

        if($course->id){
            CoursePrerequisites::where('course_id',$course->id)->delete();
        }

//        if(!empty($request->no_of_pre_req_course)){
//
//            foreach ($request->pre_req_courses as $pre_req_course){
//                $course_prerequisites                   =   new CoursePrerequisites();
//                $course_prerequisites->course_id        =   $course->id;
//                $course_prerequisites->preq_course_id   =   $pre_req_course;
//                if(!$course_prerequisites->save()){
//                    $this->reverse_store_process($course->id);
//                    return response()->json(['status'=>false,'msg'=>'Failed to save course prerequisites metadata. Please try again.']);
//                }
//            }
//        }

        $foreign_relations = ForeignRelations::where('foreign_id',$id)->where('module','courses')->where('name','credit_types')->delete();
        $credit_types =  $request->credit_types;

        if(!empty($request->credit_types) && !empty($ids) ){

            $check_same_value = array_intersect($request->credit_types,$ids);
            if(!empty($check_same_value)){
                // dd("Match found");
                return response()->json(['status'=> false,'msg'=>'Credit type selection must be unique']);
            }

            $credit_types = array_unique(array_merge($ids,$request->credit_types));
        }

        if(empty($request->credit_types) && !empty($ids)){
            $credit_types = $ids;
        }

        $response = Course::where('id', $id)->update(['no_of_credit_types' => count($credit_types) ]);

        foreach ($credit_types as $credit_type){
            $foreign_relation               =   new ForeignRelations();
            $foreign_relation->foreign_id   =   $id;
            $foreign_relation->module       =   'courses';
            $foreign_relation->name         =   'credit_types';
            $foreign_relation->value        =   $credit_type;
            if(!$foreign_relation->save()){
                return response()->json(['status'=>false,'msg'=>'Failed to save credit types metadata. Please try again.']);
            }
        }

        // Detect type change and update

        // $credit_type_ids = [];
        // $credit_types = [];
        // foreach ($foreign_relations as $foreign_relation){
        //     array_push($credit_type_ids,$foreign_relation->id);
        //     array_push($credit_types,$foreign_relation->value);
        // }
        // if( (sizeof($credit_types)!==sizeof($request->credit_types)) || sizeof(array_diff($credit_types,$request->credit_types))){
        //     $additional_changes[] = [
        //         'label'  =>  'credit_types',
        //         'prev'   =>  $credit_types,
        //         'new'    =>  $request->credit_types,
        //     ];
        //     foreach ($credit_types as $credit_type){
        //         $foreign_relation               =   new ForeignRelations();
        //         $foreign_relation->foreign_id   =   $course->id;
        //         $foreign_relation->module       =   'courses';
        //         $foreign_relation->name         =   'credit_types';
        //         $foreign_relation->value        =   $credit_type;
        //         if(!$foreign_relation->save()){
        //             $error.="<li>Failed to save types metadata.</li>";
        //         }
        //     }
        //     foreach ($credit_type_ids as $credit_type_id){
        //         ForeignRelations::where('id',$credit_type_id)->delete();
        //     }
        // }

        // Create History
        $key_label = array(
            'fema_course'   =>  'FEMA course',
            'course_name'   =>  'Course name',
            'nfpa_std'      =>  'NFPA STD',
            'admin_ceu'     =>  'Admin CEU',
            'tech_ceu'      =>  'Tech CEU',
            'course_hours'  =>  'Course hours',
            'no_of_credit_types'  =>  'No. of Credit types',
            'instructor_level'  =>  'Instructor level',
        );

        $response = Helper::create_history($prev_object,$new_object,$course->id,'courses',$key_label,$additional_changes);
        if(!$response){
            $error.="<li>Failed to create update course history</li>";
        }

        if($error){
            $msg = '<p>Course Updated. Some errors occurred are stated:</p>';
            return response()->json(array('status'=>false,'msg'=>"{$msg}<ul class='pl-4'>{$error}</ul>"));
        }else{
            return response()->json(array('status'=>true,'msg'=>'Updated Successfully !'));
        }
    }

    public function history($id) {
        $histories = History::where('foreign_id',$id)->where('module','courses')->orderBy('created_at','desc')->get();
        if($histories && $histories->count()){
            foreach ($histories as $key=>$history){
                $array = json_decode($history->data,true);
                foreach ($array as $key_2=>$data){
                    $label = strtolower($data['label']);
                    $prev = null;
                    $new = null;
                    if($label === 'credit_types'){
                        $prev = CreditType::select('description')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('description')->toArray() : 'N/A';
                        $new = CreditType::select('description')->whereIn('id',$data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('description')->toArray() : 'N/A';
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

    public function is_deletable(){
        return true;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function view_firefighters($semester_id,$course_id)
    {
        $total_courses = FirefighterCourses::select(DB::raw('COUNT(id) as count'))->where('course_id',$course_id)->where('semester_id',$semester_id)->first();
        return view('course.firefighter-courses.index')->with('title','View Firefighter')->with('semester_id',$semester_id)->with('course_id',$course_id)->with('total_courses',$total_courses);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function view_firefighters_paginate(Request $request,$semester_id,$course_id)
    {
        $per_page = Helper::per_page();

        $query = FirefighterCourses::where('course_id',$course_id)->where('semester_id',$semester_id)
        ->leftJoin('courses','courses.id','=','firefighter_courses.course_id')
        ->leftJoin('firefighters','firefighters.id','=','firefighter_courses.firefighter_id')
        ->leftJoin('semesters','semesters.id','=','firefighter_courses.semester_id')
        ->leftJoin('rejected_reasons','rejected_reasons.firefighter_courses_id','=','firefighter_courses.id')
        ->select('firefighter_courses.id','semesters.semester','semesters.year','courses.course_name','firefighters.id as firefighters_id','firefighters.prefix_id','firefighters.f_name','firefighters.m_name','firefighters.l_name','firefighter_courses.status','rejected_reasons.reason');

        if($request->firefighter_name){
            $query = $query->where('firefighters.f_name',$request->firefighter_name)->orWhere('firefighters.m_name',$request->firefighter_name)->orWhere('firefighters.l_name',$request->firefighter_name);
        }

        if($request->prefix_id){
            $query = $query->having('prefix_id','like',"%{$request->prefix_id}%");
        }

        if($request->type){
            $query = $query->where('firefighter_courses.status',$request->type);
        }

        $view_firefighters = $query->orderByRaw("FIELD(firefighter_courses.status, 'applied', 'enrolled','rejected')")->orderBy('firefighter_courses.created_at','DESC')->paginate($per_page)->appends(request()->query());

        return view('course.firefighter-courses.paginate',['view_firefighters' => $view_firefighters]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function approved_firefighters_courses(Request $request)
    {
        if(empty($request->firefighter))
            return response()->json(array('status'=>false,'msg'=>'Select record(s) to perform action.'));

        $firefighters =  $request->firefighter;
        $statuses     =  $request->status;

        foreach ($statuses as $firefighter_id=>$status){

            if(!isset($firefighters[$firefighter_id])){
                unset($statuses[$firefighter_id]);
                continue;
            }

            $firefighter_courses = FirefighterCourses::where('id',$firefighter_id)->limit(1)->first();

            $semester    = Semester::find($firefighter_courses->semester_id);
            $course      = Course::find($firefighter_courses->course_id);
            $firefighter = Firefighter::find($firefighter_courses->firefighter_id);

            if($status !== $firefighter_courses->status && $status !== "applied" ){
                $firefighter_courses->status = $status;
                $firefighter_courses->save();

                // Send Emails to Firefighter
                dispatch(new CourseEnrollmentJob($firefighter->email,$firefighter->f_name,$course->course_name,$semester->semester.' '.$semester->year,"Approval of Enrollment Request for Course $course->course_name"));
            }
        }

        return response()->json(array('status'=>true,'msg'=>'Updated Successfully !'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function firefighters_courses_reject(Request $request)
    {
        $rules = [
            'reason'    =>  'required|max:255',
        ];

        $this->validate($request,$rules);

        $rejectded = RejectedReasons::where('firefighter_courses_id',$request->id)->count();

        if($rejectded > 0){
            $rejected_reason = RejectedReasons::where('firefighter_courses_id',$request->id)->first();
            $rejected_reason->reason = $request->reason;
        }else{
            $rejected_reason = new RejectedReasons;
            $rejected_reason->firefighter_courses_id = $request->id;
            $rejected_reason->reason = $request->reason;
        }

        if(!$rejected_reason->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save reason. Please try again.']);
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $semester_course = SemesterCourse::where('course_id',$id)->get();
        if($semester_course->count() > 0){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        if(!$this->reverse_store_process($id)){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        History::where('foreign_id',$id)->where('module','courses')->delete();
        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        if(!$this->is_deletable())
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);

        Course::where('id',$request->archive)->update(['is_archive'=>1,'archived_at'=>date('Y-m-d H:i:s'),'archived_by'=>Auth::user()->id]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));

    }

    public function unarchive(Request $request){
        Course::where('id',$request->archive)->update(['is_archive'=>null,'archived_at'=>null,'archived_by'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }

    public function archive(){
        $courses = Course::select(DB::raw('COUNT(id) as count'))->where('is_archive',1)->first();
        return view('course.archive')->with('title','Course')->with('courses',$courses);
    }

    public function search_credit_type(Request $request){
        $per_page = Helper::per_page();
        $courses = CreditType::where('description','like',"%{$request->search}%")->orWhere('prefix_id', 'like',"%{$request->search}%")->limit($per_page)->get();
        return response()->json($courses);
    }

}
