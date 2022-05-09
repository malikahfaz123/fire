<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 4/20/2020
 * Time: 6:41 PM
 */

namespace App\Http\Helpers;
use App\Attachment;
use App\AwardCertificate;
use App\Classes;
use App\CompletedCourse;
use App\Course;
use App\CourseClass;
use App\Firefighter;
use App\History;
use App\InstructorPrerequisites;
use App\Role;
use App\Document;
use App\Note;
use App\Semester;
use App\Setting;
use App\UserSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;


class Helper
{

    public $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    public static function prefix_id($id){
        return sprintf("%06s", $id);
    }

    // check if value exists
    public static function is_val($var, $key = null, $default = null) {
        if( empty($key) ) {
            if( isset($var) && !empty($var) )
                return $var;
            else
                return $default;
        }
        else {
            if( is_array($var) && array_key_exists($key, $var) )
                return self::is_val($var[$key], null, $default);
            else if( is_object($var) && isset($var->$key) )
                return self::is_val($var->$key, null, $default);
        }

        return $default;
    }

    public static function per_page(){
        $settings = Setting::where('name','per_page')->limit(1)->first();
        return self::is_val($settings, 'value',config('constant.per_page'));
    }

    public static function date_format($datetime){
        return date("M d, Y",strtotime($datetime));
    }

    public static function datetime_format($datetime){
        return date("M d, Y - g:i A",strtotime($datetime));
    }

    public static function time_format($datetime){
        return date("g:i A",strtotime($datetime));
    }

    public static function create_history($prev_object = null,$new_object = null,$foreign_id,$module,$key_label = null,$additional_changes = null){

        if(!$prev_object && !$new_object && !$additional_changes){
            throw new \Error("All arrays cannot be null");
        }

        if($prev_object && $new_object) {

            $array_1 = $prev_object;
            $array_2 = $new_object;

            unset($array_1['is_archive']);
            unset($array_1['created_at']);
            unset($array_1['updated_at']);
            unset($array_1['created_by']);
            unset($array_2['is_archive']);
            unset($array_2['created_at']);
            unset($array_2['updated_at']);
            unset($array_2['created_by']);

            $diff = [];

            foreach ($array_1 as $key => $value) {
                if (isset($array_1[$key]) && !isset($array_2[$key])) {
                    $diff[] = [
                        'label' =>  (isset($key_label[$key])) ? $key_label[$key] : $key,
                        'prev'  =>  $array_1[$key],
                        'new'   =>  ''
                    ];
                } elseif (!isset($array_1[$key]) && isset($array_2[$key])) {
                    $diff[] = [
                        'label' =>  (isset($key_label[$key])) ? $key_label[$key] : $key,
                        'prev'    =>  '',
                        'new'    =>  $array_2[$key],
                    ];
                } elseif (isset($array_1[$key]) && isset($array_2[$key])) {
                    if ($array_1[$key] != $array_2[$key]) {
                        $diff[] = [
                            'label' =>  (isset($key_label[$key])) ? $key_label[$key] : $key,
                            'prev'    =>  $array_1[$key],
                            'new'    =>  $array_2[$key],
                        ];
                    }
                }
            }
        }

        if($additional_changes){
            foreach ($additional_changes as $additional_change){
                $diff[] = $additional_change;
            }
        }

        if(!empty($diff)){
            $history = new History();
            $history->user_id = Auth::user()->id;
            $history->foreign_id = $foreign_id;
            $history->module = $module;
            $history->data = json_encode($diff);
            return $history->save();
        }

        return true;
    }

    public static function get_last_updated($module,$foreign_id){
        $history = History::where('module',$module)->where('foreign_id',$foreign_id)->orderBy('created_at','desc')->limit(1)->first();
        if($history && $history->count()){
            return $history;
        }
        return false;
    }

    public static function toSql($query){
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        foreach($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }

    public static function print_r($arg,$exit = false){
        echo "<pre>";
        print_r($arg);
        echo "</pre>";
        if($exit) exit;
    }

    public static function check_array_has_value($array){
        foreach ($array as $value){
            if($value !== '') return true;
        }
        return false;
    }

    public static function filter($table,$params,$query = null,$unset_keys = null){

        unset($params['page']);
        if(!self::check_array_has_value($params)) return $query;
        $search = isset($params['search']) ? $params['search'] : '';
        unset($params['search']);
        unset($params['is_archive']);

        $schema = Schema::getColumnListing($table);
        $schema = array_flip($schema);
        if($unset_keys){
            foreach ($unset_keys as $unset)
                unset($schema[$unset]);
        }

        foreach ($params as $key=>$param){
            //if($search) unset($schema[$key]); // Comment this if we want to ignore other fields from global search
            if($param == '') continue;
            if($query){
                $query = $query->where($key,$param);
            }else{
                $query = DB::table($table)->where($key,$param);
            }
        }
        if($search){
            $flag = false;
            if($query){
                $query = $query->where(function ($query) use ($search,$schema,$flag){
                    foreach ($schema as $column=>$key){
                        if(!$flag){
                            $flag = true;
                            $query = $query->where($column,'like',"%{$search}%");
                        }else{
                            $query = $query->orWhere($column,'like',"%{$search}%");
                        }
                    }
                });
            }else{
                foreach ($schema as $column=>$key){
                    if(!$flag){
                        $flag = true;
                        $query = DB::table($table)->where($column,'like',"%{$search}%");
                    }else{
                        $query = $query->orWhere($column,'like',"%{$search}%");
                    }
                }
            }
        }

//        if(!empty($query)){
//            echo Helper::toSql($query);
//        }
        return $query;
    }

    public static function get_fall_start(){
        $settings = Setting::where('name','fall_start')->limit(1)->first();
        return self::is_val($settings, 'value',config('constant.fall_start'));
    }

//    public static function detect_semester(){
//        $year = date('Y');
//        $fall = $year."-".self::get_fall_start();
//        $full_date = date('Y-m-d');
//        return $full_date<$fall ? 'spring' : 'fall';
//    }

    public static function get_current_semester(){

        $current_date = date('Y-m-d');
        $semester = Semester::where('start_date','<=',$current_date)->where('end_date','>=',$current_date)->limit(1)->first();
        return isset($semester->id) && $semester->id ? $semester : false;
    }

    public static function get_min_attendance_perc(){
        $settings = Setting::where('name','min_attendance_perc')->limit(1)->first();
        return self::is_val($settings, 'value',config('constant.min_attendance_perc'));
    }

    public static function total_classes($semester_id,$course_id,$firefighter_id){
        $course_classes = Classes::select(DB::raw('COUNT(course_classes.id) as count'))->leftJoin('course_classes','classes.id','=','course_classes.class_id')->where('classes.semester_id',$semester_id)->where('course_classes.course_id',$course_id)->where('course_classes.firefighter_id',$firefighter_id)->limit(1)->first();
        return isset($course_classes->count) && $course_classes->count ? $course_classes->count : 0;
    }

    public static function get_attended_classes($semester_id,$course_id,$firefighter_id){
        $attendance = 0;
        $course_class = Classes::select(DB::raw('COUNT(course_classes.id) as count'))->leftJoin('course_classes','classes.id','=','course_classes.class_id')->where('classes.semester_id',$semester_id)->where('course_classes.course_id',$course_id)->limit(1)->first();
        if(isset($course_class->count) && $course_class->count){
            $firefighter_attendance = Classes::select(DB::raw('COUNT(course_classes.id) as count'))->leftJoin('course_classes','classes.id','=','course_classes.class_id')->where('classes.semester_id',$semester_id)->where('course_classes.course_id',$course_id)->where('course_classes.firefighter_id',$firefighter_id)->where('course_classes.attendance','completed')->limit(1)->first();
            if(isset($firefighter_attendance->count) && $firefighter_attendance->count){
                $attendance = $firefighter_attendance->count;
            }
        }
        return $attendance;
    }

    public static function is_semester_completed($semester, $year){

        $db_semester = Semester::select('end_date')->where('semester',$semester)->where('year',$year)->limit(1)->first();
        return isset($db_semester->end_date) && date('Y-m-d') > $db_semester->end_date ? true : false;
    }

    public static function certification_history_count($firefighter_id,$certificate_id){
        $award_certificate = AwardCertificate::select(DB::raw('COUNT(id) as count'))->where('firefighter_id',$firefighter_id)->where('certificate_id',$certificate_id)->limit(1)->first();
        return isset($award_certificate->count) && $award_certificate->count ? $award_certificate->count : 0;
    }

    public static function enrollment_limit(){
        $settings = Setting::where('name','enrollment_limit')->limit(1)->first();
        return self::is_val($settings, 'value',config('constant.enrollment_limit'));
    }

    /* @Return filename */
    public static function upload_file($request,$field,$path,$multiple = true)
    {
        $file = $request->file($field);
        $ext = $file->getClientOriginalExtension();
        $filename = str_replace(".$ext",'',$file->getClientOriginalName());
        $filename = uniqid($filename.'-').'.'.$ext;
        if($multiple){
            // Thumbnail size
            $thumbnail_image = Image::make($file->getRealPath());
            $thumbnail_image->resize(config('constant.thumbnail_size'), null, function ($constraint) {
                $constraint->aspectRatio();
            });
            if (!file_exists(public_path($path.'/thumbnail'))) {
                mkdir(public_path($path.'/thumbnail'), 755, true);
            }
            $thumbnail_image->save(public_path($path.'/thumbnail/' .$filename));

            // Medium size
            $medium_image = Image::make($file->getRealPath());
            $medium_image->resize(config('constant.medium_size'), null, function ($constraint) {
                $constraint->aspectRatio();
            });
            if (!file_exists(public_path($path.'/medium'))) {
                mkdir(public_path($path.'/medium'), 755, true);
            }
            $medium_image->save(public_path($path.'/medium/' .$filename));

            // Original size
            $file->move($path.'/fullsize/',$filename);
        }else{
            $file->move($path.'/',$filename);
        }

        return $filename;
    }

    public static function handle_delete($path){
        $storage_path =  storage_path($path);
        if(file_exists($storage_path)){
            unlink($storage_path);
        }
        $public_path =  public_path($path);
        if(file_exists($public_path)){
            unlink($public_path);
        }
    }

    public static function get_logo_link(){
        $settings = Setting::where('name','logo')->limit(1)->first();
        $logo = self::is_val($settings, 'value',config('constant.logo'));
        return asset('storage/logo/'.$logo);
    }

    public static function get_favicon_link(){
        $settings = Setting::where('name','favicon')->limit(1)->first();
        $favicon = self::is_val($settings, 'value',config('constant.favicon'));
        return asset('storage/logo/'.$favicon);
    }

    public static function get_app_name(){
        $settings = Setting::where('name','logo')->limit(1)->first();
        return self::is_val($settings, 'app_name',config('app.name', 'Laravel'));
    }

    public static function get_phone_code(){
        $settings = Setting::where('name','phone_code')->limit(1)->first();
        return self::is_val($settings, 'phone_code',config('constant.phone_code'));
    }

    public static function separate_phone_code($phone_number){
        $phone_code = self::get_phone_code();
        return trim(str_replace($phone_code,'',$phone_number));
    }

    public static function format_phone_number($phone_number){
        $phone_code = self::get_phone_code();
        $phone_number = self::separate_phone_code($phone_number);
        return $phone_code.' ('.substr($phone_number,0, 3) . ') ' . substr($phone_number,3, 3) . ' ' . substr($phone_number,6,4);
    }

    public static function ordinal_suffix_of($i){
        $j = $i % 10;
        $k = $i % 100;
        if ($j == 1 && $k != 11) {
            return $i . "st";
        }
        if ($j == 2 && $k != 12) {
            return $i . "nd";
        }
        if ($j == 3 && $k != 13) {
            return $i . "rd";
        }
        return $i . "th";
    }

    public static function check_instructor_eligibility($course_id,$firefighter_id,$course_level = null){
        if(!$course_level)
            $course_level = Course::select('instructor_level')->where('id',$course_id)->limit(1)->first();

        $firefighter = Firefighter::select('instructor_level')->where('id',$firefighter_id)->limit(1)->first();
        if($firefighter->instructor_level){
            if($firefighter->instructor_level == $course_level->instructor_level){
                return ['status'=>true];
            }
            return ['status'=>false,'msg'=>'This instructor is not eligible for instructing this course.'];
        }
        return ['status'=>false,'msg'=>'Instructor level not found.'];
    }

    public static function semester_unique_date_range($start_date,$end_date,$year,$except_id = null){
        $query = Semester::select('id','semester')->where(function ($query) use ($start_date,$end_date) {
            $query->whereBetween('start_date',[$start_date,$end_date])
                ->orWhereBetween('end_date',[$start_date,$end_date]);
        });
        if($except_id){
            $query = $query->where('id','!=',$except_id);
        }

        $semester = $query->limit(1)->first();
        if(isset($semester->id) && $semester->id){
            return ['status'=>false,'msg'=>"Selected date range overlaps date range of ".ucfirst($semester->semester)." Semester {$year}. Date range must not overlap any other semester's date range."];
        }
        return ['status'=>true];
    }
}
