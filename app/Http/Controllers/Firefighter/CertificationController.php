<?php

namespace App\Http\Controllers\Firefighter;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AwardCertificate;
use App\Certification;
use App\CompletedCourse;
use App\Course;
use App\certificatehistory;
use App\Firefighter;
use App\ForeignRelations;
use App\History;
use App\Http\Helpers\Helper;
use App\Mail\SendCertificate;
use App\Organization;
use App\Prerequisite;
use App\CertificateStatus;
use App\FirefighterCertificates;
use App\CoursePrerequisites;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\User;
use App\Jobs\FirefighterCertificateEnrollementJob;
use App\Jobs\FirefighterCertificateSupplyJob;

class CertificationController extends Controller
{
    public function all_credential_index(){

        return view('firefighter-frontend.all-credentials.index')
            ->withTitle('All Credentials')
            ->withCertifications(Certification::select(DB::raw('COUNT(id) as count'))
                ->where('certification_cycle_end', '>=', carbon::now()->toDateString())
                ->orWhere('renewed_expiry_date', '>=', carbon::now()->toDateString())
                ->orWhere('renewable','=', 0)
                ->first());
    }

    public function all_credential_paginte(Request $request){

        $per_page = Helper::per_page();
        $query = Certification::select('id','prefix_id','title','renewable');
        $query = Helper::filter('certifications',$request->all(),$query,['renewable','renewal_period','no_of_credit_types','admin_ceu','tech_ceu']);
        /*$certifications = $query->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());*/
        $certifications = $query->where('certification_cycle_end', '>=', carbon::now()->toDateString())->orWhere('renewed_expiry_date', '>=', carbon::now()->toDateString())->orWhere('renewable','=', 0)->orderBy('created_at','desc')->paginate($per_page)->appends(request()->query());
        return view('firefighter-frontend.all-credentials.paginate')->with('certifications',$certifications);
    }

    public function all_credential_show($id)
    {
        $certificate = Certification::find($id);
        if($certificate && $certificate->count()){
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp){
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($credit_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','certifications')->where('name','credit_types')->get();
                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types,true) : '';
            }

            $pre_req_certs = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','certifications.prefix_id','certifications.title')
                ->leftJoin('certifications','prerequisites.pre_req_certificate_id','=','certifications.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_certificate_id')->get();

            $pre_req_courses = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','courses.prefix_id','courses.course_name')
                ->leftJoin('courses','prerequisites.pre_req_course_id','=','courses.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_course_id')->get();

            $prereq_certificates = [];
            if($pre_req_certs && $pre_req_certs->count()){
                foreach ($pre_req_certs as $pre_req_cert){
                    $prereq_certificates[] = "{$pre_req_cert->title} ($pre_req_cert->prefix_id)";
                }
            }

            $prereq_courses = [];
            if($pre_req_courses && $pre_req_courses->count()){
                foreach ($pre_req_courses as $pre_req_course){
                    $prereq_courses[] = "{$pre_req_course->course_name} ($pre_req_course->prefix_id)";
                }
            }

            $last_updated = Helper::get_last_updated('certifications',$id);

            return view('firefighter-frontend.all-credentials.show', ['title' => 'View Credential','certificate'=>$certificate,'prereq_courses'=>$prereq_courses,'credit_types'=>$credit_types,'prereq_certificates'=>$prereq_certificates,'last_updated'=>$last_updated,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'pre_req_certs'=>$pre_req_certs,'pre_req_courses'=>$pre_req_courses]);
        }
        else{
            return view('404');
        }
    }

    // firefighter apply certification
    public function apply_certification_index(Request $request){

        $certifications = FirefighterCertificates::select(DB::raw('COUNT(id) as count'))->where('status','applied')->where('firefighter_id',Auth::guard('firefighters')->user()->id)->first();
        return view('firefighter-frontend.apply-certifications.index')->with('certifications',$certifications)->with('title','Applied Credentials');
    }

    public function apply_certification_paginate(Request $request){

        $per_page = Helper::per_page();

        $query = FirefighterCertificates::where('firefighter_id',Auth::guard('firefighters')->user()->id)
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->leftJoin('firefighters','firefighters.id','=','firefighter_certificates.firefighter_id')
            ->select('certifications.prefix_id','certifications.title','certifications.id','firefighter_certificates.status','firefighter_certificates.created_at')
            ->where('firefighter_certificates.status','applied')
            ->orderBy('firefighter_certificates.created_at','DESC');

            if($request->prefix_id){
                $query = $query->having('certifications.prefix_id','like',"%{$request->prefix_id}%");
            }

            if($request->credential_title){
                $query = $query->having('certifications.title','like',"%{$request->credential_title}%");
            }

        $firefighter_certificates = $query->paginate($per_page)->appends(request()->query());

        return view('firefighter-frontend.apply-certifications.paginate')->with('firefighter_certificates',$firefighter_certificates);
    }

    public function apply_certification_show($id)
    {
        $certificate = Certification::find($id);
        if($certificate && $certificate->count()){
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp){
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($credit_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','certifications')->where('name','credit_types')->get();
                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types,true) : '';
            }

            $pre_req_certs = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','certifications.prefix_id','certifications.title')
                ->leftJoin('certifications','prerequisites.pre_req_certificate_id','=','certifications.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_certificate_id')->get();

            $pre_req_courses = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','courses.prefix_id','courses.course_name')
                ->leftJoin('courses','prerequisites.pre_req_course_id','=','courses.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_course_id')->get();

            $prereq_certificates = [];
            if($pre_req_certs && $pre_req_certs->count()){
                foreach ($pre_req_certs as $pre_req_cert){
                    $prereq_certificates[] = "{$pre_req_cert->title} ($pre_req_cert->prefix_id)";
                }
            }

            $prereq_courses = [];
            if($pre_req_courses && $pre_req_courses->count()){
                foreach ($pre_req_courses as $pre_req_course){
                    $prereq_courses[] = "{$pre_req_course->course_name} ($pre_req_course->prefix_id)";
                }
            }

            // status and reason in detail
            $firefighter_certificates = FirefighterCertificates::where('firefighter_certificates.firefighter_id', Auth::guard('firefighters')->user()->id)
            ->where('firefighter_certificates.certificate_id',$id)
            ->leftJoin('certificate_rejected_reasons','certificate_rejected_reasons.firefighter_certificates_id','=','firefighter_certificates.id')
            ->select('certificate_rejected_reasons.reason','firefighter_certificates.status')
            ->first();

            return view('firefighter-frontend.apply-certifications.show', ['title' => 'View Credentials','certificate'=>$certificate,'prereq_courses'=>$prereq_courses,'credit_types'=>$credit_types,'prereq_certificates'=>$prereq_certificates,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'pre_req_certs'=>$pre_req_certs,'pre_req_courses'=>$pre_req_courses, 'firefighter_certificates' => $firefighter_certificates ]);
        }
        else{
            return view('404');
        }
    }

    // firefighter failed certification
    public function failed_certification_index(Request $request){

        $certifications = FirefighterCertificates::where('firefighter_certificates.firefighter_id',Auth::guard('firefighters')->user()->id)->where('firefighter_certificates.test_status','failed')->where('firefighter_certificates.status','accepted')->count();

        return view('firefighter-frontend.failed-certifications.index')->with('certifications',$certifications)->with('title','Failed Credentials');
    }

    public function failed_certification_paginate(Request $request){

        $per_page = Helper::per_page();

        $query = FirefighterCertificates::where('firefighter_certificates.firefighter_id',Auth::guard('firefighters')->user()->id)
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->leftJoin('firefighters','firefighters.id','=','firefighter_certificates.firefighter_id')
            ->leftJoin('certificate_statuses','certificate_statuses.firefighter_certificates_id','=','firefighter_certificates.id')
            ->select('certifications.id','certifications.prefix_id','certifications.title','firefighter_certificates.status as firefighter_certificates_status','firefighter_certificates.created_at',DB::raw('min(certificate_statuses.read_status) as read_status'))
            ->where('firefighter_certificates.test_status','failed')
            ->where('firefighter_certificates.status','accepted');

        if($request->prefix_id){
            $query = $query->having('certifications.prefix_id','like',"%{$request->prefix_id}%");
        }

        if($request->credential_title){
            $query = $query->having('certifications.title','like',"%{$request->credential_title}%");
        }

        $query = $query->groupBy('certifications.id')->orderBy('firefighter_certificates.created_at','DESC');

        $firefighter_certificates = $query->paginate($per_page)->appends(request()->query());

        return view('firefighter-frontend.failed-certifications.paginate')->with('firefighter_certificates',$firefighter_certificates);
    }

    public function failed_certification_show($id)
    {
        $certificate = Certification::find($id);
        if($certificate && $certificate->count()){
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp){
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($credit_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','certifications')->where('name','credit_types')->get();
                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types,true) : '';
            }

            $pre_req_certs = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','certifications.prefix_id','certifications.title')
                ->leftJoin('certifications','prerequisites.pre_req_certificate_id','=','certifications.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_certificate_id')->get();

            $pre_req_courses = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','courses.prefix_id','courses.course_name')
                ->leftJoin('courses','prerequisites.pre_req_course_id','=','courses.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_course_id')->get();

            $prereq_certificates = [];
            if($pre_req_certs && $pre_req_certs->count()){
                foreach ($pre_req_certs as $pre_req_cert){
                    $prereq_certificates[] = "{$pre_req_cert->title} ($pre_req_cert->prefix_id)";
                }
            }

            $prereq_courses = [];
            if($pre_req_courses && $pre_req_courses->count()){
                foreach ($pre_req_courses as $pre_req_course){
                    $prereq_courses[] = "{$pre_req_course->course_name} ($pre_req_course->prefix_id)";
                }
            }

            // certificate status and test in detail
            $firefighter_certificates = FirefighterCertificates::where('firefighter_certificates.firefighter_id', Auth::guard('firefighters')->user()->id)
            ->where('firefighter_certificates.certificate_id',$id)
            ->select(DB::raw('max(firefighter_certificates.test_status) as test_status'),'firefighter_certificates.status')
            ->first();

            // status and certificates in detail
            $firefighter_certificates_details = FirefighterCertificates::where('firefighter_certificates.firefighter_id', Auth::guard('firefighters')->user()->id)
            ->where('firefighter_certificates.certificate_id',$id)
            ->join('certificate_statuses','certificate_statuses.firefighter_certificates_id','=','firefighter_certificates.id')
            ->leftJoin('firefighters','firefighters.id','=', 'firefighter_certificates.firefighter_id' )
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->select('certificate_statuses.read_status','certifications.title','certifications.prefix_id','certificate_statuses.id','certificate_statuses.test_date','certificate_statuses.test_time','certificate_statuses.status as test_status')
            ->get();

            // update firefighter read status
            $update_certificate_read_status = DB::table('firefighter_certificates')
            ->where('certificate_statuses.firefighter_id',Auth::guard('firefighters')->user()->id)
            ->where('firefighter_certificates.status',"accepted")
            ->where('certificate_statuses.read_status','!=',1)
            ->where('certifications.id','=',$id)
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->leftJoin('certificate_statuses', 'certificate_statuses.firefighter_certificates_id', '=', 'firefighter_certificates.id')
            ->select()
            ->update(['certificate_statuses.read_status' => 1 ]);

            return view('firefighter-frontend.failed-certifications.show', ['title' => 'View Credentials','certificate'=>$certificate,'prereq_courses'=>$prereq_courses,'credit_types'=>$credit_types,'prereq_certificates'=>$prereq_certificates,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'pre_req_certs'=>$pre_req_certs,'pre_req_courses'=>$pre_req_courses, 'firefighter_certificates' => $firefighter_certificates, 'firefighter_certificates_details' => $firefighter_certificates_details ]);
        }
        else{
            return view('404');
        }
    }

    // firefighter reject certification
    public function reject_certification_index(Request $request){

        $certifications = FirefighterCertificates::select(DB::raw('COUNT(id) as count'))->where('status','rejected')->where('firefighter_id',Auth::guard('firefighters')->user()->id)->first();

        return view('firefighter-frontend.reject-certifications.index')->with('certifications',$certifications)->with('title','Rejected Credentials');
    }

    public function reject_certification_paginate(Request $request){

        $per_page = Helper::per_page();

        $query = FirefighterCertificates::where('firefighter_certificates.firefighter_id',Auth::guard('firefighters')->user()->id)
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->leftJoin('firefighters','firefighters.id','=','firefighter_certificates.firefighter_id')
            ->leftJoin('certificate_rejected_reasons','certificate_rejected_reasons.firefighter_certificates_id','=','firefighter_certificates.id')
            ->select('certifications.id','certifications.prefix_id','certifications.title','firefighter_certificates.status','certificate_rejected_reasons.read_status as read_status','certificate_rejected_reasons.created_at')
            ->where('firefighter_certificates.status','rejected');

            if($request->prefix_id){
                $query = $query->having('certifications.prefix_id','like',"%{$request->prefix_id}%");
            }

            if($request->credential_title){
                $query = $query->having('certifications.title','like',"%{$request->credential_title}%");
            }

        $query = $query->orderByRaw("CASE WHEN read_status = 0 THEN 1 ELSE 2 END ASC")->orderBy('certificate_rejected_reasons.created_at','DESC');

        $firefighter_certificates = $query->paginate($per_page)->appends(request()->query());

        return view('firefighter-frontend.reject-certifications.paginate')->with('firefighter_certificates',$firefighter_certificates);
    }

    public function reject_certification_show($id)
    {
        $certificate = Certification::find($id);
        if($certificate && $certificate->count()){
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp){
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($credit_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','certifications')->where('name','credit_types')->get();
                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types,true) : '';
            }

            $pre_req_certs = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','certifications.prefix_id','certifications.title')
                ->leftJoin('certifications','prerequisites.pre_req_certificate_id','=','certifications.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_certificate_id')->get();

            $pre_req_courses = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','courses.prefix_id','courses.course_name')
                ->leftJoin('courses','prerequisites.pre_req_course_id','=','courses.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_course_id')->get();

            $prereq_certificates = [];
            if($pre_req_certs && $pre_req_certs->count()){
                foreach ($pre_req_certs as $pre_req_cert){
                    $prereq_certificates[] = "{$pre_req_cert->title} ($pre_req_cert->prefix_id)";
                }
            }

            $prereq_courses = [];
            if($pre_req_courses && $pre_req_courses->count()){
                foreach ($pre_req_courses as $pre_req_course){
                    $prereq_courses[] = "{$pre_req_course->course_name} ($pre_req_course->prefix_id)";
                }
            }

            // status and reason in detail
            $firefighter_certificates = FirefighterCertificates::where('firefighter_certificates.firefighter_id', Auth::guard('firefighters')->user()->id)
            ->where('firefighter_certificates.certificate_id',$id)
            ->leftJoin('certificate_rejected_reasons','certificate_rejected_reasons.firefighter_certificates_id','=','firefighter_certificates.id')
            ->select('certificate_rejected_reasons.reason','firefighter_certificates.status')
            ->first();

            $update_certificate_read_status = DB::table('firefighter_certificates')
            ->where('firefighter_certificates.status',"rejected")
            ->where('certificate_rejected_reasons.read_status','!=',1)
            ->where('certifications.id','=',$id)
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->leftJoin('certificate_rejected_reasons', 'certificate_rejected_reasons.firefighter_certificates_id', '=', 'firefighter_certificates.id')
            ->update(['certificate_rejected_reasons.read_status' => 1 ]);

            return view('firefighter-frontend.reject-certifications.show', ['title' => 'View Credentials','certificate'=>$certificate,'prereq_courses'=>$prereq_courses,'credit_types'=>$credit_types,'prereq_certificates'=>$prereq_certificates,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'pre_req_certs'=>$pre_req_certs,'pre_req_courses'=>$pre_req_courses, 'firefighter_certificates' => $firefighter_certificates ]);
        }
        else{
            return view('404');
        }
    }


    // firefighter Approved certification
    public function approved_certification_index(Request $request){

        $certifications = FirefighterCertificates::select(DB::raw('COUNT(id) as count'))->where('status','accepted')->where('firefighter_id',Auth::guard('firefighters')->user()->id)->first();
        return view('firefighter-frontend.approved-certifications.index')->with('certifications',$certifications)->with('title','Applied Credentials');
    }

    public function approved_certification_paginate(Request $request){

        $per_page = Helper::per_page();

        $query = FirefighterCertificates::where('firefighter_certificates.firefighter_id',Auth::guard('firefighters')->user()->id)
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->leftJoin('firefighters','firefighters.id','=','firefighter_certificates.firefighter_id')
            ->leftJoin('certificate_statuses','certificate_statuses.firefighter_certificates_id','=','firefighter_certificates.id')
            ->select('certifications.prefix_id','certifications.title','certifications.id','firefighter_certificates.test_status','firefighter_certificates.status','certificate_statuses.id as certificate_statuses_id','certificate_statuses.updated_at',DB::raw('min(certificate_statuses.read_status) as read_status'))
            ->where('firefighter_certificates.test_status','none')
            ->where('firefighter_certificates.status','=','accepted');

            if($request->prefix_id){
                $query = $query->having('certifications.prefix_id','like',"%{$request->prefix_id}%");
            }

            if($request->credential_title){
                $query = $query->having('certifications.title','like',"%{$request->credential_title}%");
            }

        $firefighter_certificates = $query->orderByRaw("CASE WHEN read_status = 0 THEN 1 ELSE 2 END ASC")->orderBy('certificate_statuses.updated_at','DESC')->groupBy('certifications.id')->paginate($per_page)->appends(request()->query());

        return view('firefighter-frontend.approved-certifications.paginate')->with('firefighter_certificates',$firefighter_certificates);
    }

    public function approved_certification_show($id)
    {
        $certificate = Certification::find($id);
        if($certificate && $certificate->count()){
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp){
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if(!empty($credit_types)){
                $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','certifications')->where('name','credit_types')->get();
                foreach ($temps as $temp){
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types,true) : '';
            }

            $pre_req_certs = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','certifications.prefix_id','certifications.title')
                ->leftJoin('certifications','prerequisites.pre_req_certificate_id','=','certifications.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_certificate_id')->get();

            $pre_req_courses = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','courses.prefix_id','courses.course_name')
                ->leftJoin('courses','prerequisites.pre_req_course_id','=','courses.id')
                ->where('certification_id',$certificate->id)->whereNotNull('pre_req_course_id')->get();

            $prereq_certificates = [];
            if($pre_req_certs && $pre_req_certs->count()){
                foreach ($pre_req_certs as $pre_req_cert){
                    $prereq_certificates[] = "{$pre_req_cert->title} ($pre_req_cert->prefix_id)";
                }
            }

            $prereq_courses = [];
            if($pre_req_courses && $pre_req_courses->count()){
                foreach ($pre_req_courses as $pre_req_course){
                    $prereq_courses[] = "{$pre_req_course->course_name} ($pre_req_course->prefix_id)";
                }
            }

            // status and certificates in detail
            $firefighter_certificates_details = FirefighterCertificates::where('firefighter_certificates.firefighter_id', Auth::guard('firefighters')->user()->id)
            ->where('firefighter_certificates.certificate_id',$id)
            ->leftJoin('certificate_statuses','certificate_statuses.firefighter_certificates_id','=','firefighter_certificates.id')
            ->leftJoin('firefighters','firefighters.id','=', 'firefighter_certificates.firefighter_id' )
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->select('certificate_statuses.read_status','certifications.title','certifications.prefix_id','certificate_statuses.id','certificate_statuses.test_date','certificate_statuses.test_time','certificate_statuses.status as test_status')
            ->get();

            // update firefighter read status
            $update_certificate_read_status = DB::table('firefighter_certificates')
            ->where('certificate_statuses.firefighter_id',Auth::guard('firefighters')->user()->id)
            ->where('firefighter_certificates.status',"accepted")
            ->where('certificate_statuses.read_status','!=',1)
            ->where('certifications.id','=',$id)
            ->leftJoin('certifications','certifications.id','=','firefighter_certificates.certificate_id')
            ->leftJoin('certificate_statuses', 'certificate_statuses.firefighter_certificates_id', '=', 'firefighter_certificates.id')
            ->select()
            ->update(['certificate_statuses.read_status' => 1 ]);

            return view('firefighter-frontend.approved-certifications.show', ['title' => 'View Credentials','certificate'=>$certificate,'prereq_courses'=>$prereq_courses,'credit_types'=>$credit_types,'prereq_certificates'=>$prereq_certificates,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'pre_req_certs'=>$pre_req_certs,'pre_req_courses'=>$pre_req_courses, 'firefighter_certificates_details' => $firefighter_certificates_details ]);
        }
        else{
            return view('404');
        }
    }

    public function awarded_certification_index(){

        $firefighter = Firefighter::find(Auth::guard('firefighters')->user()->id);
        if($firefighter && $firefighter->count()){
            $awarded_certificates = AwardCertificate::select(DB::raw('COUNT(DISTINCT certificate_id) as count'))->where('firefighter_id',Auth::guard('firefighters')->user()->id)->first();

            return view('firefighter-frontend.awarded-certifications.index')->with('title','Awarded Credentials')->with('awarded_certificates',$awarded_certificates)->with('firefighter',$firefighter);
        }else{
            return view('404');
        }

    }

    public function awarded_certification_paginate(Request $request){

        $per_page = Helper::per_page();


        $query = AwardCertificate::where('awarded_certificates.firefighter_id',Auth::guard('firefighters')->user()->id)
            ->leftJoin('certifications', 'certifications.id', '=', 'awarded_certificates.certificate_id')
            ->leftJoin('firefighters', 'firefighters.id', '=', 'awarded_certificates.firefighter_id')
            ->leftJoin('organizations', 'organizations.id', '=', 'awarded_certificates.organization_id')
            ->select(DB::raw('max(awarded_certificates.id) as id'),'awarded_certificates.certificate_id',DB::raw('max(awarded_certificates.receiving_date) as receiving_date'),'awarded_certificates.organization_id',DB::raw('max(awarded_certificates.issue_date) as issue_date'),DB::raw('max(awarded_certificates.lapse_date) as lapse_date'),DB::raw('max(awarded_certificates.stage) as stage'),DB::raw('min(awarded_certificates.firefighters_read_status) as firefighters_read_status'),'awarded_certificates.firefighter_id','certifications.title','certifications.prefix_id as certifications_prefix_id','certifications.renewable','firefighters.f_name','firefighters.m_name','firefighters.l_name','organizations.name as organization_name');

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

        $query = $query->orderByRaw("CASE WHEN firefighters_read_status = 0 THEN 1 ELSE 2 END ASC")->orderBy('awarded_certificates.created_at','DESC');

        $awarded_certificates = $query->groupBy('awarded_certificates.certificate_id')->paginate($per_page)->appends(request()->query());

        return view('firefighter-frontend.awarded-certifications.paginate')->with('awarded_certificates',$awarded_certificates);
    }

    public function view_all_credential($certificate_id){

        $firefighter = Firefighter::find(Auth::guard('firefighters')->user()->id);
        if(!isset($firefighter->id) || !$firefighter->id)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request'));

        // Using primary key as certificate ID
        $awarded_certificate = AwardCertificate::where('firefighter_id',Auth::guard('firefighters')->user()->id)->where('id',$certificate_id)->limit(1)->first();
        if(!isset($awarded_certificate->id) || !$awarded_certificate->id)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request'));

        $certificate = Certification::find($awarded_certificate->certificate_id);
        if(!isset($certificate->id) || !$certificate->id)
            return response()->json(array('status'=>false,'msg'=>'Invalid Request'));

        // update firefighters view status
        AwardCertificate::where('firefighter_id',Auth::guard('firefighters')->user()->id)->where('id',$certificate_id)->update(array('firefighters_read_status' => 1));

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

    public function credential_past_records($certificate_id){
        $firefighter = Firefighter::find(Auth::guard('firefighters')->user()->id);
        $certification = Certification::find($certificate_id);

        if(!$firefighter || !$firefighter->count() || !$certification || !$certification->count())
            return view('404');

        $awarded_certificates = AwardCertificate::select(DB::raw('COUNT(id) as count'))->where('firefighter_id',Auth::guard('firefighters')->user()->id)->where('certificate_id',$certificate_id)->first();
        return view('firefighter-frontend.awarded-certifications.certifications-past-records')->with('title','Certification History')->with('awarded_certificates',$awarded_certificates)->with('firefighter',$firefighter)->with('certification',$certification);
    }

    public function paginate_credentials_past_records($certificate_id){

        $per_page = Helper::per_page();
        $awarded_certificates = AwardCertificate::select('awarded_certificates.*','certifications.title','certifications.prefix_id','certifications.renewable')->leftJoin('certifications','awarded_certificates.certificate_id','=','certifications.id')->where('awarded_certificates.firefighter_id',Auth::guard('firefighters')->user()->id)->where('awarded_certificates.certificate_id',$certificate_id)->orderBy('awarded_certificates.created_at','DESC')->paginate($per_page);
        return view('firefighter-frontend.awarded-certifications.paginate-certifications-past-records')->with('awarded_certificates',$awarded_certificates);
    }

    public function supply_store(Request $request){

        $validate_certificate_count = FirefighterCertificates::where('certificate_id',$request->certification_id)->where('firefighter_id',$request->firefighter_id)->where('status','accepted')->count();

        if($validate_certificate_count > 1){
            return response()->json(['status'=> false,'msg'=>'You already Applied for Supply Credential.' ]);
        }

        $firefighter_course = new FirefighterCertificates();
        $firefighter_course->firefighter_id   =  $request->firefighter_id;
        $firefighter_course->certificate_id   =  $request->certification_id;
        $firefighter_course->status           =  "applied";
        $firefighter_course->test_status      =  "none";

        if(!$firefighter_course->save()){
            return response()->json(['status'=> false,'msg'=>'Failed to save course. Please try again.']);
        }

        $user           =  User::where('role_id',1)->select('name','email')->first();
        $certificate    =  Certification::where('id',$request->certification_id)->select('title')->first();
        $firefighter    =  Firefighter::where('id',$request->firefighter_id)->select('email','f_name','m_name','l_name','cell_phone')->first();

        // Sending Email to Admin
        dispatch(new FirefighterCertificateSupplyJob($user->name,$user->email,$firefighter->email,$firefighter->f_name,$firefighter->m_name,$firefighter->l_name,$firefighter->cell_phone,$certificate->title,'Request for Rescheduling Test for Credential '.ucfirst($certificate->title)));

        return response()->json(['status'=>true,'msg'=>'Supply Applied Successfully !']);
    }

    public function store(Request $request){

        // check already exist
        $applied_certificate_count = FirefighterCertificates::where('certificate_id',$request->certification_id)->where('firefighter_id',$request->firefighter_id)->where('status','applied')->count();
        if($applied_certificate_count > 0){
            return response()->json(['status'=> false,'msg'=>'You already Applied for this Credential.' ]);
        }

        $applied_certificate_count = FirefighterCertificates::where('certificate_id',$request->certification_id)->where('firefighter_id',$request->firefighter_id)->where('status','accepted')->count();
        if($applied_certificate_count > 0){
            return response()->json(['status'=> false,'msg'=>'You already Applied for this Credential.' ]);
        }

        $applied_certificate_count = FirefighterCertificates::where('certificate_id',$request->certification_id)->where('firefighter_id',$request->firefighter_id)->where('status','rejected')->count();
        if($applied_certificate_count > 0){
            return response()->json(['status'=> false,'msg'=>'You already Applied for this Credential.' ]);
        }

        $prerequisite_certificate_ids = Prerequisite::where('certification_id', $request->certification_id)->whereNotNull('pre_req_certificate_id')->pluck('pre_req_certificate_id');

        if($prerequisite_certificate_ids->count() > 0){
            $firefighter_complete_certificate = AwardCertificate::where('firefighter_id',$request->firefighter_id)->where('certificate_id',$prerequisite_certificate_ids)->pluck('certificate_id');
        }else{
            $firefighter_complete_certificate = AwardCertificate::where('firefighter_id',$request->firefighter_id)->pluck('certificate_id');

            // dd($firefighter_complete_certificate);
        }

        // if($prerequisite_certificate_ids != $firefighter_complete_certificate){
            $pre_req_certificate = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_certificate_id','certifications.title')
            ->leftJoin('certifications','certifications.id','=','prerequisites.pre_req_certificate_id')
            ->whereIn('prerequisites.pre_req_certificate_id',$prerequisite_certificate_ids)
            ->groupBy('prerequisites.pre_req_certificate_id')
            ->pluck('certifications.title')->toArray();

        //     return response()->json(['status'=> false,'msg' => "You should have do this Prerequisite Certificate First: ".implode(', ',$pre_req_certificate) ]);
        // }

        $prerequisite_courses_ids = Prerequisite::where('certification_id',$request->certification_id)->whereNotNull('pre_req_course_id')->pluck('pre_req_course_id');

        // dd($prerequisite_courses_ids);
        if($prerequisite_courses_ids->count() > 0){
            $firefighter_complete_courses = CompletedCourse::where('firefighter_id',$request->firefighter_id)->where('course_id',$prerequisite_courses_ids)->pluck('course_id');
            // dd($firefighter_complete_courses);
        }
        else{
            $firefighter_complete_courses = CompletedCourse::where('firefighter_id',$request->firefighter_id)->pluck('course_id');
            // $prerequisite_courses_ids = Prerequisite::where('certification_id', $request->certification_id)->whereNotNull('pre_req_course_id')->pluck('pre_req_course_id');
        }

        // dd($prerequisite_courses_ids);

        // $firefighter_complete_courses = CompletedCourse::where('firefighter_id',$request->firefighter_id)->pluck('course_id');

        // dd($firefighter_complete_courses);

        // if($prerequisite_courses_ids != $firefighter_complete_courses){

            $pre_req_courses = Prerequisite::select('prerequisites.id','prerequisites.pre_req_course_id','prerequisites.pre_req_course_id','courses.course_name as course_name')
                ->leftJoin('courses','courses.id','=','prerequisites.pre_req_course_id')
                ->whereIn('prerequisites.pre_req_course_id',$prerequisite_courses_ids)
                ->groupBy('prerequisites.pre_req_course_id')
                ->pluck('courses.course_name')->toArray();

                // dd($pre_req_courses);

        //         return response()->json(['status'=> false,'msg' => "You should have do this Prerequisite Course First: ".implode(', ',$pre_req_courses) ]);
        // }


        if($prerequisite_courses_ids->count() > 0 && $prerequisite_certificate_ids->count() > 0 ){
            if($prerequisite_certificate_ids != $firefighter_complete_certificate && $prerequisite_courses_ids != $firefighter_complete_courses){
                return response()->json(['status'=> false,'msg' => "You should have to do this Prerequisite Certificates and Courses :  ".implode(', ',$pre_req_certificate).', '.implode(', ',$pre_req_courses) ]);
            }
        }

        if($prerequisite_courses_ids->count() > 0){
            if($prerequisite_courses_ids != $firefighter_complete_courses){
                return response()->json(['status'=> false,'msg' => "You should have to do this Prerequisite Course First: ".implode(', ',$pre_req_courses) ]);
            }
        }

        if($prerequisite_certificate_ids->count() > 0){
            if($prerequisite_certificate_ids != $firefighter_complete_certificate){
                return response()->json(['status'=> false,'msg' => "You should have to do this Prerequisite Certificate First: ".implode(', ',$pre_req_certificate) ]);
            }
        }


           // history table
           $history  = new certificatehistory();
           $history->firefighter_id = $request->firefighter_id;
           $history->certificate_id = $request->certification_id;
           $history->operation = 'Credential Applied';
           $history->date =  carbon::now()->toDateString();
           $history->save();

        $firefighter_course = new FirefighterCertificates();
        $firefighter_course->firefighter_id   =  $request->firefighter_id;
        $firefighter_course->certificate_id =  $request->certification_id;
        $firefighter_course->status           =  "applied";

        if(!$firefighter_course->save()){
            return response()->json(['status'=> false,'msg'=>'Failed to save course. Please try again.']);
        }

      

        $user           =  User::where('role_id',1)->select('name','email')->first();
        $certificate    =  Certification::where('id',$request->certification_id)->select('title')->first();
        $firefighter    =  Firefighter::where('id',$request->firefighter_id)->select('email','f_name','m_name','l_name','cell_phone')->first();


       

        // Sending Email to Admin
        // dispatch(new FirefighterCertificateEnrollementJob($user->name,$user->email,$firefighter->email,$firefighter->f_name,$firefighter->m_name,$firefighter->l_name,$firefighter->cell_phone,$certificate->title,"Request for Credential ".ucfirst($certificate->title)));

        return response()->json(['status'=>true,'msg'=>'Applied Successfully !']);
    }

    /* Old Credentials work for firefighter */

    // public function index(){
    //     $certifications = Certification::select(DB::raw('COUNT(id) as count'))->first();
    //     return view('firefighter-frontend.certification.index')->with('title','Credentials')->with('certifications',$certifications);
    // }

    // public function paginate(Request $request)
    // {
    //     $per_page = Helper::per_page();
    //     $query = Certification::select('id','prefix_id','title','renewable');
    //     $query = Helper::filter('certifications',$request->all(),$query,['renewable','renewal_period','no_of_credit_types','admin_ceu','tech_ceu']);
    //     $certifications = $query->orderBy('certifications.created_at','desc')->paginate($per_page)->appends(request()->query());
    //     return view('firefighter-frontend.certification.paginate')->with('certifications',$certifications);
    // }

    // public function show($id)
    // {
    //     $certificate = Certification::find($id);
    //     if($certificate && $certificate->count()){
    //         $temps = CreditType::all()->toArray();
    //         foreach ($temps as $temp){
    //             $credit_types[$temp['id']] = $temp;
    //         }
    //         $foreign_relations = [];
    //         if(!empty($credit_types)){
    //             $temps = ForeignRelations::select('value')->where('foreign_id',$id)->where('module','certifications')->where('name','credit_types')->get();
    //             foreach ($temps as $temp){
    //                 $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
    //             }
    //             $db_credit_types = $credit_types;
    //             $credit_types = $credit_types ? json_encode($credit_types,true) : '';
    //         }

    //         $pre_req_certs = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','certifications.prefix_id','certifications.title')
    //             ->leftJoin('certifications','prerequisites.pre_req_certificate_id','=','certifications.id')
    //             ->where('certification_id',$certificate->id)->whereNotNull('pre_req_certificate_id')->get();

    //         $pre_req_courses = Prerequisite::select('prerequisites.id','prerequisites.certification_id','prerequisites.pre_req_course_id','prerequisites.pre_req_certificate_id','courses.prefix_id','courses.course_name')
    //             ->leftJoin('courses','prerequisites.pre_req_course_id','=','courses.id')
    //             ->where('certification_id',$certificate->id)->whereNotNull('pre_req_course_id')->get();

    //         $prereq_certificates = [];
    //         if($pre_req_certs && $pre_req_certs->count()){
    //             foreach ($pre_req_certs as $pre_req_cert){
    //                 $prereq_certificates[] = "{$pre_req_cert->title} ($pre_req_cert->prefix_id)";
    //             }
    //         }

    //         $prereq_courses = [];
    //         if($pre_req_courses && $pre_req_courses->count()){
    //             foreach ($pre_req_courses as $pre_req_course){
    //                 $prereq_courses[] = "{$pre_req_course->course_name} ($pre_req_course->prefix_id)";
    //             }
    //         }

    //         // status and reason in detail
    //         $firefighter_certificates = FirefighterCertificates::where('firefighter_id', Auth::guard('firefighters')->user()->id)
    //         ->where('firefighter_certificates.certificate_id',$id)
    //         ->leftJoin('certificate_rejected_reasons','certificate_rejected_reasons.firefighter_certificates_id','=','firefighter_certificates.id')
    //         ->select('certificate_rejected_reasons.reason','firefighter_certificates.status')
    //         ->first();

    //         return view('firefighter-frontend.certification.show', ['title' => 'View Credentials','certificate'=>$certificate,'prereq_courses'=>$prereq_courses,'credit_types'=>$credit_types,'prereq_certificates'=>$prereq_certificates,'db_credit_types'=>$db_credit_types,'foreign_relations'=>$foreign_relations,'pre_req_certs'=>$pre_req_certs,'pre_req_courses'=>$pre_req_courses, 'firefighter_certificates' => $firefighter_certificates ]);
    //     }
    //     else{
    //         return view('404');
    //     }
    // }
}
