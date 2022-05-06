@extends('layouts.app',[ 'title' => $title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>View Personnel</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Total Courses:'=> $total_courses->count ]])
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('course.index') }}" class="btn bg-white text-secondary" ><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
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
                            <label for="id">Personnel ID</label>
                            <input type="search" name="prefix_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group selectpicker-custom-style">
                            <label for="type">Personnel Name</label>
                            <input type="search" name="firefighter_name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group selectpicker-custom-style">
                            <label for="type">Status type</label>
                            <select name="type" class="form-control selectpicker" title="Choose an option" >
                                <option value="">Any</option>
                                <option value="applied">Applied</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="rejected">Rejected</option>
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
        <form id="add" novalidate>
            <div class="record-container">
                <div class="col-12 col-xl-8 m-auto">
                    <div class="form-group selectpicker-custom-style">
                        <label class="roboto-bold">Bulk Select</label>
                        <div style="max-width: 300px;">
                            <select name="bulk_selection" class="form-control selectpicker">
                                <option value="">Choose an option</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div id="table-content">
                        @include('partials/loading-table')
                    </div>
                </div>
            </div>
            <br>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3" ><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('modals')
    <div id="rejected-modal" tabindex="1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true"
         class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="rejected-form" >
                    @csrf
                    
                    <div class="modal-header"><h5 id="delete-modal-title" class="modal-title cambria-bold">Confirmation Required</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <label for="">Reason</label>
                        <textarea name="reason" class="form-control char-textarea" data-length=255 cols="30" rows="10"></textarea>
                        <small class="text-secondary">(<span class="char-count">255</span> characters remaining)</small>
                        <div id="reason" class="invalid-feedback"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="rejected-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="reason-modal" tabindex="1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true"
         class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header"><h5 id="delete-modal-title" class="modal-title cambria-bold">Rejected Reason</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body" style="max-width: 500px;">
                    <input type="hidden" name="id">
                    <div id="show-view-reason" style="word-wrap: break-word;"></div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Close</button>
                </div>
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
            url = url ? url : `{{ route('course.view-firefighters-paginate',[$semester_id,$course_id]) }}?${form}&page=${page}`;
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
            url = `{{ route('course.view-firefighters-paginate',[$semester_id,$course_id]) }}?${req}&page=${page}`;
            load_records(page,url);
        }

        $(document).on('change','[name*=status]',function () {
            $(document).find($(this).data('toggle')).prop('checked',true);
            let value = $(this).val();
            if(value == 'rejected'){
                $('#rejected-modal').modal('show');
                id = $(this).data('id');
                $('[name=id]').val(id);
            }
        });

        // for chechbox 
        // $(document).on('change','#bulk-checkbox',function () {
        //     $(document).find('[name*="firefighter"]').prop('checked',$(this).is(':checked'));
        // });
        
        // $(document).on('change','select[name=bulk_selection]',function () {
        //     $(document).find('[name*=status]').val($(this).val());
        // });

        $(document).on('change','#bulk-checkbox',function () {
            $(document).find('[name*="firefighter"]').prop('checked',$(this).is(':checked'));
        });

        $(document).on('change','select[name=bulk_selection]',function () {
            $(document).find('[name*=status]').val($(this).val())
        });

        $(document).on('change','[name*=status]',function () {
            $(document).find($(this).data('toggle')).prop('checked',true);
        });

        /* View and Submit rejected reason */
        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');

            axios.post("{{ route('course.approved-firefighters-courses') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    // $(document).find('[name*="firefighter"]').prop('checked',$(this).is(':checked'));
                    // reload_current_page();
                    location.reload();
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

        /* View and Submit rejected reason */
        
        $(document).on('click','#view-reason',function (e) {
            e.preventDefault();
            let modal = $('#reason-modal');
            reason = $(this).data('viewreason');
            modal.find('[id=show-view-reason]').html(reason);
            modal.modal('show');
        });

        $('#rejected-form').on('submit',function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');

            axios.post("{{ route('course.firefighters.reject.course') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });

                    $("#add").submit();
                    $(this)[0].reset();
                    $('#rejected-modal').modal('hide');
                    reload_current_page();

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

        // Calculate character length of reason

        $(".char-textarea").on("keyup",function(event){
            checkTextAreaMaxLength(this,event);
        });

        function checkTextAreaMaxLength(textBox, e) { 
            var maxLength = parseInt($(textBox).data("length"));
            if (!checkSpecialKeys(e)) { 
                if (textBox.value.length > maxLength - 1) textBox.value = textBox.value.substring(0, maxLength); 
            } 
            $(".char-count").html(maxLength - textBox.value.length);
            return true; 
        } 

        function checkSpecialKeys(e) { 
            if (e.keyCode != 8 && e.keyCode != 46 && e.keyCode != 37 && e.keyCode != 38 && e.keyCode != 39 && e.keyCode != 40) 
                return false; 
            else 
                return true; 
        }
      
    </script>
@endpush