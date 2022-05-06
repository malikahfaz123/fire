<?php

namespace App\Http\Controllers;

use App\ForeignRelations;
use Illuminate\Http\Request;
use App\FireDepartmentType;
use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\FireDepartmentTypeHelper;

class FireDepartmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fire_department_types = FireDepartmentType::select(DB::raw('COUNT(id) as count'))->first();
        return view('fire-department-type.index')->with('title','Fire Department Type')->with('fire_department_types',$fire_department_types);
    }

    public function paginate(Request $request)
    {
        $per_page = Helper::per_page();
        $query = Helper::filter('fire_department_types',$request->all(),null,['created_at','updated_at']);
        if($query){
            $fire_department_types = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $fire_department_types = FireDepartmentType::orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }
        return view('fire-department-type.paginate')->with('fire_department_types',$fire_department_types);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'description'    =>  'required|unique:fire_department_types',
        ];

        $this->validate($request,$rules);

        $fire_department_type = new FireDepartmentType();
        $fire_department_type->description         =    $request->description;

        if(!$fire_department_type->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save fire department type. Please try again.']);
        }

        // Update Prefix ID
        $response = FireDepartmentType::where('id',$fire_department_type->id)->update(['prefix_id'=>FireDepartmentTypeHelper::prefix_id($fire_department_type->id)]);
        if(!$response){
            $this->reverse_store_process($fire_department_type->id);
            return response()->json(array('status'=>false,'msg'=>'Something went wrong while updating prefix id.'));
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);

    }

    public function reverse_store_process($id){
        return FireDepartmentType::where('id',$id)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fire_department_type = FireDepartmentType::find($id);
        if(isset($fire_department_type->id) && $fire_department_type->id)
            return response()->json(['status'=>true,'fire_department_type'=>$fire_department_type]);

        return response()->json(['status'=>false,'msg'=>'No record found.','fire_department_type'=>[]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'description_1'    =>  'required|unique:fire_department_types,description,'.$id,
            'id'    =>  'required|numeric',
        ];

        $message = [
            'description_1.required'=>'The description field is required.',
            'description_1.unique'  =>'This description has already been taken.',
        ];

        $this->validate($request,$rules,$message);

        $fire_department_type = FireDepartmentType::find($id);
        $fire_department_type->description         =    $request->description_1;

        if(!$fire_department_type->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save fire department type. Please try again.']);
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

        $fire_department_type = ForeignRelations::select('id')->where('name','fire_department_types')->where('value',$id)->limit(1)->first();
        if($fire_department_type && $fire_department_type->id){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        $response = FireDepartmentType::where('id',$id)->delete();
        if(!$response){
            return response()->json(['status'=>false,'msg'=>'Failed to delete fire department type. Please try again.']);
        }

        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }
}
