<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Firefighter;
use App\ForeignRelations;
use App\Http\Helpers\FirefighterHelper;
use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\Hash;

class FirefighterLoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest:firefighters')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.firefighters-login');
    }

    public function showRegistrationForm()
    {
        return view('auth.firefighters-register');
    }

    public function register(Request $request){

        $rules = [
            'f_name'    =>  'required|min:3',
            'm_name'    =>  'nullable|min:3',
            'l_name'    =>  'required|min:3',
            'email'    =>  'required|email|unique:firefighters',
            'password'          =>  'required|min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password'  =>  'required',
            'gender'    =>  'required',
            'dob'    =>  'required|before:18 years ago',
            'address_title'    =>  'required',
            'address'    =>  'required',
            'city'    =>  'required',
            'state'    =>  'required',
            'zipcode'    =>  'required|max:10',
            'cell_phone'    =>  'required|min:12|max:12|unique:firefighters',
            'work_email'    =>  'required|email|unique:firefighters',
        ];

        $messages = [
            'f_name.required' => 'The first name field is required.',
            'm_name.required' => 'The middle name field is required.',
            'l_name.required' => 'The last name field is required.',
            'dob.required' => 'The date of birth field is required.',
            'before' => 'You must be at least 18 years old.'
        ];

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['cell_phone']     =    $request->cell_phone ? $phone_code.$request->cell_phone : null;


        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        $dates = explode('-',$request->dob);
        if(!checkdate($dates[1], $dates[2], $dates[0])){
            return response()->json(['status'=>false,'msg'=>'Invalid date entry.']);
        }

        $firefighter = new Firefighter();
        $firefighter->f_name         =    $request->f_name;
        $firefighter->m_name         =    $request->m_name ? $request->m_name : '';
        $firefighter->l_name         =    $request->l_name;
        $firefighter->email          =    $request->email;
        $firefighter->password       =    Hash::make($request->password);
        $firefighter->gender         =    $request->gender;
        $firefighter->dob            =    $request->dob;
        $firefighter->address_title  =    $request->address_title;
        $firefighter->address        =    $request->address;
        $firefighter->city           =    $request->city;
        $firefighter->state          =    $request->state;
        $firefighter->zipcode        =    $request->zipcode;
        $firefighter->cell_phone     =    $input['cell_phone'];
        $firefighter->work_email     =    $request->work_email;

        if(!$firefighter->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save firefighters. Please try again.']);
        }

        // Update Prefix ID
        $response = Firefighter::where('id',$firefighter->id)->update(['prefix_id'=>FirefighterHelper::prefix_id($firefighter->id)]);
        if(!$response){
            $this->reverse_store_process($firefighter->id);
            return response()->json(array('status'=>false,'msg'=>'Something went wrong while updating prefix id.'));
        }

        $foreign_relation               =   new ForeignRelations();
        $foreign_relation->foreign_id   =   $firefighter->id;
        $foreign_relation->module       =   'firefighters';
        $foreign_relation->name         =   'type';
        $foreign_relation->value        =   'fire inspector';
        if(!$foreign_relation->save()){
            $this->reverse_store_process($firefighter->id);
            return response()->json(['status'=>false,'msg'=>'Failed to save types metadata. Please try again.']);
        }

        return response()->json(['status'=>true,'msg'=>'Register Successfully !']);
    }

    public function login(Request $request)
    {
        // Validate form data
        $this->validate($request,
            [
                'email' => 'required|string|email',
                'password' => 'required|string|min:8'
            ]
        );

        // Attempt to login as admin
        if (Auth::guard('firefighters')->attempt(['email' => $request->email, 'password' => $request->password])) {

            // If successful then redirect to intended route or admin dashboard
            // return redirect()->intended(route('firefighters.dashboard'));

            return redirect()->route('firefighters.dashboard');
        }

        // If unsuccessful then redirect back to login page with email and remember fields and throw error message 
        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => ['These credentials do not match our records.']
        ]);

        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('firefighters')->logout();
        return redirect()->route('firefighters.login');
    }
}
