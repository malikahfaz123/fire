<?php

namespace App\Http\Controllers;

use App\Facility;
use App\FacilityType;
use App\ForeignRelations;
use App\Http\Helpers\FacilityHelper;
use App\Http\Helpers\Helper;
use App\Http\Middleware\EncryptCookies;
use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facilities = Facility::select(DB::raw('COUNT(id) as count'))->whereNull('is_archive')->first();
        return view('facility.index')->with('title','Facility')->with('facilities',$facilities);
    }

    public function paginate(Request $request)
    {
        $per_page = Helper::per_page();
        $query = $request->is_archive ? Facility::where('is_archive',1) : Facility::whereNull('is_archive');
        if($request->type){
            $foreign_relations = ForeignRelations::where('module','facilities')->where('name','type')->whereIn('value',$request->type)->get();
            $ids = [];
            foreach ($foreign_relations as $foreign_relation){
                if(!in_array($foreign_relation->foreign_id,$ids)){
                    array_push($ids,$foreign_relation->foreign_id);
                }
            }
            $query = $query->whereIn('id',$ids);
        }

        $query = Helper::filter('facilities',$request->all(),$query,'');
        $facilities = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());

        if($request->is_archive){
            return view('facility.paginate-archive')->with('facilities',$facilities);
        }
        return view('facility.paginate')->with('facilities',$facilities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::select('id','name','prefix_id')->get();
        $facility_types = FacilityType::select('id','description')->get();

        return view('facility.create', ['title' => 'Add Facility'])->with('organizations',$organizations)->with('facility_types',$facility_types);
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
            'category'    =>  'required',
            'country_municipal_code'    =>  'required',
            'name'    =>  'required',
            'type'    =>  'required',
            'status'    =>  'required',
            'contact_person_phone'    =>  'nullable|max:12|unique:facilities',
            'live_burn_permit'    =>  'required',
        ];

        if($request->contact_person_phone){
            $rules['contact_person_phone'] = $rules['contact_person_phone'].'|min:12';
        }

        $messages['country_municipal_code.required'] = 'The country/municipal code field is required.';

        if($request->category && strtolower($request->category)==='temporary'){
            $rules['organization'] = 'required|numeric';
            $rules['vacancy_status'] = 'required';
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
            $rules['signator'] = 'required';
            $rules['signator_phone'] = 'required';
        }

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['contact_person_phone']             =    $request->contact_person_phone ? $phone_code.$request->contact_person_phone : null;

        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        //  Facility Information
        $facility = new Facility();
        $facility->category = $request->category;
        $facility->country_municipal_code = $request->country_municipal_code;
        $facility->name = $request->name;
        $facility->status = $request->status;
        $facility->live_burn_permit = $request->live_burn_permit;
        $facility->lapse_date = $request->lapse_date;
        $facility->tier = $request->tier;
        if($request->category && strtolower($request->category)==='temporary'){
            $facility->organization = (int) $request->organization;
            $facility->vacancy_status = $request->vacancy_status;
            $facility->start_date = $request->start_date;
            $facility->end_date = $request->end_date;
            $facility->signator = $request->signator;
            $facility->signator_phone = $request->signator_phone;
        }

        // Address Details
        $facility->mail_address             =    $request->mail_address;
        $facility->mail_municipality        =    $request->mail_municipality;
        $facility->mail_state               =    $request->mail_state;
        $facility->mail_zipcode             =    $request->mail_zipcode;
        $facility->physical_address         =    $request->physical_address;
        $facility->physical_municipality    =    $request->physical_municipality;
        $facility->physical_state           =    $request->physical_state;
        $facility->physical_zipcode         =    $request->physical_zipcode;
        $facility->comment                  =    $request->comment;

        // Related Personnel
        if($request->category && strtolower($request->category)==='permanent'){
            $facility->owner_name               =    $request->owner_name;
            $facility->owner_address            =    $request->owner_address;
            $facility->owner_city               =    $request->owner_city;
            $facility->owner_state              =    $request->owner_state;
            $facility->owner_zipcode            =    $request->owner_zipcode;
            $facility->contact_person_name      =    $request->contact_person_name;
            $facility->contact_person_phone     =    $input['contact_person_phone'];
            $facility->representative_name      =    $request->representative_name;
            $facility->representative_phone     =    $request->representative_phone;
        }
        $facility->created_by               =    Auth::user()->id;

        if(!$facility->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save facility. Please try again.']);
        }

        // Update Prefix ID
        $response = Facility::where('id',$facility->id)->update(['prefix_id'=>FacilityHelper::prefix_id($facility->id)]);
        if(!$response){
            $this->reverse_store_process($facility->id);
            return response()->json(array('status'=>false,'msg'=>'Something went wrong while updating prefix id.'));
        }

        foreach ($request->type as $type){
            $foreign_relation               =   new ForeignRelations();
            $foreign_relation->foreign_id   =   $facility->id;
            $foreign_relation->module       =   'facilities';
            $foreign_relation->name         =   'facility_type';
            $foreign_relation->value        =   $type;
            if(!$foreign_relation->save()){
                $this->reverse_store_process($facility->id);
                return response()->json(['status'=>false,'msg'=>'Failed to save types metadata. Please try again.']);
            }
        }
        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);

    }

    public function reverse_store_process($id){
        $response = Facility::where('id',$id)->delete();
        if($response){
            ForeignRelations::where('foreign_id',$id)->where('module','facilities')->delete();
            return true;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $facility = Facility::find($id);
        if($facility && $facility->count()){
            $types = FacilityHelper::get_type($id);
            $organization = [];
            if($facility->category === 'temporary'){
                $temp = Organization::select('prefix_id','name')->where('id',$facility->organization)->limit(1)->first();
                $organization[$facility->organization] = "{$temp->name} ({$temp->prefix_id})";
            }

            // show all organization
            $all_organizations = Organization::select('id','prefix_id','name')->get();

            // show all facility type
            $all_facility_types = FacilityType::select('id','description')->get();

            $facility_types = [];
            if($all_facility_types && $all_facility_types->count()){
                foreach ($all_facility_types as $all_facility_type){
                    $facility_types[$all_facility_type->id] = "$all_facility_type->description";
                }
            }
            
            return view('facility.show')
                ->with('title', ucwords($facility->name))->with('facility',$facility)->with('types',$types)->with('facility_types',$facility_types)->with('organization',$organization)->with('all_organizations',$all_organizations);
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
        $facility = Facility::find($id);
        if($facility && $facility->count()){
            $types = FacilityHelper::get_type($id);
            $organization = [];
            if($facility->category === 'temporary'){
                $temp = Organization::select('prefix_id','name')->where('id',$facility->organization)->limit(1)->first();
                $organization[$facility->organization] = "{$temp->name} ({$temp->prefix_id})";
            }

            // show all organization
            $all_organizations = Organization::select('id','prefix_id','name')->get();

            // show all facility type
            $all_facility_types = FacilityType::select('id','description')->get();

            $facility_types = [];
            if($all_facility_types && $all_facility_types->count()){
                foreach ($all_facility_types as $all_facility_type){
                    $facility_types[$all_facility_type->id] = "$all_facility_type->description";
                }
            }

            return view('facility.edit')
                ->with('title', 'Edit Facility')
                ->with('facility',$facility)
                ->with('types',$types)
                ->with('facility_types',$facility_types)
                ->with('organization',$organization)
                ->with('all_organizations',$all_organizations);
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
            'category'    =>  'required',
            'country_municipal_code'    =>  'required',
            'name'    =>  'required',
            'type'    =>  'required',
            'status'    =>  'required',
            'contact_person_phone'    =>  'nullable|max:12|unique:facilities,contact_person_phone,'.$id,
            'live_burn_permit'    =>  'required',
        ];

        if($request->contact_person_phone){
            $rules['contact_person_phone'] = $rules['contact_person_phone'].'|min:12';
        }

        $messages['country_municipal_code.required'] = 'The country/municipal code field is required.';

        if($request->category && strtolower($request->category)==='temporary'){
            $rules['organization'] = 'required|numeric';
            $rules['vacancy_status'] = 'required';
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
            $rules['signator'] = 'required';
            $rules['signator_phone'] = 'required';
        }

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['contact_person_phone']             =    $request->contact_person_phone ? $phone_code.$request->contact_person_phone : null;

        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        $facility = Facility::find($id);

        //  Facility Information
        $facility->category = $request->category;
        $facility->country_municipal_code = $request->country_municipal_code;
        $facility->name = $request->name;
        $facility->status = $request->status;
        $facility->live_burn_permit = $request->live_burn_permit;
        $facility->lapse_date = $request->lapse_date;
        $facility->tier = $request->tier;
        if($request->category && strtolower($request->category)==='temporary'){
            $facility->organization = (int) $request->organization;
            $facility->vacancy_status = $request->vacancy_status;
            $facility->start_date = $request->start_date;
            $facility->end_date = $request->end_date;
            $facility->signator = $request->signator;
            $facility->signator_phone = $request->signator_phone;
        }else{
            $facility->organization = null;
            $facility->vacancy_status = null;
            $facility->start_date = null;
            $facility->end_date = null;
            $facility->signator = null;
            $facility->signator_phone = null;
        }

        // Address Details
        $facility->mail_address             =    $request->mail_address;
        $facility->mail_municipality        =    $request->mail_municipality;
        $facility->mail_state               =    $request->mail_state;
        $facility->mail_zipcode             =    $request->mail_zipcode;
        $facility->physical_address         =    $request->physical_address;
        $facility->physical_municipality    =    $request->physical_municipality;
        $facility->physical_state           =    $request->physical_state;
        $facility->physical_zipcode         =    $request->physical_zipcode;
        $facility->comment                  =    $request->comment;

        // Related Personnel
        if($request->category && strtolower($request->category)==='permanent'){
            $facility->owner_name               =    $request->owner_name;
            $facility->owner_address            =    $request->owner_address;
            $facility->owner_city               =    $request->owner_city;
            $facility->owner_state              =    $request->owner_state;
            $facility->owner_zipcode            =    $request->owner_zipcode;
            $facility->contact_person_name      =    $request->contact_person_name;
            $facility->contact_person_phone     =    $input['contact_person_phone'];
            $facility->representative_name      =    $request->representative_name;
            $facility->representative_phone     =    $request->representative_phone;
        }else{
            $facility->owner_name               =    null;
            $facility->owner_address            =    null;
            $facility->owner_city               =    null;
            $facility->owner_state              =    null;
            $facility->owner_zipcode            =    null;
            $facility->contact_person_name      =    null;
            $facility->contact_person_phone     =    null;
            $facility->representative_name      =    null;
            $facility->representative_phone     =    null;
        }

        if(!$facility->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save facility. Please try again.']);
        }

        // Detect type change and update
        $foreign_relations = ForeignRelations::where('foreign_id',$id)->where('module','facilities')->where('name','facility_type')->get();
        $facility_type_ids = [];
        $facility_types = [];
        foreach ($foreign_relations as $foreign_relation){
            array_push($facility_type_ids,$foreign_relation->id);
            array_push($facility_types,$foreign_relation->value);
        }
        if( (sizeof($facility_types)!==sizeof($request->type)) || sizeof(array_diff($facility_types,$request->type))){
            foreach ($request->type as $type){
                $foreign_relation               =   new ForeignRelations();
                $foreign_relation->foreign_id   =   $facility->id;
                $foreign_relation->module       =   'facilities';
                $foreign_relation->name         =   'facility_type';
                $foreign_relation->value        =   $type;
                if(!$foreign_relation->save()){
                    $this->reverse_store_process($facility->id);
                    return response()->json(['status'=>false,'msg'=>'Failed to save types metadata. Please try again.']);
                }
            }
            foreach ($facility_type_ids as $facility_type_id){
                ForeignRelations::where('id',$facility_type_id)->delete();
            }
        }
        return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
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
            $response = Facility::where('id',$id)->delete();
        }catch (\Exception $error){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        if(!$response){
            return response()->json(['status'=>false,'msg'=>'Failed to delete facility. Please try again.']);
        }

        ForeignRelations::where('foreign_id',$id)->where('module','facilities')->delete();
        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }

    public function search_organization(Request $request){
        $per_page = Helper::per_page();
        $organization = Organization::where(function ($query) use($request,$per_page) {
            $query->where('prefix_id','like',"%{$request->search}%")
                ->orWhere('name','like',"%{$request->search}%")
                ->limit($per_page);
        })->get();

        return response()->json($organization);
    }

    public function search_facility_type(Request $request){
        $per_page = Helper::per_page();
        $facility_types = FacilityType::where('description','like',"%{$request->search}%")->limit($per_page)->get();
        return response()->json($facility_types);
    }

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        Facility::where('id',$request->archive)->update(['is_archive'=>1,'archived_at'=>date('Y-m-d H:i:s'),'archived_by'=>Auth::user()->id]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));
    }

    public function archive(){
        $facilities = Facility::select(DB::raw('COUNT(id) as count'))->where('is_archive',1)->first();
        return view('facility.archive')->with('title','Archived Facilities')->with('facilities',$facilities);
    }

    public function unarchive(Request $request){
        Facility::where('id',$request->archive)->update(['is_archive'=>null,'archived_at'=>null,'archived_by'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }
}
