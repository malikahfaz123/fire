<?php

namespace App\Http\Controllers;

use App\Firefighter;
use App\InviteFirefighter;
use App\History;
use App\Mail\EmailConfirmation;
use App\Mail\RegisterInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers\Helper;
use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    // Under Construction
    public function index(){
        $user = User::select(DB::raw('COUNT(id) as count'))->whereNull('is_archive')->first();
        return view('user.index')->with('title','Users')->with('user',$user);
    }

    public function confirm_email(Request $request,$id)
    {
        switch ($request->module){
            case "firefighters":
                $object = Firefighter::find($id);
                $f_name = $object->f_name;
                $token = uniqid();
                $object->email_token = $token;
                $email = $object->work_email;
                break;
            default:
                return response()->json(array('status'=>false,'msg'=>'Unknown parameters'));
        }

        if(!$object->save()){
            return response()->json(array('status'=>false,'msg'=>'Failed to create a token'));
        }

        $link = route('verify.email',[$request->module,$token]);
        Mail::to($email)->send(new EmailConfirmation($link,$f_name));
        return response()->json(array('status'=>true,'msg'=>'Email Sent Successfully !'));
    }

    public function verify_email($module,$token)
    {
        if(!$module || !$token){
            return view('404');
        }
        switch ($module){
            case "firefighters":
                $query = Firefighter::where('email_token',$token)->first();
                break;
            default:
                return view('404');
        }

        if(!isset($query->id)){
            return view('layouts.verification')->with('status',false)->with('message','The link has been expired or does not exist.');
        }

        $response = DB::table($module)->where('id',$query->id)->update([
            'work_email_verified'    => 1,
            'email_token'       => null
        ]);

        if($response){
            return view('layouts.verification')->with('status',true)->with('message','Email has been verified successfully');
        }else{
            return view('layouts.verification')->with('status',false)->with('message','Something went wrong. Please try again or contact support for help');
        }

    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = $request->is_archive ? User::where('is_archive',1) : User::whereNull('is_archive');
        $query = Helper::filter('users',$request->all(),$query,['email_verified_at','password','is_archive','remember_token','reset_password','created_at','updated_at']);
        $users = $query->orderBy('created_at','DESC')->paginate($per_page)->appends(request()->query());
        if($request->is_archive){
            return view('users.paginate-archive')->with('users',$users);
        }
        return view('users.paginate')->with('users',$users);
    }

    public function create(){
        $roles = Role::all();
        return view('users.create')->with('title','Add User')->with('roles',$roles);
    }

    public function store(Request $request){

        $rules = [
            'full_name'     =>  'required',
            'role'          =>  'required|numeric',
            'email'         =>  'required|email|unique:users',
        ];

        $this->validate($request,$rules);
        $role = Role::findById($request->role);
        $user = new User();
        $user->name = $request->full_name;
        $user->role_id = $role->id;
        $user->email = $request->email;
        $user->reset_password = uniqid();
        if($user->save()){
            $user->assignRole($role->name);
            Mail::to($user->email)->send(new RegisterInvitation(Auth::user(),$user));
            return response()->json(['status'=>true,'msg'=>'Invitation Sent !']);
        }
        return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again.']);
    }

    public function user_invitation($token){
        $user = User::where('reset_password',$token)->limit(1)->first();
        return view('users.register-invitation')->with('title','Account Setup')->with('user',$user);
    }

    public function reset_password(Request $request,$token){

        $rules = [
            'password'          =>  'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password'  =>  'required',
        ];

        $this->validate($request,$rules);

        $user = User::where('reset_password',$token)->limit(1)->first();
        if(isset($user->id) && $user->id){
            $user->password = Hash::make($request->password);
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->reset_password = null;
            if($user->save()){
                return response()->json(['status'=>true,'msg'=>'Password Updated Successfully !']);
            }
            return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again !']);
        }
        return response()->json(['status'=>false,'msg'=>'Invalid Request !']);
    }

    public function show($id){
        $user = User::find($id);
        if(!isset($user->id) || !$user->id){
            return view('404');
        }
        $roles = Role::all();
        return view('users.show')->with('title',$user->name)->with('user',$user)->with('roles',$roles);
    }

    public function edit($id){
        $user = User::find($id);
        if(!isset($user->id) || !$user->id){
            return view('404');
        }
        $roles = Role::all();
        return view('users.edit')->with('title','Edit User')->with('user',$user)->with('roles',$roles);
    }

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        if($request->archive == config('constant.system_user_id'))
            return response()->json(['status'=>false,'msg'=>'Cannot archive system user.']);

        User::where('id',$request->archive)->update(['is_archive'=>1,'archived_at'=>date('Y-m-d H:i:s'),'archived_by'=>Auth::user()->id]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));
    }

    public function archive(){
        $users = User::select(DB::raw('COUNT(id) as count'))->where('is_archive',1)->first();
        $roles = Role::all();
        return view('users.archive')->with('title','Archived Users')->with('users',$users)->with('roles',$roles);
    }

    public function unarchive(Request $request){
        User::where('id',$request->archive)->update(['is_archive'=>null,'archived_at'=>null,'archived_by'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }

    public function destroy($id){

        if($id == config('constant.system_user_id'))
            return response()->json(['status'=>false,'msg'=>'Cannot delete system user.']);

        try{
            $user = User::find($id);
            $role = Role::findById($user->role_id);
            $response = User::where('id',$id)->delete();
            Firefighter::where('email',$user->email)->delete();
            InviteFirefighter::where('email',$user->email)->delete();
        }catch (\Exception $error){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        if($response){
            $user->removeRole($role->name);
            History::where('user_id',$id)->delete();
            return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
        }
        return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
    }

    public function update(Request $request,$id){

        $user = User::find($id);
        if($id == config('constant.system_user_id'))
            return response()->json(['status'=>false,'msg'=>'Cannot edit system user.']);

        if((int) $request->role !== $user->role_id){

            $old_role = Role::findById($user->role_id);
            $user->removeRole($old_role->name);

            $user->role_id = (int) $request->role;
            if(!$user->save()){
                return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again !']);
            }

            $new_role = Role::findById($request->role);
            $user->assignRole($new_role->name);
        }

        return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
    }

    public function profile(){
        $user = Auth::user();
        return view('users.profile')->with('title','My Profile')->with('user',$user);
    }

    public function update_profile(Request $request){

        $user = User::find(Auth::user()->id);
        $rules = [
            'name'    =>  'required|min:3',
            'cell_phone'    =>  'nullable|unique:users,cell_phone,'.$user->id,
        ];

        // Remove existing image if removed or uploaded new image at front-end
        if( ($request->hasFile('user_image') && $user->user_image) || $request->delete_image ){
            if(file_exists(public_path('storage/users/thumbnail/'.$user->user_image))){
                unlink(public_path('storage/users/thumbnail/'.$user->user_image));
            }
            if(file_exists(public_path('storage/users/medium/'.$user->user_image))){
                unlink(public_path('storage/users/medium/'.$user->user_image));
            }
            if(file_exists(public_path('storage/users/fullsize/'.$user->user_image))){
                unlink(public_path('storage/users/fullsize/'.$user->user_image));
            }
            $user_image = '';
        }

        // Upload image
        if ($request->hasFile('user_image')) {
            $user_image = Helper::upload_file($request, 'user_image', 'storage/users');
            if (!$user_image) {
                return response()->json(array('status' => false, 'msg' => 'Something went wrong while uploading user image.'));
            }
        }

        $this->validate($request,$rules);

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->dob = $request->dob;
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
        if(isset($user_image)){
            $user->user_image = $user_image ? $user_image : null;
        }

        if(!$user->save()){
            return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again !']);
        }
        return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
    }

    public function create_permissions(){
        $modules = ['firefighters','semesters','courses','certifications','fire_departments','organizations','facilities','settings'];
        $operations = ['create','read','update','delete'];
        $count = 1;
        foreach ($modules as $module){
            foreach ($operations as $operation){
                $record = DB::table('permissions')->select(DB::raw('COUNT(id) as count'))->where('name',"{$module}.{$operation}")->limit(1)->first();
                if(!isset($record->count) || !$record->count){
                    DB::table('permissions')->insert(['id'=>"$count",'name'=>"{$module}.{$operation}",'guard_name'=>'web']);
                    $count++;
                }
            }
        }
    }

    public function permissions(){
//        $modules = ['firefighters','semesters','courses','certifications','fire_departments','organizations','facilities','settings'];
//        $operations = ['create','read','update','delete'];
//        $role = Role::find(1);
//        foreach ($modules as $module){
//            foreach ($operations as $operation){
//                $role->givePermissionTo("{$module}.{$operation}");
//                echo "<div>{$module}.{$operation}: ".$role->hasPermissionTo("{$module}.{$operation}").'</div>';
//            }
//        }

        $user = User::find(1);
        //Helper::print_r($user->getAllPermissions(),true);
        $user->assignRole('admin');

//        if($user->hasPermissionTo('semester.read')){
//            echo 'YES';
//        }else{
//            echo 'NO';
//        }
    }

    public function revoke_permissions(){

        $role = Role::find(1);
        $role->revokePermissionTo("settings.delete");
        exit;


        $modules = ['firefighters','semesters','courses','credit_types','certifications','fire_departments','organizations','facilities','facility_types'];
        $operations = ['create','read','update','delete'];
        $role = Role::find(1);
        foreach ($modules as $module){
            foreach ($operations as $operation){
                $role->revokePermissionTo("{$module}.{$operation}");
                echo "<div>{$module}.{$operation}: ".$role->hasPermissionTo("{$module}.{$operation}").'</div>';
            }
        }
    }

    public function revoke_invitation(Request $request)
    {
        $invitation = \App\InviteFirefighter::find($request->data);
        $invitation->status = "revoked";
        if(!$invitation->save()){
            return json_encode(['status'=>false,'msg'=>'Something went wrong. Please try again!']);
        }
        return json_encode([ 'status' => true, 'msg' => 'Invitation revoked successfully!' ]);
    }

    public function delete_invitation(Request $request)
    {
        $invitation = InviteFirefighter::where('email',$request->data);

        $invitation_firefighter = Firefighter::where('email',$request->data)->delete();

        // $invitation_firefighter_admin = User::where('email',$request->data)->delete();

        if(!$invitation->delete())
        {
            return json_encode(['status'=>false,'msg'=>'Something went wrong. Please try again!']);
        }
      

        return json_encode([ 'status' => true, 'msg' => 'Invitation deleted successfully!' ]);
    }
}
