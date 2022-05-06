@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Edit Semester</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Semesters > Edit Semester</span>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Semester ID:'=>\App\Http\Helpers\Helper::prefix_id($semester->id)]])
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('semester.index') }}" class="btn bg-white text-secondary">
                    <span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back
                </a>
            </div>
        </div>
        <form id="add">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Semester Information</h4>
                </div>
                <div class="card-body">
                    <div class="col-md-7">
                        <table class="table table-borderless w-100">
                            <tbody>
                                <tr>
                                    <th width="160">
                                        <label>Semester</label>
                                    </th>
                                    <td>
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
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Semester Year</label>
                                    </th>
                                    <td class="selectpicker-custom-style">
                                        @php $start_year = date('Y')+3; @endphp
                                        <select class="form-control selectpicker" name="year" data-live-search="true">
                                            <option value="">Choose an option</option>
                                            @for($i = $start_year; $i>($start_year-32); $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <div id="year" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Start Date</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control" name="start_date" value="{{ $semester->start_date }}">
                                        <div id="start_date" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">End Date</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control" name="end_date" value="{{ $semester->end_date }}">
                                        <div id="end_date" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Semester Courses</label>
                                    </th>
                                    <td>
                                        <select id="courses-select2" multiple="multiple" name="id[]" class="form-control" {{ $edit_courses ? '' : 'disabled' }}>
                                            @if($semester_courses && $semester_courses->count())
                                                @foreach($semester_courses as $semester_course)
                                                    <option value="{{ $semester_course->course_id }}" selected>{{ $semester_course->course->course_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div id="id" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Comments</label>
                                    </th>
                                    <td>
                                        <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $semester->comment }}</textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('semester.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection


@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection

@push('js')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function () {
        $('select[name=year]').val("{{ $semester->year }}").selectpicker("refresh");
    })

    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');
        axios.post("{{ route('semester.update',$semester->id) }}",$(this).serialize()).then((response)=>{
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

    $("#courses-select2").select2({
        minimumInputLength: 2,
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
                            text: course.course_name,
                            id: course.id
                        }
                    })
                };
            }
        }
    });

</script>
@endpush