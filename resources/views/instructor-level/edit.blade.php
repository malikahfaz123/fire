@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Edit Instructor Level</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Facilities > Edit Instructor Level</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <form id="add">
            @csrf
            @method('put')
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
                                        <input type="hidden" name="current_instructor_level" value="{{ $instructor_level }}">
                                        <select class="form-control selectpicker" name="instructor_level">
                                            <option value="">Any</option>
                                            <option {{ $instructor_level== 1 ? 'selected' : '' }} value="1">1</option>
                                            <option {{ $instructor_level== 2 ? 'selected' : '' }} value="2">2</option>
                                            <option {{ $instructor_level== 3 ? 'selected' : '' }} value="3">3</option>
                                            <option {{ $instructor_level== 4 ? 'selected' : '' }} value="4">4</option>
                                            <option {{ $instructor_level== 5 ? 'selected' : '' }} value="5">5</option>
                                        </select>
                                        <div id="instructor_level" class="invalid-feedback">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Courses</label>
                                    </th>
                                    <td>
                                        <select id="courses-select2" multiple="multiple" name="courses[]" class="form-control">
                                            @if($courses && $courses->count())
                                                @foreach($courses as $course)
                                                    @php $course = $course->course @endphp
                                                    <option value="{{ $course->id }}" selected>{{ $course->course_name }} ({{ $course->prefix_id }})</option>
                                                @endforeach
                                            @endif
                                        </select>
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
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
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

        $('#add').on('submit',function (e) {
            e.preventDefault();

            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('instructor-level.update',$instructor_level) }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    window.location.href = `{{ route('instructor-level.index') }}/${response.data.instructor_level}/edit`;
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