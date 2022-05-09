@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Edit Course</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Courses > Edit Course</span>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Courses ID:'=>$course->prefix_id]])
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('course.index') }}" class="btn bg-white text-secondary">
                    <span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back
                </a>
            </div>
        </div>
        <form id="add">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Course Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="160">
                                        <label>FEMA Course</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="fema_course" value="{{ $course->fema_course }}">
                                        <div id="fema_course" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Course Name</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="course_name" value="{{ $course->course_name }}">
                                        <div id="course_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>NFPA STD</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="nfpa_std" value="{{ $course->nfpa_std }}">
                                        <div id="nfpa_std" class="invalid-feedback"></div>
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <th>--}}
{{--                                        <label>Admin CEU's</label>--}}
{{--                                    </th>--}}
{{--                                    <td>--}}
{{--                                        <input type="number" min="0" step="0.1" class="form-control" name="admin_ceu" value="{{ $course->admin_ceu }}">--}}
{{--                                        <div id="admin_ceu" class="invalid-feedback"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <th>--}}
{{--                                        <label>Tech CEU's</label>--}}
{{--                                    </th>--}}
{{--                                    <td>--}}
{{--                                        <input type="number" class="form-control" name="tech_ceu" value="{{ $course->tech_ceu }}">--}}
{{--                                        <div id="tech_ceu" class="invalid-feedback d-block"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                                <tr>
                                    <th>
                                        <label class="required">Course Hours</label>
                                    </th>
                                    <td>
                                        <input type="number" class="form-control" name="course_hours" value="{{ $course->course_hours }}">
                                        <div id="course_hours" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="180">
                                        <label class="required" for="maximum_students">Maximum Students</label>
                                    </th>
                                    <td>
                                        <input type="number" name="maximum_students" class="form-control" value="{{ $course->maximum_students }}">
                                        <div id="maximum_students" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="170">
                                        <label class="required">Instructor Level</label>
                                    </th>
                                    <td>
                                        <select name="instructor_level" class="form-control">
                                            <option value="">Choose an option</option>
                                            <option {{ $course->instructor_level== 1 ? 'selected' : '' }} value="1">1</option>
                                            <option {{ $course->instructor_level== 2 ? 'selected' : '' }} value="2">2</option>
                                            <option {{ $course->instructor_level== 3 ? 'selected' : '' }} value="3">3</option>
                                            <option {{ $course->instructor_level== 4 ? 'selected' : '' }} value="4">4</option>
                                            <option {{ $course->instructor_level== 5 ? 'selected' : '' }} value="5">5</option>
                                        </select>
                                        <div id="instructor_level" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>

{{--                            <table class="table table-borderless w-100 mb-0">--}}
{{--                                <tr>--}}
{{--                                    <th width="170"><label>No. of Pre-requisite Course(s)</label></th>--}}
{{--                                    <td>--}}
{{--                                        <select name="no_of_pre_req_course" class="form-control">--}}
{{--                                            <option value="">Choose an option</option>--}}
{{--                                            <option value="1" {{ $course->no_of_pre_req_course == '1' ? 'selected' : '' }}>1</option>--}}
{{--                                            <option value="2" {{ $course->no_of_pre_req_course == '2' ? 'selected' : '' }}>2</option>--}}
{{--                                            <option value="3" {{ $course->no_of_pre_req_course == '3' ? 'selected' : '' }}>3</option>--}}
{{--                                        </select>--}}
{{--                                        <div id="no_of_pre_req_course" class="invalid-feedback"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}

{{--                            <div id="pre-req-course-container">--}}
{{--                                @if($pre_req_courses)--}}
{{--                                    <table class="table table-borderless w-100 mb-0">--}}
{{--                                        @php $prefix = ['1st','2nd','3rd']; $count = 0; @endphp--}}
{{--                                        @foreach($pre_req_courses as $key=>$pre_req_course)--}}
{{--                                            <tr>--}}
{{--                                                <th width="170" class="text-right">--}}
{{--                                                    @php--}}
{{--                                                        echo $prefix[$count];--}}
{{--                                                        $count++;--}}
{{--                                                    @endphp--}}
{{--                                                </th>--}}
{{--                                                <td class="selectpicker-custom-style">--}}
{{--                                                    --}}{{-- <select name="pre_req_courses[]" class="form-control courses-select2" data-live-search="true">--}}
{{--                                                        <option selected value="{{ $pre_req_course->preq_course_id }}">{{ $pre_req_course->course_name }} ({{ $pre_req_course->prefix_id }}) </option>--}}
{{--                                                    </select> --}}
{{--                                                    <select name="pre_req_courses[]" class="form-control courses-select2" data-live-search="true">--}}
{{--                                                        <option value=""></option>--}}
{{--                                                        @foreach ($all_courses as $all_course)--}}
{{--                                                                <option value="{{ $all_course->id }}" {{ $all_course->id == $key ? 'selected' : '' }}>{{ $all_course->course_name.' ('.$all_course->prefix_id.')' }}</option>--}}
{{--                                                        @endforeach--}}
{{--                                                    </select>--}}
{{--                                                    <div id="pre_req_courses" class="invalid-feedback"></div>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                    </table>--}}
{{--                                @endif--}}
{{--                            </div>--}}

                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th width="170">
                                        <label class="required">No. of Credit type</label>
                                    </th>
                                    <td>
                                        <select name="no_of_credit_types" class="form-control">
                                            <option value="">Choose an option</option>
                                            @for($i=1; $i<=20; $i++)
                                                <option {{ $course->no_of_credit_types== $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <div id="no_of_credit_types" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                            <div id="credit-types-container">
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
                                                    <select name="credit_types[]" class="form-control credit_types-select2" data-live-search="true">
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
                                <tr>
                                    <th width="170">
                                        <label class=""> Credit Type Group</label>
                                    </th>
                                    <td>
                                        <select name="group_credit_types" id="group_credit_types" class="form-control">
                                            <option value="">Choose an option</option>
                                            <option selected="selected">Choose an option</option>
                                            @foreach ($group_credit_types as $item)
                                            <option value="{{$item->credit_code}}">{{$item->credit_code}} ({!! implode(', <br>',$item->g_credit_types) !!})</option>
                                            @endforeach
                                        </select>
                                        <div id="group_credit_types" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Comments:</label>
                                    </th>
                                    <td>
                                        <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $course->comment }}</textarea>
                                    </td>
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('course.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection


@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection

@push('js')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>

<script type="text/javascript">

    $("#group_credit_types").select2({ placeholder: "Select Group Credit Types" });

    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');
        axios.post("{{ route('course.update',$course->id) }}",$(this).serialize()).then((response)=>{
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

    $(".credit_types-select2").select2({ placeholder: "Select Credit Type" });
    $('[name=no_of_credit_types]').on('change',function () {
        let types = $(this).val(),records = $(document).find('#credit-types-container [name*=credit_types]'),length = records.length,values = {};
        if(length){
            for (let i=0; i<length; i++){
                values[i] = {
                    [records[i].value] : typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                }
            }
        }

        html = '<table class="table table-borderless w-100 mb-0">';
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
                            <th width="170" class="text-right">${ordinal_suffix_of(i+1)}</th>
                            <td><select name="credit_types[]" class="form-control credit_types-select2" data-live-search="true">${option}</select></td>
                       </tr>`
        }
        html+= '<tr><th></th><td class="pt-0"><div id="credit_types" class="invalid-feedback"></div></td></tr></table>';
        $('#credit-types-container').html(html);
        // initSelect2();
        $(".credit_types-select2").select2({ placeholder: "Select Credit Type" });
    });

    // function initSelect2(){
    //     $(document).find(".credit_types-select2").select2({
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
    //                             text: `${credit_type.description} (${credit_type.prefix_id})`,
    //                             id: credit_type.id
    //                         }
    //                     })
    //                 };
    //             }
    //         }
    //     });
    // }

    $(".courses-select2").select2({ placeholder: "Select Courses" });
    $('[name=no_of_pre_req_course]').on('change',function () {
            let types = $(this).val(),records = $(document).find('#pre-req-course-container [name*=pre_req_courses]'),length = records.length,values = {};
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
                        option +=`<option value="${all_courses[i].id}">${all_courses[i].course_name}</option>`;
                    }
                }
                html+=`<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="pre_req_courses[]" class="form-control courses-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html+= '<tr><th></th><td class="pt-0"><div id="pre_req_courses" class="invalid-feedback"></div></td></tr></table>';
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

    // $(document).ready(function () {
    //     initSelect2();
    //     initPreReqCourse();
    // })

</script>
@endpush
