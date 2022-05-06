@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add Course</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Courses > Add Course</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-right">
                <a href="{{ route('course.index') }}" class="btn bg-white text-secondary">
                    <span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back
                </a>
            </div>
        </div>
        <form id="add">
            @csrf
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
                                        <input type="text" class="form-control" name="fema_course">
                                        <div id="fema_course" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Course Name</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="course_name">
                                        <div id="course_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>NFPA STD</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="nfpa_std">
                                        <div id="nfpa_std" class="invalid-feedback"></div>
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <th>--}}
{{--                                        <label>Admin CEU's</label>--}}
{{--                                    </th>--}}
{{--                                    <td>--}}
{{--                                        <input type="number" min="0" step="0.1" class="form-control" name="admin_ceu">--}}
{{--                                        <div id="admin_ceu" class="invalid-feedback"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <th>--}}
{{--                                        <label class="required">Tech CEU's</label>--}}
{{--                                    </th>--}}
{{--                                    <td>--}}
{{--                                        <input type="number" class="form-control" name="tech_ceu">--}}
{{--                                        <div id="tech_ceu" class="invalid-feedback d-block"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                                <tr>
                                    <th>
                                        <label class="required">Course Hours</label>
                                    </th>
                                    <td>
                                        <input type="number" min="0" class="form-control" name="course_hours">
                                        <div id="course_hours" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="180">
                                        <label class="required" for="maximum_students">Maximum Students</label>
                                    </th>
                                    <td>
                                        <input type="number" min="0" name="maximum_students" class="form-control">
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
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
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
{{--                                            <option value="1">1</option>--}}
{{--                                            <option value="2">2</option>--}}
{{--                                            <option value="3">3</option>--}}
{{--                                        </select>--}}
{{--                                        <div id="no_of_pre_req_course" class="invalid-feedback"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                            <div id="pre-req-course-container"></div>--}}

                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th width="170">
                                        <label class="required">No. of Credit type</label>
                                    </th>
                                    <td>
                                        <select name="no_of_credit_types" class="form-control">
                                            <option value="">Choose an option</option>
                                            @for($i=1; $i<=20; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <div id="no_of_credit_types" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                            <div id="credit-types-container"></div>

                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th width="170">
                                        <label class=""> Credit Type Group</label>
                                    </th>
                                    <td>
                                        <select name="group_credit_types" id="group_credit_types" class="form-control">
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
                                        <textarea name="comment" class="form-control" rows="5" style="resize: none;"></textarea>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Create</button>
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
                // if(typeof values[i] !== "undefined" && Object.size(values)){
                //     option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
                // }else{
                    var courses = '@php echo json_encode($courses); @endphp';
                    var courses = JSON.parse(courses);
                    option = '';
                    option +=`<option value=""></option>`;
                    for (let i=0; i < courses.length; i++){
                        option +=`<option value="${courses[i].id}">${courses[i].course_name}</option>`;
                    }
                // }
                html+=`<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="pre_req_courses[]" class="form-control courses-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html+= '<tr><th></th><td class="pt-0"><div id="pre_req_courses" class="invalid-feedback"></div></td></tr></table>';
            $('#pre-req-course-container').html(html);
            $(".courses-select2").select2({ placeholder: "Select Courses" });

            // $(document).find(".courses-select2").select2({
            //     minimumInputLength: 2,
            //     placeholder: 'Search Courses',
            //     ajax: {
            //         url: '{{ route('semester.search-courses') }}',
            //         dataType: 'json',
            //         type: "GET",
            //         quietMillis: 50,
            //         data: function (search) {
            //             return {
            //                 search: search.term
            //             };
            //         },
            //         processResults: function (courses) {
            //             return {
            //                 results: $.map(courses, function (course) {
            //                     return {
            //                         text: course.course_name+' '+`(${course.prefix_id})`,
            //                         id: course.id
            //                     }
            //                 })
            //             };
            //         }
            //     }
            // })
        });

    function formReset(){
        document.getElementById("add").reset();
        $('#credit-types-container').html('');
        $('#pre-req-cert-container').html('');
        $('#pre-req-course-container').html('');
        $("#group_credit_types").val("").trigger('change');
    }
    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');
        axios.post("{{ route('course.store') }}",$(this).serialize()).then((response)=>{
            if(response.data.status){
                formReset();
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
            // if(typeof values[i] !== "undefined" && Object.size(values)){
            //     option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
            // }else{
                var credit_types = '@php echo json_encode($credit_types); @endphp';
                var credit_types = JSON.parse(credit_types);
                option = '';
                option +=`<option value=""></option>`;
                for (let i=0; i < credit_types.length; i++){
                    option +=`<option value="${credit_types[i].id}">${credit_types[i].description} (${credit_types[i].prefix_id})</option>`;
                }
            // }

            html+=`<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${ordinal_suffix_of(i+1)}</th>
                            <td><select name="credit_types[]" class="form-control credit-types-select2" data-live-search="true">${option}</select></td>
                       </tr>`
        }
        html+= '<tr><th></th><td class="pt-0"><div id="credit_types" class="invalid-feedback"></div></td></tr></table>';
        $('#credit-types-container').html(html);

        $(".credit-types-select2").select2({ placeholder: "Select Credit Type" });
        // $(document).find(".credit-types-select2").select2({
        //     minimumInputLength: 2,
        //     placeholder: 'Search credit type',
        //     ajax: {
        //         url: '{{ route('course.search-credit-type') }}',
        //         dataType: 'json',
        //         type: "GET",
        //         quietMillis: 50,
        //         data: function (search) {
        //             return {
        //                 search: search.term
        //             };
        //         },
        //         processResults: function (credit_types) {
        //             return {
        //                 results: $.map(credit_types, function (credit_type) {
        //                     return {
        //                         text: `${credit_type.description} (${credit_type.prefix_id})`,
        //                         id: credit_type.id
        //                     }
        //                 })
        //             };
        //         }
        //     }
        // });
    });
</script>
@endpush
