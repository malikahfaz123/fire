<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CreditType;
use App\GroupCreditType;
use App\Course;
use App\ForeignRelations;
use Illuminate\Support\Facades\DB;

use App\Http\Helpers\Helper;

class GroupCreditTypeController extends Controller
{
    public function index(){

        $credit_code = GroupCreditType::groupBy('credit_code')->get();

        return view('group-credit-type.index',['title'=>'Group Credit Types','credit_code'=> $credit_code ]);
    }

    public function paginate(Request $request){

        $per_page = Helper::per_page();

        $query = GroupCreditType::groupBy('credit_code')->orderBy('credit_code','asc');

        if($request->credit_codes){
            // $query = $query->where('credit_code', $request->credit_codes);
            $query = $query->where('credit_code', 'like', '%' . $request->credit_codes . '%');
        }

        if($request->search){
            $query = $query->where('credit_type_description', 'like', '%' . $request->search . '%');
        }

        $group_credit_types = $query->paginate($per_page)->appends(request()->query());

        // dd($group_credit_types);


        if($group_credit_types && $group_credit_types->count()){
            foreach ($group_credit_types as $key=>$group_credit_type){
                $credit_types = GroupCreditType::select('credit_types.id','credit_types.description')
                ->leftJoin('credit_types','group_credit_types.credit_type_id','=','credit_types.id')
                ->where('group_credit_types.credit_code',$group_credit_type->credit_code)
                ->get();

                $arr = [];
                foreach ($credit_types as $credit_type){
                    $arr[] = "{$credit_type->description}";
                }
                $group_credit_types[$key]->credit_types  = $arr;
            }
        }

        return view('group-credit-type.paginate',['title'=>'Group Credit Type'])->with('group_credit_types',$group_credit_types);
    }

    public function create(){

        $credit_types = CreditType::orderBy('created_at','desc')->get();
        return view('group-credit-type.create',[ 'title'=> 'Group Credit Types', 'credit_types' => $credit_types ]);
    }

    public function store(Request $request){

        $rules = [
            'credit_code'    =>  'required|unique:group_credit_types',
            'credit_types'   =>  'required|array',
        ];

        $this->validate($request,$rules);

        foreach ($request->credit_types as $credit_type){
            $group_credit_type = new GroupCreditType();

            $credit_type_description = CreditType::select('id','description')->find($credit_type);

            $group_credit_type->credit_code = $request->credit_code;
            $group_credit_type->credit_type_id = $credit_type;
            /*$group_credit_type->credit_type_description = $credit_type_description->description;*/
            $group_credit_type->description = $credit_type_description->description;

            if(!$group_credit_type->save()){
                return response()->json(['status'=>false,'msg'=>'Failed to save record. Please try again.']);
            }
        }
        return response()->json(['status'=>true,'msg'=>'Created Successfully !']);
    }

    public function edit($credit_code){

        $group_credit_types = GroupCreditType::where('credit_code',$credit_code)->get();

        $all_credit_types = CreditType::select('id','description')->get();

        if($group_credit_types && $group_credit_types->count()){
            return view('group-credit-type.edit', ['title' => 'Group Credit Type','credit_code'=>$credit_code,'group_credit_types'=>$group_credit_types,'all_credit_types'=>$all_credit_types]);
        }
        return view('404');
    }

    public function update(Request $request,$credit_code){
        $rules = [
            'credit_code'    =>  'required',
            'credit_types'   =>  'required|array',
        ];

        $this->validate($request,$rules);

        GroupCreditType::where('credit_code', $credit_code)->delete();

        $group_credit_types = GroupCreditType::where('credit_code',$credit_code)->get();

        foreach ($request->credit_types as $credit_type){

            $group_credit_type = new GroupCreditType();
            $credit_type_description = CreditType::select('id','description')->find($credit_type);
            $group_credit_type->credit_code = $request->credit_code;
            $group_credit_type->credit_type_id = $credit_type;
//            $group_credit_type->credit_type_description = $credit_type_description->description;
            $group_credit_type->description = $credit_type_description->description;

            if(!$group_credit_type->save()){
                return response()->json(['status'=>false,'msg'=>'Failed to save record. Please try again.']);
            }
        }

        return response()->json(['status'=>true,'msg'=>'Updated Successfully !']);
    }


    public function destroy($credit_code){

        $group_credit_types = GroupCreditType::where('credit_code', $credit_code)->delete();

        if($group_credit_types){
            return response()->json(['status'=>true,'msg'=>'Deleted Successfully !']);
        }
        return response()->json(['status'=>false,'msg'=>'Delete failed.']);
    }

    public function courses_credit_type_index(){

        $courses = CreditType::select(DB::raw('COUNT(id) as count'))->first();

        return view('course-credit-type.index',['title'=>' Credit Types Courses', 'courses'=> $courses  ]);
    }


    public function courses_credit_type_paginate(Request $request){

        $per_page = Helper::per_page();

        $query = CreditType::select('id','prefix_id','description');

        if($request->prefix_id){
            $query = $query->having('prefix_id','like',"%{$request->prefix_id}%");
        }

        if($request->search){
            $query = $query->having('description','like',"%{$request->search}%");
        }

        $credit_types = $query->orderBy('id','ASC')->paginate($per_page)->appends(request()->query());

        // if($credit_types && $credit_types->count()){
        //     foreach ($credit_types as $key=>$credit_type){

        //         $foreign_relations = ForeignRelations::select('foreign_relations.foreign_id','foreign_relations.module','foreign_relations.name','foreign_relations.value','credit_types.description')
        //         ->leftJoin('credit_types','credit_types.id','=','foreign_relations.value')
        //         ->where('foreign_relations.foreign_id',$course->id)
        //         ->where('foreign_relations.module','courses')
        //         ->where('foreign_relations.name','credit_types')
        //         ->get();

        //         $arr = [];
        //         foreach ($foreign_relations as $foreign_relation){
        //             $arr[] = "{$foreign_relation->description}";
        //         }
        //         $courses[$key]->credit_types  = $arr;
        //     }
        // }

        // dd($credit_types);

        // $query = Course::select('id','prefix_id','course_name','no_of_credit_types');

        // if($request->prefix_id){
        //     $query = $query->having('prefix_id','like',"%{$request->prefix_id}%");
        // }

        // if($request->course_name){
        //     $query = $query->where('course_name','like',"%{$request->course_name}%");
        // }

        // if($request->search){

        //     $credit_types = CreditType::where('description','like',"%{$request->search}%")->pluck('id')->toArray();
        //     $foreign_relations = ForeignRelations::where('foreign_relations.module','courses')->where('foreign_relations.name','credit_types')->whereIn('foreign_relations.value',$credit_types)->pluck('foreign_relations.foreign_id')->toArray();
        //     $query = $query->whereIn('id',$foreign_relations);
        // }

        // $courses = $query->orderBy('id','ASC')->paginate($per_page)->appends(request()->query());

        // if($courses && $courses->count()){
        //     foreach ($courses as $key=>$course){

        //         $foreign_relations = ForeignRelations::select('foreign_relations.foreign_id','foreign_relations.module','foreign_relations.name','foreign_relations.value','credit_types.description')
        //         ->leftJoin('credit_types','credit_types.id','=','foreign_relations.value')
        //         ->where('foreign_relations.foreign_id',$course->id)
        //         ->where('foreign_relations.module','courses')
        //         ->where('foreign_relations.name','credit_types')
        //         ->get();

        //         $arr = [];
        //         foreach ($foreign_relations as $foreign_relation){
        //             $arr[] = "{$foreign_relation->description}";
        //         }
        //         $courses[$key]->credit_types  = $arr;
        //     }
        // }

        return view('course-credit-type.paginate',['title'=>'Group Credit Type'])->with('credit_types',$credit_types);
    }

    public function view_courses_credit_type($id){

        $courses = ForeignRelations::
        select('foreign_relations.foreign_id','foreign_relations.module','foreign_relations.name','foreign_relations.value','courses.course_name','courses.prefix_id')
        ->leftJoin('courses','courses.id','=','foreign_relations.foreign_id')
        ->leftJoin('credit_types','credit_types.id','=','foreign_relations.value')
        ->where('foreign_relations.module','courses')
        ->where('foreign_relations.name','credit_types')
        ->where('foreign_relations.value',$id)
        ->get();

        // dd($courses);

        return view('course-credit-type.view-courses-paginate',['title'=>'Group Credit Type', 'courses' => $courses ]);
    }

}
