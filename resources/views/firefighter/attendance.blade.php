@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Firefighter</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Personnel > View Personnel > Courses > Course Attendance</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Course ID:'=>$course->prefix_id,'DFSID:'=> $firefighter->prefix_id,'Total Classes:'=> $course_classes->count]])
            </div>
            <div class="col-md-6 text-right">
                @include('partials.history-button')
                <a href="{{ route('firefighter.course',$firefighter->id) }}" style="cursor: {{ isset($_SERVER['HTTP_REFERER']) ?  'pointer' : 'not-allowed' }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
            </div>
        </div>
        @can('firefighters.update')
        <div class="text-right mb-3 mt-3">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="toggle_edit" name="toggle_edit">
                <label class="custom-control-label" for="toggle_edit" {{ !$course_classes->count ? 'disabled' : '' }}>Edit</label>
            </div>
        </div>
        @endcan
        <div class="filter-container">
            <div class="mb-3">
                <h5>Data Filters</h5>
            </div>
            <form id="filter" action="#" novalidate>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sequence No.</label>
                            <input type="search" name="class_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="search" name="start_date" class="form-control datepicker" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Attendance</label>
                            <select id="attendance" class="form-control" name="attendance">
                                <option value="">Any</option>
                                <option value="completed">Completed</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="withdraw">Withdraw</option>
                                <option value="no show">No show</option>
                                <option value="stand by">Stand by</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-info btn-wd"><span class="material-icons">filter_alt</span> Filter</button>
                        <button id="clear" type="reset" class="btn btn-wd"><span class="material-icons">refresh</span> Reset</button>
                    </div>
                </div>
            </form>
        </div>
        <form id="add">
            @csrf
            @method('put')
            <div class="record-container">
                <div class="col-12 col-xl-7 m-auto">
                    <div id="table-content">
                        @include('partials/loading-table')
                    </div>
                </div>
            </div>
            @can('firefighters.update')
                @if($course_classes->count)
                    <div class="edit-field mt-3 d-none text-center">
                        <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                        <a href="{{ route('firefighter.course',$firefighter->id) }}" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
                    </div>
                @endif
            @endcan
        </form>
    </div>
@endsection

@section('modals')
    @include('partials.message-modal',['id'=>'history-modal','title'=>'History','max_width'=>750])
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>

        let loading;
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('firefighter.paginate-attendance',[$course->id,$firefighter->id]) }}?${form}&page=${page}`;
            axios.get(url).then((response)=>{
                $('#table-content').html(response.data);
            })
        }

        document.addEventListener("DOMContentLoaded", ()=>{
            loading = $('#table-content').html();
            load_records(1);

            let datepicker = $('.datepicker');
            datepicker.datepicker();
            datepicker.on( "change", function() {
                $( this ).datepicker( "option", "dateFormat", 'yy-mm-dd'  );
            });
        });

        document.getElementById('clear').addEventListener('click',function(){
            document.getElementById("filter").reset();
            load_records(1);
        });

        document.getElementById("filter").addEventListener('submit',(e)=>{
            e.preventDefault();
            load_records(1);
        });

        $(document).on('click','.page-item:not(.active) .page-link',function (e) {
            e.preventDefault();
            let href = $(this).prop('href');
            load_records(null,href);
        });

        function reload_current_page(){
            let url,page = 1;
            if($(document).find('.page-item.active .page-link').length){
                page = parseInt($(document).find('.page-item.active .page-link').text());
            }
            req = $('#filter').serialize();
            url = `{{ route('firefighter.paginate-attendance',[$course->id,$firefighter->id]) }}?${req}&page=${page}`;
            load_records(page,url);
        }

        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('firefighter.update-attendance',[$course->id,$firefighter->id]) }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    setTimeout(()=>{
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


        $(document).on('click','.view-history',function () {
            let html = '<h5 class="text-center"><div class="spinner mb-2"></div> Loading...</h5>';
            $('#history-modal-content').html(html);
            $('#history-modal').modal('show');
            $.ajax({
                url: '{{ route('course-classes.history',[$course->id,$firefighter->id]) }}',
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
        })

    </script>
@endpush