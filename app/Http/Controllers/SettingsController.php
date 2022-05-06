<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{

    public function __construct()
    {
        //$settings = Setting::where('name','logo')->limit(1)->first();
        //unlink(public_path('storage/logo/PinClipart.com_fake-clip-art_1665242-5f7587f355c52.png'));
        //Helper::handle_delete('public/logo/'.'');
    }

    public function index(){
        $users = User::select(DB::raw('COUNT(id) as count'))->whereNull('is_archive')->first();
        $roles = Role::all();
        return view('settings.index')->with('title','System Settings')->with('users',$users)->with('roles',$roles);
    }

    public function enrollment_limit(){
        $array = Setting::where('name','enrollment_limit')->orWhere('name','pboard')->orWhere('name','ifsac')->get()->toArray();
        $settings = [];
        if(!empty($array) && sizeof($array)){
            foreach ($array as $setting){
                $settings[$setting['name']] = $setting['value'];
            }
        }
        return view('settings.enrollment-limit')->with('title','Enrollment Limit')->with('settings',$settings);
    }

    public function save_enrollment_limit(Request $request){
        if($this->insertOrUpdate($request->except('_method','_token'))){
            return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
        }
        return response()->json(['status'=>false, 'msg'=>'Something went wrong. Please try again.']);
    }

    public function insertOrUpdate($input){
        $response = null;
        foreach ($input as $key=>$value){
            $db_setting = Setting::select('id')->where('name',$key)->limit(1)->first();
            if(isset($db_setting->id) && $db_setting->id){
                $db_setting->value = $value;
            }else{
                $db_setting = new Setting();
                $db_setting->name = $key;
                $db_setting->value = $value;
            }
            $response = $db_setting->save();
        }
        return $response;
    }

    public function other_settings(){
        $array = Setting::where('name','per_page')->orWhere('name','fall_start')->orWhere('name','min_attendance_perc')->get()->toArray();
        $logo_url = Helper::get_logo_link();
        $favicon_url = Helper::get_favicon_link();
        $settings = [];
        if(!empty($array) && sizeof($array)){
            foreach ($array as $setting){
                $settings[$setting['name']] = $setting['value'];
            }
        }
        return view('settings.other-settings')->with('title','Other Settings')->with('settings',$settings)->with('logo_url',$logo_url)->with('favicon_url',$favicon_url);
    }

    public function update_settings_value($name,$value){
        return Setting::where('name',$name)->update(['value'=>$value]);
    }

    public function delete_logo(){
        $settings = Setting::where('name','logo')->limit(1)->first();
        if(isset($settings->value) && $settings->value){
            Helper::handle_delete('storage/logo/'.$settings->value);
            $this->update_settings_value('logo','');
        }
    }

    public function delete_favicon(){
        $settings = Setting::where('name','favicon')->limit(1)->first();
        if(isset($settings->value) && $settings->value){
            $this->update_settings_value('favicon','');
            Helper::handle_delete('storage/logo/'.$settings->value);
        }
    }

    public function save_other_settings(Request $request){
        if( ($request->fall_start_day && !$request->fall_start_month) || (!$request->fall_start_day && $request->fall_start_month) ){
            $rules['fall_start'] = 'required';
            $this->validate($request,$rules);
        }

        // if(!checkdate((int) $request->fall_start_month, (int) $request->fall_start_day, date('Y'))){
        //     return response()->json(['status'=>false,'msg'=>'Invalid Date !']);
        // }

        $input = $request->except('_method','_token','fall_start_month','fall_start_day','delete_logo','delete_favicon');
        $input['fall_start'] = $request->fall_start_month && $request->fall_start_day ? "{$request->fall_start_month}-{$request->fall_start_day}" : null;

        // Process Logo
        if($request->delete_logo){
            $this->delete_logo();
        }
        if($request->logo){
            $this->delete_logo();
            $input['logo'] = Helper::upload_file($request, 'logo', 'storage/logo',false);
            if (!$input['logo']) {
                return response()->json(array('status' => false, 'msg' => 'Something went wrong while uploading logo.'));
            }
        }

        // Process Favicon
        if($request->delete_favicon){
            $this->delete_favicon();
        }
        if($request->favicon){
            $this->delete_favicon();
            $input['favicon'] = Helper::upload_file($request, 'favicon', 'storage/logo',false);
            if (!$input['favicon']) {
                return response()->json(array('status' => false, 'msg' => 'Something went wrong while uploading favicon.'));
            }
        }

        if($this->insertOrUpdate($input)){
            return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
        }
        return response()->json(['status'=>false, 'msg'=>'Something went wrong. Please try again.']);

    }
}
