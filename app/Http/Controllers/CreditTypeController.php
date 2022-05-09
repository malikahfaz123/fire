<?php

namespace App\Http\Controllers;

use App\CreditType;
use App\ForeignRelations;
use App\Http\Helpers\CreditTypeHelper;
use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credit_types = CreditType::select(DB::raw('COUNT(id) as count'))->first();
        return view('credit-type.index')->with('title','Credit Type')->with('credit_types',$credit_types);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = Helper::filter('credit_types',$request->all(),null,['admin_ceu','tech_ceu','nfpa_std','no_of_credit_types','is_archive','created_by','created_at','updated_at']);
        if($query){
            $credit_types = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $credit_types = CreditType::orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }
        return view('credit-type.paginate')->with('credit_types',$credit_types);
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
            'description'    =>  'required|max:15|unique:credit_types',
        ];

        $message = [
            'description.max'  => 'The description may not be greater than 15 characters.',
        ];

        $this->validate($request,$rules,$message);

        $credit_type = new CreditType();
        $credit_type->code                =    $request->code;
        $credit_type->description         =    $request->description;
        $credit_type->comment             =    $request->comment;

        if(!$credit_type->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save credit type. Please try again.']);
        }

        // Update Prefix ID
        $response = CreditType::where('id',$credit_type->id)->update(['prefix_id'=>CreditTypeHelper::prefix_id($credit_type->id)]);
        if(!$response){
            $this->reverse_store_process($credit_type->id);
            return response()->json(array('status'=>false,'msg'=>'Something went wrong while updating prefix id.'));
        }

        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);

    }

    public function reverse_store_process($id){
        return CreditType::where('id',$id)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $credit_type = CreditType::find($id);
        if(isset($credit_type->id) && $credit_type->id)
            return response()->json(['status'=>true,'credit_type'=>$credit_type]);

        return response()->json(['status'=>false,'msg'=>'No record found.','credit_type'=>[]]);
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
            'description_1'    =>  'required|max:15|unique:credit_types,description,'.$id,
            'id'    =>  'required|numeric',
        ];

        $message = [
            'description_1.required'=>'The description field is required.',
            'description_1.unique'  =>'This description has already been taken.',
            'description_1.max'  => 'The description may not be greater than 15 characters.',
        ];

        $this->validate($request,$rules,$message);

        $credit_type = CreditType::find($id);
        $credit_type->description         =    $request->description_1;
        $credit_type->comment             =    $request->comment;

        if(!$credit_type->save()){
            return response()->json(['status'=>false,'msg'=>'Failed to save credit type. Please try again.']);
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

        $credit_type = ForeignRelations::select('id')->where('name','credit_types')->where('value',$id)->limit(1)->first();
        if($credit_type && $credit_type->id){
            return response()->json(['status'=>false,'msg'=>'One or more records are associated with this record.']);
        }

        $response = CreditType::where('id',$id)->delete();
        if(!$response){
            return response()->json(['status'=>false,'msg'=>'Failed to delete credit type. Please try again.']);
        }

        return response()->json(array('status'=>true,'msg'=>'Deleted Successfully !'));
    }
}
