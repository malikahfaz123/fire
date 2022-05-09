<?php

namespace App\Http\Controllers;

use App\FacilityType;
use App\ForeignRelations;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facility_types = FacilityType::select(DB::raw('COUNT(id) as count'))->first();
        return view('facility-type.index')->with('title','Facility Type')->with('facility_types',$facility_types);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = Helper::filter('facility_types',$request->all(),null,['admin_ceu','tech_ceu','nfpa_std','no_of_facility_types','is_archive','created_by','created_at','updated_at']);
        if($query){
            $facility_types = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $facility_types = FacilityType::orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }
        return view('facility-type.paginate')->with('facility_types',$facility_types);
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
            'description'    =>  'required|unique:facility_types',
        ];

        $this->validate($request,$rules);

        $facility_type = new FacilityType();
        $facility_type->description         =    $request->description;

        if(!$facility_type->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save facility type. Please try again.']);
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);

    }

    public function reverse_store_process($id){
        return FacilityType::where('id',$id)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $facility_type = FacilityType::find($id);
        if(isset($facility_type->id) && $facility_type->id)
            return response()->json(['status'=>true,'facility_type'=>$facility_type]);

        return response()->json(['status'=>false,'msg'=>'No record found.','facility_type'=>[]]);
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
            'description_1'    =>  'required|unique:facility_types,description,'.$id,
            'id'    =>  'required|numeric',
        ];

        $message = [
            'description_1.required'=>'The description field is required.',
            'description_1.unique'  =>'This description has already been taken.',
        ];

        $this->validate($request,$rules,$message);

        $facility_type = FacilityType::find($id);
        $facility_type->description         =    $request->description_1;

        if(!$facility_type->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save facility type. Please try again.']);
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
        $facility_type = ForeignRelations::select('id')->where('name','facility_type')->where('value',$id)->limit(1)->first();
        if($facility_type && $facility_type->id){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        $response = FacilityType::where('id',$id)->delete();
        if(!$response){
            return response()->json(['status'=>false,'msg'=>'Failed to delete facility type. Please try again.']);
        }

        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }
}
