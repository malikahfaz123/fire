<?php

namespace App\Http\Controllers\Firefighter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Firefighter;
use App\FirefighterCourses;
use App\Setting;
use App\Classes;
use App\Course;
use App\AwardCertificate;
use App\FirefighterCertificates;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helper;


class FirefighterDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function __construct()
    {
        $this->middleware('auth:firefighters');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $yesterday_date = date('Y-m-d',strtotime('-1 day',time()));
        $tomorrow_date = date('Y-m-d',strtotime('+1 day',time()));
        $today_date = date('Y-m-d');
        $data['today_classes'] = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$today_date)->first();
        $data['yesterday_classes'] = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$yesterday_date)->first();
        $data['tomorrow_classes'] = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$tomorrow_date)->first();
        $data['firefighter_courses'] = FirefighterCourses::select(DB::raw('COUNT(id) as count'))->where('firefighter_id',Auth::guard('firefighters')->user()->id)->where('status','enrolled')->first();
        $data['awarded_certificates'] = AwardCertificate::select(DB::raw('COUNT(DISTINCT certificate_id) as count'))->where('firefighter_id',Auth::guard('firefighters')->user()->id)->first();
        $data['approved_certificates'] = FirefighterCertificates::select(DB::raw('COUNT(id) as count'))->where('status','accepted')->where('firefighter_id',Auth::guard('firefighters')->user()->id)->first();
        return view('firefighter',$data);
    }

    
    public function today_classes(){
        $start_date = date('Y-m-d');
        $classes = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$start_date)->first();
        return view('firefighter-frontend.dashboard.today-classes.index')->with('title',"Today's Classes")->with('classes',$classes)->with('start_date',$start_date);
    }

    public function today_classes_paginate(){
        $per_page = Helper::per_page();
        $classes = Classes::select(DB::raw('classes.*'),'courses.course_name')->join('courses','classes.course_id','=','courses.id')->where('classes.start_date',date('Y-m-d'))->paginate($per_page);
        return view('firefighter-frontend.dashboard.today-classes.paginate')->with('classes',$classes);
    }

    public function tomorrow_classes(){
        $tomorrow_classes = date('Y-m-d',strtotime('+1 day',time()));
        $classes = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$tomorrow_classes)->first();
        return view('firefighter-frontend.dashboard.tomorrow-classes.index')->with('title',"Tomorrows's Classes")->with('classes',$classes)->with('start_date',$tomorrow_classes);
    }

    public function tomorrow_classes_paginate(){
        $per_page = Helper::per_page();
        $classes = Classes::select(DB::raw('classes.*'),'courses.course_name')->join('courses','classes.course_id','=','courses.id')->where('classes.start_date',date('Y-m-d',strtotime('+1 day',time())))->paginate($per_page);
        return view('firefighter-frontend.dashboard.tomorrow-classes.paginate')->with('classes',$classes);
    }

    public function yesterday_classes(){
        $yesterday_date = date('Y-m-d',strtotime('-1 day',time()));
        $classes = Classes::select(DB::raw('COUNT(id) as count'))->where('start_date',$yesterday_date)->first();
        return view('firefighter-frontend.dashboard.yesterday-classes.index')->with('title',"Yesterday's Classes")->with('classes',$classes)->with('start_date',$yesterday_date);
    }

    public function yesterday_classes_paginate(){
        $per_page = Helper::per_page();
        $classes = Classes::select(DB::raw('classes.*'),'courses.course_name')->join('courses','classes.course_id','=','courses.id')->where('classes.start_date',date('Y-m-d',strtotime('-1 day',time())))->paginate($per_page);
        
        return view('firefighter-frontend.dashboard.yesterday-classes.paginate')->with('classes',$classes);
    }

    public function profile()
    {
        $user = Auth::guard('firefighters')->user();

        // dd($user);
        return view('firefighter-frontend.profile')->with('title','My Profile')->with('user',$user);
    }

    public function update_profile(Request $request){

        $user = Firefighter::find(Auth::guard('firefighters')->user()->id);

        $rules = [
            'f_name'    =>  'required|min:3',
            'l_name'    =>  'required|min:3',
            'cell_phone'    =>  'nullable|unique:users,cell_phone,'.$user->id,
        ];

        $messages = [
            'f_name.required' => 'The first name field is required.',
            'm_name.required' => 'The middle name field is required.',
            'l_name.required' => 'The last name field is required.',
        ];

        // Remove existing image if removed or uploaded new image at front-end
        if( ($request->hasFile('user_image') && $user->firefighter_image) || $request->delete_image ){
            if(file_exists(public_path('storage/firefighter/'.$user->id.'/thumbnail/'.$user->firefighter_image))){
                unlink(public_path('storage/firefighter/'.$user->id.'/thumbnail/'.$user->firefighter_image));
            }
            if(file_exists(public_path('storage/firefighter/'.$user->id.'/medium/'.$user->firefighter_image))){
                unlink(public_path('storage/firefighter/'.$user->id.'/medium/'.$user->firefighter_image));
            }
            if(file_exists(public_path('storage/firefighter/'.$user->id.'/fullsize/'.$user->firefighter_image))){
                unlink(public_path('storage/firefighter/'.$user->id.'/fullsize/'.$user->firefighter_image));
            }
            $user_image = '';
        }

        // Upload image
        if ($request->hasFile('user_image')) {
            $user_image = Helper::upload_file($request, 'user_image', 'storage/firefighter/'.$user->id);
            if (!$user_image) {
                return response()->json(array('status' => false, 'msg' => 'Something went wrong while uploading user image.'));
            }
        }

        $this->validate($request,$rules);

        $user = Firefighter::find(Auth::user()->id);
        $user->f_name = $request->f_name;
        $user->m_name = $request->m_name;
        $user->l_name = $request->l_name;
        $user->dob = $request->dob;
        $user->age = $request->age;
        $user->gender = $request->gender;
        $user->race = $request->race;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zipcode = $request->zipcode;
        $user->home_phone = $request->home_phone;
        $user->cell_phone = $request->cell_phone;
        $user->work_phone = $request->work_phone;
        $user->work_phone_ext = $request->work_phone_ext;
        
        $user->email = $request->email;
        $user->email_2 = $request->email_2;
        $user->email_3 = $request->email_3;

        if(isset($user_image)){
            $user->firefighter_image = $user_image ? $user_image : null;
        }

        if(!$user->save()){
            return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again !']);
        }
        return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
    }
}
