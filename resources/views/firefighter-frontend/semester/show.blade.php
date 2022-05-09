@extends('layouts.firefighters-app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>View Semester</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Semesters > View Semester</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Semester ID:'=>\App\Http\Helpers\Helper::prefix_id($semester->id)]])
                @include('partials.meta-box',['labels'=>['Total Courses:'=> $semester_courses->count() ]])
            </div>
            <div class="col-md-6 text-right">
                <div class="pb-1">
                    @include('partials.back-button')
                </div>
            </div>
        </div>
        <div class="text-right mt-3 mb-3">
        </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Semester Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="160">
                                        <label>Semester</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $semester->semester }}</div>
                                        <div class="edit-field d-none">
                                            <div class="form-check d-inline-block mr-2">
                                                <input class="form-check-input" type="radio" name="semester" id="semester-spring" value="spring" {{ $semester->semester == 'spring' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="semester-spring">Spring</label>
                                            </div>
                                            <div class="form-check d-inline-block mr-2">
                                                <input class="form-check-input" type="radio" name="semester" id="semester-summer" value="summer" {{ $semester->semester == 'summer' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="semester-summer">Summer</label>
                                            </div>
                                            <div class="form-check d-inline-block mr-2">
                                                <input class="form-check-input" type="radio" name="semester" id="semester-fall" value="fall" {{ $semester->semester == 'fall' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="semester-fall">Fall</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio" name="semester" id="semester-winter" value="winter" {{ $semester->semester == 'winter' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="semester-winter">Winter</label>
                                            </div>
                                            <div id="semester" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Semester Year</label>
                                    </th>
                                    <td class="selectpicker-custom-style">
                                        <div class="show-field text-capitalize">{{ $semester->year }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="">Start Date</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $semester->start_date ? \App\Http\Helpers\Helper::date_format($semester->start_date) : 'N/A' }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="">End Date</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $semester->end_date ? \App\Http\Helpers\Helper::date_format($semester->end_date) : 'N/A' }}</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Semester Courses</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover app-table text-center mb-0">
                            <thead>
                                <tr>
                                    <th> ID</th>
                                    <th>Course Name</th>
                                    <th>Course Hours</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($semester_courses as $semester_course)
                                    <tr>
                                        <td>{{ $semester_course->prefix_id }}</td>
                                        <td>{{ $semester_course->course_name }}</td>
                                        <td>{{ $semester_course->course_hours }}</td>
                                        <td>
                                        @php
                                            $before_date = date_create($semester_course->start_date);
                                            date_sub($before_date,date_interval_create_from_date_string("30 days"));
                                            $before_date = date_format($before_date,"Y-m-d");

                                            $after_date = date_create($semester_course->start_date);
                                            date_add($after_date,date_interval_create_from_date_string("7 days"));
                                            $after_date = date_format($after_date,"Y-m-d");
                                        @endphp
                                            {{-- {{\App\Http\Helpers\Helper::date_format($before_date) }} --}}
                                            {{-- {{\App\Http\Helpers\Helper::date_format($after_date) }} --}}
                                            @if(date("Y-m-d") >= $before_date && date("Y-m-d") <= $after_date)
                                                <a href="javascript:void(0)"
                                                    data-semester_id="{{ $semester_course->semester_id }}" 
                                                    data-semester_name="{{ $semester_course->semester_name }}" 
                                                    data-course_id="{{ $semester_course->course_id }}" 
                                                    data-prefix_id="{{ $semester_course->prefix_id }}" 
                                                    data-course_name="{{ $semester_course->course_name }}" 
                                                    data-course_hours="{{ $semester_course->course_hours }}" 
                                                    data-firefighter_id="{{ Auth::guard('firefighters')->user()->id }}" 
                                                    class="delete" title="Apply"><span class="material-icons">add</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div id="apply-course-modal" tabindex="1" role="dialog" aria-labelledby="apply-course-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="add" >
                    @csrf

                    <div class="modal-header"><h3 id="" class="modal-title cambria-bold ml-auto">Application Form </h3>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Course Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless w-100">
                                            <tbody>
                                                <input type="hidden" name="semester_id">
                                                <input type="hidden" name="course_id">
                                                <input type="hidden" name="firefighter_id">
                                            <tr>
                                                <th width="160">
                                                    <label>Semester Name</label>
                                                </th>
                                                <td>
                                                    <input type="text" class="form-control text-capitalize" name="semester_name" disabled>
                                                    <div id="semester_name" class="invalid-feedback"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="160">
                                                    <label>Course ID</label>
                                                </th>
                                                <td>
                                                    <input type="text" class="form-control text-capitalize" name="prefix_id" disabled>
                                                    <div id="prefix_id" class="invalid-feedback"></div>
                                                </td>
                                            </tr>
                                
                                            <tr>
                                                <th width="180">
                                                    <label class="" for="course_name">Course Name</label>
                                                </th>
                                                <td>
                                                    <input type="text" name="course_name" class="form-control text-capitalize" disabled>
                                                    <div id="course_name" class="invalid-feedback"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th width="180">
                                                    <label class="" for="course_hours">Course Hours</label>
                                                </th>
                                                <td>
                                                    <input type="number" name="course_hours" class="form-control text-capitalize" disabled>
                                                    <div id="course_hours" class="invalid-feedback"></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                            
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Apply</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
        
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>

<script type="text/javascript">

    /*============================
        Course Application Form
    *=============================*/  

    $(document).on('click','.delete',function (e) {
        e.preventDefault();

        let modal = $('#apply-course-modal');
        let semester_id    =  $(this).data('semester_id');
        let semester_name  =  $(this).data('semester_name');
        let course_id      =  $(this).data('course_id');
        let prefix_id      =  $(this).data('prefix_id');
        let course_name    =  $(this).data('course_name');
        let course_hours   =  $(this).data('course_hours');
        let firefighter_id =  $(this).data('firefighter_id');

        modal.modal('show');
        modal.find('[name=semester_id]').val(semester_id);
        modal.find('[name=semester_name]').val(semester_name);
        modal.find('[name=course_id]').val(course_id);
        modal.find('[name=prefix_id]').val(prefix_id);
        modal.find('[name=course_name]').val(course_name);
        modal.find('[name=course_hours]').val(course_hours);
        modal.find('[name=firefighter_id]').val(firefighter_id);
    });

    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        axios.post("{{ route('firefighters.apply.courses') }}",$(this).serialize()).then((response)=>{
            if(response.data.status){

                $('#apply-course-modal').modal('hide');

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
</script>
@endpush