@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Edit Credential</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Credentials > Edit Credential</span>
            </div>
        </div>
        <div class="text-right mb-3">
            <a href="{{ route('certification.index') }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
        </div>
        <form id="add">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Credential Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="180">
                                        <label class="required">Credential Code</label>
                                    </th>
                                    <td>
                                        <input type="text" maxlength="5" class="form-control alphanumeric-only" name="prefix_id" value="{{ $certificate->prefix_id }}">
                                        <div id="prefix_id" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Credential Title</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="title" value="{{ $certificate->title }}">
                                        <div id="title" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label class="required">Short Title</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="short_title" value="{{ $certificate->short_title }}">
                                        <div id="short_title" class="invalid-feedback"></div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label>Renewable</label>
                                    </th>
                                    <td>
                                        <div class="form-check d-inline-block mr-2">
                                            <input class="form-check-input" type="radio" name="renewable" id="renewable-yes" {{ $certificate->renewable ? 'checked' : '' }} value="1">
                                            <label class="form-check-label" for="renewable-yes">Yes</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="renewable" id="renewable-no" {{ !$certificate->renewable ? 'checked' : '' }} value="0">
                                            <label class="form-check-label" for="renewable-no">No</label>
                                        </div>
                                    </td>
                                </tr>
                               {{-- <tr id="renewal-period-container" class="{{ $certificate->renewal_period ? '' : 'd-none' }}">
                                    <th>
                                        <label>Renewal Period</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control float-left" name="certification_cycle_start" value="{{ $certificate->certification_cycle_start }}" id="certification_cycle_start" style="width: 50%;">
                                        <input type="date" class="form-control float-right" name="certification_cycle_end" value="{{ $certificate->certification_cycle_end }}" id="certification_cycle_end" style="width: 50%;">
                                        <input type="hidden" name="renewal_period" id="renewal_period" value="{{ $certificate->renewal_period }}">
                                        <div id="renewal_period" class="invalid-feedback"></div>
                                    </td>
                                </tr>--}}
                                <tr id="renewal-period-container-1" class="{{ $certificate->renewal_period ? '' : 'd-none' }}">
                                    <th>
                                        <label for="certification_cycle_start">Cycle Start Date</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control" name="certification_cycle_start" id="certification_cycle_start" value="{{ $certificate->certification_cycle_start }}" style="width: 100%;">
                                        <input type="hidden" name="renewal_period" value="{{ $certificate->renewal_period }}" id="renewal_period">
                                        <div id="renewal_period" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr id="renewal-period-container-2" class="{{ $certificate->renewal_period ? '' : 'd-none' }}">
                                    <th>
                                        <label for="certification_cycle_start">Cycle End Date</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control" name="certification_cycle_end" id="certification_cycle_end" value="{{ $certificate->certification_cycle_end }}" style="width: 100%;">
                                    </td>
                                </tr>
                                <tr id="renewal-period-container-2" class="{{ !empty($certificate->renewed_expiry_date) ? '' : 'd-none' }}">
                                    <th>
                                        <label for="renewed_expiry_date">Renewed Cycle End Date</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control" name="renewed_expiry_date" id="renewed_expiry_date" value="{{ $certificate->renewed_expiry_date }}" style="width: 100%;" disabled>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0 {{ $certificate->renewable ? '' : 'd-none' }} admin_ceus">
                                <tr>
                                    <th><label>Req. Admin CEU's</label></th>
                                    <td>
                                        <!-- <input type="number" step="0.1" name="admin_ceu" class="form-control" value="{{ $certificate->admin_ceu }}"> -->
                                        <select class="form-control" name="admin_ceu">
                                            <option value="" selected>Choose an option</option>
                                            <option value="1.0" {{ $certificate->admin_ceu == '1.0' ? 'selected' : '' }}>1.0</option>
                                            <option value="1.5" {{ $certificate->admin_ceu == '1.5' ? 'selected' : '' }}>1.5</option>
                                            <option value="2.0" {{ $certificate->admin_ceu == '2.0' ? 'selected' : '' }}>2.0</option>
                                        </select>
                                        <div id="admin_ceu" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Req. Tech CEU's</label></th>
                                    <td>
                                        <!-- <input type="number" step="0.1" name="tech_ceu" class="form-control" value="{{ $certificate->tech_ceu }}"> -->
                                        <select class="form-control" name="tech_ceu">
                                            <option value="">Choose an option</option>
                                            <option value="1.0" {{ $certificate->tech_ceu == '1.0' ? 'selected' : '' }}>1.0</option>
                                            <option value="1.5" {{ $certificate->tech_ceu == '1.5' ? 'selected' : '' }}>1.5</option>
                                            <option value="2.0" {{ $certificate->tech_ceu == '2.0' ? 'selected' : '' }}>2.0</option>
                                        </select>
                                        <div id="tech_ceu" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="170">
                                        <label class="required">No. of Credit type</label>
                                    </th>
                                    <td>
                                        <select name="no_of_credit_types" class="form-control">
                                            <option value="">Choose an option</option>
                                            <option value="1" {{ $certificate->no_of_credit_types == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ $certificate->no_of_credit_types == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ $certificate->no_of_credit_types == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ $certificate->no_of_credit_types == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ $certificate->no_of_credit_types == '5' ? 'selected' : '' }}>5</option>
                                        </select>
                                        <div id="no_of_credit_types" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                            <div id="credit-types-container" class="{{ $certificate->renewable ? '' : 'd-none' }}">
                                @if($foreign_relations)
                                @php $count = 1; @endphp
                                    <table class="table table-borderless w-100 mb-0">
                                        @foreach($foreign_relations as $key=>$foreign_relation)
                                            <tr>
                                                <th width="170" class="text-right">
                                                    @php
                                                        echo \App\Http\Helpers\Helper::ordinal_suffix_of($count);
                                                        $count++;
                                                    @endphp
                                                </th>
                                                <td class="selectpicker-custom-style">
                                                    <select name="credit_types[]" class="form-control credit-type-select2" data-live-search="true">
                                                        @foreach($db_credit_types as $credit_type)
                                                            <option {{ $credit_type['id']==$key ? 'selected' : '' }} value="{{ $credit_type['id'] }}">{{ $credit_type['description'] }} ({{ $credit_type['prefix_id'] }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </div>
                            <table class="table table-borderless w-100 mb-0">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label>Comments:</label>
                                        </th>
                                        <td>
                                            <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $certificate->comment }}</textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="card">--}}
{{--                <div class="card-header">--}}
{{--                    <h4 class="mb-0">Pre-requisites Information</h4>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <table class="table table-borderless w-100 mb-0">--}}
{{--                                <tr>--}}
{{--                                    <th width="170"><label>No. of Pre-requisite Credential(s)</label></th>--}}
{{--                                    <td>--}}
{{--                                        <select name="no_of_pre_req_cert" class="form-control">--}}
{{--                                            <option value="">Choose an option</option>--}}
{{--                                            <option value="1" {{ $certificate->no_of_pre_req_cert == '1' ? 'selected' : '' }}>1</option>--}}
{{--                                            <option value="2" {{ $certificate->no_of_pre_req_cert == '2' ? 'selected' : '' }}>2</option>--}}
{{--                                            <option value="3" {{ $certificate->no_of_pre_req_cert == '3' ? 'selected' : '' }}>3</option>--}}
{{--                                        </select>--}}
{{--                                        <div id="no_of_pre_req_cert" class="invalid-feedback"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                            <div id="pre-req-cert-container">--}}
{{--                                @if($pre_req_certs && $pre_req_certs->count())--}}
{{--                                    @php $prefix = ['1st','2nd','3rd']; $count = 0; @endphp--}}
{{--                                    <table class="table table-borderless w-100 mb-0">--}}
{{--                                        @foreach($pre_req_certs as $key=>$pre_req_cert)--}}
{{--                                            <tr>--}}
{{--                                                <th width="170" class="text-right">--}}
{{--                                                    @php--}}
{{--                                                        echo $prefix[$count];--}}
{{--                                                        $count++;--}}
{{--                                                    @endphp--}}
{{--                                                </th>--}}
{{--                                                <td class="selectpicker-custom-style">--}}
{{--                                                    --}}{{-- <select name="pre_req_cert[]" class="form-control certification-select2" data-live-search="true">--}}
{{--                                                        <option selected value="{{ $pre_req_cert->pre_req_certificate_id }}">{{ $pre_req_cert->title }} ({{ $pre_req_cert->prefix_id }})</option>--}}
{{--                                                    </select> --}}

{{--                                                    <select name="pre_req_cert[]" class="form-control certification-select2" data-live-search="true">--}}
{{--                                                        @foreach ($all_certifications as $all_certification)--}}
{{--                                                            <option value="{{ $all_certification->id }}" {{ $all_certification->id == $pre_req_cert->pre_req_certificate_id ? 'selected' : '' }}>{{ $all_certification->title }} ({{ $all_certification->prefix_id }})</option>--}}
{{--                                                        @endforeach--}}
{{--                                                    </select>--}}
{{--                                                    <div id="pre_req_cert" class="invalid-feedback"></div>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                    </table>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <table class="table table-borderless w-100 mb-0">--}}
{{--                                <tr>--}}
{{--                                    <th width="170"><label>No. of Pre-requisite Course(s)</label></th>--}}
{{--                                    <td>--}}
{{--                                        <select name="no_of_pre_req_course" class="form-control">--}}
{{--                                            <option value="">Choose an option</option>--}}
{{--                                            <option value="1" {{ $certificate->no_of_pre_req_course == '1' ? 'selected' : '' }}>1</option>--}}
{{--                                            <option value="2" {{ $certificate->no_of_pre_req_course == '2' ? 'selected' : '' }}>2</option>--}}
{{--                                            <option value="3" {{ $certificate->no_of_pre_req_course == '3' ? 'selected' : '' }}>3</option>--}}
{{--                                        </select>--}}
{{--                                        <div id="no_of_pre_req_course" class="invalid-feedback"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                            <div id="pre-req-course-container">--}}
{{--                                @if($pre_req_courses && $pre_req_courses->count())--}}
{{--                                    @php $prefix = ['1st','2nd','3rd']; $count = 0; @endphp--}}
{{--                                    <table class="table table-borderless w-100 mb-0">--}}
{{--                                        @foreach($pre_req_courses as $key=>$pre_req_course)--}}
{{--                                            <tr>--}}
{{--                                                <th width="170" class="text-right">--}}
{{--                                                    @php--}}
{{--                                                        echo $prefix[$count];--}}
{{--                                                        $count++;--}}
{{--                                                    @endphp--}}
{{--                                                </th>--}}
{{--                                                <td class="selectpicker-custom-style">--}}
{{--                                                    <select name="pre_req_course[]" class="form-control courses-select2" data-live-search="true">--}}
{{--                                                        @foreach ($all_courses as $all_course)--}}
{{--                                                            <option value="{{ $all_course->id }}" {{ $all_course->id == $pre_req_course->pre_req_course_id ? 'selected' : '' }}>{{ $all_course->course_name }} ({{ $all_course->prefix_id }})</option>--}}
{{--                                                        @endforeach--}}
{{--                                                    </select>--}}
{{--                                                    <div id="pre_req_course" class="invalid-feedback"></div>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                    </table>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('certification.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(".credit-type-select2").select2({ placeholder: "Select Credit Type" });
        $('[name=no_of_credit_types]').on('change',function () {
            $('#credit-types-container').show();
            let types = $(this).val(),records = $(document).find('#credit-types-container [name*=credit_types]'),length = records.length,values = {};
            if(length){
                for (let i=0; i<length; i++){
                    values[i] = {
                        [records[i].value] : typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                    }
                }
            }

            html = '<table class="table table-borderless w-100 mb-0">';
            prefixes = {
                '0':'1st',
                '1':'2nd',
                '2':'3rd',
                '3':'4th',
                '4':'5th',
            };
            for (let i=0; i<types; i++){
                if(typeof values[i] !== "undefined" && Object.size(values)){
                    option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
                }else{
                    var all_credit_types = '@php echo json_encode($all_credit_types); @endphp';
                    var all_credit_types = JSON.parse(all_credit_types);
                    option = '';
                    option +=`<option value=""></option>`;
                    for (let i=0; i < all_credit_types.length; i++){
                        option +=`<option value="${all_credit_types[i].id}">${all_credit_types[i].description} (${all_credit_types[i].prefix_id})</option>`;
                    }
                }
                html+=`<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="credit_types[]" class="form-control credit-type-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html+= '<tr><th></th><td class="pt-0"><div id="credit_types" class="invalid-feedback"></div></td></tr></table>';
            $('#credit-types-container').html(html);

            $(".credit-type-select2").select2({ placeholder: "Select Credit Type" });
            // initCreditType();
        });

        // function initCreditType(){
        //     $(document).find(".credit-type-select2").select2({
        //         minimumInputLength: 2,
        //         placeholder: 'Search credit type',
        //         ajax: {
        //             url: '{{ route('course.search-credit-type') }}',
        //             dataType: 'json',
        //             type: "GET",
        //             quietMillis: 50,
        //             data: function (search) {
        //                 return {
        //                     search: search.term
        //                 };
        //             },
        //             processResults: function (credit_types) {
        //                 return {
        //                     results: $.map(credit_types, function (credit_type) {
        //                         return {
        //                             text: credit_type.description,
        //                             id: credit_type.id
        //                         }
        //                     })
        //                 };
        //             }
        //         }
        //     });
        // }

        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('certification.update',$certificate->id) }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: response.data.msg,
                    });
                }
                submit_btn.prop('disabled', false);
                submit_btn.removeClass('disabled');
            }).catch((error)=>{
                if(error.response.status === 422) {
                    submit_btn.prop('disabled', false);
                    submit_btn.removeClass('disabled');
                    Toast.fire({
                        icon: 'info',
                        title: 'Please fill form carefully !'
                    });
                }
            })
        });

        $(".courses-select2").select2({ placeholder: "Select Courses" });
        $('[name=no_of_pre_req_course]').on('change',function () {
            let types = $(this).val(),records = $(document).find('#pre-req-course-container [name*=pre_req_course]'),length = records.length,values = {};
            if(length){
                for (let i=0; i<length; i++){
                    values[i] = {
                        [records[i].value] : typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                    }
                }
            }

            html = '<table class="table table-borderless w-100 mb-0">';
            prefixes = {
                '0':'1st',
                '1':'2nd',
                '2':'3rd',
            };
            for (let i=0; i<types; i++){
                if(typeof values[i] !== "undefined" && Object.size(values)){
                    option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
                }else{
                    var all_courses = '@php echo json_encode($all_courses); @endphp';
                    var all_courses = JSON.parse(all_courses);
                    option = '';
                    option +=`<option value=""></option>`;
                    for (let i=0; i < all_courses.length; i++){
                        option +=`<option value="${all_courses[i].id}">${all_courses[i].course_name} (${all_courses[i].prefix_id})</option>`;
                    }
                }
                html+=`<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="pre_req_course[]" class="form-control courses-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html+= '<tr><th></th><td class="pt-0"><div id="pre_req_course" class="invalid-feedback"></div></td></tr></table>';
            $('#pre-req-course-container').html(html);
            $(".courses-select2").select2({ placeholder: "Select Courses" });
            // initPreReqCourse();
        });

        // function initPreReqCourse(){
        //     $(document).find(".courses-select2").select2({
        //         minimumInputLength: 2,
        //         placeholder: 'Search Courses',
        //         ajax: {
        //             url: '{{ route('semester.search-courses') }}',
        //             dataType: 'json',
        //             type: "GET",
        //             quietMillis: 50,
        //             data: function (search) {
        //                 return {
        //                     search: search.term
        //                 };
        //             },
        //             processResults: function (courses) {
        //                 return {
        //                     results: $.map(courses, function (course) {
        //                         return {
        //                             text: course.course_name+' '+`(${course.prefix_id})`,
        //                             id: course.id
        //                         }
        //                     })
        //                 };
        //             }
        //         }
        //     })
        // }

        $(".certification-select2").select2({ placeholder: "Select Credential" });
        $('[name=no_of_pre_req_cert]').on('change',function () {
            let types = $(this).val(),records = $(document).find('#pre-req-cert-container [name*=pre_req_cert]'),length = records.length,values = {};
            if(length){
                for (let i=0; i<length; i++){
                    values[i] = {
                        [records[i].value] : typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                    }
                }
            }

            html = '<table class="table table-borderless w-100 mb-0">';
            prefixes = {
                '0':'1st',
                '1':'2nd',
                '2':'3rd',
            };
            for (let i=0; i<types; i++){
                if(typeof values[i] !== "undefined" && Object.size(values)){
                    option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
                }else{
                    var all_certifications = '@php echo json_encode($all_certifications); @endphp';
                    var all_certifications = JSON.parse(all_certifications);
                    option = '';
                    option +=`<option value=""></option>`;
                    for (let i=0; i < all_certifications.length; i++){
                        option +=`<option value="${all_certifications[i].id}">${all_certifications[i].title} (${all_certifications[i].prefix_id})</option>`;
                    }
                }
                html+=`<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="pre_req_cert[]" class="form-control certification-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html+= '<tr><th></th><td class="pt-0"><div id="pre_req_cert" class="invalid-feedback"></div></td></tr></table>';
            $('#pre-req-cert-container').html(html);
            $(".certification-select2").select2({ placeholder: "Select Credential" });

            // initPreReqCerfificates();
        });

        // function initPreReqCerfificates(){
        //     $(document).find(".certification-select2").select2({
        //         minimumInputLength: 2,
        //         placeholder: 'Search Certification',
        //         ajax: {
        //             url: '{{ route('certification.search-certifications') }}',
        //             dataType: 'json',
        //             type: "GET",
        //             quietMillis: 50,
        //             data: function (search) {
        //                 return {
        //                     search: search.term
        //                 };
        //             },
        //             processResults: function (pre_req_cert) {
        //                 return {
        //                     results: $.map(pre_req_cert, function (certification) {
        //                         return {
        //                             text: `${certification.title} (${certification.prefix_id})`,
        //                             id: certification.id
        //                         }
        //                     })
        //                 };
        //             }
        //         }
        //     })
        // }

        // $(document).ready(function () {
        //     initPreReqCerfificates();
        //     initPreReqCourse();
        //     initCreditType();
        // })

    </script>
@endpush
