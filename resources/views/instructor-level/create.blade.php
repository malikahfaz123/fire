@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add Instructor Level</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Firefighter > Instructor Level > Add Instructor Level</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <form id="add">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Level Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th width="210">
                                            <label>Instructor Level</label>
                                        </th>
                                        <td class="selectpicker-custom-style">
                                            <select class="form-control selectpicker" name="instructor_level">
                                                <option value="">Any</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                            <div id="instructor_level" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="required">Courses</label>
                                        </th>
                                        <td>
                                            <select id="courses-select2" multiple="multiple" name="courses[]" class="form-control"></select>
                                            <div id="courses" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Create</button>
                <a href="<?php echo route('instructor-level.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

    <script type="text/javascript">

        function formReset(){
            document.getElementById("add").reset();
            $(".selectpicker").selectpicker("refresh");
            $("#courses-select2").empty();
        }

        $('#add').on('submit',function (e) {
            e.preventDefault();

            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('instructor-level.store') }}",$(this).serialize()).then((response)=>{
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

        $("#courses-select2").select2({
            minimumInputLength: 2,
            placeholder: 'Search Courses',
            ajax: {
                url: '{{ route('semester.search-courses') }}',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function (search) {
                    return {
                        search: search.term
                    };
                },
                processResults: function (courses) {
                    return {
                        results: $.map(courses, function (course) {
                            return {
                                text: course.course_name+' '+`(${course.prefix_id})`,
                                id: course.id
                            }
                        })
                    };
                }
            }
        });

    </script>
@endpush