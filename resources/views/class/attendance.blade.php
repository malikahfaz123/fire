@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add Attendance</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Training Details > Courses > View Course > View Classes > Add Attendance</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Course ID:'=>$course->prefix_id,'Sequence No.'=> $class->id,'Total Firefighters:'=> $firefighters->count]])
                @include('partials.meta-box',['icon'=>'school','labels'=>['Course Name:'=>$course->course_name,'Start Date:'=>\App\Http\Helpers\Helper::date_format($class->start_date)],'bg_class'=>'bg-gradient-dark'])
            </div>
            <div class="col-md-6 text-right">
                @include('partials.history-button')
                @include('partials.back-button')
            </div>
        </div>
        <div class="filter-container">
            <div class="mb-3">
                <h5>Data Filters</h5>
            </div>
            <form id="filter" action="#" novalidate>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id">DFSID</label>
                            <input type="search" name="prefix_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Firefighter Name</label>
                            <input type="search" name="firefighter_name" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-info btn-wd"><span class="material-icons">filter_alt</span> Filter</button>
                        <button id="clear" type="reset" class="btn btn-wd"><span class="material-icons">refresh</span> Reset</button>
                    </div>
                </div>
            </form>
        </div>
        <form id="add" novalidate>
            @csrf
            @method('put')
            <div class="record-container">
                <div class="col-md-7 m-auto">
                    <div class="form-group selectpicker-custom-style">
                        <label class="roboto-bold">Bulk Select</label>
                        <div style="max-width: 300px;">
                            <select name="bulk_selection" class="form-control selectpicker">
                                <option value="">Choose an option</option>
                                <option value="completed">Completed</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="withdraw">Withdraw</option>
                                <option value="no show">No show</option>
                                <option value="stand by">Stand by</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="class_id" value="{{ $class->id }}">
                    <input type="hidden" name="admin_ceu" value="{{ \App\Http\Helpers\FirefighterHelper::get_admin_ceu($class->id) }}">
                    <input type="hidden" name="tech_ceu" value="{{ \App\Http\Helpers\FirefighterHelper::get_tech_ceu($class->id) }}">
                    <div id="table-content">
                        @include('partials/loading-table')
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3" {{ $firefighters->count ? '' : 'disabled' }}><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                {{-- <a href="{{ route('class.show',[$course->id,$class->id]) }}" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a> --}}
            </div>
        </form>
    </div>
@endsection

@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    @include('partials.message-modal',['id'=>'history-modal','title'=>'Attendance History','max_width'=>750])
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        let loading, selectpicker = $(".selectpicker");
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('class.paginate-attendance',[$semester_id,$course->id,$class->id]) }}?${form}&page=${page}`;
            axios.get(url).then((response)=>{
                $('#table-content').html(response.data);
            })
        }

        document.addEventListener("DOMContentLoaded", ()=>{
            loading = $('#table-content').html();
            load_records(1);
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
            url = `{{ route('class.paginate-attendance',[$semester_id,$course->id,$class->id]) }}?${req}&page=${page}`;
            load_records(page,url);
        }

        $(document).on('change','#bulk-checkbox',function () {
            $(document).find('[name*="firefighter"]').prop('checked',$(this).is(':checked'));
        });

        $(document).on('change','select[name=bulk_selection]',function () {
            $(document).find('[name*=attendance]').val($(this).val())
        });

        $(document).on('change','[name*=attendance]',function () {
            $(document).find($(this).data('toggle')).prop('checked',true);
        });

        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('class.update-attendance',[$semester_id,$class->course_id,$class->id]) }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    $(document).find('[name*="firefighter"]').prop('checked',$(this).is(':checked'));
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
                url: '{{ route('class.history-attendance',[$course->id,$class->id]) }}',
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
