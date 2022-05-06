<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FirefighterHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\certificatehistory;
use App\AwardCertificate;
use App\Certification;
use App\CompletedCourse;
use App\Course;
use App\CreditType;
use App\Firefighter;
use App\ForeignRelations;
use App\History;
use App\Http\Helpers\Helper;
use App\Mail\SendCertificate;
use App\Organization;
use App\Prerequisite;
use App\CertificateRejectedReasons;
use App\FirefighterCertificates;
use App\CertificateStatus;

use App\Jobs\CertificationApprovedJob;
use App\Jobs\CertificationFailedJob;
use App\Jobs\CertificationResheduleJob;
use App\Jobs\CertificationResheduleFailedJob;
use App\Jobs\FirefighterCertificateAwardedJob;
use App\Jobs\SendCertificateResheduleJob;

use PDF;

class CertificationController extends Controller
{
    public function index()
    {
        return view('certification.index')
            ->withTitle('Certifications')
            ->withCertifications(Certification::select(DB::raw('COUNT(id) as count'))
                ->where('certification_cycle_end', '>=', Carbon::now()->toDateString())
                ->orWhere('renewed_expiry_date', '>=', Carbon::now()->toDateString())
                ->orWhere('renewable','=', 0)
                ->first());
    }

    public function paginate()
    {
        $per_page = Helper::per_page();
        $query = Certification::select('id', 'prefix_id', 'title', 'renewable', 'certification_cycle_start', 'certification_cycle_end', 'renewed_expiry_date', DB::raw("(SELECT count(firefighter_certificates.certificate_id) FROM firefighter_certificates WHERE firefighter_certificates.certificate_id = certifications.id AND firefighter_certificates.status = 'applied') AS certificates_request_count"));
        $query = Helper::filter('certifications', request()->except(['status']), $query, ['renewable', 'renewal_period', 'no_of_credit_types', 'admin_ceu', 'tech_ceu']);
        $certifications = $query->orderByRaw("CASE WHEN certificates_request_count > 0 THEN 1 ELSE 2 END ASC")->where('certification_cycle_end', '>=', Carbon::now()->toDateString())->orWhere('renewed_expiry_date', '>=', Carbon::now()->toDateString())->orWhere('renewable','=', 0)->orderBy('certificates_request_count', 'desc')->paginate($per_page)->appends(request()->query());

        return view('certification.paginate')->with('certifications', $certifications);
    }

    public function create()
    {
        $credit_types = CreditType::select('id', 'prefix_id', 'description')->get();
        $courses = Course::select('id', 'prefix_id', 'course_name')->get();
        $certifications = Certification::select('id', 'prefix_id', 'title')->get();

        return view('certification.create', ['title' => 'Add Certification', 'credit_types' => $credit_types, 'courses' => $courses, 'certifications' => $certifications]);
    }

    public function store(Request $request)
    {
        $rules = [
            'prefix_id' => 'required|unique:certifications|min:5|max:5|alpha_num',
            'title' => 'required|unique:certifications|max:35',
            'renewable' => 'required|numeric',
            // 'renewal_period'    =>  'required',
            // 'no_of_credit_types'    =>  'required',
            // 'credit_types'    =>  'required',
        ];

        if ($request->renewable == '1') {
            $rules['admin_ceu'] = 'required';
            $rules['tech_ceu'] = 'required';
            $rules['no_of_credit_types'] = 'required';
        }

        // if($request->no_of_pre_req_cert){
        //     $rules['pre_req_cert'] = 'required';
        //     $messages['pre_req_cert.required'] = 'The pre requisite certificate field is required.';
        // }

        // if($request->no_of_pre_req_course){
        //     $rules['pre_req_course'] = 'required';
        //     $messages['pre_req_course.required'] = 'The pre requisite course field is required.';
        // }

        // if( $request->no_of_pre_req_course ){
        //     $rules['pre_req_course'] = 'required|array';
        //     $rules['pre_req_course'].='|size:'.$request->no_of_pre_req_course;
        // }

        $messages['prefix_id.required'] = 'The Certificate ID field is required.';
        $messages['prefix_id.unique'] = 'The Certificate ID field has already been taken.';
        $messages['prefix_id.min'] = 'The Certificate ID must have minimum 5 characters.';
        $messages['prefix_id.max'] = 'The Certificate ID must have maximum 5 characters.';
        $messages['prefix_id.alpha_num'] = 'The Certificate ID must have alphanumeric characters.';

        $this->validate($request, $rules, $messages);

        // Validation No of credit_types is equal credit_types value.
        if (!empty($request->no_of_credit_types)) {
            if ($request->no_of_credit_types) {
                $no_of_credit_types = count(array_filter($request->credit_types));
                if ($no_of_credit_types != $request->no_of_credit_types) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'credit_types' => ['The Credit Type field is required.']
                    ]);
                }
            }
        }

        // Validation No of certificate is equal certificate value.
//        if($request->no_of_pre_req_cert){
//            $no_of_pre_req_certificate_count = count(array_filter($request->pre_req_cert));
//            if($no_of_pre_req_certificate_count != $request->no_of_pre_req_cert){
//                throw \Illuminate\Validation\ValidationException::withMessages([
//                    'pre_req_cert' => ['The No. of Pre-requisite Credential Titles field is required.']
//                ]);
//            }
//        }

        // Validation No of courses is equal courses value.
//        if($request->no_of_pre_req_course){
//            $no_of_pre_req_course_count = count(array_filter($request->pre_req_course));
//            if($no_of_pre_req_course_count != $request->no_of_pre_req_course){
//                throw \Illuminate\Validation\ValidationException::withMessages([
//                    'pre_req_course' => ['The Pre-requisite Courses field is required.']
//                ]);
//                // return response()->json(['status'=> false,'msg'=>'Please Select Correct No. of Pre-requisite Course(s).']);
//            }
//        }

        // Validation for unique credit types
        if (!empty($request->no_of_credit_types)) {
            $credit_types = [];
            foreach ($request->credit_types as $credit_type) {
                if (in_array($credit_type, $credit_types)) {
                    return response()->json(['status' => false, 'msg' => 'Credit type selection must be unique']);
                } else {
                    $credit_types[] = $credit_type;
                }
            }
        }

        // Validation for unique prerequisite certificates
//        if($request->no_of_pre_req_cert){
//            $pre_req_certs = [];
//            foreach ($request->pre_req_cert as $pre_req_cert){
//                if(in_array($pre_req_cert,$pre_req_certs)){
//                    return response()->json(['status'=> false,'msg'=>'Prerequisite certificates selection must be unique']);
//                }else{
//                    $pre_req_certs[] = $pre_req_cert;
//                }
//            }
//        }

        // Validation for unique prerequisite courses
//        if($request->no_of_pre_req_course){
//            $pre_req_courses = [];
//            foreach ($request->pre_req_course as $pre_req_course){
//                if(in_array($pre_req_course,$pre_req_courses)){
//                    return response()->json(['status'=> false,'msg'=>'Prerequisite courses selection must be unique']);
//                }else{
//                    $pre_req_courses[] = $pre_req_course;
//                }
//            }
//        }

        $certificate = new Certification();
        $certificate->prefix_id = strtoupper($request->prefix_id);
        $certificate->title = $request->title;
        $certificate->renewable = (int)$request->renewable ? 1 : 0;
        $certificate->renewal_period = $certificate->renewable ? $request->renewal_period : null;
        $certificate->certification_cycle_start = $request->renewable ? $request->certification_cycle_start : null;
        $certificate->certification_cycle_end = $request->renewable ? $request->certification_cycle_end : null;
        $certificate->renewed_expiry_date = $request->renewable ? $request->certification_cycle_end : null;
        $certificate->no_of_credit_types = $request->renewable ? (int)$request->no_of_credit_types : null;
        $certificate->no_of_pre_req_cert = $request->no_of_pre_req_cert ? (int)$request->no_of_pre_req_cert : null;
        $certificate->no_of_pre_req_course = $request->no_of_pre_req_course ? (int)$request->no_of_pre_req_course : null;
        $certificate->admin_ceu = $request->renewable ? (float)$request->admin_ceu : null;
        $certificate->tech_ceu = $request->renewable ? (float)$request->tech_ceu : null;
        $certificate->comment = $request->comment;
        $certificate->created_by = Auth::user()->id;
        if (!$certificate->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate. Please try again.']);
        }

        if (!empty($request->no_of_credit_types)) {
            foreach ($request->credit_types as $credit_type) {
                $foreign_relation = new ForeignRelations();
                $foreign_relation->foreign_id = $certificate->id;
                $foreign_relation->module = 'certifications';
                $foreign_relation->name = 'credit_types';
                $foreign_relation->value = $credit_type;
                if (!$foreign_relation->save()) {
                    $this->reverse_store_process($certificate->id);
                    return response()->json(['status' => false, 'msg' => 'Failed to save credit types metadata. Please try again.']);
                }
            }
        }

        if($request->no_of_pre_req_cert){
            foreach ($request->pre_req_cert as $pre_req_cert){
                $prerequisite = new Prerequisite();
                $prerequisite->certification_id = $certificate->id;
                $prerequisite->pre_req_certificate_id = $pre_req_cert;
                if(!$prerequisite->save()){
                    return response()->json(['status'=>false,'msg'=>'Failed to save prerequisite certificate(s). Please try again.']);
                }
            }
        }

        if($request->no_of_pre_req_course){
            foreach ($request->pre_req_course as $pre_req_course){
                $prerequisite = new Prerequisite();
                $prerequisite->certification_id = $certificate->id;
                $prerequisite->pre_req_course_id = $pre_req_course;
                if(!$prerequisite->save()){
                    return response()->json(['status'=>false,'msg'=>'Failed to save prerequisite course(s). Please try again.']);
                }
            }
        }
        return response()->json(['status' => true, 'msg' => 'Created Successfully !', "data" => Certification::where('id', $certificate->id)->get()->first()]);
    }

    public function view_all_history()
    {
      
        return view('certification.history.index');
    }
    public function reverse_store_process($id)
    {
        try {
            $response = Certification::where('id', $id)->delete();
        } catch (\Exception $error) {
            return false;
        }
        if ($response) {
            ForeignRelations::where('foreign_id', $id)->where('module', 'certifications')->delete();
            History::where('foreign_id', $id)->where('module', 'certifications')->delete();
            return true;
        }
        return false;
    }

    public function show($id)
    {
        $certificate = Certification::find($id);
        if ($certificate && $certificate->count()) {
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp) {
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if (!empty($credit_types)) {
                $temps = ForeignRelations::select('value')->where('foreign_id', $id)->where('module', 'certifications')->where('name', 'credit_types')->get();
                foreach ($temps as $temp) {
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types, true) : '';
            }
//            Helper::print_r($foreign_relations,true);

            $pre_req_certs = Prerequisite::select('prerequisites.id', 'prerequisites.certification_id', 'prerequisites.pre_req_course_id', 'prerequisites.pre_req_certificate_id', 'certifications.prefix_id', 'certifications.title')
                ->leftJoin('certifications', 'prerequisites.pre_req_certificate_id', '=', 'certifications.id')
                ->where('certification_id', $certificate->id)->whereNotNull('pre_req_certificate_id')->get();

            $pre_req_courses = Prerequisite::select('prerequisites.id', 'prerequisites.certification_id', 'prerequisites.pre_req_course_id', 'prerequisites.pre_req_certificate_id', 'courses.prefix_id', 'courses.course_name')
                ->leftJoin('courses', 'prerequisites.pre_req_course_id', '=', 'courses.id')
                ->where('certification_id', $certificate->id)->whereNotNull('pre_req_course_id')->get();

            $prereq_certificates = [];
            if ($pre_req_certs && $pre_req_certs->count()) {
                foreach ($pre_req_certs as $pre_req_cert) {
                    $prereq_certificates[] = "{$pre_req_cert->title} ($pre_req_cert->prefix_id)";
                }
            }

            $prereq_courses = [];
            if ($pre_req_courses && $pre_req_courses->count()) {
                foreach ($pre_req_courses as $pre_req_course) {
                    $prereq_courses[] = "{$pre_req_course->course_name} ($pre_req_course->prefix_id)";
                }
            }

            // show credit type list
            $all_credit_types = CreditType::select('id', 'prefix_id', 'description')->get();

            $last_updated = Helper::get_last_updated('certifications', $id);

            // show courses list
            $all_courses = Course::select('id', 'prefix_id', 'course_name')->get();

            // show certifications list
            $all_certifications = Certification::select('id', 'prefix_id', 'title')->get();

            return view('certification.show', ['title' => 'View Certification', 'certificate' => $certificate, 'prereq_courses' => $prereq_courses, 'credit_types' => $credit_types, 'prereq_certificates' => $prereq_certificates, 'last_updated' => $last_updated, 'db_credit_types' => $db_credit_types, 'foreign_relations' => $foreign_relations, 'pre_req_certs' => $pre_req_certs, 'pre_req_courses' => $pre_req_courses, 'all_credit_types' => $all_credit_types, 'all_courses' => $all_courses, 'all_certifications' => $all_certifications]);
        } else {
            return view('404');
        }
    }

    public function edit($id)
    {
        $certificate = Certification::find($id);
        if ($certificate && $certificate->count()) {
            $temps = CreditType::all()->toArray();
            foreach ($temps as $temp) {
                $credit_types[$temp['id']] = $temp;
            }
            $foreign_relations = [];
            if (!empty($credit_types)) {
                $temps = ForeignRelations::select('value')->where('foreign_id', $id)->where('module', 'certifications')->where('name', 'credit_types')->get();
                foreach ($temps as $temp) {
                    $foreign_relations[$temp->value] = $credit_types[$temp->value]['description'];
                }
                $db_credit_types = $credit_types;
                $credit_types = $credit_types ? json_encode($credit_types, true) : '';
            }

            $pre_req_certs = Prerequisite::select('prerequisites.id', 'prerequisites.certification_id', 'prerequisites.pre_req_course_id', 'prerequisites.pre_req_certificate_id', 'certifications.prefix_id', 'certifications.title')
                ->leftJoin('certifications', 'prerequisites.pre_req_certificate_id', '=', 'certifications.id')
                ->where('certification_id', $certificate->id)->whereNotNull('pre_req_certificate_id')->get();

            $pre_req_courses = Prerequisite::select('prerequisites.id', 'prerequisites.certification_id', 'prerequisites.pre_req_course_id', 'prerequisites.pre_req_certificate_id', 'courses.prefix_id', 'courses.course_name')
                ->leftJoin('courses', 'prerequisites.pre_req_course_id', '=', 'courses.id')
                ->where('certification_id', $certificate->id)->whereNotNull('pre_req_course_id')->get();

            // show credit type list
            $all_credit_types = CreditType::select('id', 'prefix_id', 'description')->get();

            // show courses list
            $all_courses = Course::select('id', 'prefix_id', 'course_name')->get();

            // show certifications list
            $all_certifications = Certification::select('id', 'prefix_id', 'title')->get();

            return view('certification.edit', ['title' => 'Edit Course', 'certificate' => $certificate, 'credit_types' => $credit_types, 'db_credit_types' => $db_credit_types, 'foreign_relations' => $foreign_relations, 'pre_req_certs' => $pre_req_certs, 'pre_req_courses' => $pre_req_courses, 'all_credit_types' => $all_credit_types, 'all_courses' => $all_courses, 'all_certifications' => $all_certifications]);
        } else {
            return view('404');
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'prefix_id' => "required|unique:certifications,prefix_id,{$id}|min:5|max:5|alpha_num",
            'title' => "required|max:35|unique:certifications,title,{$id}",
            'renewable' => 'required|numeric',
            // 'renewal_period'    =>  'required',
            // 'no_of_credit_types'    =>  'required',
            // 'credit_types'    =>  'required',
        ];

        if ($request->renewable == '1') {
            $rules['admin_ceu'] = 'required';
            $rules['tech_ceu'] = 'required';
            $rules['no_of_credit_types'] = 'required';
        }

        // if(!empty($request->admin_ceu) && (!empty($request->tech_ceu))){
        //     $rules['tech_ceu'] = 'required|numeric';
        // }

        // if($request->no_of_pre_req_cert){
        //     $rules['pre_req_cert'] = 'required';
        //     $messages['pre_req_cert.required'] = 'The pre requisite certificate field is required.';
        // }

        // if($request->no_of_pre_req_course){
        //     $rules['pre_req_course'] = 'required';
        //     $messages['pre_req_course.required'] = 'The pre requisite course field is required.';
        // }

        // if( $request->no_of_pre_req_course ){
        //     $rules['pre_req_course'] = 'required|array';
        //     $rules['pre_req_course'].='|size:'.$request->no_of_pre_req_course;
        // }

        $messages['prefix_id.required'] = 'The Certificate ID field is required.';
        $messages['prefix_id.unique'] = 'The Certificate ID field has already been taken.';
        $messages['prefix_id.min'] = 'The Certificate ID must have minimum 5 characters.';
        $messages['prefix_id.max'] = 'The Certificate ID must have maximum 5 characters.';
        $messages['prefix_id.alpha_num'] = 'The Certificate ID must have alphanumeric characters.';

        $this->validate($request, $rules, $messages);

        // Validation No of credit_types is equal credit_types value.
        if (!empty($request->no_of_credit_types)) {
            if ($request->no_of_credit_types) {
                $no_of_credit_types = count(array_filter($request->credit_types));
                if ($no_of_credit_types != $request->no_of_credit_types) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'no_of_credit_types' => ['Wrong No. of Credit type Selection.']
                    ]);
                }
            }
        }

        // Validation No of credential is equal credential value.
//        if($request->no_of_pre_req_cert){
//            $no_of_pre_req_certificate_count = count(array_filter($request->pre_req_cert));
//            if($no_of_pre_req_certificate_count != $request->no_of_pre_req_cert){
//                throw \Illuminate\Validation\ValidationException::withMessages([
//                    'no_of_pre_req_cert' => [' Wrong No. of Pre-requisite Credential Titles Selection.']
//                ]);
//                // return response()->json(['status'=> false,'msg'=>'Please Select Correct No. of Pre-requisite Course(s).']);
//            }
//        }

        // Validation No of courses is equal courses value.
//        if($request->no_of_pre_req_course){
//            $no_of_pre_req_course_count = count(array_filter($request->pre_req_course));
//            if($no_of_pre_req_course_count != $request->no_of_pre_req_course){
//                throw \Illuminate\Validation\ValidationException::withMessages([
//                    'no_of_pre_req_course' => [' Wrong No. of Pre-requisite Course(s) Selection.']
//                ]);
//                // return response()->json(['status'=> false,'msg'=>'Please Select Correct No. of Pre-requisite Course(s).']);
//            }
//        }

        // Validation for unique credit types
        if (!empty($request->no_of_credit_types)) {
            $credit_types = [];
            foreach ($request->credit_types as $credit_type) {
                if (in_array($credit_type, $credit_types)) {
                    return response()->json(['status' => false, 'msg' => 'Credit type selection must be unique']);
                } else {
                    $credit_types[] = $credit_type;
                }
            }
        }

        // Validation for correct Pre-requisite selection
//        if(!empty($request->no_of_pre_req_cert)){
//            if(in_array($id,$request->pre_req_cert)){
//                return response()->json(['status'=> false,'msg'=>'Wrong Pre-requisite selection']);
//            }
//        }

        // Validation for unique prerequisite certificates
//        if($request->no_of_pre_req_cert){
//
//            // A ceritificate not be Self Prerequisite
//            if(in_array($id,$request->pre_req_cert)){
//                return response()->json(['status'=> false,'msg'=>'A certificate not be self prerequisite']);
//            }
//
//            $pre_req_certs = [];
//            foreach ($request->pre_req_cert as $pre_req_cert){
//                if(in_array($pre_req_cert,$pre_req_certs)){
//                    return response()->json(['status'=> false,'msg'=>'Prerequisite certificates selection must be unique']);
//                }else{
//                    $pre_req_certs[] = $pre_req_cert;
//                }
//            }
//        }

        // Validation for unique prerequisite courses
//        if($request->no_of_pre_req_course){
//            $pre_req_courses = [];
//            foreach ($request->pre_req_course as $pre_req_course){
//                if(in_array($pre_req_course,$pre_req_courses)){
//                    return response()->json(['status'=> false,'msg'=>'Prerequisite courses selection must be unique']);
//                }else{
//                    $pre_req_courses[] = $pre_req_course;
//                }
//            }
//        }

        $error = '';
        $additional_changes = [];
        $certificate = Certification::find($id);
        $certificate->prefix_id = strtoupper($request->prefix_id);
        $certificate->title = $request->title;
        $certificate->renewable = (int)$request->renewable ? 1 : 0;
        $certificate->renewal_period = $certificate->renewable ? $request->renewal_period : null;
        $certificate->certification_cycle_start = $request->renewable ? $request->certification_cycle_start : null;
        $certificate->certification_cycle_end = $request->renewable ? $request->certification_cycle_end : null;
        $certificate->renewed_expiry_date = $request->renewable ? $request->certification_cycle_end : null;
        $certificate->no_of_credit_types = $request->renewable ? (int)$request->no_of_credit_types : null;
        $certificate->no_of_pre_req_cert = $request->no_of_pre_req_cert ? (int) $request->no_of_pre_req_cert : null;
        $certificate->no_of_pre_req_course = $request->no_of_pre_req_course ? (int) $request->no_of_pre_req_course : null;
        $certificate->admin_ceu = $request->renewable ? (float)$request->admin_ceu : null;
        $certificate->tech_ceu = $request->renewable ? (float)$request->tech_ceu : null;
        $certificate->comment = $request->comment;
        $certificate->created_by = Auth::user()->id;

        $prev_object = $certificate->getOriginal();
        $new_object = $certificate->getAttributes();

        if (!$certificate->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate. Please try again.']);
        }

        // update expiry date in awarded certificate table only for initial cert.
        if(request()->renewable) {
            $awarded_cert_check_if_exists = AwardCertificate::where('certificate_id', $id)->get()->first();
            if($awarded_cert_check_if_exists) {
                $awarded_cert = AwardCertificate::where('certificate_id', $id)
                ->where('stage','initial')
                ->update([
                    'lapse_date' => request()->certification_cycle_end
                ]);
                if (!$awarded_cert) {
                    return response()->json(['status' => false, 'msg' => 'Failed to update awarded certificate lapse date. Please try again.']);
                }
            }
        }

        // Detect type change and update
        if (!empty($request->no_of_credit_types)) {
            $foreign_relations = ForeignRelations::where('foreign_id', $id)->where('module', 'certifications')->where('name', 'credit_types')->get();
            $credit_type_ids = [];
            $credit_types = [];
            foreach ($foreign_relations as $foreign_relation) {
                array_push($credit_type_ids, $foreign_relation->id);
                array_push($credit_types, $foreign_relation->value);
            }
            if ((sizeof($credit_types) !== sizeof($request->credit_types)) || sizeof(array_diff($credit_types, $request->credit_types))) {
                $additional_changes[] = [
                    'label' => 'credit_types',
                    'prev' => $credit_types,
                    'new' => $request->credit_types,
                ];
                foreach ($request->credit_types as $credit_type) {
                    $foreign_relation = new ForeignRelations();
                    $foreign_relation->foreign_id = $certificate->id;
                    $foreign_relation->module = 'certifications';
                    $foreign_relation->name = 'credit_types';
                    $foreign_relation->value = $credit_type;
                    if (!$foreign_relation->save()) {
                        $error .= "<li>Failed to save types metadata.</li>";
                    }
                }
                foreach ($credit_type_ids as $credit_type_id) {
                    ForeignRelations::where('id', $credit_type_id)->delete();
                }
            }
        }

        // Detect prerequisite certificate change and update
        $foreign_relations = Prerequisite::where('certification_id', $id)->whereNotNull('pre_req_certificate_id')->get();
        $ids = [];
        $arrays = [];
        foreach ($foreign_relations as $foreign_relation) {
            array_push($ids, $foreign_relation->id);
            array_push($arrays, $foreign_relation->pre_req_certificate_id);
        }

//        if($request->pre_req_cert){
//            if( (sizeof($arrays)!==sizeof($request->pre_req_cert)) || sizeof(array_diff($arrays,$request->pre_req_cert))){
//                $additional_changes[] = [
//                    'label'  =>  'prerequisite_certificates',
//                    'prev'   =>  $arrays,
//                    'new'    =>  $request->pre_req_cert,
//                ];
//                foreach ($request->pre_req_cert as $array){
//                    $prerequisite = new Prerequisite();
//                    $prerequisite->certification_id = $certificate->id;
//                    $prerequisite->pre_req_certificate_id = $array;
//                    if(!$prerequisite->save()){
//                        $error.="<li>Failed to save prerequisites certificate.</li>";
//                    }
//                }
//                foreach ($ids as $single_id){
//                    Prerequisite::where('id',$single_id)->delete();
//                }
//            }
//        }else{
//            foreach ($ids as $single_id){
//                Prerequisite::where('id',$single_id)->delete();
//            }
//        }

        // Detect prerequisite course change and update
        $foreign_relations = Prerequisite::where('certification_id', $id)->whereNotNull('pre_req_course_id')->get();
        $ids = [];
        $arrays = [];
        foreach ($foreign_relations as $foreign_relation) {
            array_push($ids, $foreign_relation->id);
            array_push($arrays, $foreign_relation->pre_req_course_id);
        }

//        if($request->pre_req_course){
//            if( (sizeof($arrays)!==sizeof($request->pre_req_course)) || sizeof(array_diff($arrays,$request->pre_req_course))){
//                $additional_changes[] = [
//                    'label'  =>  'prerequisite_courses',
//                    'prev'   =>  $arrays,
//                    'new'    =>  $request->pre_req_course,
//                ];
//                foreach ($request->pre_req_course as $array){
//                    $prerequisite = new Prerequisite();
//                    $prerequisite->certification_id = $certificate->id;
//                    $prerequisite->pre_req_course_id = $array;
//                    if(!$prerequisite->save()){
//                        $error.="<li>Failed to save prerequisites course.</li>";
//                    }
//                }
//                foreach ($ids as $single_id){
//                    Prerequisite::where('id',$single_id)->delete();
//                }
//            }
//        }else{
//            foreach ($ids as $single_id){
//                Prerequisite::where('id',$single_id)->delete();
//            }
//        }

        // Create History
        $key_label = array(
            'short_title' => 'short title',
            'renewal_period' => 'renewal period',
            'no_of_credit_types' => 'no of credit types',
            'no_of_pre_req_cert' => 'no of prerequisite certificate',
            'no_of_pre_req_course' => 'no of prerequisite course',
            'admin_ceu' => 'admin ceu',
            'tech_ceu' => 'tech ceu',
        );

        $response = Helper::create_history($prev_object, $new_object, $certificate->id, 'certifications', $key_label, $additional_changes);
        if (!$response) {
            $error .= "<li>Failed to create update course history</li>";
        }

        if ($error) {
            $msg = '<p>Certificate Updated. Some errors occurred are stated:</p>';
            return response()->json(array('status' => false, 'msg' => "{$msg}<ul class='pl-4'>{$error}</ul>"));
        } else {
            return response()->json(array('status' => true, 'msg' => 'Updated Successfully !'));
        }
    }

    public function destroy($id)
    {
        $chech_prerequisite = Prerequisite::where('certification_id', $id)->get();
        if ($chech_prerequisite->count() > 0) {
            return response()->json(['status' => false, 'msg' => 'One or more records are associated with this record.']);
        }

        if (!$this->reverse_store_process($id)) {
            return response()->json(['status' => false, 'msg' => 'One or more records are associated with this record.']);
        }
        return response()->json(array('status' => true, 'msg' => 'Deleted Successfully !'));
    }

    public function search_certifications(Request $request)
    {
        $per_page = Helper::per_page();
        $certifications = Certification::select('id', 'prefix_id', 'title', 'short_title')->where(function ($query) use ($request, $per_page) {
            $query->where('prefix_id', 'like', "%{$request->search}%")
                ->orWhere('title', 'like', "%{$request->search}%")
                ->orWhere('short_title', 'like', "%{$request->search}%")
                ->limit($per_page);
        })->get();

        return response()->json($certifications);
    }

    public function history($id)
    {
        $histories = History::where('foreign_id', $id)->where('module', 'certifications')->orderBy('created_at', 'desc')->get();
        if ($histories && $histories->count()) {
            foreach ($histories as $key => $history) {
                $array = json_decode($history->data, true);
                foreach ($array as $key_2 => $data) {
                    $label = strtolower($data['label']);
                    $prev = null;
                    $new = null;
                    if ($label === 'renewable') {
                        $prev = $data['prev'] ? 'Yes' : 'No';
                        $new = $data['new'] ? 'Yes' : 'No';
                    } elseif ($label === 'credit_types') {
                        $prev = CreditType::select('description')->whereIn('id', $data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('description')->toArray() : 'N/A';
                        $new = CreditType::select('description')->whereIn('id', $data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('description')->toArray() : 'N/A';
                    } elseif ($label === 'prerequisite_certificates') {
                        $prev = Certification::select('title')->whereIn('id', $data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('title')->toArray() : 'N/A';
                        $new = Certification::select('title')->whereIn('id', $data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('title')->toArray() : 'N/A';
                    } elseif ($label === 'prerequisite_courses') {
                        $prev = Course::select('course_name')->whereIn('id', $data['prev'])->get();
                        $prev = sizeof($prev) ? collect($prev)->pluck('course_name')->toArray() : 'N/A';
                        $new = Course::select('course_name')->whereIn('id', $data['new'])->get();
                        $new = sizeof($new) ? collect($new)->pluck('course_name')->toArray() : 'N/A';
                    }
                    if (isset($prev) && isset($new)) {
                        $array[$key_2]['prev'] = $prev;
                        $array[$key_2]['new'] = $new;
                    }
                    $histories[$key]->data = $array;
                }
            }
            return view('partials.update-history')->with('histories', $histories);
        }
    }

    public function search_firefighter(Request $request)
    {
        $per_page = Helper::per_page();
        $query = Firefighter::select('id', 'prefix_id', 'f_name', 'm_name', 'l_name')->whereNull('is_archive')->where(function ($query) use ($request) {
            $query->where('id', 'like', "%$request->search%")
                ->orWhere('prefix_id', 'like', "%$request->search%")
                ->orWhere('f_name', 'like', "%$request->search%")
                ->orWhere('m_name', 'like', "%$request->search%")
                ->orWhere('l_name', 'like', "%$request->search%")
                ->orWhereRaw("REPLACE(Concat(f_name,' ',m_name,' ',l_name),'  ',' ') LIKE '%{$request->search}%'");
        });

        if ($request->ids_not_in) {
            $query = $query->whereNotIn('id', $request->ids_not_in);
        }
        $firefighters = $query->limit($per_page)->get();

        if ($firefighters && $firefighters->count()) {

            foreach ($firefighters as $key => $firefighter) {
                $firefighters[$key]->eligibility = true;
                $firefighters[$key]->name = $firefighter->m_name ? "$firefighter->f_name $firefighter->m_name $firefighter->l_name" : "$firefighter->f_name $firefighter->l_name";

                // Check if firefighter is already awarded
                $awarded = AwardCertificate::select('id')->where('certificate_id', $request->certification_id)->where('firefighter_id', $firefighter->id)->limit(1)->first();
                $firefighters[$key]->awarded = isset($awarded->id) && $awarded->id;

                // Check if firefighter is eligible
                $prerequisites = Prerequisite::where('certification_id', $request->certification_id)->get();
                $has_prerequisites = $prerequisites && $prerequisites->count();
                if ($has_prerequisites) {
                    foreach ($prerequisites as $prerequisite) {
                        if ($prerequisite->pre_req_certificate_id) {
                            $awarded_prerequisite = AwardCertificate::select('id')->where('firefighter_id', $firefighter->id)->where('certificate_id', $prerequisite->pre_req_certificate_id)->limit(1)->first();
                            if (isset($awarded_prerequisite->id) && $awarded_prerequisite->id) {
                                continue;
                            } else {
                                $firefighters[$key]->eligibility = false;
                                break;
                            }
                        } elseif ($prerequisite->pre_req_course_id) {
                            $completed_course = CompletedCourse::select('id')->where('firefighter_id', $firefighter->id)->where('course_id', $prerequisite->pre_req_course_id)->limit(1)->first();
                            if (isset($completed_course->id) && $completed_course->id) {
                                continue;
                            } else {
                                $firefighters[$key]->eligibility = false;
                                break;
                            }
                        }
                    }
                }
            }
            return response()->json($firefighters);
        }
        return response()->json([]);
    }

    public function award($id)
    {
        $certificate = Certification::find($id);
        return view('certification.award')->with('title', 'Award Certificate')->with('certification', $certificate);
    }

    public function award_firefighters(Request $request, $id)
    {
        if ($request->firefighter_id && !empty($request->firefighter_id)) {

            $this->validate($request, ['organization' => 'required|numeric']);

            // Check if firefighter is already awarded
            foreach ($request->firefighter_id as $firefighter_id) {
                $awarded = AwardCertificate::select('id')->where('certificate_id', $id)->where('firefighter_id', $firefighter_id)->limit(1)->first();
                if (isset($awarded->id) && $awarded->id)
                    return response()->json(['status' => false, 'msg' => 'One or more firefighter is already awarded this certificate.']);
            }

            // Check if firefighter is eligible
            $prerequisites = Prerequisite::where('certification_id', (int)$id)->get();
            $has_prerequisites = $prerequisites && $prerequisites->count();
            if ($has_prerequisites) {
                foreach ($prerequisites as $prerequisite) {
                    foreach ($request->firefighter_id as $firefighter_id) {
                        if ($prerequisite->pre_req_certificate_id) {
                            $awarded_prerequisite = AwardCertificate::select('id')->where('firefighter_id', $firefighter_id)->where('certificate_id', $prerequisite->pre_req_certificate_id)->limit(1)->first();
                            if (isset($awarded_prerequisite->id) && $awarded_prerequisite->id) {
                                continue;
                            } else {
                                return response()->json(['status' => false, 'msg' => 'One or more firefighter is not eligible this certificate.']);
                            }
                        } elseif ($prerequisite->pre_req_course_id) {
                            $completed_course = CompletedCourse::select('id')->where('firefighter_id', $firefighter_id)->where('course_id', $prerequisite->pre_req_course_id)->limit(1)->first();
                            if (isset($completed_course->id) && $completed_course->id) {
                                continue;
                            } else {
                                return response()->json(['status' => false, 'msg' => 'One or more firefighter is not eligible this certificate.']);
                            }
                        }
                    }
                }
            }

            // Award Certificate
            $certificate = Certification::find($id);
            $success = 0;
            $fails = 0;
            foreach ($request->firefighter_id as $firefighter_id) {
                $award_certificate = new AwardCertificate();
                $award_certificate->certificate_id = $id;
                $award_certificate->firefighter_id = $firefighter_id;
                $award_certificate->organization_id = $request->organization;
                $award_certificate->issue_date = carbon::now()->toDateString();
                if ($certificate->renewable && $certificate->renewal_period) {
                    /*$award_certificate->lapse_date = date('Y-m-d', strtotime($certificate->renewal_period));*/
                    $award_certificate->lapse_date = $certificate->renewed_expiry_date ? $certificate->renewed_expiry_date : $certificate->certification_cycle_end;
                }
                if ($award_certificate->save()) {
                    $success++;
                    if ($request->send_email) {
                        $firefighter = Firefighter::find($firefighter_id);
                        $data = array(
                            'title' => config('app.name'),
                            'firefighter' => $firefighter,
                            'certificate' => $certificate,
                            'issue_date' => Helper::date_format($award_certificate->issue_date),
                            'lapse_date' => $award_certificate->lapse_date ? Helper::date_format($award_certificate->lapse_date) : null,
                        );

                        $pdf = PDF::loadView('firefighter.awarded-certificate', $data);
                        $attachment = $pdf->output();
                        Mail::to($firefighter->work_email)->send(new SendCertificate($firefighter, $certificate, $attachment));
                    }
                } else {
                    $fails++;
                }
            }

            if ($success && $fails) {
                return response()->json(['status' => false, 'msg' => 'Failed to award certificate to some of the firefighters.']);
            } elseif ($fails) {
                return response()->json(['status' => false, 'msg' => 'Failed to award certificate.']);
            } elseif ($success) {
                return response()->json(['status' => true, 'msg' => 'Certificate awarded successfully !']);
            }
        }
        return response()->json(['status' => false, 'msg' => 'Invalid Request.']);
    }

    public function search_organization(Request $request)
    {
        $per_page = Helper::per_page();
        $organization = Organization::where(function ($query) use ($request, $per_page) {
            $query->where('prefix_id', 'like', "%{$request->search}%")
                ->orWhere('name', 'like', "%{$request->search}%")
                ->limit($per_page);
        })->get();

        return response()->json($organization);
    }


    public function view_firefighters($certificate_id)
    {

        $total_count = FirefighterCertificates::select(DB::raw('COUNT(id) as count'))->where('certificate_id', $certificate_id)->first();

        $certification = Certification::find($certificate_id)->select('prefix_id', 'title')->first();

        return view('certification.firefighter-certifications.index')->with('title', 'View Firefighter Credentials')->with('certificate_id', $certificate_id)->with('certification', $certification)->with('total_count', $total_count);
    }

    public function view_firefighters_paginate(Request $request, $certificate_id)
    {

        $per_page = Helper::per_page();

        $query = FirefighterCertificates::where('certificate_id', $certificate_id)
            ->leftJoin('certifications', 'certifications.id', '=', 'firefighter_certificates.certificate_id')
            ->leftJoin('firefighters', 'firefighters.id', '=', 'firefighter_certificates.firefighter_id')
            ->leftJoin('certificate_rejected_reasons', 'certificate_rejected_reasons.firefighter_certificates_id', '=', 'firefighter_certificates.id')
            ->leftJoin('certificate_statuses', 'certificate_statuses.firefighter_certificates_id', '=', 'firefighter_certificates.id')
            ->select('firefighter_certificates.id', 'firefighter_certificates.status', 'firefighter_certificates.test_status as test_result', 'certifications.id as certificate_id', 'certifications.prefix_id', 'certifications.title', 'firefighters.f_name', 'firefighters.id as firefighter_id', 'firefighters.prefix_id as firefighter_prefix_id', 'firefighters.m_name', 'firefighters.l_name', 'certificate_rejected_reasons.reason', DB::raw('max(certificate_statuses.status) as test_status'), 'certificate_statuses.test_date');

        if ($request->firefighter_name) {

            $query = $query->orWhereRaw("concat(firefighters.f_name, ' ', firefighters.l_name) like '%{$request->firefighter_name}%' ")->where('firefighter_certificates.certificate_id', $certificate_id);
        }

        if ($request->prefix_id) {
            $query = $query->having('firefighters.prefix_id', 'like', "%{$request->prefix_id}%");
        }

        if ($request->type) {
            $query = $query->where('firefighter_certificates.status', $request->type);
        }

        $view_firefighters = $query->orderByRaw("FIELD(firefighter_certificates.status, 'applied', 'accepted','rejected')")->orderByRaw("FIELD(certificate_statuses.status, 'none', 'passed','failed')")->groupBy('firefighter_certificates.id')->paginate($per_page)->appends(request()->query());

        return view('certification.firefighter-certifications.paginate', ['view_firefighters' => $view_firefighters]);
    }

    public function approved_firefighters_certifications(Request $request)
    {
        // if(empty($request->firefighter))
        //     return response()->json(array('status'=>false,'msg'=>'Select record(s) to perform action.'));

        $firefighters = $request->firefighter;
        $status = $request->status;

        foreach ($status as $firefighter_id => $status) {

            $firefighter_certificates = FirefighterCertificates::where('id', $firefighter_id)->limit(1)->first();
            if ($status !== $firefighter_certificates->status) {
                $firefighter_certificates->status = $status;
                $firefighter_certificates->save();
            }
        }

        return response()->json(array('status' => true, 'msg' => 'Updated Successfully !'));
    }

    public function view_firefighters_status_index(Request $request, $id)
    {

        $prefix_id = DB::select("select `certifications`.`prefix_id` as `certifications_prefix_id`, `firefighters`.`prefix_id` as `firefighters_prefix_id` from `firefighter_certificates` left join `firefighters` on `firefighters`.`id` = `firefighter_certificates`.`firefighter_id` left join `certifications` on `certifications`.`id` = `firefighter_certificates`.`certificate_id` where `firefighter_certificates`.`id`=" . $id);

        $certificate_status = CertificateStatus::where('firefighter_certificates_id', $id)
            ->leftJoin('firefighter_certificates', 'firefighter_certificates.id', '=', 'certificate_statuses.firefighter_certificates_id')
            ->leftJoin('firefighters', 'firefighters.id', '=', 'firefighter_certificates.firefighter_id')
            ->leftJoin('certifications', 'certifications.id', '=', 'firefighter_certificates.certificate_id')
            ->select('firefighter_certificates.firefighter_id', 'firefighter_certificates.certificate_id', 'firefighter_certificates.status as firefighter_certificates_status', 'certificate_statuses.test_date as certificate_statuses_test_date', 'certificate_statuses.status as certificate_statuses_status', 'firefighters.f_name', 'firefighters.m_name', 'firefighters.l_name', 'certifications.prefix_id', 'certifications.title')
            ->get();

        return view('certification.firefighter-certifications-status.index')->with('title', 'Firefighters Credentials Details')->with('prefix_id', $prefix_id)->with('firefighter_certificate_id', $id);
    }

    public function view_firefighters_status_paginate($id)
    {

        $certificate_status = CertificateStatus::where('firefighter_certificates_id', $id)
            ->leftJoin('firefighter_certificates', 'firefighter_certificates.id', '=', 'certificate_statuses.firefighter_certificates_id')
            ->leftJoin('firefighters', 'firefighters.id', '=', 'firefighter_certificates.firefighter_id')
            ->leftJoin('certifications', 'certifications.id', '=', 'firefighter_certificates.certificate_id')
            ->select('firefighter_certificates.id', 'firefighter_certificates.firefighter_id', 'firefighter_certificates.certificate_id', 'firefighter_certificates.status as firefighter_certificates_status', 'certificate_statuses.test_date as certificate_statuses_test_date', 'certificate_statuses.test_time as certificate_statuses_test_time', 'certificate_statuses.id as certificate_statuses_id', 'certificate_statuses.status as certificate_statuses_status', 'certificate_statuses.firefighter_certificates_id as certificate_statuses_firefighter_certificates_id', 'certificate_statuses.firefighter_id as certificate_statuses_firefighter_id', 'firefighters.f_name', 'firefighters.m_name', 'firefighters.l_name', 'certifications.id as certification_id', 'certifications.prefix_id', 'certifications.title')
            ->get();

        return view('certification.firefighter-certifications-status.paginate', ['view_firefighters' => $certificate_status]);
    }

    public function view_firefighters_status_reshedule(Request $request)
    {

        $rules = [
            'test_date' => 'required|date|after:' . date('Y-m-d'),
            'start_hour' => 'required',
            'start_minute' => 'required',
        ];

        $messages['test_date.after'] = 'Date should be Greater than Today';

        $this->validate($request, $rules, $messages);

        $update_certificate_status = CertificateStatus::where('id', $request->certification_status_id)->first();

        $update_certificate_status->status = 'failed';

        // Update parent table test_status
        $firefighter_certificates = FirefighterCertificates::find($request->certificate_statuses_firefighter_certificates_id);
        $firefighter_certificates->test_status = "none";
        if (!$firefighter_certificates->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate status. Please try again.']);
        }

        if (!$update_certificate_status->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate status. Please try again.']);
        }

        $certificate_status = new CertificateStatus;
        $certificate_status->firefighter_certificates_id = $request->certificate_statuses_firefighter_certificates_id;
        $certificate_status->firefighter_id = $request->certificate_statuses_firefighter_id;
        $certificate_status->test_date = $request->test_date;
        $certificate_status->test_time = $request->start_hour . ':' . $request->start_minute . ':' . '00';
        $certificate_status->status = 'none';

        if (!$certificate_status->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate status. Please try again.']);
        }

        $certification = Certification::where('id', $request->certification_id)->select('title')->first();
        $firefighter = Firefighter::where('id', $request->certificate_statuses_firefighter_id)->select('f_name', 'email')->first();

        // Send Emails to Firefighter
        dispatch(new CertificationResheduleJob($firefighter->email, $firefighter->f_name, $certification->title, $certificate_status->test_date, $certificate_status->test_time, "Schedule for reattempt of test for Credential " . ucfirst($certification->title)));

        return response()->json(['status' => true, 'msg' => 'Updated Successfully !']);
    }

    public function view_firefighters_status_failed_certificate(Request $request)
    {

        $update_certificate_status = CertificateStatus::where('id', $request->certification_status_id)->first();
        $update_certificate_status->status = 'failed';
        // Updating seen status for fighter
        $update_certificate_status->read_status = '0';

        // Update parent table test_status
        $firefighter_certificates = FirefighterCertificates::find($request->certificate_statuses_firefighter_certificates_id);
        $firefighter_certificates->test_status = "failed";
        if (!$firefighter_certificates->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate status. Please try again.']);
        }

        if (!$update_certificate_status->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate status. Please try again.']);
        }

        $certificate_statuses = CertificateStatus::where('firefighter_certificates_id', $request->certificate_statuses_firefighter_certificates_id)
            ->leftJoin('firefighter_certificates', 'firefighter_certificates.id', '=', 'certificate_statuses.firefighter_certificates_id')
            ->select('firefighter_certificates.firefighter_id', 'firefighter_certificates.certificate_id', 'certificate_statuses.test_date', 'certificate_statuses.test_time')
            ->first();



  // history table
  $history  = new certificatehistory();
  $history->firefighter_id = $request->certificate_statuses_firefighter_certificates_id;

  $history->certificate_id = $request->certification_id;
  $history->operation = 'Failed';
  $history->date =  carbon::now()->toDateString();
  $history->save();



        $certification = Certification::where('id', $certificate_statuses->certificate_id)->select('title')->first();
        $firefighter = Firefighter::where('id', $certificate_statuses->firefighter_id)->select('f_name', 'email')->first();

        $firefighter_certificates = FirefighterCertificates::where('certificate_id', $request->certification_id)->where('firefighter_id', $request->certificate_statuses_firefighter_id)->where('status', 'accepted')->where('test_status', 'failed')->count();
        if ($firefighter_certificates > 1) {
            // Send Emails to Firefighter
            dispatch(new CertificationResheduleFailedJob($firefighter->email, $firefighter->f_name, $certification->title, "Test Results of Reattempt for Credential " . ucfirst($certification->title)));
        } else {
            // Send Emails to Firefighter
            dispatch(new CertificationFailedJob($firefighter->email, $firefighter->f_name, $certification->title, $certificate_statuses->test_date, $certificate_statuses->test_time, "Test Results for Credential " . ucfirst($certification->title)));
        }

        return response()->json(['status' => true, 'msg' => 'Updated Successfully !']);
    }

    public function firefighters_award_certificate(Request $request)
    {


        $rules = ['organization' => 'required'];

        $this->validate($request, $rules);

        $check_award_certificate = AwardCertificate::where('certificate_id', $request->certification_id)->where('firefighter_id', $request->certificate_statuses_firefighter_id)->where('stage', 'initial')->count();
        if ($check_award_certificate > 0) {
            return response()->json(['status' => false, 'msg' => 'Firefighter already awarded this certificate.']);
        }

        // Update parent table test_status
        $firefighter_certificates = FirefighterCertificates::find($request->certificate_statuses_firefighter_certificates_id);
        $firefighter_certificates->test_status = "passed";
        if (!$firefighter_certificates->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate status. Please try again.']);
        }

        // Update Certificate Status
        $update_certificate_status = CertificateStatus::where('id', $request->certification_status_id)->first();
        $update_certificate_status->status = 'passed';
        if (!$update_certificate_status->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save certificate status. Please try again.']);
        }

        // Award Certificate
        $certificate = Certification::find($request->certification_id);

        $award_certificate = new AwardCertificate();
        $award_certificate->certificate_id = $request->certification_id;
        $award_certificate->firefighter_id = $request->certificate_statuses_firefighter_id;
        $award_certificate->organization_id = $request->organization;
        $award_certificate->stage = "initial";
        $award_certificate->issue_date = carbon::now()->toDateString();
        if ($certificate->renewable && $certificate->renewal_period) {
            /*$award_certificate->lapse_date = date('Y-m-d', strtotime($certificate->renewal_period));*/
            $award_certificate->lapse_date = $certificate->renewed_expiry_date ? $certificate->renewed_expiry_date : $certificate->certification_cycle_end;
        }


  // history table
  $history  = new certificatehistory();
  $history->firefighter_id = $request->certificate_statuses_firefighter_id;

  $history->certificate_id = $request->certification_id;
  $history->operation = 'Passed & Awarded';
  $history->date =  carbon::now()->toDateString();
  $history->save();


        if ($award_certificate->save()) {

            $firefighter = Firefighter::find($request->certificate_statuses_firefighter_id);
            $data = array(
                'title' => config('app.name'),
                'firefighter' => $firefighter,
                'certificate' => $certificate,
                'issue_date' => Helper::date_format($award_certificate->issue_date),
                'lapse_date' => $award_certificate->lapse_date ? Helper::date_format($award_certificate->lapse_date) : null,
            );

            $pdf = PDF::loadView('firefighter.awarded-certificate', $data);
            $attachment = $pdf->output();

            $firefighter_certificates = FirefighterCertificates::where('certificate_id', $request->certification_id)->where('firefighter_id', $request->certificate_statuses_firefighter_id)->where('status', 'accepted')->where('test_status', 'failed')->count();
            if ($firefighter_certificates > 0) {

                // Sending Email to Firefighter when failed in first attempt
                dispatch(new SendCertificateResheduleJob($firefighter, $certificate, $data, $update_certificate_status, "Test Results of Reattempt for Credential " . ucfirst($certificate->title)));
            } else {
                Mail::to($firefighter->email)->send(new SendCertificate($firefighter, $certificate, $update_certificate_status, $attachment));
            }

            // Sending Email to Admin
            dispatch(new FirefighterCertificateAwardedJob($firefighter, $certificate, $data, $award_certificate->issue_date, "Credential Awarded to $firefighter->f_name $firefighter->m_name  $firefighter->l_name for " . ucfirst($certificate->title)));
            return response()->json(['status' => true, 'msg' => 'Awarded Successfully !']);
        }

        return response()->json(['status' => false, 'msg' => 'Failed to save certificate award. Please try again.']);
    }

    public function firefighters_certifications_reject(Request $request)
    {

        $rules = [
            'reason' => 'required',
        ];

        $this->validate($request, $rules);

        // $certificate_status_verify = FirefighterCertificates::where('certificate_id',$request->certificate_id)->where('firefighter_id',$request->certification_firefighter_id)->where('status','rejected')->count();
        // if($certificate_status_verify > 0){
        //     return response()->json(['status'=>false,'msg'=>'Record Already exist status rejected.']);
        // }

        // $certificate_status_verify = FirefighterCertificates::where('certificate_id',$request->certificate_id)->where('firefighter_id',$request->certification_firefighter_id)->where('status','accepted')->count();
        // if($certificate_status_verify > 0){
        //     return response()->json(['status'=>false,'msg'=>'Record Already exist status accepted.']);
        // }

        $rejectded = CertificateRejectedReasons::where('firefighter_certificates_id', $request->id)->count();

        if ($rejectded > 0) {
            $rejected_reason = CertificateRejectedReasons::where('firefighter_certificates_id', $request->id)->first();
            $rejected_reason->reason = $request->reason;
            $rejected_reason->firefighter_id = $request->certification_firefighter_id;
        } else {
            $rejected_reason = new CertificateRejectedReasons;
            $rejected_reason->firefighter_certificates_id = $request->id;
            $rejected_reason->reason = $request->reason;
            $rejected_reason->firefighter_id = $request->certification_firefighter_id;
        }

         // history table
         $history  = new certificatehistory();
         $history->firefighter_id = $request->id;

         $history->certificate_id = $request->firefighter_certificates_id;
         $history->operation = 'Request Rejected';
         $history->date =  carbon::now()->toDateString();
         $history->save();

        if (!$rejected_reason->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to save reason. Please try again.']);
        }

        return response()->json(['status' => true, 'msg' => 'Created Successfully !']);
    }

    public function firefighters_certifications_accept(Request $request)
    {

        $rules = [
            'test_date' => 'required|date|',
            'start_hour' => 'required',
            'start_minute' => 'required',
        ];

        $messages['test_date.after'] = 'Date should be Greater than Today.';

        $this->validate($request, $rules, $messages);

        // $certificate_status_verify = FirefighterCertificates::where('certificate_id',$request->certificate_id)->where('firefighter_id',$request->certification_firefighter_id)->where('status','accepted')->count();
        // if($certificate_status_verify > 0){
        //     return response()->json(['status'=>false,'msg'=>'Record Already exist status accepted.']);
        // }

        $certificate_status_verify = FirefighterCertificates::where('certificate_id',$request->certificate_id)->where('firefighter_id',$request->certification_firefighter_id)->where('status','rejected')->count();
        if($certificate_status_verify > 0){
            return response()->json(['status'=>false,'msg'=>'Record Already exist status accepted.']);
        }

        $certificate_status = new CertificateStatus;
        $certificate_status->firefighter_certificates_id = $request->firefighter_certificates_id;

        $certificate_status->firefighter_id = $request->certification_firefighter_id;
        $certificate_status->test_date = $request->test_date;
        $certificate_status->test_time = $request->start_hour . ':' . $request->start_minute . ':' . '00';
        $certificate_status->status = $request->status;
        $certificate_status->save();

        $certif = CertificateStatus::find($request->firefighter_certificates_id);
           // history table
           $history  = new certificatehistory();
           $history->firefighter_id = $certif->certificate_id;

           $history->certificate_id = $request->firefighter_certificates_id;
           $history->operation = 'Request Accepted , Test Date :'.$request->test_date;
           $history->date =  carbon::now()->toDateString();
           $history->save();

        if (!$certificate_status->save()) {
            return response()->json(['status' => false, 'msg' => 'Failed to shedule date. Please try again.']);
        }

        // $certificate_statuses = CertificateStatus::where('firefighter_certificates_id', $request->firefighter_certificates_id)
        //     ->leftJoin('firefighter_certificates', 'firefighter_certificates.id', '=', 'certificate_statuses.firefighter_certificates_id')
        //     ->select('firefighter_certificates.firefighter_id', 'firefighter_certificates.certificate_id', 'certificate_statuses.test_date', 'certificate_statuses.test_time')
        //     ->first();

        // $certification = Certification::where('id', $certificate_statuses->certificate_id)->select('title')->first();
        // $firefighter = Firefighter::where('id', $certificate_statuses->firefighter_id)->select('f_name', 'email')->first();

        // $firefighter_certificates = FirefighterCertificates::where('certificate_id', $request->certificate_id)->where('firefighter_id', $request->certification_firefighter_id)->where('status', 'accepted')->count();
        // if ($firefighter_certificates > 0) {
        //     // Send Emails to Firefighter
        //     dispatch(new CertificationResheduleJob($firefighter->email, $firefighter->f_name, $certification->title, $certificate_status->test_date, $certificate_status->test_time, "Schedule for reattempt of test for Credential " . ucfirst($certification->title)));
        // } else {
        //     // Send Emails to Firefighter
        //     dispatch(new CertificationApprovedJob($firefighter->email, $firefighter->f_name, $certification->title, $certificate_statuses->test_date, $certificate_statuses->test_time, "Test scheduled for Credential " . ucfirst($certification->title)));
        // }

        return response()->json(['status' => true, 'msg' => 'Created Successfully !']);
    }

    public function expired_certificates()
    {
        return view('certification.expired-certificate')
            ->withTitle('Expired Credentials')
            ->withCertifications(Certification::select(DB::raw('COUNT(id) as count'))
                ->where('renewed_expiry_date','<=',carbon::now()->toDateString())
                ->first());
    }

    public function paginate_expire()
    {
        $per_page = Helper::per_page();
        $query = Certification::select('id', 'prefix_id', 'title', 'renewable', 'certification_cycle_start', 'certification_cycle_end', DB::raw("(SELECT count(firefighter_certificates.certificate_id) FROM firefighter_certificates WHERE firefighter_certificates.certificate_id = certifications.id AND firefighter_certificates.status = 'applied') AS certificates_request_count"));
        $query = Helper::filter('certifications', request()->except(['status']), $query, ['renewable', 'renewal_period', 'no_of_credit_types', 'admin_ceu', 'tech_ceu']);
        $certifications = $query->orderByRaw("CASE WHEN certificates_request_count > 0 THEN 1 ELSE 2 END ASC")
            ->where('renewed_expiry_date','<=',carbon::now()->toDateString())
            ->orderBy('certificates_request_count', 'desc')
            ->paginate($per_page)
            ->appends(request()->query());

        return view('certification.paginate')->with('certifications', $certifications);
    }

    public function renew_certification($id){
        $certificate = Certification::find($id);
        if(isset($certificate) && $certificate->renewed_expiry_date != null && $certificate->renewed_expiry_date >= carbon::now()->toDateString()){
            return response()->json([ 'status' => false, 'msg' => 'Certificate is already renewed.']);
        }
        $certificate->renewed_expiry_date = FirefighterHelper::generate_renewed_expiry_date($certificate->renewed_expiry_date ?? $certificate->certification_cycle_end, $certificate->renewal_period);
        try {
            $certificate->save();
            return response()->json([ 'status' => true, 'msg' => 'Certificate Renewed Successfully!' ]);
        } catch (\Exception $e) {
            return response()->json([ 'status' => false, 'msg' => $e->getMessage() ]);
        }
    }

    public function bulk_renew_certification() {
        try {
            if(isset(request()->cert_ids) && !empty(request()->cert_ids)) {
                $msg = "";
                foreach (request()->cert_ids as $id) {
                    $certificate = Certification::find($id);
                    if(isset($certificate) && $certificate->renewed_expiry_date != null && $certificate->renewed_expiry_date >= carbon::now()->toDateString()) {
                        $msg .= 'This '.$certificate->title.' is already renewed.';
                        return response()->json([ 'status' => false, 'msg' => $msg ]);
                    }
                    $certificate->renewed_expiry_date = FirefighterHelper::generate_renewed_expiry_date($certificate->renewed_expiry_date ?? $certificate->certification_cycle_end, $certificate->renewal_period);
                    $certificate->save();
                }
            }
            return response()->json([ 'status' => true, 'msg' => 'Bulk renewal completed successfully.' ]);
        } catch (\Exception $e) {
            return response()->json([ 'status' => false, 'msg' => $e->getMessage() ]);
        }
    }

    public function get_awarded_certificate_personnels($certification) {
        return view('certification.awarded-certificate-firefighters')
            ->withTitle('Awarded Credential Personnels')
            ->withAwardedCertPersCount(AwardCertificate::select(DB::raw('COUNT(id) as count'),DB::raw('(SELECT title FROM certifications WHERE id = awarded_certificates.certificate_id) as title'))->where('lapse_date', '<=',  carbon::now()->toDateString())->where('certificate_id', $certification)->get()->first())
            ->withCurrentCredId($certification);
    }

    public function paginate_awarded_certificate_personnel(Request $request)
    {
        $awardedCert = AwardCertificate::select('awarded_certificates.certificate_id','awarded_certificates.firefighter_id','awarded_certificates.issue_date','awarded_certificates.lapse_date', 'awarded_certificates.stage',DB::raw('(SELECT prefix_id FROM certifications WHERE id = awarded_certificates.certificate_id) as prefix_id'),DB::raw('(SELECT title FROM certifications WHERE id = awarded_certificates.certificate_id) as title'))
            ->where('certificate_id', request()->certificateId)->where('lapse_date', '<=',  carbon::now()->toDateString())->get();

        if(request()->dfsid)
        {
            $firefighterIds = Firefighter::select('id')
                ->where('prefix_id','LIKE',"%$request->dfsid%")
                ->get();

            $awardedCert = AwardCertificate::select('awarded_certificates.certificate_id','awarded_certificates.firefighter_id', 'awarded_certificates.issue_date', 'awarded_certificates.lapse_date', 'awarded_certificates.stage', DB::raw('(SELECT prefix_id FROM certifications WHERE id = awarded_certificates.certificate_id) as prefix_id'),DB::raw('(SELECT title FROM certifications WHERE id = awarded_certificates.certificate_id) as title'))->whereIn('firefighter_id',$firefighterIds)->where('certificate_id', request()->certificateId)->where('lapse_date', '<=',  carbon::now()->toDateString())->get();
        }

        if(request()->firefighter_name)
        {
            $firefighterIds = Firefighter::select('id')
                ->where('f_name','LIKE',"%$request->firefighter_name%")
                ->orWhere('m_name','LIKE',"%$request->firefighter_name%")
                ->orWhere('l_name','LIKE',"%$request->firefighter_name%")
                ->orWhereRaw("REPLACE(Concat(f_name,' ',l_name),'  ',' ') LIKE '%{$request->firefighter_name}%'")
                ->orWhereRaw("REPLACE(Concat(f_name,' ',m_name,' ',l_name),'  ',' ') LIKE '%{$request->firefighter_name}%'")
                ->get();

            $awardedCert = AwardCertificate::select('awarded_certificates.certificate_id','awarded_certificates.firefighter_id', 'awarded_certificates.issue_date', 'awarded_certificates.lapse_date', 'awarded_certificates.stage', DB::raw('(SELECT prefix_id FROM certifications WHERE id = awarded_certificates.certificate_id) as prefix_id'),DB::raw('(SELECT title FROM certifications WHERE id = awarded_certificates.certificate_id) as title'))->whereIn('firefighter_id',$firefighterIds)->where('certificate_id', request()->certificateId)->where('lapse_date', '<=',  carbon::now()->toDateString())->get();
        }

        if(request()->search) {
            $firefighterIds = Firefighter::select('id')
                ->orWhere('prefix_id','LIKE',"%$request->search%")
                ->orWhere('f_name','LIKE',"%$request->search%")
                ->orWhere('m_name','LIKE',"%$request->search%")
                ->orWhere('l_name','LIKE',"%$request->search%")
                ->orWhereRaw("REPLACE(Concat(f_name,' ',l_name),'  ',' ') LIKE '%{$request->search}%'")
                ->orWhereRaw("REPLACE(Concat(f_name,' ',m_name,' ',l_name),'  ',' ') LIKE '%{$request->search}%'")
                ->get();
            $awardedCert = AwardCertificate::select('awarded_certificates.certificate_id','awarded_certificates.firefighter_id', 'awarded_certificates.issue_date', 'awarded_certificates.lapse_date', 'awarded_certificates.stage', DB::raw('(SELECT prefix_id FROM certifications WHERE id = awarded_certificates.certificate_id) as prefix_id'),DB::raw('(SELECT title FROM certifications WHERE id = awarded_certificates.certificate_id) as title'))->whereIn('firefighter_id',$firefighterIds)->where('certificate_id', request()->certificateId)->where('lapse_date', '<=',  carbon::now()->toDateString())->get();
        }

        return view('certification.paginate-awarded-certificate-firefighters')->withAwardedCertPers($awardedCert);
    }
}
