<?php

namespace App\Http\Controllers;

use App\AwardCertificate;
use App\Certification;
use App\Classes;
use App\Course;
use App\Firefighter;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Help;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
          $data = [
            'user'  =>  $user,
            'title' =>  'Dashboard'
        ];
        $today_date = date('Y-m-d');
        if($user->can('courses.read')){
            $yesterday_date = date('Y-m-d',strtotime('-1 day',time()));
            $tomorrow_date = date('Y-m-d',strtotime('+1 day',time()));
            $data['today_classes'] = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$today_date)->first();
            $data['yesterday_classes'] = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$yesterday_date)->first();
            $data['tomorrow_classes'] = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$tomorrow_date)->first();
            $data['courses'] = Course::select(DB::raw('COUNT(id) as count'))->limit(1)->first();
        }
        if($user->can('certifications.read')){
            $data['certifications'] = Certification::select(DB::raw('COUNT(id) as count'))->limit(1)->first();
            $data['expired_certifications'] = AwardCertificate::select('lapse_date')->whereNotNull('lapse_date')->groupBy(DB::raw('certificate_id desc'))->having('lapse_date','<',$today_date)->get();
        }
        if($user->can('firefighters.read')){
            $data['firefighters'] = Firefighter::select(DB::raw('COUNT(id) as count'))->limit(1)->first();
        }
        return view('dashboard.index',$data);
    }

    public function today_classes(){
        $start_date = date('Y-m-d');
        $classes = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$start_date)->first();
        return view('dashboard.today-classes')->with('title',"Today's Classes")->with('classes',$classes)->with('start_date',$start_date);
    }

    public function paginate_today_classes(){
        $per_page = Helper::per_page();
        $classes = Classes::select(DB::raw('classes.*'),'courses.course_name')->join('courses','classes.course_id','=','courses.id')->where('classes.start_date',date('Y-m-d'))->paginate($per_page);
        return view('dashboard.paginate-today-classes')->with('classes',$classes);
    }

    public function yesterday_classes(){
        $yesterday_date = date('Y-m-d',strtotime('-1 day',time()));
        $classes = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$yesterday_date)->first();
        return view('dashboard.yesterday-classes')->with('title',"Yesterday's Classes")->with('classes',$classes)->with('start_date',$yesterday_date);
    }

    public function paginate_yesterday_classes(){
        $per_page = Helper::per_page();
        $classes = Classes::select(DB::raw('classes.*'),'courses.course_name')->join('courses','classes.course_id','=','courses.id')->where('classes.start_date',date('Y-m-d',strtotime('-1 day',time())))->paginate($per_page);
        return view('dashboard.paginate-yesterday-classes')->with('classes',$classes);
    }

    public function tomorrow_classes(){
        $tomorrow_classes = date('Y-m-d',strtotime('+1 day',time()));
        $classes = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$tomorrow_classes)->first();
        return view('dashboard.tomorrow-classes')->with('title',"Tomorrows's Classes")->with('classes',$classes)->with('start_date',$tomorrow_classes);
    }

    public function paginate_tomorrow_classes(){
        $per_page = Helper::per_page();
        $classes = Classes::select(DB::raw('classes.*'),'courses.course_name')->join('courses','classes.course_id','=','courses.id')->where('classes.start_date',date('Y-m-d',strtotime('+1 day',time())))->paginate($per_page);
        return view('dashboard.paginate-tomorrow-classes')->with('classes',$classes);
    }

    public function renewal_certifications(){
        $today_date = date('Y-m-d');
        $certifications = AwardCertificate::select('lapse_date')->whereNotNull('lapse_date')->groupBy(DB::raw('certificate_id desc'))->having('lapse_date','<',$today_date)->get();
        return view('dashboard.renewal-certifications')->with('title','Renewal of Certifications')->with('certifications',$certifications);
    }

    public function paginate_renewal_certifications(Request $request){
        $today_date = date('Y-m-d');
        $certifications = AwardCertificate::select('awarded_certificates.certificate_id','awarded_certificates.firefighter_id','awarded_certificates.lapse_date',DB::raw('(SELECT prefix_id FROM certifications WHERE id = awarded_certificates.certificate_id) as prefix_id'),DB::raw('(SELECT title FROM certifications WHERE id = awarded_certificates.certificate_id) as title'),DB::raw('(SELECT renewable FROM certifications WHERE id = awarded_certificates.certificate_id) as renewable'))
            ->whereNotNull('lapse_date')->groupBy(DB::raw('certificate_id desc'))->having('lapse_date','<',$today_date)->get();
        if($request->name)
        {
            $firefighterIds = Firefighter::select('id')
            ->where('f_name','LIKE',"%$request->name%")
            ->orWhere('m_name','LIKE',"%$request->name%")
            ->orWhere('l_name','LIKE',"%$request->name%")
            ->orWhereRaw("REPLACE(Concat(f_name,' ',m_name,' ',l_name),'  ',' ') LIKE '%{$request->name}%'")
            ->get();

            $certifications = AwardCertificate::select('awarded_certificates.certificate_id','awarded_certificates.firefighter_id','awarded_certificates.lapse_date',DB::raw('(SELECT prefix_id FROM certifications WHERE id = awarded_certificates.certificate_id) as prefix_id'),DB::raw('(SELECT title FROM certifications WHERE id = awarded_certificates.certificate_id) as title'),DB::raw('(SELECT renewable FROM certifications WHERE id = awarded_certificates.certificate_id) as renewable'))->whereIn('firefighter_id',$firefighterIds)->whereNotNull('lapse_date')->groupBy(DB::raw('certificate_id desc'))->having('lapse_date','<',$today_date)->get();
        }
        return view('dashboard.paginate-renewal-certifications')->with('certifications',$certifications);
    }
}
