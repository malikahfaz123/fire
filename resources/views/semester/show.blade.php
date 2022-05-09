@extends('layouts.app',['title'=>$title])
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
                @if($last_updated)
                    @include('partials.meta-box',['icon'=>'schedule','labels'=>['Last updated on:'=>\App\Http\Helpers\Helper::date_format($last_updated->created_at),'Last updated by:'=>ucwords($last_updated->user->name)],'bg_class'=>'bg-gradient-dark'])
                @endif
            </div>
            <div class="col-md-6 text-right">
                <div class="pb-1">
                    @include('partials.history-button')
                    <a href="{{ route('semester.index') }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
                </div>
                @can('semesters.update')
                    <button class="btn btn-secondary archive {{ $semester->is_archive ? 'd-none' : '' }}" data-archive="{{ $semester->id }}"><span class="material-icons">archive</span> Archive</button>
                    <button class="btn btn-secondary unarchive {{ $semester->is_archive ? '' : 'd-none' }}" data-archive="{{ $semester->id }}"><span class="material-icons">unarchive</span> Unarchive</button>
                @endcan
                @can('semesters.delete')
                    <button data-delete="{{ $semester->id }}" class="btn btn-danger delete" title="Delete"><span class="material-icons">delete_outline</span> Delete</button>
                @endcan
            </div>
        </div>
        @can('semesters.update')
        <div class="text-right mt-3 mb-3">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="toggle_edit" name="toggle_edit">
                <label class="custom-control-label" for="toggle_edit">Edit</label>
            </div>
        </div>
        @endcan
        <form id="add">
            @csrf
            @method('put')
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
                                            <div class="edit-field d-none">
                                                @php $start_year = date('Y')+3; @endphp
                                                <select class="form-control selectpicker" name="year" data-live-search="true">
                                                    <option value="">Choose an option</option>
                                                    @for($i = $start_year; $i>($start_year-32); $i--)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <div id="year" class="invalid-feedback"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="required">Start Date</label>
                                        </th>
                                        <td>
                                            <div class="show-field text-capitalize">{{ $semester->start_date ? \App\Http\Helpers\Helper::date_format($semester->start_date) : 'N/A' }}</div>
                                            <div class="edit-field d-none">
                                                <input type="date" class="form-control" name="start_date" value="{{ $semester->start_date }}">
                                                <div id="start_date" class="invalid-feedback"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="required">End Date</label>
                                        </th>
                                        <td>
                                            <div class="show-field text-capitalize">{{ $semester->end_date ? \App\Http\Helpers\Helper::date_format($semester->end_date) : 'N/A' }}</div>
                                            <div class="edit-field d-none">
                                                <input type="date" class="form-control" name="end_date" value="{{ $semester->end_date }}">
                                                <div id="end_date" class="invalid-feedback"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Semester Courses</label>
                                        </th>
                                        <td>
                                            <div class="show-field text-capitalize">
                                                @foreach($semester_courses as $semester_course)
                                                    <div>{{ $semester_course->course->course_name }} ({{ $semester_course->course->prefix_id }})</div>
                                                @endforeach
                                            </div>
                                            <div class="edit-field d-none">
                                                <select id="courses-select2" multiple="multiple" name="id[]" class="form-control" {{ $edit_courses ? '' : 'disabled' }}>
                                                    @if($semester_courses && $semester_courses->count())
                                                        @foreach($semester_courses as $semester_course)
                                                            <option value="{{ $semester_course->course_id }}" selected>{{ $semester_course->course->course_name }} ({{ $semester_course->course->prefix_id }})</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="id" class="invalid-feedback"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Comments:</label>
                                        </th>
                                        <td>
                                            <div class="show-field">{{ $semester->comment }}</div>
                                            <div class="edit-field d-none">
                                                <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $semester->comment }}</textarea>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="edit-field d-none text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('semester.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection


@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    @include('partials.message-modal',['id'=>'history-modal','title'=>'History','max_width'=>750])
    @can('semesters.update')
    <div id="archive-modal" tabindex="1" role="dialog" aria-labelledby="archive-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="archive-form" novalidate>
                    @csrf
                    <input type="hidden" name="archive">
                    <div class="modal-header"><h5 id="archive-modal-title" class="modal-title cambria-bold">Notice</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="archive-modal-content" class="modal-body">Are you sure you want to archive this record ?</div>
                    <div class="modal-footer">
                        <button type="submit" id="archive-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="unarchive-modal" tabindex="1" role="dialog" aria-labelledby="unarchive-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="unarchive-form" novalidate>
                    @csrf
                    <input type="hidden" name="archive">
                    <div class="modal-header"><h5 id="unarchive-modal-title" class="modal-title cambria-bold">Notice</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="unarchive-modal-content" class="modal-body">Are you sure you want to unarchive this record ?</div>
                    <div class="modal-footer">
                        <button type="submit" id="unarchive-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
    @can('semesters.delete')
        <div id="delete-modal" tabindex="1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true"
             class="modal fade">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <form id="delete-form" novalidate>
                        @csrf
                        @method('delete')
                        <input type="hidden" name="delete">
                        <div class="modal-header"><h5 id="delete-modal-title" class="modal-title cambria-bold">Confirmation Required</h5>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div id="delete-modal-content" class="modal-body">Are you sure you want to delete this record ?</div>
                        <div class="modal-footer">
                            <button type="submit" id="delete-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                            <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
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
                setTimeout(function () {
                    window.location.reload();
                },1500)
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
                            text: `${course.course_name} (${course.prefix_id})`,
                            id: course.id
                        }
                    })
                };
            }
        }
    });

    $(document).on('click','.view-history',function () {
        let html = '<h5 class="text-center"><div class="spinner mb-2"></div> Loading...</h5>';
        $('#history-modal-content').html(html);
        $('#history-modal').modal('show');
        $.ajax({
            url: '{{ route('semester.history',$semester->id) }}',
            dataType: 'html',
            success: function (response) {
                if(!response){
                    response = '<h5 class="text-center">No history was found.</h5>';
                }
                $('#history-modal-content').html(response);
            },
            failure: function () {
                alert('Operation Failed');
            }
        });
    });

    /*============================
                ARCHIVE
    *=============================*/
    $(document).on('click','.archive',function (e) {
        e.preventDefault();
        let id = $(this).data('archive');
        let modal = $('#archive-modal');
        modal.modal('show');
        modal.find('[name=archive]').val(id);
    });

    $('#archive-form').on('submit',function (e) {
        e.preventDefault();
        let submit_btn = $(this).find('[type=submit]');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        axios.post("{{ route('semester.archive-create') }}",$(this).serialize()).then((response)=>{
            if(response.data.status){
                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });
                $('.archive').addClass('d-none');
                $('.unarchive').removeClass('d-none');
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: response.data.msg,
                });
            }
            $('#archive-modal').modal('hide');
            submit_btn.prop('disabled', false);
            submit_btn.removeClass('disabled');
        })
    });
    /*============================
                UNARCHIVE
    *=============================*/
    $(document).on('click','.unarchive',function (e) {
        e.preventDefault();
        let id = $(this).data('archive');
        let modal = $('#unarchive-modal');
        modal.modal('show');
        modal.find('[name=archive]').val(id);
    });

    $('#unarchive-form').on('submit',function (e) {
        e.preventDefault();
        let submit_btn = $(this).find('[type=submit]');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        axios.post("{{ route('semester.unarchive') }}",$(this).serialize()).then((response)=>{
            if(response.data.status){
                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });
                $('.archive').removeClass('d-none');
                $('.unarchive').addClass('d-none');
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: response.data.msg,
                });
            }
            $('#unarchive-modal').modal('hide');
            submit_btn.prop('disabled', false);
            submit_btn.removeClass('disabled');
        })
    });

    /*============================
                DELETE
    *=============================*/
    $(document).on('click','.delete',function (e) {
        e.preventDefault();
        let id = $(this).data('delete');
        let modal = $('#delete-modal');
        modal.modal('show');
        modal.find('[name=delete]').val(id);
    });
    $('#delete-form').on('submit',function (e) {
        e.preventDefault();
        let submit_btn = $(this).find('[type=submit]');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        axios.delete("{{ route('semester.index') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
            if(response.data.status){
                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });
                setTimeout(function () {
                    window.location.href = '{{ route('semester.index') }}';
                },1500)
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: response.data.msg,
                });
            }
            $('#delete-modal').modal('hide');
            submit_btn.prop('disabled', false);
            submit_btn.removeClass('disabled');
        })
    });
</script>
@endpush