<?php

namespace App\Http\Controllers;

use App\Certification;
use App\Course;
use App\CreditType;
use App\FireDepartmentType;
use App\History;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function history(){
        $history = History::select(DB::raw('COUNT(id) as count'))->first();
        $modules = array('firefighters','semesters','courses','classes','course_classes','certifications','fire_departments');
        return view('reports.history')->with('title','History')->with('history',$history)->with('modules',$modules);
    }
    public function paginate_history(Request $request){
        $per_page = Helper::per_page();
        $query = History::select('*');
        if($request->foreign_id){
            $query = $query->where('foreign_id',$request->foreign_id);
        }
        if($request->module){
            $query = $query->whereIn('module',$request->module);
        }
        $histories = $query->orderBy('created_at','desc')->paginate($per_page);
        if($histories && $histories->count()){
            foreach ($histories as $key=>$history){
                $array = json_decode($history->data,true);
                foreach ($array as $key_2=>$data){
                    $label = strtolower($data['label']);
                    $prev = null;
                    $new = null;
                    if($label === 'renewable'){
                        $prev = $data['prev'] ? 'Yes' : 'No';
                        $new = $data['new'] ? 'Yes' : 'No';
                    }elseif($label === 'credit_types'){
                        $prev = CreditType::select('description')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('description')->toArray() : 'N/A';
                        $new = CreditType::select('description')->whereIn('id',$data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('description')->toArray() : 'N/A';
                    }elseif($label === 'fire_department_types'){
                        $prev = FireDepartmentType::select('description')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('description')->toArray() : 'N/A';
                        $new = FireDepartmentType::select('description')->whereIn('id',$data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('description')->toArray() : 'N/A';
                    }elseif($label === 'prerequisite_certificates'){
                        $prev = Certification::select('title')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('title')->toArray() : 'N/A';
                        $new = Certification::select('title')->whereIn('id',$data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('title')->toArray() : 'N/A';
                    }elseif($label === 'prerequisite_courses'){
                        $prev = Course::select('course_name')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('course_name')->toArray() : 'N/A';
                        $new = Course::select('course_name')->whereIn('id',$data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('course_name')->toArray() : 'N/A';
                    }elseif($label === 'semester_courses'){
                        $prev = Course::select('course_name')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('course_name')->toArray() : 'N/A';
                        $new = Course::select('course_name')->whereIn('id',$data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('course_name')->toArray() : 'N/A';
                    }elseif($label === 'home_phone' || $label === 'cell_phone' || $label === 'work_phone' || $label === 'phone' || $label === 'phone2'){
                        $prev = $data['prev'] ? Helper::format_phone_number($data['prev']) : 'N/A';
                        $new = $data['new'] ? Helper::format_phone_number($data['new']) : 'N/A';
                    }
                    if(isset($prev) && isset($new)){
                        $array[$key_2]['prev'] = $prev;
                        $array[$key_2]['new'] = $new;
                    }
                    $histories[$key]->data = $array;
                }
            }
        }
        return view('reports.paginate-history')->with('histories',$histories);
    }
}
