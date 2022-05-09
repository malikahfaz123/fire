<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Helpers\FirefighterHelper;

use App\Classes;
use App\Course;
use App\CourseClass;
use App\Facility;
use App\FacilityType;
use App\Firefighter;
use App\ForeignRelations;
use App\History;
use App\Http\Helpers\Helper;
use App\Organization;
use App\Semester;
use App\SemesterCourse;
use App\InstructorPrerequisites;
use App\FirefighterCourses;
use App\FireDepartment;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($semester_id,$course_id)
    {
        $classes = Classes::select(DB::raw('COUNT(id) as count'))->where('semester_id',$semester_id)->where('course_id',$course_id)->limit(1)->first();
        $course = Course::find($course_id);
        // get semester start_date and end_date to restrict add class button
        $semester = Semester::where('id',$semester_id)->select('start_date','end_date')->first();
        return view('class.index')->with('title','View Classes')->with('semester',$semester)->with('semester_id',$semester_id)->with('course',$course)->with('classes',$classes);
    }

    public function paginate(Request $request,$semester_id,$course_id){
        $per_page = Helper::per_page();
        $query = $request->is_archive ? Classes::where('is_archive',1) : Classes::whereNull('is_archive')->where('course_id',$course_id)->where('semester_id',$semester_id);
        $query = Helper::filter('classes',$request->all(),$query,['semester_id','organization_id','instructor_id','facility_id','no_of_facility_types','created_by','created_by','created_at','updated_at']);
        if($query){
            $classes = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $classes = Classes::orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }
        if($request->is_archive){
            return view('class.paginate-archive')->with('classes',$classes);
        }
        return view('class.paginate')->with('classes',$classes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($semester_id,$course_id)
    {
        $firefighters = FirefighterCourses::where('firefighter_courses.course_id',$course_id)->where('firefighter_courses.semester_id',$semester_id)->where('status','enrolled')
        ->leftJoin('firefighters','firefighters.id','=','firefighter_courses.firefighter_id')
        ->leftJoin('semesters','semesters.id','=','firefighter_courses.semester_id')
        ->select('firefighters.id as firefighter_id','firefighters.prefix_id','firefighters.f_name','firefighters.l_name','semesters.id as semesters_id','semesters.semester','semesters.year')
        ->get();

        // show instructor list
        $level = Course::select('instructor_level')->where('id',$course_id)->limit(1)->first();
        $per_page = Helper::per_page();
        $query = Firefighter::whereNull('is_archive');
        $foreign_relations = ForeignRelations::where('module','firefighters')->where('name','type')->where('value','fire instructor')->get();
        $ids = [];
        foreach ($foreign_relations as $foreign_relation){
            if(!in_array($foreign_relation->foreign_id,$ids)){
                array_push($ids,$foreign_relation->foreign_id);
            }
        }
        $query = $query->whereIn('id',$ids);

        $query = $query->where('instructor_level',$level->instructor_level);
        $instructors = $query->orderBy('created_at','desc')->limit($per_page)->select('id','prefix_id','f_name','l_name')->get();

        // show facility list
        $facilities = Facility::select('id','name','prefix_id')->get();

        $semester = Semester::find($semester_id);
        $organizations = Organization::select('id','name','prefix_id')->get();
        $fire_departments = FireDepartment::select('id','name','prefix_id')->get();

        $facility_types = FacilityType::select('id','description')->get();

        // dd($facility_types);

        $course = Course::find($course_id);
        if(!$course || !$course->id){
            return view('404');
        }
        return view('class.create')->with('firefighters',$firefighters)->with('title','Add Class')->with('course',$course)->with('semester_id',$semester_id)->with('semester',$semester)->with('instructors',$instructors)->with('facilities',$facilities)->with('organizations',$organizations)->with('fire_departments',$fire_departments)->with('facility_types',$facility_types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$semester_id,$id)
    {
        $rules = [
            'organization_type' =>   'required',
            // 'organization' =>   'required|numeric',
            'instructor' =>   'required|numeric',
            'facility' =>   'required|numeric',
            'no_of_facility_types' =>   'required|numeric',
            'facility_types' =>   'required',
            'start_date' =>   'required|date|after_or_equal:today',
            // 'end_date' =>   'required|date|after_or_equal:start_date|same:start_date',
            // 'maximum_students' =>   'required|numeric|min:1',
            'firefighter' =>   'required',
            'admin_ceu' =>   'required',
            'tech_ceu' =>   'required',
            'semester' =>   'required|numeric',
        ];

        if($request->organization_type == "EO"){
            $rules['organization'] = 'required|numeric';
        }

        if($request->organization_type == "FD"){
            $rules['firedepartment'] = 'required|numeric';
        }

        $this->validate($request,$rules);

        $endTime   = '0'.($request->start_hour + $request->duration);
        $startTime = $request->start_hour.':'.$request->start_minute.':'.'00';
        $endTime   = $endTime.':'.$request->start_minute.':'.'00';

        $Availability = Classes::where(function ($query) use ($startTime, $endTime) {
            $query
            ->where(function ($query) use ($startTime, $endTime) {
                $query
                    ->where('start_time', '<=', $startTime)
                    ->where('end_time', '>', $startTime);
            })
            ->orWhere(function ($query) use ($startTime, $endTime) {
                $query
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>=', $endTime);
            });
        })->where('instructor_id',$request->instructor)->where('start_date',$request->start_date)->count();

        if($Availability > 0){
            return response()->json(['status'=> false,'msg'=>'Class time over lap of this instructor.']);
        }

        // Check if course offered in the selected semester
        $semester_course = SemesterCourse::select('id')->where('semester_id',$request->semester)->where('course_id',$id)->limit(1)->first();
        if(!$semester_course || !isset($semester_course->id) || !$semester_course->id){
            return response()->json(['status'=> false,'msg'=>'This course is not offered in the selected semester.']);
        }

        // $class = Classes::select('id')->where('course_id',$id)->where('start_date',$request->start_date)->limit(1)->first();
        // if($class && isset($class->id)){
        //     return response()->json(['status'=> false,'msg'=>'A class of this course is already scheduled on the specified start date.']);
        // }

        // Validation for size should not exceed threshold value.
        // if( sizeof($request->firefighter) > $request->maximum_students ){
        //     return response()->json(['status'=> false,'msg'=>'Firefighters should not exceed maximum students capacity.']);
        // }

        // Validation for unique facility types
        $facility_types = [];
        foreach ($request->facility_types as $facility_type){
            if(in_array($facility_type,$facility_types)){
                return response()->json(['status'=> false,'msg'=>'Facility type selection must be unique.']);
            }else{
                $facility_types[] = $facility_type;
            }
        }

        // Validation for teacher(instructor) cannot be enrolled as a student(firefighter)
        if(in_array($request->instructor,$request->firefighter)){
            return response()->json(['status'=> false,'msg'=>'Instructor cannot be enrolled as a student.']);
        }

        // Validation for firefighter cannot enroll more than a limit in a semester
        // $enrollment_limit = Helper::enrollment_limit();
        // foreach ($request->firefighter as $firefighter_id){
        //     $enrollment = Classes::select(DB::raw('COUNT(DISTINCT(classes.course_id)) as count'))->join('course_classes','classes.id','=','course_classes.class_id')->where('classes.semester_id',$request->semester)->where('course_classes.firefighter_id',$firefighter_id)->first();
        //     if($enrollment->count>=$enrollment_limit){
        //         $semester = Semester::find($request->semester);
        //         $firefighter = Firefighter::select('prefix_id')->where('id',$firefighter_id)->first();
        //         return response()->json(['status'=> false,'msg'=>"Firefighter of ID {$firefighter->prefix_id} has reached enrollment limit and cannot enroll any further in any course for semester {$semester->semester} ({$semester->year})."]);
        //     }
        // }

        // Check instructor eligibility to instruct this course
        $eligibility = Helper::check_instructor_eligibility($id,$request->instructor);
        if(!$eligibility['status']){
            return response()->json($eligibility);
        }

        // Validation for start_date year should be same as semester year
        $start_date = substr($request->start_date,0,4);
        $semester = Semester::select('start_date','end_date','year')->where('id',$request->semester)->limit(1)->first();
        if($semester->year != $start_date ){
            return response()->json(['status'=> false,'msg'=> 'Semester Year should be same as Class start Date']);
        }

        // Validation to check that class start and end date lie within semester date range
        if( $request->start_date < $semester->start_date || $request->start_date > $semester->end_date){
            return response()->json(['status'=> false,'msg'=> 'Class dates should lie on selected semester date range.']);
        }

        $class = new Classes();
        $class->semester_id = $request->semester;
        $class->course_id = $id;
        $class->organization_type = $request->organization_type;
        // $class->organization_id = $request->organization;
        if($request->organization_type == "EO"){
            $class->organization_id = $request->organization;
        }

        if($request->organization_type == "FD"){
            $class->fire_department_id = $request->firedepartment;
        }

        $class->instructor_id = $request->instructor;
        $class->facility_id = $request->facility;
        $class->no_of_facility_types = $request->no_of_facility_types;
        $class->start_date = $request->start_date;
        // $class->end_date = $request->end_date;
        $class->start_time = $request->start_hour.':'.$request->start_minute.':'.'00';
        $class->end_time = $endTime;
        $class->admin_ceu = $request->admin_ceu ? $request->admin_ceu : null;
        $class->tech_ceu  =  $request->tech_ceu ? $request->tech_ceu : null;
        // $class->maximum_students = $request->maximum_students;
        $class->created_by = Auth::user()->id;

        if(!$class->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save class. Please try again.']);
        }

        foreach ($request->facility_types as $facility_type){
            $foreign_relation               =   new ForeignRelations();
            $foreign_relation->foreign_id   =   $class->id;
            $foreign_relation->module       =   'classes';
            $foreign_relation->name         =   'facility_type';
            $foreign_relation->value        =   $facility_type;
            if(!$foreign_relation->save()){
                $this->reverse_store_process($class->id);
                return response()->json(['status'=>false,'msg'=>'Failed to save types metadata. Please try again.']);
            }
        }

        foreach ($request->firefighter as $firefighter){
            $course_class = new CourseClass();
            $course_class->semester_id = $semester_id;
            $course_class->course_id = $id;
            $course_class->class_id = $class->id;
            $course_class->firefighter_id = $firefighter;
            if(!$course_class->save()){
                $this->reverse_store_process($class->id);
                return response()->json(['status'=>false,'msg'=>'Failed to save types metadata. Please try again.']);
            }
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }

    public function reverse_store_process($id){
        try{
            $response = Classes::where('id',$id)->delete();
        }catch (\Exception $error){
            return false;
        }
        if($response){
            CourseClass::where('class_id',$id)->delete();
            ForeignRelations::where('foreign_id',$id)->where('module','classes')->delete();
            History::where('foreign_id',$id)->where('module','classes')->delete();
            return true;
        }
        return false;
    }

    public function class_alterable($class){
        if(!is_object($class))
            $class = Classes::find($class);

        $current_date = date('Y-m-d');
        if($class->start_date > $current_date){
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
    public function show($semester_id,$course_id,$id)
    {
        $course = Course::find($course_id);
        $class = Classes::find($id);

        if(!$course || !$course->id || !$class || !$class->id){
            return view('404');
        }

        $temps = ForeignRelations::where('foreign_id',$class->id)->where('module','classes')->where('name','facility_type')->get();
        foreach ($temps as $temp){
            $temp = FacilityType::select('id','description')->where('id',$temp->value)->limit(1)->first();
            $facility_types[$temp->id] = $temp->description;
        }

        $time = explode(':',$class->start_time);
        $class->start_hour = $time[0];
        $class->start_minute = $time[1];

        $firefighters = CourseClass::where('class_id',$class->id)->where('semester_id',$class->semester_id)
        ->leftJoin('firefighters','firefighters.id','=','course_classes.firefighter_id')
        ->select('course_classes.course_id','course_classes.class_id','course_classes.firefighter_id','course_classes.attendance','firefighters.prefix_id','firefighters.f_name')
        ->get();

        // show instructor list
        $level = Course::select('instructor_level')->where('id',$course_id)->limit(1)->first();
        $per_page = Helper::per_page();
        $query = Firefighter::whereNull('is_archive');
        $foreign_relations = ForeignRelations::where('module','firefighters')->where('name','type')->where('value','fire instructor')->get();
        $ids = [];
        foreach ($foreign_relations as $foreign_relation){
            if(!in_array($foreign_relation->foreign_id,$ids)){
                array_push($ids,$foreign_relation->foreign_id);
            }
        }
        $query = $query->whereIn('id',$ids);

        $query = $query->where('instructor_level',$level->instructor_level);
        $instructors_lists = $query->orderBy('created_at','desc')->limit($per_page)->select('id','prefix_id','f_name','l_name')->get();

        $class_alterable = $this->class_alterable($class);
        $last_updated = Helper::get_last_updated('classes',$id);

        // show facility list
        $facilities_lists = Facility::select('id','name','prefix_id')->get();

        // show facility type list
        $all_facility_types = FacilityType::select('id','description')->get();

        // show organizations list
        $all_organizations = Organization::select('id','prefix_id','name')->get();

        // show fire_departments list
        $all_fire_departments = FireDepartment::select('id','name','prefix_id')->get();

        $data = [
            'title'             =>  'View Class',
            'course'            =>  $course,
            'class'             =>  $class,
            'organization'      =>  Organization::select('id','prefix_id','name')->where('id',$class->organization_id)->limit(1)->first(),
            'all_organizations' =>  $all_organizations,
            'fire_department'   =>  FireDepartment::select('id','prefix_id','name')->where('id',$class->fire_department_id)->limit(1)->first(),
            'all_fire_departments'=>  $all_fire_departments,
            'instructor'        =>  Firefighter::select('id','prefix_id','f_name','m_name','l_name')->where('id',$class->instructor_id)->limit(1)->first(),
            'instructors_lists' =>  $instructors_lists,
            'facility'          =>  Facility::select('id','prefix_id','name')->where('id',$class->facility_id)->limit(1)->first(),
            'facilities_lists'  =>  $facilities_lists,
            'facility_types'    =>  $facility_types,
            'all_facility_types'=>  $all_facility_types,
            'semester'          =>  Semester::where('id',$class->semester_id)->limit(1)->first(),
            'firefighters'      =>  $firefighters,
            // 'selected_firefighters'    =>  $selected_firefighters,
            'class_alterable'      =>  $class_alterable,
            'last_updated'      =>  $last_updated,
            'semester_id'      =>  $semester_id
        ];

        return view('class.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($semester_id,$course_id,$id)
    {
        $course = Course::find($course_id);
        $class = Classes::find($id);
        if(!$course || !$course->id || !$class || !$class->id){
            return view('404');
        }

        $title = 'Edit Class';
        $class_alterable = $this->class_alterable($class);
        if(!$this->class_alterable($class)){
            return view('class.edit')->with('title',$title)->with('course',$course)->with('class',$class)->with('class_alterable',$class_alterable);
        }

        $temps = ForeignRelations::where('foreign_id',$class->id)->where('module','classes')->where('name','facility_type')->get();
        foreach ($temps as $temp){
            $temp = FacilityType::select('id','description')->where('id',$temp->value)->limit(1)->first();
            $facility_types[$temp->id] = $temp->description;
        }

        $firefighters = CourseClass::where('class_id',$class->id)
        ->leftJoin('firefighters','firefighters.id','=','course_classes.firefighter_id')
        ->select('course_classes.course_id','course_classes.class_id','course_classes.firefighter_id','course_classes.attendance','firefighters.prefix_id','firefighters.f_name')->get();

        $time = explode(':',$class->start_time);
        $class->start_hour = $time[0];
        $class->start_minute = $time[1];

        // show instructor list
        $level = Course::select('instructor_level')->where('id',$course_id)->limit(1)->first();
        $per_page = Helper::per_page();
        $query = Firefighter::whereNull('is_archive');
        $foreign_relations = ForeignRelations::where('module','firefighters')->where('name','type')->where('value','fire instructor')->get();
        $ids = [];
        foreach ($foreign_relations as $foreign_relation){
            if(!in_array($foreign_relation->foreign_id,$ids)){
                array_push($ids,$foreign_relation->foreign_id);
            }
        }
        $query = $query->whereIn('id',$ids);

        $query = $query->where('instructor_level',$level->instructor_level);
        $instructors_lists = $query->orderBy('created_at','desc')->limit($per_page)->select('id','prefix_id','f_name','l_name')->get();

        // show facility list
        $facilities_lists = Facility::select('id','name','prefix_id')->get();

        // show facility type list
        $all_facility_types = FacilityType::select('id','description')->get();

        // show organizations list
        $all_organizations = Organization::select('id','prefix_id','name')->get();

        // show fire_departments list
        $all_fire_departments = FireDepartment::select('id','name','prefix_id')->get();

        $data = [
            'title'             =>  $title,
            'course'            =>  $course,
            'class'             =>  $class,
            'organization'      =>  Organization::select('id','prefix_id','name')->where('id',$class->organization_id)->limit(1)->first(),
            'all_organizations'=>   $all_organizations,
            'fire_department'   =>  FireDepartment::select('id','prefix_id','name')->where('id',$class->fire_department_id)->limit(1)->first(),
            'all_fire_departments' => $all_fire_departments,
            'instructor'        =>  Firefighter::select('id','prefix_id','f_name','m_name','l_name')->where('id',$class->instructor_id)->limit(1)->first(),
            'instructors_lists' =>  $instructors_lists,
            'facility'          =>  Facility::select('id','prefix_id','name')->where('id',$class->facility_id)->limit(1)->first(),
            'facilities_lists'  =>  $facilities_lists,
            'facility_types'    =>  $facility_types,
            'all_facility_types'=>  $all_facility_types,
            'semester'          =>  Semester::where('id',$class->semester_id)->limit(1)->first(),
            // 'course_classes'    =>  CourseClass::where('class_id',$class->id)->get(),
            'firefighters'    =>  $firefighters,
            'class_alterable'   =>  $class_alterable,
        ];

        return view('class.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $course_id,$class_id)
    {
        if(!$this->class_alterable($class_id)){
            return response()->json(['status'=> false,'msg'=>'This class cannot be altered now.']);
        }

        $rules = [
            'organization_type' =>   'required',
            // 'organization' =>   'required|numeric',
            'instructor' =>   'required|numeric',
            'facility' =>   'required|numeric',
            'no_of_facility_types' =>   'required|numeric',
            'facility_types' =>   'required',
            'start_date' =>   'required|date|after_or_equal:today',
            // 'end_date' =>   'required|date|after_or_equal:start_date',
            // 'maximum_students' =>   'required|numeric|min:1',
            'firefighter' =>   'required',
            'semester' =>   'required|numeric',
            'admin_ceu' =>   'required',
            'tech_ceu' =>   'required',
        ];

        if($request->organization_type == "EO"){
            $rules['organization'] = 'required|numeric';
        }

        if($request->organization_type == "FD"){
            $rules['firedepartment'] = 'required|numeric';
        }

        $this->validate($request,$rules);

        // Check if course offered in the selected semester
        $semester_course = SemesterCourse::select('id')->where('semester_id',$request->semester)->where('course_id',$course_id)->limit(1)->first();
        if(!$semester_course || !isset($semester_course->id) || !$semester_course->id){
            return response()->json(['status'=> false,'msg'=>'This course is not offered in the selected semester.']);
        }

        // Validation for size should not exceed threshold value.
        // if( sizeof($request->firefighter) > $request->maximum_students ){
        //     return response()->json(['status'=> false,'msg'=>'Firefighters should not exceed maximum students capacity.']);
        // }

        $endTime   = '0'.($request->start_hour + $request->duration);
        $startTime = $request->start_hour.':'.$request->start_minute.':'.'00';
        $endTime   = $endTime.':'.$request->start_minute.':'.'00';

        // $Availability = Classes::where(function ($query) use ($startTime, $endTime) {
        //     $query
        //     ->where(function ($query) use ($startTime, $endTime) {
        //         $query
        //             ->where('start_time', '<=', $startTime)
        //             ->where('end_time', '>', $startTime);
        //     })
        //     ->orWhere(function ($query) use ($startTime, $endTime) {
        //         $query
        //             ->where('start_time', '<', $endTime)
        //             ->where('end_time', '>=', $endTime);
        //     });
        // })->where('instructor_id',$request->instructor)->where('start_date',$request->start_date)->count();

        // if($Availability > 0){
        //     return response()->json(['status'=> false,'msg'=>'Class time over lap of this instructor.']);
        // }

        // Validation for unique facility types
        $facility_types = [];
        foreach ($request->facility_types as $facility_type){
            if(in_array($facility_type,$facility_types)){
                return response()->json(['status'=> false,'msg'=>'Facility type selection must be unique.']);
            }else{
                $facility_types[] = $facility_type;
            }
        }

        // Validation for teacher(instructor) cannot be enrolled as a student(firefighter)
        if(in_array($request->instructor,$request->firefighter)){
            return response()->json(['status'=> false,'msg'=>'Instructor cannot be enrolled as a student.']);
        }

        // Validation for firefighter cannot enroll more than a limit in a semester
        $enrollment_limit = Helper::enrollment_limit();
        foreach ($request->firefighter as $firefighter_id){
            $enrollment = Classes::select(DB::raw('COUNT(DISTINCT(classes.course_id)) as count'))->join('course_classes','classes.id','=','course_classes.class_id')->where('classes.semester_id',$request->semester)->where('course_classes.firefighter_id',$firefighter_id)->first();
            if($enrollment->count>=$enrollment_limit){
                $semester = Semester::find($request->semester);
                $firefighter = Firefighter::select('prefix_id')->where('id',$firefighter_id)->first();
                return response()->json(['status'=> false,'msg'=>"Firefighter of ID {$firefighter->prefix_id} has reached enrollment limit and cannot enroll any further in any course for semester {$semester->semester} ({$semester->year})."]);
            }
        }

        // Check instructor eligibility to instruct this course
        $eligibility = Helper::check_instructor_eligibility($course_id,$request->instructor);
        if(!$eligibility['status']){
            return response()->json($eligibility);
        }

        // Validation for start_date year should be same as semester year
        $start_date = substr($request->start_date,0,4);
        $semester = Semester::select('start_date','end_date','year')->where('id',$request->semester)->limit(1)->first();
        if($semester->year != $start_date ){
            return response()->json(['status'=> false,'msg'=> 'Semester Year should be same as Class start Date']);
        }

        // Validation to check that class start and end date lie within semester date range
        if( $request->start_date < $semester->start_date || $request->start_date > $semester->end_date){
            return response()->json(['status'=> false,'msg'=> 'Class dates should lie on selected semester date range.']);
        }

        $error = '';
        $additional_changes = [];
        $class = Classes::find($class_id);
        $class->semester_id = (int) $request->semester;
        $class->organization_type = $request->organization_type;
        // $class->organization_id = (int) $request->organization;
        if($request->organization_type == "EO"){
            $class->organization_id     =  $request->organization;
            $class->fire_department_id  =  null;
        }

        if($request->organization_type == "FD"){
            $class->fire_department_id =  $request->firedepartment;
            $class->organization_id    =  null;
        }
        $class->instructor_id = (int) $request->instructor;
        $class->facility_id = (int) $request->facility;
        $class->no_of_facility_types = (int) $request->no_of_facility_types;
        $class->start_date = $request->start_date;
        // $class->end_date = $request->end_date;
        $class->start_time = $request->start_hour.':'.$request->start_minute.':'.'00';
        $class->end_time = $endTime;
        $class->admin_ceu = $request->admin_ceu ? $request->admin_ceu : null;
        $class->tech_ceu  =  $request->tech_ceu ? $request->tech_ceu : null;
        // $class->maximum_students = (int) $request->maximum_students;

        $prev_object = $class->getOriginal();
        $new_object = $class->getAttributes();

        if(!$class->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save class. Please try again.']);
        }

        // Detect type change and update
        $foreign_relations = ForeignRelations::where('foreign_id',$class_id)->where('module','classes')->where('name','facility_type')->get();
        $facility_type_ids = [];
        $facility_types = [];
        foreach ($foreign_relations as $foreign_relation){
            array_push($facility_type_ids,$foreign_relation->id);
            array_push($facility_types,$foreign_relation->value);
        }
        if( (sizeof($facility_types)!==sizeof($request->facility_types)) || sizeof(array_diff($facility_types,$request->facility_types))){
            $additional_changes[] = [
                'label'  =>  'facility_type',
                'prev'   =>  $facility_types,
                'new'    =>  $request->facility_types,
            ];
            foreach ($request->facility_types as $facility_type){
                $foreign_relation               =   new ForeignRelations();
                $foreign_relation->foreign_id   =   $class->id;
                $foreign_relation->module       =   'classes';
                $foreign_relation->name         =   'facility_type';
                $foreign_relation->value        =   $facility_type;
                if(!$foreign_relation->save()){
                    $error.="<li>Failed to save facility types metadata.</li>";
                }
            }
            foreach ($facility_type_ids as $facility_type_id){
                ForeignRelations::where('id',$facility_type_id)->delete();
            }
        }

        // Detect type change and update
        $course_classes = CourseClass::where('class_id',$class->id)->get();
        $course_class_ids = [];
        $firefighter_ids = [];
        foreach ($course_classes as $course_class){
            array_push($course_class_ids,$course_class->id);
            array_push($firefighter_ids,$course_class->firefighter_id);
        }
        if( (sizeof($firefighter_ids)!==sizeof($request->firefighter)) || sizeof(array_diff($firefighter_ids,$request->firefighter))){
            $additional_changes[] = 'firefighter';
            foreach ($request->firefighter as $firefighter){
                $course_class = new CourseClass();
                $course_class->course_id = $course_id;
                $course_class->class_id = $class->id;
                $course_class->firefighter_id = $firefighter;
                if(!$course_class->save()){
                    $this->reverse_store_process($class->id);
                    return response()->json(['status'=>false,'msg'=>'Failed to save types metadata. Please try again.']);
                }
            }
            foreach ($course_class_ids as $course_class_id){
                CourseClass::where('id',$course_class_id)->delete();
            }
        }

        // Create Class History
        $key_label = array(
            'semester_id'           =>  'semester',
            'organization_type'     =>  'organization type',
            'organization_id'       =>  'organization',
            'instructor_id'         =>  'instructor',
            'facility_id'           =>  'facility',
            'no_of_facility_types'  =>  'no. of facility types',
            'start_date'            =>  'start date',
            'end_date'              =>  'end date',
            'start_time'            =>  'start time',
            // 'maximum_students'      =>  'maximum students',
        );

        $response = Helper::create_history($prev_object,$new_object,$class->id,'classes',$key_label,$additional_changes);
        if(!$response){
            $error.="<li>Failed to create update course history</li>";
        }

        if($error){
            $msg = '<p>Class Updated. Some errors occurred are stated:</p>';
            return response()->json(array('status'=>false,'msg'=>"{$msg}<ul class='pl-4'>{$error}</ul>"));
        }else{
            return response()->json(array('status'=>true,'msg'=>'Updated Successfully !'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->class_alterable($id)){
            return response()->json(['status'=>false,'msg'=>'Upcoming or today\'s classes cannot be deleted.']);
        }

        if(!$this->reverse_store_process($id)){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }
        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));

    }

    public function search_organization(Request $request){
        $per_page = Helper::per_page();
        $organization = Organization::where(function ($query) use($request,$per_page) {
            $query->where('prefix_id','like',"%{$request->search}%")
                ->orWhere('name','like',"%{$request->search}%")
                ->limit($per_page);
        })->get();

        return response()->json($organization);
    }

    public function search_firedepartment(Request $request){
        $per_page = Helper::per_page();
        $firedepartment = FireDepartment::where(function ($query) use($request,$per_page) {
            $query->where('prefix_id','like',"%{$request->search}%")
                ->orWhere('name','like',"%{$request->search}%")
                ->limit($per_page);
        })->get();

        return response()->json($firedepartment);
    }

    /*
     * This search only returns instructors with applying search filters (e.g name and course based on Instr. lvl)
     * */
    public function search_instructor(Request $request)
    {
        $level = Course::select('instructor_level')->where('id',$request->course_id)->limit(1)->first();
        $per_page = Helper::per_page();
        $query = Firefighter::whereNull('is_archive');
        $foreign_relations = ForeignRelations::where('module','firefighters')->where('name','type')->where('value','fire instructor')->get();
        $ids = [];
        foreach ($foreign_relations as $foreign_relation){
            if(!in_array($foreign_relation->foreign_id,$ids)){
                array_push($ids,$foreign_relation->foreign_id);
            }
        }
        $query = $query->whereIn('id',$ids);
        $query = $query->where(function ($query) use($request) {
            $query->where('prefix_id','like',"%$request->search%")
                ->orWhere('f_name','like',"%$request->search%")
                ->orWhere('m_name','like',"%$request->search%")
                ->orWhere('l_name','like',"%$request->search%")
                ->orWhereRaw("REPLACE(Concat(f_name,' ',m_name,' ',l_name),'  ',' ') LIKE '%{$request->search}%'");
        });

        $query = $query->where('instructor_level',$level->instructor_level);
        $instructors = $query->orderBy('created_at','desc')->limit($per_page)->get();
        if($instructors && $instructors->count()){
            foreach ($instructors as $key=>$instructor){
                $instructors[$key]->name = $instructor->m_name ? "$instructor->f_name $instructor->m_name $instructor->l_name" : "$instructor->f_name $instructor->l_name";
            }
            return response()->json($instructors);
        }
        return response()->json([]);
    }

    public function search_facility(Request $request){
        $per_page = Helper::per_page();
        $facilities = Facility::whereNull('is_archive')->where(function ($query) use($request) {
            $query->where('id','like',"%$request->search%")
                ->orWhere('prefix_id','like',"%$request->search%")
                ->orWhere('name','like',"%$request->search%");
        })->limit($per_page)->get();
        if($facilities && $facilities->count()){
            return response()->json($facilities);
        }
        return response()->json([]);
    }

    public function search_facility_type(Request $request){
        $per_page = Helper::per_page();
        $courses = FacilityType::where('description','like',"%{$request->search}%")->limit($per_page)->get();
        return response()->json($courses);
    }

    public function search_firefighter(Request $request){
        $per_page = Helper::per_page();
        $firefighters = Firefighter::whereNull('is_archive')->where(function ($query) use($request) {
            $query->where('id','like',"%$request->search%")
                ->orWhere('prefix_id','like',"%$request->search%");
        })->limit($per_page)->get();
        if($firefighters && $firefighters->count()){
            foreach ($firefighters as $key=>$firefighter){
                $firefighters[$key]->name = $firefighter->m_name ? "$firefighter->f_name $firefighter->m_name $firefighter->l_name" : "$firefighter->f_name $firefighter->l_name";
            }
            return response()->json($firefighters);
        }
        return response()->json([]);
    }

    public function search_semester(Request $request){
        $semesters = Semester::select('id','semester','year')->where('year',$request->year)->limit(4)->get();
        if($semesters && $semesters->count()){
            return response()->json($semesters);
        }
        return response()->json([]);
    }

    public function history($id){
        $histories = History::where('foreign_id',$id)->where('module','classes')->orderBy('created_at','desc')->get();
        if($histories && $histories->count()){
            foreach ($histories as $key=>$history){
                $array = json_decode($history->data,true);
                foreach ($array as $key_2=>$data){
                    $label = strtolower($data['label']);
                    $prev = null;
                    $new = null;
                    if($label === 'organization'){
                        $prev = Organization::select('name')->where('id',$data['prev'])->first();
                        $prev = isset($prev->name) && $prev->name ? $prev->name : 'N/A';
                        $new = Organization::select('name')->where('id',$data['new'])->first();
                        $new = isset($new->name) && $new->name ? $new->name : 'N/A';
                    }elseif($label === 'instructor'){
                        $prev = Firefighter::select('f_name','m_name','l_name')->where('id',$data['prev'])->first();
                        $prev = isset($prev->f_name) && $prev->f_name ? FirefighterHelper::get_full_name($prev) : 'N/A';
                        $new = Firefighter::select('f_name','m_name','l_name')->where('id',$data['new'])->first();
                        $new = isset($new->f_name) && $new->f_name ? FirefighterHelper::get_full_name($new) : 'N/A';
                    }elseif($label === 'facility'){
                        $prev = Facility::select('name')->where('id',$data['prev'])->first();
                        $prev = isset($prev->name) && $prev->name ? $prev->name : 'N/A';
                        $new = Facility::select('name')->where('id',$data['new'])->first();
                        $new = isset($new->name) && $new->name ? $new->name : 'N/A';
                    }elseif($label === 'facility_type'){
                        $prev = FacilityType::select('description')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('description')->toArray() : 'N/A';
                        $new = FacilityType::select('description')->whereIn('id',$data['new'])->get()->toArray();
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

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        if($this->class_alterable($request->archive))
            return response()->json(['status'=>false,'msg'=>'Upcoming or today\'s classes cannot be archived.']);

        Classes::where('id',$request->archive)->update(['is_archive'=>1,'archived_at'=>date('Y-m-d H:i:s'),'archived_by'=>Auth::user()->id]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));

    }

    public function archive($course_id){
        $classes = Classes::select(DB::raw('COUNT(id) as count'))->where('course_id',$course_id)->where('is_archive',1)->first();
        $course = Course::find($course_id);
        return view('class.archive')->with('title','Archived Classes')->with('course',$course)->with('classes',$classes);
    }

    public function unarchive(Request $request){
        Classes::where('id',$request->archive)->update(['is_archive'=>null,'archived_at'=>null,'archived_by'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }

    public function attendance($semester_id,$course_id,$class_id){

        $class = Classes::find($class_id);
        if(date('Y-m-d') >= $class->start_date) {
            $firefighters = CourseClass::select(DB::raw('COUNT(id) as count'))->where('course_id',$course_id)->where('class_id',$class_id)->limit(1)->first();
            $course = Course::find($course_id);
            return view('class.attendance')->with('firefighters',$firefighters)->with('title', 'Add Attendance')->with('course', $course)->with('class', $class)->with('semester_id', $semester_id);
        }
        else{
            return view('access-denied');
        }
    }

    public function paginate_attendance(Request $request,$semester_id,$course_id,$class_id){
        $per_page = Helper::per_page();
        $query = CourseClass::select('course_classes.firefighter_id as id','course_classes.attendance','firefighters.prefix_id','firefighters.f_name as f_name','firefighters.m_name as m_name','firefighters.l_name as l_name')->leftJoin('firefighters','course_classes.firefighter_id','=','firefighters.id')->where('course_classes.semester_id',$semester_id)->where('course_classes.course_id',$course_id)->where('course_classes.class_id',$class_id);
        if($request->prefix_id){
            $query = $query->where('firefighters.prefix_id',$request->prefix_id);
        }
        if($request->firefighter_name){
            $query = $query->whereRaw("REPLACE(Concat(firefighters.f_name,' ',firefighters.m_name,' ',firefighters.l_name),'  ',' ') LIKE '%{$request->firefighter_name}%'");
        }

        $firefighters = $query->orderBy('course_classes.created_at','DESC')->paginate($per_page)->appends(request()->query());
        return view('class.paginate-attendance')->with('firefighters',$firefighters)->with('course_id',$course_id)->with('class_id',$class_id);
    }

    public function history_attendance($course_id,$class_id){
        $course_classes = CourseClass::where('course_id',$course_id)->where('class_id',$class_id)->get();
        $course_class_ids = [];
        foreach ($course_classes as $course_class){
            $course_class_ids[] = $course_class->id;
        }
        $histories = History::whereIn('foreign_id',$course_class_ids)->where('module','course_classes')->get();
        if($histories && $histories->count()){
            foreach ($histories as $key=>$history){
                $histories[$key]->firefighter = CourseClass::select('firefighters.prefix_id','firefighters.f_name','firefighters.m_name','firefighters.l_name')->leftJoin('firefighters','course_classes.firefighter_id','=','firefighters.id')->where('course_classes.id',$history->foreign_id)->limit(1)->first();
            }
            return view('class.attendance-history')->with('histories',$histories);
        }
    }

    public function update_attendance(Request $request,$semester_id,$course_id,$class_id){
        if(empty($request->firefighter))
            return response()->json(array('status'=>false,'msg'=>'Select records to mark attendance.'));

        $firefighters = $request->firefighter;
        $attendances = $request->attendance;

        foreach ($attendances as $firefighter_id=>$attendance) {
            if (!isset($firefighters[$firefighter_id])) {
                unset($attendances[$firefighter_id]);
                continue;
            }
            $course_class = CourseClass::where('semester_id', $semester_id)->where('course_id', $course_id)->where('class_id', $class_id)->where('firefighter_id', $firefighter_id)->limit(1)->first();

            $additional_changes = [];
            if ($attendance !== $course_class->attendance) {
                $additional_changes[] = [
                    'label' => 'attendance',
                    'prev' => $course_class->attendance,
                    'new' => $attendance,
                    'class' => $class_id,
                    'firefighter' => $firefighter_id,
                ];
                $course_class->attendance = $attendance;
                $course_class->save();
                Helper::create_history(null, null, $course_class->id, 'course_classes', null, $additional_changes);
            }
            if($attendance == "completed")
            {
                if(DB::table('class_firefighter')->where('class_id', $class_id)->where('firefighter_id', $firefighter_id)->limit(1)->first() == true)
                {
                    return response()->json(array( 'status' => false, 'msg' => 'Attendance already marked!'));
                }
                DB::table('class_firefighter')->insert([
                    'class_id' => $class_id,
                    'firefighter_id' => $firefighter_id,
                    'admin_ceu' => $request->admin_ceu,
                    'tech_ceu' => $request->tech_ceu,
                    'created_at' => date('Y-m-d h:i:s'),
                ]);
            }
        }
        return response()->json(array( 'status' => true, 'msg' => 'Updated Successfully!'));
    }
}
