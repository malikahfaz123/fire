<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Mail\FirefighterInvitation;
use App\Mail\RegisterInvitationFirefighter;

use App\Firefighter;
use App\User;
use App\ForeignRelations;

use App\Http\Helpers\FirefighterHelper;
use App\Http\Helpers\Helper;
use App\InviteFirefighter;

class FirefighterSettingController extends Controller
{
    public function index()
    {
        $firefighter = InviteFirefighter::select('id')->groupBy('email')->get()->count();
        return view('firefighter-setting.index')->with('title','Invite Firefighter')->with('firefighter',$firefighter);
    }

    public function paginate(Request $request){

        $per_page = Helper::per_page();

        $query = InviteFirefighter::select('id','email',DB::raw('max(date) as date'),'status');

        if($request->email){
            $query = $query->where('email',$request->email);
        }

        if($request->date){
            $query = $query->where('date',$request->date);
        }

        $firefighters = $query->orderBy('created_at','DESC')->groupBy('email')->paginate($per_page)->appends(request()->query());

        return view('firefighter-setting.paginate')->with('firefighters',$firefighters);
    }

    public function invite_firefighter(Request $request){
        return view('firefighter-setting.create')->with('title','Invite Firefighter');
    }

    public function store_invite_firefighter(Request $request){
        $rules = [
            'prefix_id' => 'required|unique:firefighters', //Application_key\
            'name_suffix' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:firefighters',
            'home_phone' => 'nullable|max:12|unique:firefighters',
            'cell_phone' => 'required|min:12|max:12|unique:firefighters',
            'dob' => 'required|before:18 years ago',
            // 'ssn' => 'required|string|min:11|max:11|unique:firefighters,ssn,'.substr(\Request::getRequestUri(), -1),
            'gender' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'role' => 'required',
        ];
        $messages = [
            'name_suffix.required' => 'The Name suffix field is required.',
            'f_name.required' => 'The First name field is required.',
            'l_name.required' => 'The last name field is required.',
            'email.required' => 'The Email field is required.',
            'email.unique' => 'This Email is already registered.',
            'prefix_id.required' => 'The Application key field is required.', //Application_key
            'prefix_id.unique' => 'The Application key is already taken.', //Application_key
            'cell_phone' => 'The Cell phone field is required.',
            'home_phone' => 'The Home phone field is required.',
            'dob.required' => 'The date of birth field is required.',
            'before' => 'You must be at least 18 years old.',
            'ssn' => 'The SSN field is required.',
            'gender' => 'The Gender field is required.',
            'address' => 'The Address field is required.',
            'city' => 'The City field is required.',
            'zipcode' => 'The Zipcode field is required.',
            'role' => 'The Role field is required.',
        ];

        if($request->home_phone){
            $rules['home_phone'] = $rules['home_phone'].'|min:12';
        }

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['phone_no'] = $request->phone_no ? $phone_code.$request->phone_no : null;
        $input['home_phone'] = $request->home_phone ? $phone_code.$request->home_phone : null;
        $input['cell_phone'] = $request->cell_phone ? $phone_code.$request->cell_phone : null;


        $validator = Validator::make($input, $rules, $messages);
        if($validator->fails())
        {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $dates = explode('-', $request->dob);
        if(!checkdate($dates[1], $dates[2], $dates[0]))
        {
            return response()->json([
               'status' => false,
                'msg' => 'Invalid date entry.'
            ]);
        }

        $invite_firefighter = new InviteFirefighter;
        $invite_firefighter->email = $request->email;
        $invite_firefighter->date = date('Y-m-d');
        $invite_firefighter->status = "sent";
        $invite_firefighter->save();

        $firefighter = new Firefighter;
        $firefighter->prefix_id = $request->prefix_id;
        $firefighter->name_suffix = $request->name_suffix;
        $firefighter->f_name = $request->f_name;
        $firefighter->m_name = $request->m_name;
        $firefighter->l_name = $request->l_name;
        $firefighter->business_name = $request->business_name;
        $firefighter->email = $request->email;
        $firefighter->phone_no = $input['phone_no'];
        $firefighter->dob = $request->dob;
        $firefighter->age = $request->age ? $request->age : '';
        $firefighter->ssn = $request->ssn;
        $firefighter->cfdid_no = $request->cfdid_no ? $request->cfdid_no : '';
        $firefighter->cfd_name = $request->cfd_name ? $request->cfd_name : '';
        $firefighter->cfd_county = $request->cfd_county ? $request->cfd_county : '';
        $firefighter->gender = $request->gender;
        $firefighter->vfdid_no = $request->vfdid_no ? $request->vfdid_no : '';
        $firefighter->vfd_name =$request->vfd_name ? $request->vfd_name : '';
        $firefighter->vfd_county =$request->vfd_county ? $request->vfd_county : '';
        $firefighter->race =$request->race ? $request->race : '';
        $firefighter->address = $request->address;
        $firefighter->city = $request->city;
        $firefighter->state = $request->state;
        $firefighter->zipcode = $request->zipcode;
        $firefighter->muni = $request->muni;
        $firefighter->county = $request->county ? $request->county : '';
        $firefighter->home_phone = $input['home_phone'];
        $firefighter->cell_phone = $input['cell_phone'];
        $firefighter->role = $request->role;
        $firefighter->role_manager = $request->role_manager ? 'yes' : 'no';
        $firefighter->reset_password = uniqid();

        if($firefighter->save()){
            Mail::to($firefighter->email)->send(new RegisterInvitationFirefighter(Auth::user(),$firefighter));
            if($request->role_manager){
                $full_name = $request->f_name.' '.$request->m_name.' '.$request->l_name;
                $invitedFirefighter = Firefighter::where('email',$request->email)->limit(1)->first();
                $token = $invitedFirefighter->reset_password;

                $user = new User();
                $user->name = $full_name;
                $user->role_id = 1;
                $user->email = $request->email;
                $user->reset_password = $token;
                $user->save();
                $user->assignRole('admin');

                $invitedFirefighter->role_manager = 'yes';
                $invitedFirefighter->save();
            }
            return response()->json(['status'=>true,'msg'=>'Invitation Sent !']);
        }
        return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again.']);
    }

    public function verify_firefighter_invitation($token){
        $firefighter = Firefighter::where('reset_password',$token)->limit(1)->first();
        return view('firefighter-setting.firefighter-register-invitation')->with('title','Firefighter Account Setup')->with('firefighter',$firefighter);
    }

    public function firefighter_reset_password(Request $request,$token){
        $rules = [
            'password'          =>  'required|min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password'  =>  'required',
        ];

        $this->validate($request,$rules);

        $firefighter = Firefighter::where('reset_password',$token)->limit(1)->first();

        if(isset($firefighter->id) && $firefighter->id){
            $firefighter->password = Hash::make($request->password);
            $firefighter->email_verified_at = date('Y-m-d H:i:s');
            $firefighter->reset_password = null;

            $resp = InviteFirefighter::where('email',$firefighter->email)->update([
                'status' => "accepted",
            ]);

            if($firefighter->save() && $resp){
                $user = User::where('email',$firefighter->email)->limit(1)->first();
                if(isset($user->id) && $user->reset_password == $token){
                    $user->password = Hash::make($request->password);
                    $user->email_verified_at = date('Y-m-d H:i:s');
                    $user->reset_password = null;
                    $user->save();
                }
                return response()->json(['status'=>true,'msg'=>'Account Registered Successfully !']);
            }
            return response()->json(['status'=>false,'msg'=>'Something went wrong. Please try again !']);
        }
        return response()->json(['status'=>false,'msg'=>'Invalid Request !']);
    }

    public function manage_role_firefighter(Request $request) {
        $id = $request->id;
        $email = $request->email;
        if($id && $email){
            if($request->admin == "yes")
            {
                $firefighter_name = Firefighter::where('email', $email)->get(['f_name','m_name', 'l_name']);
                // dd($firefighter_name[0]->f_name);
                $full_name = $firefighter_name[0]['f_name'].' '.$firefighter_name[0]['m_name'].' '.$firefighter_name[0]['l_name'];
                // dd($full_name);

                Firefighter::where('email', $email)->update(array('role_manager' => 'yes'));

                $user = new User();
                $user->role_id = 1;
                $user->name = $full_name;
                $user->email = $request->email;
                $user->password = Hash::make('12345678');
                $user->save();
                $user->assignRole('admin');
                return response()->json(['status'=>true,'msg'=>'Student role change to admin Successfully!']);
            } elseif($request->admin == "no"){
                $user = User::where('email', $email)->get()->first();
                $user->delete();
                Firefighter::where('email', $email)->update(array('role_manager' => 'no'));
                return response()->json(['status'=>true,'msg'=>'Admin role change to student Successfully!']);
            } else{
                return response()->json(['status'=>false,'msg'=>'Invalid Request !']);
            }
        }
            return response()->json(['status'=>false,'msg'=>'Invalid Request !']);
    }
}
