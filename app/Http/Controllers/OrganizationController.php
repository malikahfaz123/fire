<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Helper;
use App\Http\Helpers\OrganizationHelper;
use App\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::select(DB::raw('COUNT(id) as count'))->first();
        return view('organization.index')->with('title','Organizations')->with('organizations',$organizations);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = $request->is_archive ? Organization::where('is_archive',1) : Organization::whereNull('is_archive');
        $query = Helper::filter('organizations',$request->all(),$query,['admin_ceu','tech_ceu','nfpa_std','no_of_organizations','is_archive','created_by','created_at','updated_at']);
        if($query){
            $organizations = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $organizations = Organization::orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }
        if($request->is_archive){
            return view('organization.paginate-archive')->with('organizations',$organizations);
        }
        return view('organization.paginate')->with('organizations',$organizations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('organization.create', ['title' => 'Add Organization']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'country_municipal_code'    =>  'required',
            'name'    =>  'required',
            'type'    =>  'required',
            'phone'   =>  'nullable|max:12|unique:organizations',
            'auth_sign_phone'    =>  'required|max:12|unique:organizations',
            'chief_dir_email'    =>  'required|email|unique:organizations',
        ];

        // if($request->phone){
        //     $rules['phone'] = $rules['phone'].'|min:12';
        // }

        // if($request->email_2){
        //     $rules['email_2'] = 'email|different:email';
        // }

        // if($request->email_3){
        //     $rules['email_3'] = 'email|different:email,email_2';
        // }

        // if($request->chief_dir_email_2){
        //     $rules['chief_dir_email_2'] = 'chief_dir_email|different:chief_dir_email';
        // }

        // if($request->chief_dir_email_3){
        //     $rules['chief_dir_email_3'] = 'chief_dir_email|different:chief_dir_email,chief_dir_email_2';
        // }

        // if($request->auth_sign_email_2){
        //     $rules['auth_sign_email_2'] = 'auth_sign_email|different:auth_sign_email';
        // }

        // if($request->auth_sign_email_3){
        //     $rules['auth_sign_email_3'] = 'auth_sign_email|different:auth_sign_email,auth_sign_email_3';
        // }

        $messages['country_municipal_code.required'] = 'The country/municipal code field is required.';

        // $messages['email_2.email'] = 'The second email must be a valid email address.';
        // $messages['email_2.different'] = 'The second email must be different email address.';
        // $messages['email_3.different'] = 'The third email must be different email address.';

        if(strtolower($request->type) =='other'){
            $rules['other_type'] = 'required';
        }

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['phone']             =    $request->phone ? $phone_code.$request->phone : null;

        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        $organization = new Organization();
        $organization->country_municipal_code   =    $request->country_municipal_code;
        $organization->name                     =    $request->name;

        $organization->chief_dir_name           =    $request->chief_dir_name;
        $organization->auth_sign_name           =    $request->auth_sign_name;

        $organization->chief_dir_email          =    $request->chief_dir_email;
        $organization->chief_dir_email_2        =    !empty($request->chief_dir_email_2) ? $request->chief_dir_email_2 : "";
        $organization->chief_dir_email_3        =    !empty($request->chief_dir_email_3) ? $request->chief_dir_email_3 : "";

        $organization->auth_sign_email          =    $request->auth_sign_email;
        $organization->auth_sign_email_2        =    !empty($request->auth_sign_email_2) ? $request->auth_sign_email_2 : "";
        $organization->auth_sign_email_3        =    !empty($request->auth_sign_email_3) ? $request->auth_sign_email_3 : "";

        $organization->type                     =    $request->type;
        $organization->other_type               =    $request->other_type;
        $organization->phone                    =    $input['phone'];
        // $organization->fax                   =    $request->fax;
        // $organization->signator              =    $request->signator;

        $organization->auth_sign_phone          =    $input['auth_sign_phone'];
        $organization->chief_dir_phone          =    $input['chief_dir_phone'];

        $organization->mail_address             =    $request->mail_address;
        $organization->mail_municipality        =    $request->mail_municipality;
        $organization->mail_state               =    $request->mail_state;
        $organization->mail_zipcode             =    $request->mail_zipcode;
        $organization->physical_address         =    $request->physical_address;
        $organization->physical_municipality    =    $request->physical_municipality;
        $organization->physical_state           =    $request->physical_state;
        $organization->physical_zipcode         =    $request->physical_zipcode;
        $organization->comment                  =    $request->comment;

        if(!$organization->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save organization. Please try again.']);
        }

        // Update Prefix ID
        $response = Organization::where('id',$organization->id)->update(['prefix_id'=>OrganizationHelper::prefix_id($organization->id)]);
        if(!$response){
            $this->reverse_store_process($organization->id);
            return response()->json(array('status'=>false,'msg'=>'Something went wrong while updating prefix id.'));
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);

    }

    public function reverse_store_process($id){
        return Organization::where('id',$id)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $organization = Organization::find($id);
        if($organization && $organization->count() && !$organization->is_archive){
            return view('organization.show')->with('title','View Organization')->with('organization',$organization);
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
        $organization = Organization::find($id);
        if($organization && $organization->count() && !$organization->is_archive){
            return view('organization.edit')->with('title','Edit Organization')->with('organization',$organization);
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
        $rules = [
            'country_municipal_code'    =>  'required',
            'name'    =>  'required',
            'type'    =>  'required',
            'phone'   =>  'nullable|max:12|unique:organizations,phone,'.$id,
        ];

        if($request->phone){
            $rules['phone'] = $rules['phone'].'|min:12';
        }


        // if($request->email_2){
        //     $rules['email_2'] = 'email|different:email';
        // }

        // if($request->email_3){
        //     $rules['email_3'] = 'email|different:email,email_2';
        // }

        $messages['country_municipal_code.required'] = 'The country/municipal code field is required.';
        // $messages['email_2.email'] = 'The second email must be a valid email address.';
        // $messages['email_2.different'] = 'The second email must be different email address.';
        // $messages['email_3.different'] = 'The third email must be different email address.';

        if(strtolower($request->type) =='other'){
            $rules['other_type'] = 'required';
        }

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['phone']             =    $request->phone ? $phone_code.$request->phone : null;

        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        $organization = Organization::find($id);
        $organization->country_municipal_code   =    $request->country_municipal_code;
        $organization->name                     =    $request->name;

        $organization->chief_dir_name           =    $request->chief_dir_name;
        $organization->auth_sign_name           =    $request->auth_sign_name;

        $organization->chief_dir_email          =    $request->chief_dir_email;
        $organization->chief_dir_email_2        =    !empty($request->chief_dir_email_2) ? $request->chief_dir_email_2 : "";
        $organization->chief_dir_email_3        =    !empty($request->chief_dir_email_3) ? $request->chief_dir_email_3 : "";

        $organization->auth_sign_email          =    $request->auth_sign_email;
        $organization->auth_sign_email_2        =    !empty($request->auth_sign_email_2) ? $request->auth_sign_email_2 : "";
        $organization->auth_sign_email_3        =    !empty($request->auth_sign_email_3) ? $request->auth_sign_email_3 : "";

        $organization->type                     =    $request->type;
        $organization->other_type               =    $request->other_type;
        $organization->phone                    =    $input['phone'];

        // $organization->fax                      =    $request->fax;
        // $organization->signator                 =    $request->signator;

        $organization->auth_sign_phone          =    $input['auth_sign_phone'];
        $organization->chief_dir_phone          =    $input['chief_dir_phone'];

        $organization->mail_address             =    $request->mail_address;
        $organization->mail_municipality        =    $request->mail_municipality;
        $organization->mail_state               =    $request->mail_state;
        $organization->mail_zipcode             =    $request->mail_zipcode;
        $organization->physical_address         =    $request->physical_address;
        $organization->physical_municipality    =    $request->physical_municipality;
        $organization->physical_state           =    $request->physical_state;
        $organization->physical_zipcode         =    $request->physical_zipcode;
        $organization->comment                  =    $request->comment;

        if(!$organization->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save organization. Please try again.']);
        }

        return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
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

        try{
            $response = Organization::where('id',$id)->delete();
        }catch (\Exception $error){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        if(!$response){
            return response()->json(['status'=>false,'msg'=>'Failed to delete organization. Please try again.']);
        }

        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        if(!$this->is_deletable())
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);

        Organization::where('id',$request->archive)->update(['is_archive'=>1,'archived_at'=>date('Y-m-d H:i:s'),'archived_by'=>Auth::user()->id]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));

    }

    public function unarchive(Request $request){
        Organization::where('id',$request->archive)->update(['is_archive'=>null,'archived_at'=>null,'archived_by'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }

    public function archive(){
        $organizations = Organization::select(DB::raw('COUNT(id) as count'))->where('is_archive',1)->first();
        return view('organization.archive')->with('title','Archived Organizations')->with('organizations',$organizations);
    }
}
