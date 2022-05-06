@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush

@section('content')

    <div class="pl-3">
        <div class="page-title">
            <h3>View Completed Courses</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Personnel > View Personnel > Courses > View Completed Courses</span>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['DFSID:'=>$firefighter->prefix_id,'Completed Courses:'=> $completed_courses->count]])
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('completed-course.archive',$firefighter->id) }}" class="btn btn-secondary btn-wd"><span class="material-icons">archive</span> Archived Courses</a>
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
                            <label for="id">Course ID</label>
                            <input type="search" name="course_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group selectpicker-custom-style">
                            <label for="type">Course Name</label>
                            <input type="search" name="course_name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group selectpicker-custom-style">
                            <label for="appointed">Completion Date</label>
                            <input type="date" name="created_at" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-info btn-wd"><span class="material-icons">filter_alt</span> Filter</button>
                        <button id="clear" type="reset" class="btn btn-wd"><span class="material-icons">refresh</span> Reset</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="record-container">
            <div class="col-12 col-xl-7 m-auto">
                <div id="table-content">
                    @include('partials/loading-table')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
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
    <div id="archive-modal" tabindex="1" role="dialog" aria-labelledby="archive-modal-title" aria-hidden="true"
         class="modal fade">
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
    <div id="transcript-modal" tabindex="1" role="dialog" aria-labelledby="transcript-modal-title" aria-hidden="true"
         class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="transcript-form" novalidate>
                    @csrf
                    <input type="hidden" name="firefighter">
                    <input type="hidden" name="semester">
                    <input type="hidden" name="course">
                    <input type="hidden" name="send_email" value="1">
                    <div class="modal-header"><h5 id="transcript-modal-title" class="modal-title cambria-bold">Transcript Options</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="transcript-modal-content" class="modal-body">
                        <div class="form-group selectpicker-custom-style">
                            <label>Transcript Code</label>
                            <select class="form-control selectpicker" name="transcript_code" data-title="Choose an option">
                                <option value="R1">R1</option>
                                <option value="R2">R2</option>
                                <option value="X1">X1</option>
                                <option value="X2">X2</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="preview" disabled="" class="btn btn-success">Preview Transcript</button>
                        <button type="submit" id="transcript-form-btn" disabled="" class="btn btn-primary">Send email</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        let loading, selectpicker = $(".selectpicker");
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('completed-course.paginate',$firefighter->id) }}?${form}&page=${page}`;
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
            selectpicker.val('default');
            selectpicker.selectpicker("refresh");
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
            url = `{{ route('completed-course.paginate',$firefighter->id) }}?${req}&page=${page}`;
            load_records(page,url);
        }

        $(document).on('click','#open-transcript-modal',function () {
            $('[name=firefighter]').val($(this).data('firefighter-id'));
            $('[name=semester]').val( $(this).data('semester-id'));
            $('[name=course]').val($(this).data('course-id'));
            $('[name=transcript_code]').val('');
            $('.selectpicker').selectpicker('refresh');
            $('#preview').prop('disabled',true);
            $('#transcript-form-btn').prop('disabled',true);
            $('#transcript-modal').modal('show');
        });

        $('[name=transcript_code]').on('change',function () {
            if($(this).val()){
                $('#preview').prop('disabled',false);
                $('#transcript-form-btn').prop('disabled',false);
            }else{
                $('#preview').prop('disabled',true);
                $('#transcript-form-btn').prop('disabled',true);
            }
        });

        $('#preview').on('click',function () {
            let url = '{{ \Illuminate\Support\Facades\URL::to('/completed-course') }}/'+$('[name=firefighter]').val()+'/'+$('[name=semester]').val()+'/'+$('[name=course]').val()+'/'+$('[name=transcript_code]').val()+'/process-transcript';
            var win = window.open(url, '_blank');
            win.focus();
        });

        $('#transcript-form').on('submit',function (e) {
            e.preventDefault();
            let buttons = $('#transcript-form').find('.modal-footer button');
            buttons.prop('disabled',true);
            let url = '{{ \Illuminate\Support\Facades\URL::to('/completed-course') }}/'+$('[name=firefighter]').val()+'/'+$('[name=semester]').val()+'/'+$('[name=course]').val()+'/'+$('[name=transcript_code]').val()+'/process-transcript';
            axios.post(url,$(this).serialize()).then((response)=>{
                if(response.data.status){
                    reload_current_page();
                    buttons.prop('disabled',false);
                    $('#transcript-modal').modal('hide');
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
                    buttons.prop('disabled',false);
                }
            })
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

            axios.post("{{ route('completed-course.archive-create',$firefighter->id) }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    reload_current_page();
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
        })
    </script>
@endpush