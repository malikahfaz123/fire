<?php

namespace App\Http\Controllers\FireFighter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CreditType;
use App\ForeignRelations;
use App\Http\Helpers\CreditTypeHelper;
use App\Http\Helpers\Helper;

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
        return view('firefighter-frontend.credit-type.index')->with('title','Credit Type')->with('credit_types',$credit_types);
    }

    public function paginate(Request $request){
        $per_page = Helper::per_page();
        $query = Helper::filter('credit_types',$request->all(),null,['admin_ceu','tech_ceu','nfpa_std','no_of_credit_types','is_archive','created_by','created_at','updated_at']);
        if($query){
            $credit_types = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }else{
            $credit_types = CreditType::orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        }
        return view('firefighter-frontend.credit-type.paginate')->with('credit_types',$credit_types);
    }
}
