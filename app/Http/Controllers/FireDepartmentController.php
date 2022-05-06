<?php

namespace App\Http\Controllers;

use App\FireDepartment;
use App\FireDepartmentType;
use App\ForeignRelations;
use App\History;
use App\Http\Helpers\FireDeptHelper;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FireDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fire_department = FireDepartment::select(DB::raw('COUNT(id) as count'))->whereNull('is_archive')->first();
        return view('fire-department.index')->with('title','Fire Departments')->with('fire_departments',$fire_department);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = $request->is_archive ? FireDepartment::where('is_archive',1) : FireDepartment::whereNull('is_archive');
        $query = Helper::filter('fire_departments',$request->all(),$query,['is_archive','created_by','created_at','updated_at']);
        $fire_departments = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        if($request->is_archive){
            return view('fire-department.paginate-archive')->with('fire_departments',$fire_departments);
        }
        return view('fire-department.paginate')->with('fire_departments',$fire_departments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fireDepartment_types = FireDepartmentType::select('id','prefix_id','description')->get();
        return view('fire-department.create', ['title' => 'Add Fire Department', 'fireDepartment_types' => $fireDepartment_types ]);
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
            'name'    =>  'required|unique:fire_departments',
            'address' =>  'required',
            'city'    =>  'required',
            'state'   =>  'required',
            'zipcode' =>  'required',
            'no_of_dept_types' =>  'required',
            'fire_department_types' =>  'required|array',
            'phone'   =>  'required|min:12|max:12|unique:fire_departments',
            'phone2'  =>  'nullable|max:12|unique:fire_departments',
            'email'   =>  'required|email|unique:fire_departments',
        ];

        if($request->phone2){
            $rules['phone2'] = $rules['phone2'].'|min:12';
        }

        if($request->email_2){
            $rules['email_2'] = 'email|different:email';
        }

        if($request->email_3){
            $rules['email_3'] = 'email|different:email,email_2';
        }

        $messages['email_2.email'] = 'The second email must be a valid email address.';
        $messages['email_2.different'] = 'The second email must be different email address.';
        $messages['email_3.different'] = 'The third email must be different email address.';

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['phone']     =    $request->phone ? $phone_code.$request->phone : null;
        $input['phone2']    =    $request->phone2 ? $phone_code.$request->phone2 : null;

        if( $request->no_of_dept_types ){
            $rules['fire_department_types'].='|size:'.$request->no_of_dept_types;
        }

        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        // Validation for unique fire department types
        $fire_department_types = [];
        foreach ($request->fire_department_types as $fire_department_type){
            if(in_array($fire_department_type,$fire_department_types)){
                return response()->json(['status'=> false,'msg'=>'Fire department type selection must be unique']);
            }else{
                $fire_department_types[] = $fire_department_type;
            }
        }

        $fire_department = new FireDepartment();
        $fire_department->name = $request->name;
        $fire_department->address = $request->address;
        $fire_department->city = $request->city;
        $fire_department->state = $request->state;
        $fire_department->zipcode = $request->zipcode;
        $fire_department->no_of_dept_types = $request->no_of_dept_types;
        $fire_department->phone = $input['phone'];
        $fire_department->phone2 = $input['phone2'];
        $fire_department->email = $input['email'];
        $fire_department->email_2 = $input['email_2'] ? $input['email_2'] : null;
        $fire_department->email_3 = $input['email_3'] ? $input['email_3'] : null;
        $fire_department->comment = $request->comment;
        $fire_department->created_by = Auth::user()->id;

        if(!$fire_department->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save facility. Please try again.']);
        }
        // Update Prefix ID
        $response = FireDepartment::where('id',$fire_department->id)->update(['prefix_id'=>FireDeptHelper::prefix_id($fire_department->id)]);
        if(!$response){
            $this->reverse_store_process($fire_department->id);
            return response()->json(array('status'=>false,'msg'=>'Something went wrong while updating prefix id.'));
        }

        foreach ($request->fire_department_types as $fire_department_type){
            $foreign_relation               =   new ForeignRelations();
            $foreign_relation->foreign_id   =   $fire_department->id;
            $foreign_relation->module       =   'fire_departments';
            $foreign_relation->name         =   'fire_department_types';
            $foreign_relation->value        =   $fire_department_type;
            if(!$foreign_relation->save()){
                $this->reverse_store_process($fire_department->id);
                return response()->json(['status'=>false,'msg'=>'Failed to save credit types metadata. Please try again.']);
            }
        }


        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fire_department = FireDepartment::find($id);
        if($fire_department && $fire_department->count()){
            $temps = FireDepartmentType::all()->toArray();
            $fire_department_types = [];
            $db_fire_department_types = [];
            foreach ($temps as $temp){
                $fire_department_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($fire_department_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','fire_departments')->where('name','fire_department_types')->get();

                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $fire_department_types[$temp->value]['description']." ({$fire_department_types[$temp->value]['prefix_id']})";
                }
                $db_fire_department_types = $fire_department_types;
                $fire_department_types = $fire_department_types ? json_encode($fire_department_types,true) : '';
            }
            $last_updated = Helper::get_last_updated('fire_departments',$id);

            $all_fireDepartment_types = FireDepartmentType::select('id','prefix_id','description')->get();
            return view('fire-department.show',['title'=>'View Fire Dept.','fire_department'=>$fire_department,'fire_department_types'=>$fire_department_types,'db_fire_department_types'=>$db_fire_department_types,'foreign_relations'=>$foreign_relations,'last_updated'=>$last_updated,  'all_fireDepartment_types'=>$all_fireDepartment_types]);
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
        $fire_department = FireDepartment::find($id);
        if($fire_department && $fire_department->count()){
            $temps = FireDepartmentType::all()->toArray();
            $fire_department_types = [];
            $db_fire_department_types = [];
            foreach ($temps as $temp){
                $fire_department_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($fire_department_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','fire_departments')->where('name','fire_department_types')->get();

                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $fire_department_types[$temp->value]['description']." ({$fire_department_types[$temp->value]['prefix_id']})";
                }
                $db_fire_department_types = $fire_department_types;
                $fire_department_types = $fire_department_types ? json_encode($fire_department_types,true) : '';
            }

            $all_fireDepartment_types = FireDepartmentType::select('id','prefix_id','description')->get();

            return view('fire-department.edit',['title'=>'Edit Fire Dept.','fire_department'=>$fire_department,'fire_department_types'=>$fire_department_types,'db_fire_department_types'=>$db_fire_department_types,'foreign_relations'=>$foreign_relations , 'all_fireDepartment_types'=>$all_fireDepartment_types]);
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
            'name'    =>  'required|unique:fire_departments,name,'.$id,
            'address' =>  'required',
            'city'    =>  'required',
            'zipcode' =>  'required',
            'no_of_dept_types' =>  'required',
            'fire_department_types' =>  'required|array',
            'phone'   =>  'required|min:12|max:12|unique:fire_departments,phone,'.$id,
            'phone2'  =>  'nullable|max:12|unique:fire_departments,phone2,'.$id,
            'email'   =>  'required|email|unique:fire_departments,email,'.$id,
        ];

        if($request->phone2){
            $rules['phone2'] = $rules['phone2'].'|min:12';
        }

        if($request->email_2){
            $rules['email_2'] = 'email|different:email';
        }

        if($request->email_3){
            $rules['email_3'] = 'email|different:email,email_2';
        }

        $messages['email_2.email'] = 'The second email must be a valid email address.';
        $messages['email_2.different'] = 'The second email must be different email address.';
        $messages['email_3.different'] = 'The third email must be different email address.';

        $phone_code = Helper::get_phone_code();
        $input = $request->all();
        $input['phone']     =    $request->phone ? $phone_code.$request->phone : null;
        $input['phone2']    =    $request->phone2 ? $phone_code.$request->phone2 : null;

        if( $request->no_of_dept_types ){
            $rules['fire_department_types'].='|size:'.$request->no_of_dept_types;
        }

        $validator = Validator::make($input,$rules,$messages);
        if($validator->fails()){
            return response()->json(array('message'=>'The given data was invalid.','errors'=>$validator->errors()),422);
        }

        // Validation for unique fire department types
        $fire_department_types = [];
        foreach ($request->fire_department_types as $fire_department_type){
            if(in_array($fire_department_type,$fire_department_types)){
                return response()->json(['status'=> false,'msg'=>'Fire department type selection must be unique']);
            }else{
                $fire_department_types[] = $fire_department_type;
            }
        }

        $error = '';
        $additional_changes = [];
        $fire_department = FireDepartment::find($id);
        $fire_department->name = $request->name;
        $fire_department->address = $request->address;
        $fire_department->city = $request->city;
        $fire_department->state = $request->state;
        $fire_department->zipcode = $request->zipcode;
        $fire_department->no_of_dept_types = $request->no_of_dept_types;
        $fire_department->phone = $input['phone'];
        $fire_department->phone2 = $input['phone2'];
        $fire_department->email = $input['email'];
        $fire_department->email_2 = $input['email_2'] ? $input['email_2'] : null;
        $fire_department->email_3 = $input['email_3'] ? $input['email_3'] : null;
        $fire_department->comment = $request->comment;

        $prev_object = $fire_department->getOriginal();
        $new_object = $fire_department->getAttributes();

        if(!$fire_department->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save facility. Please try again.']);
        }
        // Detect type change and update
        $foreign_relations = ForeignRelations::where('foreign_id',$id)->where('module','fire_departments')->where('name','fire_department_types')->get();
        $fire_department_type_ids = [];
        $fire_department_types = [];
        foreach ($foreign_relations as $foreign_relation){
            array_push($fire_department_type_ids,$foreign_relation->id);
            array_push($fire_department_types,$foreign_relation->value);
        }
        if( (sizeof($fire_department_types)!==sizeof($request->fire_department_types)) || sizeof(array_diff($fire_department_types,$request->fire_department_types))){
            $additional_changes[] = [
                'label'  =>  'fire_department_types',
                'prev'   =>  $fire_department_types,
                'new'    =>  $request->fire_department_types,
            ];
            foreach ($request->fire_department_types as $fire_department_type){
                $foreign_relation               =   new ForeignRelations();
                $foreign_relation->foreign_id   =   $fire_department->id;
                $foreign_relation->module       =   'fire_departments';
                $foreign_relation->name         =   'fire_department_types';
                $foreign_relation->value        =   $fire_department_type;
                if(!$foreign_relation->save()){
                    $error.="<li>Failed to save types metadata.</li>";
                }
            }
            foreach ($fire_department_type_ids as $fire_department_type_id){
                ForeignRelations::where('id',$fire_department_type_id)->delete();
            }
        }

        $response = Helper::create_history($prev_object,$new_object,$fire_department->id,'fire_departments',null,$additional_changes);
        if(!$response){
            $error.="<li>Failed to create update fire departments history</li>";
        }
        if($error){
            $msg = '<p>Updated fire departments. Some errors occurred are stated:</p>';
            return response()->json(array('status'=>false,'msg'=>"{$msg}<ul class='pl-4'>{$error}</ul>"));
        }else{
            return response()->json(array('status'=>true,'msg'=>'Updated Successfully !'));
        }
    }

    public function reverse_store_process($id){
        try{
            return FireDepartment::where('id',$id)->delete();
        }catch (\Exception $error){
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$this->reverse_store_process($id)){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        History::where('foreign_id',$id)->where('module','fire_departments')->delete();
        ForeignRelations::where('foreign_id',$id)->where('module','fire_departments')->delete();
        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }

    public function history($id) {
        $histories = History::where('foreign_id',$id)->where('module','fire_departments')->orderBy('created_at','desc')->get();
        if($histories && $histories->count()){
            foreach ($histories as $key=>$history){
                $array = json_decode($history->data,true);
                foreach ($array as $key_2=>$data){
                    $label = strtolower($data['label']);
                    $prev = null;
                    $new = null;
                    if($label === 'fire_department_types'){
                        $prev = FireDepartmentType::select('description')->whereIn('id',$data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('description')->toArray() : 'N/A';
                        $new = FireDepartmentType::select('description')->whereIn('id',$data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('description')->toArray() : 'N/A';
                    }
                    if(isset($prev) && isset($new)){
                        $array[$key_2]['prev'] = $prev;
                        $array[$key_2]['new'] = $new;
                    }
                    $histories[$key]->data = $array;
                }
            }
            return view('partials.update-history')->with('histories',$histories);
        }
    }

    public function archive_create(Request $request){
        if(!$request->archive)
            return response()->json(['status'=>false,'msg'=>'Invalid Request.']);

        FireDepartment::where('id',$request->archive)->update(['is_archive'=>1,'archived_at'=>date('Y-m-d H:i:s'),'archived_by'=>Auth::user()->id]);
        return response()->json(array('status'=>true,'msg'=>'Archived Successfully !'));

    }

    public function archive(){
        $fire_departments = FireDepartment::select(DB::raw('COUNT(id) as count'))->where('is_archive',1)->first();
        return view('fire-department.archive')->with('title','Archived Fire Departments')->with('fire_departments',$fire_departments);
    }

    public function unarchive(Request $request){
        FireDepartment::where('id',$request->archive)->update(['is_archive'=>null,'archived_at'=>null,'archived_by'=>null]);
        return response()->json(array('status'=>true,'msg'=>'Unarchived Successfully !'));
    }
    
    public function search_fire_department_type(Request $request){
        $per_page = Helper::per_page();
        $courses = FireDepartmentType::where('description','like',"%{$request->search}%")->orWhere('prefix_id', 'like',"%{$request->search}%")->limit($per_page)->get();
        return response()->json($courses);  
    }
}
