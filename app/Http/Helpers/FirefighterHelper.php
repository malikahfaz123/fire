<?php
/**
 * Created by PhpStorm.
 * User: Kingdom Vision
 * Date: 03-Jul-20
 * Time: 5:35 PM
 */

namespace App\Http\Helpers;


use App\Certification;
use App\Classes;
use App\CompletedCourse;
use App\Firefighter;
use App\ForeignRelations;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FirefighterHelper
{
    public static $unset_keys = ['gender','email_token','postal_mail','cell_phone_verified','work_email_verified','emt','is_archive','created_by','created_at','updated_at'];

    public function get_object() {
        return Firefighter::find($this->arg);
    }

    public static function prefix_id($id){
        return sprintf("%06s", $id);
    }

    public static function get_type($firefighter_id){
        $arr = [];
        $types = ForeignRelations::select('value')->where('foreign_id',$firefighter_id)->where('module','firefighters')->get();
        foreach ($types as $type){
            array_push($arr,$type->value);
        }
        return $arr;
    }

    public static function get_full_name($firefighter){
        if(!is_object($firefighter))
            $firefighter = Firefighter::find($firefighter);

            if(isset($firefighter)){
                if($firefighter->m_name){
                    return "$firefighter->f_name $firefighter->m_name $firefighter->l_name";
                }
                return "$firefighter->f_name $firefighter->l_name";
            }
    }

    public static function get_admin_ceu($class_id){
        if(!is_object($class_id))
            $admin_ceu = Classes::find($class_id);

        if(isset($admin_ceu)){
            return number_format($admin_ceu->admin_ceu, 1);
        }
    }

    public static function get_tech_ceu($class_id){
        if(!is_object($class_id))
            $tech_ceu = Classes::find($class_id);

        if(isset($tech_ceu)){
            return number_format($tech_ceu->tech_ceu, 1);
        }
    }

    public static function get_role($firefighter){
        $resp = Firefighter::select('role_manager')->where('email',$firefighter->email)->first();
        if(!empty($resp))
            return $resp->role_manager == "yes" ? "Student | Manager" : "Student";
    }

    public static function filter($params,$query = null){


        unset($params['page']);
        if(!Helper::check_array_has_value($params)) return $query;
        $search = isset($params['search']) ? $params['search'] : '';
        unset($params['search']);
        unset($params['is_archive']);
        unset($params['type']); // Specific for this module

//        echo "<pre>";
//        print_r($params);
//        echo "</pre>";
        $schema = Schema::getColumnListing('firefighters');
        $schema = array_flip($schema);
        if(!empty(self::$unset_keys)){
            foreach (self::$unset_keys as $unset)
                unset($schema[$unset]);
        }

//        echo "<pre>";
//        print_r($schema);
//        echo "</pre>";
        foreach ($params as $key=>$param){
            if($search) unset($schema[$key]);
            if($param == '') continue;
            if($query){
                $query = $query->where($key,$param);
            }else{
                $query = Firefighter::where($key,$param);
            }
        }


        if($search){
            $flag = false;
            $last_key = array_key_last($schema); // Specific for this module
            if($query){
                $query = $query->where(function ($query) use ($search,$schema,$flag,$last_key){
                    foreach ($schema as $column=>$key){
                        if(!$flag){
                            $flag = true;
                            $query = $query->where($column,'like',"%{$search}%");
                        }else{
                            $query = $query->orWhere($column,'like',"%{$search}%");
                            if($column == $last_key){ // Specific for this module
                                $query = $query->orWhereRaw("REPLACE(Concat(f_name,' ',m_name,' ',l_name),'  ',' ') LIKE '%{$search}%'");
                            }
                        }
                    }
                });
            }else{
                foreach ($schema as $column=>$key){
                    if(!$flag){
                        $flag = true;
                        $query = Firefighter::where($column,'like',"%{$search}%");
                    }else{
                        $query = $query->orWhere($column,'like',"%{$search}%");
                        if($column == $last_key){ // Specific for this module
                            $query = $query->orWhereRaw("REPLACE(Concat(f_name,' ',m_name,' ',l_name),'  ',' ') LIKE '%{$search}%'");
                        }
                    }
                }
            }
        }

//        if(!empty($query)){
//            echo Helper::toSql($query);
//        }
        return $query;
    }

    public static function is_course_completed($firefighter_id,$semester_id,$course_id){
        $completed_course = CompletedCourse::select('id')->where('firefighter_id',$firefighter_id)->where('semester_id',$semester_id)->where('course_id',$course_id)->limit(1)->first();
        return (isset($completed_course->id) && $completed_course->id) ? true : false;
    }

    public static function admin_certificate_request_counter(){
        $request_counter = DB::select("select COUNT(id) as certificate_request FROM firefighter_certificates WHERE status='applied' ");
        return array_shift($request_counter);
    }

    public static function admin_course_request_counter(){
        $request_counter = DB::select("select COUNT(id) as course_request FROM firefighter_courses WHERE status='applied' ");
        return array_shift($request_counter);
    }

    public static function approved_request_counter(){
        $request_counter = DB::select("select COUNT(id) as certificate_update FROM certificate_statuses WHERE read_status=0 AND  status='none' AND firefighter_id=".Auth::guard('firefighters')->user()->id);
        return array_shift($request_counter);
    }

    public static function failed_request_counter(){
        $request_counter = DB::select("select COUNT(id) as certificate_update FROM certificate_statuses WHERE read_status=0 AND status='failed' AND firefighter_id=".Auth::guard('firefighters')->user()->id);
        return array_shift($request_counter);
    }

    public static function rejected_request_counter(){
        $request_counter = DB::select("select COUNT(id) as certificate_update FROM certificate_rejected_reasons WHERE read_status=0 AND firefighter_id=".Auth::guard('firefighters')->user()->id);
        return array_shift($request_counter);
    }

    public static function awarded_request_counter(){
        $request_counter = DB::select("select COUNT(id) as certificate_update FROM awarded_certificates WHERE firefighters_read_status=0 AND firefighter_id=".Auth::guard('firefighters')->user()->id);
        return array_shift($request_counter);
    }

    /*public static function generate_new_lapse_date($pre_lapse_date, $renewal_period) {
        $date = Carbon::createFromFormat('Y-m-d', $pre_lapse_date);
        $get_month = $date->format('m');
        $get_year = $date->format('Y');
        if($get_month == 04 || $get_month == 10)
        {
            return Carbon::createFromFormat('Y-m-d', $pre_lapse_date)->addYear($renewal_period);
        }
        else {
            if($get_month > 07) {
                //October Cycle
                return Carbon::createFromFormat('Y-m-d', $get_year.'-10-'.'31')->addYear($renewal_period);
            }
            //April Cycle
            return Carbon::createFromFormat('Y-m-d', $get_year.'-04-'.'30')->addYear($renewal_period);
        }
    }*/

    public static function generate_new_lapse_date($id) {
        $certificate = Certification::find($id);
        $date = Carbon::createFromFormat('Y-m-d', $certificate->renewed_expiry_date);
        $get_month = $date->format('m');
        $get_year = $date->format('Y');
        if($get_month == 04 || $get_month == 10)
        {
            return $date;
        }
        else {
            if($get_month > 07) {
                //October Cycle
                return Carbon::createFromFormat('Y-m-d', $get_year.'-10-'.'31');
            }
            //April Cycle
            return Carbon::createFromFormat('Y-m-d', $get_year.'-04-'.'30');
        }
    }

    public static function generate_renewed_expiry_date($cert_cycle_end_date, $renewal_period) {
        $date = Carbon::createFromFormat('Y-m-d', $cert_cycle_end_date);
        $get_month = $date->format('m');
        $get_year = $date->format('Y');
        if($get_month == 04 || $get_month == 10)
        {
            return Carbon::createFromFormat('Y-m-d', $cert_cycle_end_date)->addYear($renewal_period)->toDateString();
        }
        else {
            if($get_month > 07) {
                //October Cycle
                return Carbon::createFromFormat('Y-m-d', $get_year.'-10-'.'31')->addYear($renewal_period)->toDateString();
            }
            //April Cycle
            return Carbon::createFromFormat('Y-m-d', $get_year.'-04-'.'30')->addYear($renewal_period)->toDateString();
        }
    }

    public static function isEligible($awarded_certificate) {
        $cid = $awarded_certificate->certificate_id;
        $certificate_details = Certification::find($cid);
        return DB::table('class_firefighter')
            ->select(DB::raw('SUM(admin_ceu) as total_admin_ceus'),DB::raw('SUM(tech_ceu) as total_tech_ceus'))
            ->where('firefighter_id', $awarded_certificate->firefighter_id)
            ->whereRaw("( DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '".$certificate_details->certification_cycle_start."' AND '".$certificate_details->certification_cycle_end."' OR DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '".$certificate_details->certification_cycle_end."' AND '".$certificate_details->renewed_expiry_date."' )")
            ->get()
            ->first();
    }

    public static function getCertificateCeus($awarded_certificate) {
        return Certification::find($awarded_certificate->certificate_id);
    }
}
