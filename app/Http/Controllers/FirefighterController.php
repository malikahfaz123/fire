<?php

namespace App\Http\Controllers;

use App\Jobs\RoleManagerAcknowledgementToFirefighterJob;
use UxWeb\SweetAlert\SweetAlert;
use Alert;
/*use Barryvdh\DomPDF\PDF;*/
use App\Mail\NewFirefighter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Redirect,Response;

use App\Http\Helpers\FirefighterHelper;
use App\Http\Helpers\Helper;

use App\Jobs\RoleManagerAcknowledgementJob;
use App\Mail\SendCertificate;
use App\Mail\SendTranscript;

use App\AwardCertificate;
use App\Certification;
use App\CompletedCourse;
use App\Course;
use App\CourseClass;
use App\ForeignRelations;
use App\History;
use App\Semester;
use App\User;
use App\Firefighter;
use App\certificatehistory;
use PDF;
use Carbon\Carbon;

class FirefighterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $firefighters = Firefighter::select(DB::raw('COUNT(id) as count'))->whereNull('is_archive')->first();
        return view('firefighter.index')->with('title','Firefighter')->with('firefighters',$firefighters);
    }

    public function paginate(Request $request)
    {
        $per_page = Helper::per_page();
        $query = $request->is_archive ? Firefighter::where('is_archive',1) : Firefighter::whereNull('is_archive');
        if($request->type){
            $foreign_relations = ForeignRelations::where('module','firefighters')->where('name','type')->whereIn('value',$request->type)->get();
            $ids = [];
            foreach ($foreign_relations as $foreign_relation){
                if(!in_array($foreign_relation->foreign_id,$ids)){
                    array_push($ids,$foreign_relation->foreign_id);
                }
            }
            $query = $query->whereIn('id',$ids);
        }
        if($request->name){
            $query = $query->where(function ($query) use ($request) {
                $query->where('f_name', 'like', "%$request->name%")
                    ->orWhere('m_name', 'like', "%$request->name%")
                    ->orWhere('l_name', 'like', "%$request->name%")
                    ->orWhereRaw("REPLACE(Concat(f_name,' ',m_name,' ',l_name),'  ',' ') LIKE '%{$request->name}%'");
            });
        }

        $query = FirefighterHelper::filter($request->except(['name']),$query);
        $firefighters = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());

        if($firefighters && $firefighters->count()){
            foreach ($firefighters as $key=>$firefighter){
                $type = FirefighterHelper::get_type($firefighter->id);
                $type = implode(', ',$type);
                $type = str_replace('fire ','',$type);
                $firefighters[$key]->type = $type;
            }
        }
        if($request->is_archive){
            return view('firefighter.paginate-archive')->with('firefighters',$firefighters);
        }
        return view('firefighter.paginate')->with('firefighters',$firefighters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('firefighter.create', ['title' => 'Add Firefighter']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name_suffix'    =>  'required|min:2',
            'f_name'    =>  'required|min:3',
            'm_name'    =>  'nullable|min:3',
            'l_name'    =>  'required|min:3',
            'address_title'    =>  'required',
            'address'    =>  'required',
            'dob'    =>  'required|before:18 years ago',
            'gender'    =>  'required',
            'type'    =>  'required',
            // 'ssn'    =>  'required',
            // 'ssn' => 'required|string|min:11|max:11|unique:firefighters,ssn,'.substr(\Request::getRequestUri(), -1),
            'vol'    =>  'required',
            'car'    =>  'required',
            'city'    =>  'required',
            'state'    =>  'required',
            'zipcode'    =>  'required|max:10',
            'home_phone'    =>  'nullable|max:12|unique:firefighters',
            'cell_phone'    =>  'required|min:12|max:12|unique:firefighters',
            'email'    =>  'required|email|unique:firefighters',
            'appkey'    =>  'required',
            'role'    =>  'required'
        ];
        $messages = [
            'f_name.required' => 'The first name field is required.',
            'm_name.required' => 'The middle name field is required.',
            'l_name.required' => 'The last name field is required.',
            'dob.required' => 'The date of birth field is required.',
            'before' => 'You must be at least 18 years old.'
        ];

        if($request->home_phone){
            $rules['home_phone'] = $rules['home_phone'].'|min:12';
        }

        if($request->address_title_2 || $request->address_2 || $request->state_2 || $request->city_2 || $request->zipcode_2){
            $rules['address_title_2'] = 'required';
            $rules['address_2'] = 'required';
            $rules['state_2'] = 'required';
            $rules['city_2'] = 'required';
            $rules['zipcode_2'] = 'required';
        }

        if($request->address_title_3 || $request->address_3 || $request->state_3 || $request->city_3 || $request->zipcode_3){
            $rules['address_title_3'] = 'required';
            $rules['address_3'] = 'required';
            $rules['state_3'] = 'required';
            $rules['city_3'] = 'required';
            $rules['zipcode_3'] = 'required';
        }

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['home_phone']     =    $request->home_phone ? $phone_code.$request->home_phone : null;
        $input['cell_phone']     =    $request->cell_phone ? $phone_code.$request->cell_phone : null;

        if($request->type && sizeof($request->type) && in_array('fire instructor',$request->type)){
            $rules['instructor_level'] = 'required';
        }

        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        $dates = explode('-',$request->dob);
        if(!checkdate($dates[1], $dates[2], $dates[0])){
            return response()->json(['status'=>false,'msg'=>'Invalid date entry.']);
        }

        $firefighter = new Firefighter();
        $firefighter->name_suffix    =    $request->name_suffix;
        $firefighter->f_name         =    $request->f_name;
        $firefighter->m_name         =    $request->m_name ? $request->m_name : '';
        $firefighter->l_name         =    $request->l_name;
        $firefighter->email          =    $request->email;
        $firefighter->email_2        =    $request->email_2 ? $request->email_2 : null;
        $firefighter->email_3        =    $request->email_3 ? $request->email_3 : null;
        $firefighter->comment        =    $request->comment ? $request->comment : null;
        $firefighter->dob            =    $request->dob;
        $firefighter->age            =    $request->age;
        $firefighter->gender         =    $request->gender;
        $firefighter->race           =    $request->race ? $request->race : null;
        $firefighter->appointed      =    $request->appointed ? 1 : 0;
        $firefighter->instructor_level =  $request->type && sizeof($request->type) && in_array('fire instructor',$request->type) ? (int) $request->instructor_level : null;
        $firefighter->ssn            =    $request->ssn;
        $firefighter->ucc            =    $request->ucc;
        $firefighter->nicet          =    $request->nicet;
        $firefighter->fema           =    $request->fema;
        $firefighter->muni           =    $request->muni;
        $firefighter->vol            =    $request->vol;
        $firefighter->car            =    $request->car;
        $firefighter->postal_mail    =    isset($request->postal_mail) ? 1 : null;

        $firefighter->postal_mail_2  =    $request->postal_mail_2 ? 1 : null;
        $firefighter->address_title  =    $request->address_title;
        $firefighter->address        =    $request->address;
        $firefighter->city           =    $request->city;
        $firefighter->state          =    $request->state;
        $firefighter->zipcode        =    $request->zipcode;

        $firefighter->postal_mail_3    =    $request->postal_mail_3 ? 1 : null;
        $firefighter->address_title_2  =    $request->address_title_2 ? $request->address_title_2 : null;
        $firefighter->address_2        =    $request->address_2 ? $request->address_2 : null;
        $firefighter->city_2           =    $request->city_2 ? $request->city_2 : null;
        $firefighter->state_2          =    $request->state_2 ? $request->state_2 : null;
        $firefighter->zipcode_2        =    $request->zipcode_2 ? $request->zipcode_2 : null;

        $firefighter->address_title_3  =    $request->address_title_3 ? $request->address_title_3 : null;
        $firefighter->address_3        =    $request->address_3 ? $request->address_3 : null;
        $firefighter->city_3           =    $request->city_3 ? $request->city_3 : null;
        $firefighter->state_3          =    $request->state_3 ? $request->state_3 : null;
        $firefighter->zipcode_3        =    $request->zipcode_3 ? $request->zipcode_3 : null;

        $firefighter->home_phone        =    $input['home_phone'];
        $firefighter->cell_phone        =    $input['cell_phone'];
        $firefighter->role              =    $request->role ? $request->role : '';
        $firefighter->role_manager      =    $request->role_manager_value == 'yes' ? 'yes' : 'no';
        $firefighter->appkey            =     $request->appkey;
        $firefighter->password          =    Hash::make('12345678');
        $firefighter->created_by        =    Auth::user()->id;

        // check if yes and make an admin
        $heading = $request->role_manager_value == 'yes' ? 'Admin Account Credentials' : '';
        $username = $request->role_manager_value == 'yes' ? 'Email: '.$request->email : '';
        $password = $request->role_manager_value == 'yes' ? 'Password: 12345678' : '';

        if($request->role_manager_value == "yes")
        {
            $full_name = $request->f_name.' '.$request->m_name.' '.$request->l_name;

            $user = new User();
            $user->role_id = 1;
            $user->name = $full_name;
            $user->email = $request->email;
            $user->password = Hash::make('12345678');
            $user->save();
            $user->assignRole('admin');

            $admins = User::all();
            $assigned_by = Auth::user()->name;
            $assigned_to = $request->name_suffix .' '. $request->f_name .' '. $request->l_name;
            $message = "assigned the admin access to";

            $firefighter_email = $request->email;
            $firefighter_name = $assigned_to;
            $msgForFirefighter = "assigned the admin access to you.";

            if(isset($admins) && !empty($admins))
            {
                foreach ($admins as $admin)
                {
                    if($admin->email != $request->email)
                    {
                        // Send Acknowledgement Email to all the existing admins
                        dispatch(new RoleManagerAcknowledgementJob($admin->name,$admin->email,$assigned_by,$assigned_to,$message,'Role Manager Access Acknowledgement'));
                    }
                }
            }
            dispatch(new RoleManagerAcknowledgementToFirefighterJob( $firefighter_name, $firefighter_email, $assigned_by, $msgForFirefighter, $heading, $username, $password, 'Role Manager Access Acknowledgement' ));
        }

        if(!$firefighter->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save firefighters. Please try again.']);
        }
        
        Mail::to($firefighter->email)->send(new NewFirefighter(Auth::user(), $firefighter));

        // $data = array('name'=>"Virat Gandhi");
        // Mail::send('mail', $data, function($message) {
        //    $message->to('ahfaz.kingdomvision@gmail.com', 'Tutorials Point')->subject
        //       ('Laravel HTML Testing Mail');
        //    $message->from('ahfaz.kingdomvision@gmail.com','Virat Gandhi');
        // });

        // Update Prefix ID
        $response = Firefighter::where('id',$firefighter->id)->update(['prefix_id'=>FirefighterHelper::prefix_id($firefighter->id)]);
        if(!$response){
            $this->reverse_store_process($firefighter->id);
            return response()->json(array('status'=>false,'msg'=>'Something went wrong while updating prefix id.'));
        }

        foreach ($request->type as $type){
            $foreign_relation               =   new ForeignRelations();
            $foreign_relation->foreign_id   =   $firefighter->id;
            $foreign_relation->module       =   'firefighters';
            $foreign_relation->name         =   'type';
            $foreign_relation->value        =   $type;
            if(!$foreign_relation->save()){
                $this->reverse_store_process($firefighter->id);
                return response()->json(['status'=>false,'msg'=>'Failed to save types metadata. Please try again.']);
            }
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }

    public function reverse_store_process($id){
        try{
            $response = Firefighter::where('id',$id)->delete();
        }catch (\Exception $error){
            return false;
        }
        if($response){
            ForeignRelations::where('foreign_id',$id)->where('module','firefighters')->delete();
            History::where('foreign_id',$id)->where('module','firefighters')->delete();
            return true;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){

        


        $firefighter = Firefighter::find($id);

        if($firefighter && $firefighter->count()){
            $foreign_relations = ForeignRelations::where('foreign_id',$firefighter->id)->where('module','firefighters')->where('name','type')->get();
            $type = [];
            if($foreign_relations && $foreign_relations->count()){
                foreach ($foreign_relations as $foreign_relation){
                    array_push($type,$foreign_relation->value);
                }
            }
            $last_updated = Helper::get_last_updated('firefighters',$id);
            $title = "{$firefighter->f_name} {$firefighter->l_name}";
            $user = Auth::user();


            $firefighter_classes_adminceu = DB::table('class_firefighter')->where('firefighter_id',$id)->sum('admin_ceu');

            $firefighter_classes_techceu = DB::table('class_firefighter')->where('firefighter_id',$id)->sum('tech_ceu');

            if($firefighter->updated_by){
                $firefighterUpdated = Firefighter::find($firefighter->updated_by);
                return view('firefighter.show')->with('title',ucwords($title))->with('user',$user)->with('firefighter',$firefighter)->with('type',$type)->with('last_updated',$last_updated)->with('firefighterUpdated', $firefighterUpdated)->with('total_admin_ceu',$firefighter_classes_adminceu)->with('total_tech_ceu',       $firefighter_classes_techceu);
            }
        
        
            return view('firefighter.show')->with('title',ucwords($title))->with('user',$user)->with('firefighter',$firefighter)->with('type',$type)->with('last_updated',$last_updated)->with('total_admin_ceu',$firefighter_classes_adminceu)->with('total_tech_ceu',       $firefighter_classes_techceu);
        }else{
            return view('404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $firefighter = Firefighter::find($id);
        if($firefighter && $firefighter->count() && !$firefighter->is_archive){
            $foreign_relations = ForeignRelations::where('foreign_id',$firefighter->id)->where('module','firefighters')->where('name','type')->get();
            $type = [];
            if($foreign_relations && $foreign_relations->count()){
                foreach ($foreign_relations as $foreign_relation){
                    array_push($type,$foreign_relation->value);
                }
            }
            $user = Auth::user();
            if($firefighter->updated_by){
                $firefighterUpdated = Firefighter::find($firefighter->updated_by);
                return view('firefighter.edit')->with('title','Edit Firefighter')->with('user',$user)->with('firefighter',$firefighter)->with('type',$type)->with('firefighterUpdated', $firefighterUpdated);
            }
            return view('firefighter.edit')->with('title','Edit Firefighter')->with('user',$user)->with('firefighter',$firefighter)->with('type',$type);
        }else{
            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $rules = [
            'address_title'    =>  'required|max:50',
            'address'    =>  'required|max:80',
            'dob'    =>  'required|before:18 years ago',
            'gender'    =>  'required',
            'type'    =>  'required',
            'vol'    =>  'required',
            'car'    =>  'required',
            'city'    =>  'required|max:50',
            'state'    =>  'required|max:50',
            'zipcode'    =>  'required|max:10',
            'home_phone'    =>  'nullable|max:12|unique:firefighters,home_phone,'.$id,
            'cell_phone'    =>  'required|min:12|max:12|unique:firefighters,cell_phone,'.$id,
            'email'    =>  'required|email|unique:firefighters,email,'.$id,
            'role'    =>  'required',
        ];
        $messages = [
            'dob.required' => 'The date of birth field is required.',
            'before' => 'You must be at least 18 years old.'
        ];

        if($user->role->name == 'admin'){
            $rules['f_name'] = 'required|min:3';
            $rules['m_name'] = 'nullable|min:3';
            $rules['l_name'] = 'required|min:3';

            $messages['f_name.required'] = 'The first name field is required.';
            $messages['m_name.required'] = 'The middle name field is required.';
            $messages['l_name.required'] = 'The last name field is required.';
        }

        if($request->home_phone){
            $rules['home_phone'] = $rules['home_phone'].'|min:12';
        }

        if($request->address_title_2 || $request->address_2 || $request->state_2 || $request->city_2 || $request->zipcode_2){
            $rules['address_title_2'] = 'required|max:50';
            $rules['address_2'] = 'required|max:80';
            $rules['state_2'] = 'required|max:50';
            $rules['city_2'] = 'required|max:50';
            $rules['zipcode_2'] = 'required|max:10';
        }

        if($request->address_title_3 || $request->address_3 || $request->state_3 || $request->city_3 || $request->zipcode_3){
            $rules['address_title_3'] = 'required|max:50';
            $rules['address_3'] = 'required|max:80';
            $rules['state_3'] = 'required|max:50';
            $rules['city_3'] = 'required|max:50';
            $rules['zipcode_3'] = 'required|max:10';
        }

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['home_phone']     =    $request->home_phone ? $phone_code.$request->home_phone : null;
        $input['cell_phone']     =    $request->cell_phone ? $phone_code.$request->cell_phone : null;

        if($request->type && sizeof($request->type) && in_array('fire instructor',$request->type)){
            $rules['instructor_level'] = 'required';
        }

        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        $dates = explode('-',$request->dob);
        if(!checkdate($dates[1], $dates[2], $dates[0])){
            return response()->json(['status'=>false,'msg'=>'Invalid date entry.']);
        }

        $error = '';
        $additional_changes = [];
        $firefighter = Firefighter::find($id);
        if($user->role->name == 'admin') {
            $firefighter->name_suffix   = $request->name_suffix;
            $firefighter->f_name        = $request->f_name;
            $firefighter->m_name        = $request->m_name ? $request->m_name : '';
            $firefighter->l_name        = $request->l_name;
        }
        $firefighter->email          =    $request->email;
        $firefighter->email_2        =    $request->email_2;
        $firefighter->email_3        =    $request->email_3;
        $firefighter->comment        =    $request->comment;
        $firefighter->dob            =    $request->dob;
        $firefighter->age            =    $request->age;
        $firefighter->gender         =    $request->gender;
        $firefighter->race           =    $request->race;
        $firefighter->appointed      =    $request->appointed ? 1 : 0;
        $firefighter->instructor_level =  $request->type && sizeof($request->type) && in_array('fire instructor',$request->type) ? (int) $request->instructor_level : null;
        $firefighter->ucc            =    $request->ucc;
        $firefighter->nicet          =    $request->nicet;
        $firefighter->fema           =    $request->fema;
        $firefighter->muni           =    $request->muni;
        $firefighter->vol            =    $request->vol;
        $firefighter->car            =    $request->car;
        $firefighter->postal_mail    =    isset($request->postal_mail) ? 1 : null;

        $firefighter->address_title  =    $request->address_title;
        $firefighter->address        =    $request->address;
        $firefighter->city           =    $request->city;
        $firefighter->state          =    $request->state;
        $firefighter->zipcode        =    $request->zipcode;

        $firefighter->postal_mail_2    =    $request->postal_mail_2 ? 1 : null;
        $firefighter->address_title_2  =    $request->address_title_2 ? $request->address_title_2 : null;
        $firefighter->address_2        =    $request->address_2 ? $request->address_2 : null;
        $firefighter->city_2           =    $request->city_2 ? $request->city_2 : null;
        $firefighter->state_2          =    $request->state_2 ? $request->state_2 : null;
        $firefighter->zipcode_2        =    $request->zipcode_2 ? $request->zipcode_2 : null;

        $firefighter->postal_mail_3    =    $request->postal_mail_3 ? 1 : null;
        $firefighter->address_title_3  =    $request->address_title_3 ? $request->address_title_3 : null;
        $firefighter->address_3        =    $request->address_3 ? $request->address_3 : null;
        $firefighter->city_3           =    $request->city_3 ? $request->city_3 : null;
        $firefighter->state_3          =    $request->state_3 ? $request->state_3 : null;
        $firefighter->zipcode_3        =    $request->zipcode_3 ? $request->zipcode_3 : null;

        $firefighter->home_phone     =    $input['home_phone'];
        $firefighter->cell_phone     =    $input['cell_phone'];

        $firefighter->updated_by     =    Auth::user()->id;
        $firefighter->updated_at     =    date("Y:m:d h:i:s");

        $admins = User::where('email', '!=', $request->email)->get();
        $assigned_by = Auth::user()->name;
        $assigned_to = $request->name_suffix .' '. $request->f_name .' '. $request->l_name;
        $message = $request->role_manager_value == "yes" ? "assigned the admin access to" : 'revoked the admin access of';

        $firefighter_email = $request->email;
        $firefighter_name = $assigned_to;
        $msgForFirefighter = $request->role_manager_value == "yes" ? "assigned the admin access to you." : "revoked the admin access to you.";

        // check if yes and make an admin
        if($request->role_manager_value == "yes")
        {
            $full_name = $request->f_name.' '.$request->m_name.' '.$request->l_name;

            $user = new User();
            $user->role_id = 1;
            $user->name = $full_name;
            $user->email = $request->email;
            $user->password = Hash::make('12345678');
            $user->save();
            $user->assignRole('admin');
        }

        $heading = $request->role_manager_value == 'yes' ? 'Admin Account Credentials' : '';
        $username = $request->role_manager_value == 'yes' ? 'Email: '.$request->email : '';
        $password = $request->role_manager_value == 'yes' ? 'Password: 12345678' : '';

        // Send Acknowledgement Email to all the existing admins
        if($request->role_manager_value != $firefighter->role_manager)
        {
            dispatch(new RoleManagerAcknowledgementJob( $firefighter_email, $assigned_by, $assigned_to, $message, 'Role Manager Access Acknowledgement'));
            dispatch(new RoleManagerAcknowledgementToFirefighterJob( $firefighter_name, $firefighter_email, $assigned_by, $msgForFirefighter, $heading, $username, $password, 'Role Manager Access Acknowledgement' ));
        }

        // check if no and revoked from admin
        if($request->role_manager_value == "no" && $request->role_manager_value != $firefighter->role_manager)
        {
            $user = User::where('email', $request->email)->get()->first();
            $user->delete();
        }

        $firefighter->role              =    $request->role ? $request->role : '';
        $firefighter->role_manager      =    $request->role_manager ? 'yes' : 'no';

        $prev_object = $firefighter->getOriginal();
        $new_object = $firefighter->getAttributes();

        if(!$firefighter->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save firefighters. Please try again.']);
        }

        // Detect type change and update
        $foreign_relations = ForeignRelations::where('foreign_id',$id)->where('module','firefighters')->where('name','type')->get();
        $type_ids = [];
        $types = [];
        foreach ($foreign_relations as $foreign_relation){
            array_push($type_ids,$foreign_relation->id);
            array_push($types,$foreign_relation->value);
        }
        if( (sizeof($types)!==sizeof($request->type)) || sizeof(array_diff($types,$request->type))){
            $additional_changes[] = [
                'label'  =>  'type',
                'prev'   =>  $types,
                'new'    =>  $request->type,
            ];
            foreach ($request->type as $type){
                $foreign_relation               =   new ForeignRelations();
                $foreign_relation->foreign_id   =   $firefighter->id;
                $foreign_relation->module       =   'firefighters';
                $foreign_relation->name         =   'type';
                $foreign_relation->value        =   $type;
                if(!$foreign_relation->save()){
                    $error.="<li>Failed to save types metadata.</li>";
                }
            }
            foreach ($type_ids as $type_id){
                ForeignRelations::where('id',$type_id)->delete();
            }
        }

        // Create History
        $key_label = array(
            'f_name'        =>  'First name',
            'm_name'        =>  'Middle name',
            'l_name'        =>  'Last name',
            'dob'           =>  'Date of Birth',
            'postal_code'   => 'postal code',
            'email_verified'=> 'Reset Verification',
        );

        $response = Helper::create_history($prev_object,$new_object,$firefighter->id,'firefighters',$key_label,$additional_changes);
        if(!$response){
            $error.="<li>Failed to create update firefighter history</li>";
        }
        if($error){
            $msg = '<p>Updated firefighter. Some errors occurred are stated:</p>';
            return response()->json(array('status'=>false,'msg'=>"{$msg}<ul class='pl-4'>{$error}</ul>"));
        }else{
            return response()->json(array('status'=>true,'msg'=>'Updated Successfully !'));
        }
    }

    public function history($id) {
        $histories = History::where('foreign_id',$id)->where('module','firefighters')->orderBy('created_at','desc')->get();
        if($histories && $histories->count()){
            return view('partials.update-history')->with('histories',$histories);
        }
    }

    public function is_deletable(){
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->reverse_store_process($id);
        if(!$response){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        History::where('foreign_id',$id)->where('module','firefighters')->delete();
        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        Firefighter::where('id',$request->archive)->update(['is_archive'=>1,'archived_at'=>date('Y-m-d H:i:s'),'archived_by'=>Auth::user()->id]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));
    }

    public function unarchive(Request $request){
        Firefighter::where('id',$request->archive)->update(['is_archive'=>null,'archived_at'=>null,'archived_by'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }

    public function archive(){
        $firefighters = Firefighter::select(DB::raw('COUNT(id) as count'))->where('is_archive',1)->first();
        return view('firefighter.archive')->with('title','Firefighter')->with('firefighters',$firefighters);
    }

    public function course($id){
        $course = CourseClass::select(DB::raw('COUNT(DISTINCT course_id) as count'))->where('firefighter_id',$id)->first();
        $firefighter = Firefighter::find($id);
        return view('firefighter.course')->with('title','Firefighter Courses')->with('firefighter_id',$id)->with('firefighter',$firefighter)->with('course',$course);
    }

    public function paginate_course(Request $request,$id){
        $query = CourseClass::select('courses.prefix_id','courses.course_name','courses.id as course_id','course_classes.class_id','course_classes.firefighter_id as firefighter_id','semesters.id as semester_id','semesters.semester','semesters.year')
            ->leftJoin('courses','course_classes.course_id','=','courses.id')
            ->leftJoin('classes','course_classes.class_id','=','classes.id')
            ->leftJoin('semesters','classes.semester_id','=','semesters.id')
            ->where('course_classes.firefighter_id',$id)
            ->groupBy('courses.prefix_id');
        if($request->course_id){
            $query = $query->where('courses.prefix_id',$request->course_id);
        }
        if($request->course_name){
            $query = $query->where('courses.course_name','like',"%{$request->course_name}%");
        }

        if($request->search){
            $query = $query->where(function ($query) use ($request) {
                $query->where('courses.prefix_id','like',"%{$request->search}%")
                    ->orWhere('courses.course_name','like',"%{$request->search}%")
                    ->orWhere('semesters.semester','like',"%{$request->search}%")
                    ->orWhere('semesters.year','like',"%{$request->search}%");
            });
        }

        $per_page = Helper::per_page();
        $courses = $query->orderBy('course_classes.created_at','DESC')->paginate($per_page)->appends(request()->query());

        // dd($courses);
        return view('firefighter.paginate-course')->with('courses',$courses);
    }

    public function attendance($course_id,$id){
        $course = Course::find($course_id);
        $firefighter = Firefighter::find($id);
        if(!isset($course->id) || !isset($firefighter->id)){
            return view('404');
        }
        $course_classes = CourseClass::select(DB::raw('COUNT(id) as count'))->where('course_id',$course_id)->where('firefighter_id',$id)->limit(1)->first();
        return view('firefighter.attendance')->with('title','View Course Attendance')->with('course',$course)->with('firefighter',$firefighter)->with('course_classes',$course_classes);
    }

    public function paginate_attendance(Request $request,$course_id,$id){
        $per_page = Helper::per_page();
        $query = CourseClass::select('course_classes.class_id as id','course_classes.attendance','classes.start_date')->leftJoin('classes','course_classes.class_id','=','classes.id')->where('course_classes.course_id',$course_id)->where('course_classes.firefighter_id',$id);
        if($request->class_id){
            $query = $query->where('course_classes.class_id',$request->class_id);
        }
        if($request->start_date){
            $query = $query->where('classes.start_date',$request->start_date);
        }
        if($request->attendance){
            $query = $query->where('course_classes.attendance',$request->attendance);
        }

        $course_classes = $query->orderBy('course_classes.created_at','DESC')->paginate($per_page)->appends(request()->query());
        return view('firefighter.paginate-attendance')->with('course_id',$course_id)->with('firefighter_id',$id)->with('course_classes',$course_classes);
    }

    public function update_attendance(Request $request,$course_id,$id){

        if(!$request->attendance || empty($request->attendance)){
            return response()->json(array('status'=>false,'msg'=>'Missing Params'));
        }

        $class_ids = [];
        foreach ($request->attendance as $class_id=>$attendance){
            if(!$class_id || !$attendance){
                return response()->json(array('status'=>false,'msg'=>'Missing Params'));
            }
            $class_ids[] = $class_id;
        }

        $per_page = Helper::per_page();
        $temps = CourseClass::where('course_id',$course_id)->where('firefighter_id',$id)->whereIn('class_id',$class_ids)->limit($per_page)->get();
        foreach ($temps as $temp){
            $course_classes[$temp->class_id] = $temp;
        }
        foreach ($request->attendance as $class_id=>$attendance){
            // Detect change in attendance
            $additional_changes = [];
            if($course_classes[$class_id]->attendance !== $attendance){
                $additional_changes[] = [
                    'label'         =>  'attendance',
                    'prev'          =>  $course_classes[$class_id]->attendance,
                    'new'           =>  $attendance,
                    'class'         =>  $class_id,
                    'firefighter'   =>  $id,
                ];
                Helper::create_history(null,null,$course_classes[$class_id]->id,'course_classes',null,$additional_changes);
            }
            CourseClass::where('course_id',$course_id)->where('firefighter_id',$id)->where('class_id',$class_id)->update(['attendance'=>$attendance]);
        }
        return response()->json(array('status'=>true,'msg'=>'Updated Successfully !'));

    }

    public function process_transcript(Request $request,$firefighter_id,$semester_id,$course_id,$code){

        if(!in_array($code,['R1','R2','X1','X2'])){
            return response()->json(array('status'=>false,'msg'=>'Invalid Request. Unknown transcript code.'));
        }

        // Validate if is semester completed
        $semester = Semester::find($semester_id);
        $is_semester_completed = \App\Http\Helpers\Helper::is_semester_completed($semester->semester,$semester->year);
        if(!$is_semester_completed)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request. Semester is not completed.'));

        // Validate if course is already completed
        $is_course_completed = \App\Http\Helpers\FirefighterHelper::is_course_completed($firefighter_id,$semester_id,$course_id);
        if(!$is_course_completed)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request. Course already completed.'));

        // Validate if candidate meets minimum attendance
        $total_classes = \App\Http\Helpers\Helper::total_classes($semester_id,$course_id,$firefighter_id);
        $attended_classes = \App\Http\Helpers\Helper::get_attended_classes($semester_id,$course_id,$firefighter_id);
        $min_attendance = \App\Http\Helpers\Helper::get_min_attendance_perc();
        $attendance = $total_classes && $attended_classes ? number_format(($attended_classes/$total_classes)*100,0) : 0;
        if($min_attendance>$attendance){
            return response()->json(array('status'=>false,'msg'=>'Invalid Request. Incomplete attendance.'));
        }

        $firefighter = Firefighter::find($firefighter_id);
        if(!isset($firefighter->id) || !$firefighter->id)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request. Firefighter not found.'));

        $course = Course::find($course_id);
        if(!isset($course->id) || !$course->id)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request. Course not found.'));

        $data = array(
            'title'           =>  config('app.name'),
            'firefighter'     =>  $firefighter,
            'course'          =>  $course,
            'code'            =>  $code,
            'print_date'      =>  Helper::date_format(date('Y-m-d')),
        );

        $pdf = PDF::loadView('firefighter.transcript', $data);
        $attachment = $pdf->output();
        if($request->send_email){
            Mail::to($firefighter->email)->send(new SendTranscript($firefighter,$course,$attachment));
            CompletedCourse::where('firefighter_id',$firefighter_id)->where('semester_id',$semester_id)->where('course_id',$course_id)->update(['transcript_sent'=>1,'issue_date'=>date('Y-m-d')]);
            return response()->json(array('status'=>true,'msg'=>'Transcript Sent Successfully !'));
        }else{
            return $pdf->stream();
        }
    }
    public function certificate_history()
    {
        $user = Auth::guard('firefighters')->user()->id;
    // $post  = certificatehistory::where('firefighter_id',$user)->get();
    // $certificate  = Certificate::where('id',$user)->get();
    $post= certificatehistory::with('certificate')->where('firefighter_id',$user)->get();
   
    
        return Response::json($post);
    }
    public function certifications($firefighter_id){
        $firefighter = Firefighter::find($firefighter_id);
        if($firefighter && $firefighter->count()){
            // $awarded_certificates = AwardCertificate::select(DB::raw('COUNT(DISTINCT certificate_id) as count'))->where('firefighter_id',$firefighter_id)->first();
              $awarded_certificates = AwardCertificate::where('firefighter_id',$firefighter_id)->first();

            return view('firefighter.certifications')->with('title','Certifications')->with('awarded_certificates',$awarded_certificates)->with('firefighter',$firefighter);
        }else{
            return view('404');
        }
    }

    public function paginate_certifications(Request $request,$firefighter_id){
        $per_page = Helper::per_page();
        $query = AwardCertificate::select('awarded_certificates.*','firefighters.f_name','firefighters.m_name','firefighters.l_name','certifications.prefix_id','firefighters.email','certifications.title','certifications.renewable','certifications.certification_cycle_start','certifications.certification_cycle_end','certifications.renewed_expiry_date')
            ->leftJoin('certifications','awarded_certificates.certificate_id','=','certifications.id')
            ->leftJoin('firefighters','awarded_certificates.firefighter_id','=','firefighters.id')
            ->where('awarded_certificates.firefighter_id',$firefighter_id)
            ->where('awarded_certificates.stage','initial');

        if($request->prefix_id){
            $query = $query->having('prefix_id','like',"%{$request->prefix_id}%");
        }
        if($request->title){
            $query = $query->having('title','like',"%{$request->title}%");
        }
        if($request->receiving_date){
            $query = $query->where('awarded_certificates.receiving_date',$request->receiving_date);
        }
        if($request->issue_date){
            $query = $query->where('awarded_certificates.issue_date',$request->issue_date);
        }
        if($request->lapse_date){
            $query = $query->where('awarded_certificates.lapse_date',$request->lapse_date);
        }
        $awarded_certificates = $query->groupBy('awarded_certificates.certificate_id')->orderBy('awarded_certificates.created_at','DESC')->paginate($per_page)->appends(request()->query());
        foreach ($awarded_certificates as $key=>$awarded_certificate){
            $latest_certificate = AwardCertificate::where('firefighter_id',$firefighter_id)->where('certificate_id',$awarded_certificate->certificate_id)->orderBy('id','DESC')->limit(1)->first();
            $awarded_certificates[$key]->id = $latest_certificate->id;
            $awarded_certificates[$key]->stage = $latest_certificate->stage;
            $awarded_certificates[$key]->receiving_date = $latest_certificate->receiving_date;
            $awarded_certificates[$key]->issue_date = $latest_certificate->issue_date;
            $awarded_certificates[$key]->lapse_date = $latest_certificate->lapse_date;
            $awarded_certificates[$key]->created_at = $latest_certificate->created_at;
        }
//        dd($awarded_certificates);
        return view('firefighter.paginate-certifications')->with('awarded_certificates',$awarded_certificates);
    }


    public function edit_lapse($id)
    {
        $post  = AwardCertificate::find($id);
 
        return Response::json($post);
    }


    public function update_lapse(Request $request)
    {
     $post  = AwardCertificate::find($request->post_id);
     $post->lapse_date = $request->lapse;
     $post->save();
     return Response::json($post);
    //  SweetAlert::message('Lapse Date Updated Successfully');
    //  return back();
    }

    public function manual_ceus(Request $request)
    {
        DB::table('class_firefighter')->insert([
    
            'firefighter_id' => $request->post_id,
            'admin_ceu' => $request->admin,
            'tech_ceu' => $request->tech,
            'created_at' => date('Y-m-d h:i:s'),
        ]);

        // $firefighter_classes = DB::table('class_firefighter')->where('firefighter_id',$request->post_id)->get();

        $firefighter_classes_adminceu = DB::table('class_firefighter')->where('firefighter_id',$request->post_id)->sum('admin_ceu');

     return Response::json($firefighter_classes_adminceu);
    //  SweetAlert::message('Lapse Date Updated Successfully');
    //  return back();
    }
 



    public function view_certification($firefighter_id,$certificate_id){
        $firefighter = Firefighter::find($firefighter_id);
        if(!isset($firefighter->id) || !$firefighter->id)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request'));

        // Using primary key as certificate ID
        $awarded_certificate = AwardCertificate::where('firefighter_id',$firefighter_id)->where('id',$certificate_id)->limit(1)->first();
        if(!isset($awarded_certificate->id) || !$awarded_certificate->id)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request'));

        $certificate = Certification::find($awarded_certificate->certificate_id);
        if(!isset($certificate->id) || !$certificate->id)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request'));

        $data = array(
            'title'           =>  config('app.name'),
            'firefighter'     =>  $firefighter,
            'certificate'     =>  $certificate,
            'issue_date'      =>  Helper::date_format($awarded_certificate->issue_date),
            'lapse_date'      =>  $awarded_certificate->lapse_date ? Helper::date_format($awarded_certificate->lapse_date) : null,
        );

        $pdf = PDF::loadView('firefighter.awarded-certificate', $data);
        return $pdf->stream();
    }

    public function renew_certification(Request $request,$id){
        $awarded_certificate = AwardCertificate::find($id);
        if(!isset($awarded_certificate->id) || !$awarded_certificate->id){
            return response()->json(array('status'=>false,'msg'=>'Invalid Request 1','id'=>$id));
        }

        $certificate = Certification::find($awarded_certificate->certificate_id);
        if(!isset($certificate->id) || !$certificate->id || !$certificate->renewable || $awarded_certificate->lapse_date > carbon::now()->toDateString()){
            return response()->json(array('status'=>false,'msg'=>'Invalid Request 2'));
        }

        if(isset($certificate->id) && $certificate->renewed_expiry_date == null)
        {
            return response()->json([ 'status' => false, 'msg' => 'This credential is expired. You need to renewed the credentials first from credentials module.' ]);
        }

        $new_awarded_certificate = new AwardCertificate();
        $new_awarded_certificate->certificate_id = $awarded_certificate->certificate_id;
        $new_awarded_certificate->firefighter_id = $awarded_certificate->firefighter_id;
        $new_awarded_certificate->organization_id = $awarded_certificate->organization_id;
        $new_awarded_certificate->stage = 'renewal';
        $new_awarded_certificate->issue_date = carbon::now()->toDateString();
        /*$new_awarded_certificate->lapse_date = date('Y-m-d',strtotime($certificate->renewal_period));*/
        /*$new_awarded_certificate->lapse_date = Carbon::now()->addYear($certificate->renewal_period)->format('Y-m-d');*/
        /*$new_awarded_certificate->lapse_date = Carbon::createFromFormat('Y-m-d', $awarded_certificate->lapse_date)->addYear($certificate->renewal_period);*/
        /*$new_awarded_certificate->lapse_date = FirefighterHelper::generate_new_lapse_date($awarded_certificate->lapse_date, $certificate->renewal_period);*/
        $new_awarded_certificate->lapse_date = FirefighterHelper::generate_new_lapse_date($certificate->id);
        if($new_awarded_certificate->save()){
            if($request->send_email){
                $firefighter = Firefighter::find($awarded_certificate->firefighter_id);
                $data = array(
                    'title'           =>  config('app.name'),
                    'firefighter'     =>  $firefighter,
                    'certificate'     =>  $certificate,
                    'issue_date'      =>  Helper::date_format($new_awarded_certificate->issue_date),
                    'lapse_date'      =>  $new_awarded_certificate->lapse_date ? Helper::date_format($new_awarded_certificate->lapse_date) : null,
                );

                $update_certificate_status = DB::table('certificate_statuses')
                    ->where('firefighter_certificates_id', $awarded_certificate->certificate_id)
                    ->where('firefighter_id', $awarded_certificate->firefighter_id)
                    ->get()
                    ->first();
                 /*dd($update_certificate_status);*/

                $pdf = PDF::loadView('firefighter.awarded-certificate', $data);
                $attachment = $pdf->output();
                Mail::to($firefighter->email)->send(new SendCertificate($firefighter, $certificate, $update_certificate_status, $attachment));
            }
            return response()->json(array('status'=>true,'msg'=>'Certificate Renewed Successfully !'));
        }
        return response()->json(array('status'=>false,'msg'=>'Something went wrong.'));
    }

    public function bulk_renew_cert(Request $request)
    {

  if(isset(request()->cert_ids) && !empty(request()->cert_ids))
 {
                $errors = [];
                $errors2 = [];
foreach(request()->cert_ids as $id)
 {
     
$awarded_certificate = AwardCertificate::where('id', $id)->where('lapse_date', '<=', carbon::now()->toDateString())->get()->first();

 if(isset($awarded_certificate) && !empty($awarded_certificate)) 
 {
$certificate = Certification::find($awarded_certificate->certificate_id);
  

// $class_fighter_adminceu = DB::table('class_firefighter')
// ->whereBetween('firefighter_id',$awarded_certificate->firefighter_id)->sum('admin_ceu');

// $class_fighter_techceu = DB::table('class_firefighter')
// ->where('firefighter_id',$awarded_certificate->firefighter_id)->sum('tech_ceu');

$class_fighter_date = DB::table('class_firefighter')
->where('firefighter_id',$awarded_certificate->firefighter_id)->get();



// // newif
// if($class_fighter_adminceu>=$certificate->admin_ceu && $class_fighter_techceu>=$certificate->tech_ceu)
// {
// dateloop
$valueSumadmin = 0;
$valueSumtech = 0;
$admin_ceuu = array();
$tech_ceuu = array();

    foreach($class_fighter_date as $date)
    {
        

$admin_ceuu = DB::table('class_firefighter')->whereBetween('created_at',[$certificate->certification_cycle_start,$certificate->certification_cycle_end])->first();

if($admin_ceuu!=null)
{
$valueSumadmin += $admin_ceuu->admin_ceu;
}




$tech_ceuu = DB::table('class_firefighter')->whereBetween('created_at',[$certificate->certification_cycle_start,$certificate->certification_cycle_end])->first();

if($tech_ceuu!=null)
{
    $valueSumtech += $tech_ceuu->tech_ceu;
}


    }

    if($valueSumadmin>=$certificate->admin_ceu && $valueSumtech>=$certificate->tech_ceu)
    {

    

    // start datecycle if
// if ($date->created_at > $certificate->certification_cycle_start && $date->created_at < $certificate->certification_cycle_end)
// {


if ($certificate->renewed_expiry_date != null || Carbon::parse($certificate->renewed_expiry_date)->gt(Carbon::now()))
 {
     $new_awarded_certificate = new AwardCertificate();
     $new_awarded_certificate->certificate_id = $awarded_certificate->certificate_id;
 $new_awarded_certificate->firefighter_id = $awarded_certificate->firefighter_id;
  $new_awarded_certificate->organization_id = $awarded_certificate->organization_id;
 $new_awarded_certificate->stage = 'renewal';  $new_awarded_certificate->issue_date = date('Y-m-d');
$new_awarded_certificate->lapse_date = FirefighterHelper::generate_new_lapse_date($certificate->id);
 $new_awarded_certificate->save();


   // history table
   $history  = new certificatehistory();
   $history->firefighter_id = $awarded_certificate->firefighter_id;

   $history->certificate_id = $awarded_certificate->certificate_id;
   $admin = Auth::user()->id;
   $checkadmin_name=User::find($admin)->name;
   $history->operation = 'Credential Renewed By '.$checkadmin_name;
   $history->date =  carbon::now()->toDateString();
   $history->save();



 if(request()->send_email)
 {
     $firefighter = Firefighter::find($awarded_certificate->firefighter_id);
        $data = array(
      'title'           =>  config('app.name'),
   'firefighter'     =>  $firefighter,
  'certificate'     =>  $certificate,
'issue_date'      =>  Helper::date_format($new_awarded_certificate->issue_date),
'lapse_date'      =>  $new_awarded_certificate->lapse_date ? Helper::date_format($new_awarded_certificate->lapse_date) : null,
                                );

$update_certificate_status = DB::table('certificate_statuses')
                                    ->where('firefighter_certificates_id', $awarded_certificate->certificate_id)
                                    ->where('firefighter_id', $awarded_certificate->firefighter_id)
                                    ->get()
                                    ->first();

$pdf = PDF::loadView('firefighter.awarded-certificate', $data);
$attachment = $pdf->output();
 Mail::to($firefighter->email)->send(new SendCertificate($firefighter, $certificate, $update_certificate_status, $attachment));
                            }
                        }
 else 
 {
 $errors[] =$date->created_at;
}

 }


 else 
 {
$certificate_name = Certification::find($awarded_certificate->certificate_id);
$errors2[] =$certificate_name->title;
 }





// }
// end datecycle if

// else 
// {
// $errors2[] = $awarded_certificate->certificate_id;

// return response()->json([ 'status' => true, 'msg' => 'Bulk renewal completed successfully.', 'errorIfAny2' => $errors2 ]);

// }




// enddateloop

//  } 
//  newifend
// else 
// {
// $errors[] = "NOT ELIGIBLE".$awarded_certificate->certificate_id;
// }

                    }

                }
if($errors2==null)
{
    return response()->json([ 'status' => true, 'msg' => 'Bulk renewal completed successfully.', 'errorIfAny' => $errors ]);
}

else 
{
    return response()->json([ 'status' => true, 'msg' => 'some credential cannot renewel.', 'errorIfAny2' => $errors2 ]);
}


    
            }
            else
             {
                return response()->json([ 'status' => false, 'msg' => 'Something went wrong. Try again!' ]);
             }
     
    }

    public function certifications_past_records($firefighter_id,$certificate_id){
        $firefighter = Firefighter::find($firefighter_id);
        $certification = Certification::find($certificate_id);

        if(!$firefighter || !$firefighter->count() || !$certification || !$certification->count())
            return view('404');

        $awarded_certificates = AwardCertificate::select(DB::raw('COUNT(id) as count'))->where('firefighter_id',$firefighter_id)->where('certificate_id',$certificate_id)->first();
        return view('firefighter.certifications-past-records')->with('title','Certification History')->with('awarded_certificates',$awarded_certificates)->with('firefighter',$firefighter)->with('certification',$certification);
    }

    public function paginate_certifications_past_records($firefighter_id,$certificate_id){
        $per_page = Helper::per_page();
        $awarded_certificates = AwardCertificate::select('awarded_certificates.*','certifications.title','certifications.prefix_id','certifications.renewable')->leftJoin('certifications','awarded_certificates.certificate_id','=','certifications.id')->where('awarded_certificates.firefighter_id',$firefighter_id)->where('awarded_certificates.certificate_id',$certificate_id)->orderBy('awarded_certificates.created_at','DESC')->paginate($per_page);
        return view('firefighter.paginate-certifications-past-records')->with('awarded_certificates',$awarded_certificates);
    }

    public function get_municode(Request $request)
    {
        $municode = DB::table('municodes')
            ->where('zipcode',$request->zipcode)
            ->first();
        return json_encode($municode);
    }

    public function change_role(Request $request)
    {
        $firefighter = Firefighter::where('id', $request->firefighter_id)->get()->first();
        $admins = User::where('email', '!=', $firefighter->email)->get();

        $assigned_by = Auth::user()->name;
        $assigned_to = $firefighter->name_suffix .' '. $firefighter->f_name .' '. $firefighter->l_name;
        $message = $request->role == "admin" ? "assigned the admin access to" : 'revoked the admin access of';

        $firefighter_email = $firefighter->email;
        $firefighter_name = $assigned_to;
        $msgForFirefighter = $request->role == "admin" ? "assigned the admin access to you." : "revoked the admin access to you.";

        $heading = $request->role == "admin" ? 'Admin Account Credentials' : null;
        $username = $request->role == "admin" ? 'Email: '.$firefighter->email : null;
        $password = $request->role == "admin" ? 'Password: 12345678' : null;

        // assign admin access
        if($request->role == "admin"){
            $full_name = $firefighter->f_name.' '.$firefighter->m_name.' '.$firefighter->l_name;

            $user = new User();
            $user->role_id = 1;
            $user->name = $full_name;
            $user->email = $firefighter->email;
            $user->password = Hash::make('12345678');
            $user->save();
            $user->assignRole('admin');
        }

        // revoke admin access
        if($request->role == "student")
        {
            $user = User::where('email', $firefighter->email)->get()->first();
            $user->delete();
        }

        $firefighter->role_manager =  $request->role == "admin" ? 'yes' : 'no';

        if(!$firefighter->save()){
            return response()->json([ 'status' => false, 'msg' => 'Failed to update the access. Please try again.']);
        }

        // Send Acknowledgement Email to the firefighter
        dispatch(new RoleManagerAcknowledgementToFirefighterJob( $firefighter_name, $firefighter_email, $assigned_by, $msgForFirefighter, $heading, $username, $password, 'Role Manager Access Acknowledgement' ));

        // Send Acknowledgement Email to all the existing admins
        dispatch(new RoleManagerAcknowledgementJob( $firefighter_email, $assigned_by, $assigned_to, $message, 'Role Manager Access Acknowledgement'));

        return response()->json(array( 'status' => true, 'msg' => 'Updated Successfully!'));
    }
}
