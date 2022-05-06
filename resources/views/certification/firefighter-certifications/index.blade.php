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
                <span class="segoe-ui-italic">Credentials > View Credential > View Personnel</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 mb-4"> 
                @include('partials.meta-box',['labels'=>['Total Records:'=> $total_count->count]])
                @include('partials.meta-box',['labels'=>['Credential Code:'=> $certification->prefix_id,'Credential Name:'=> $certification->title],'bg_class'=>'bg-gradient-dark','icon'=>'stars'])
            </div>
            <div class="col-md-4 text-right">
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
                                <option value="accepted">Accepted</option>
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
                    <div id="table-content">
                        @include('partials/loading-table')
                    </div>
                </div>
            </div>
            <br>
      
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
                        <input type="hidden" name="certification_firefighter_id">
                        <input type="hidden" name="certificate_id">
                        <label for="">Reason</label>
                        <textarea name="reason" class="form-control" cols="30" rows="10" ></textarea>

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

                <div class="modal-body">
                    <input type="hidden" name="id">
                    
                    <div id="show-view-reason"></div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="accepted-modal" tabindex="1" role="dialog" aria-labelledby="apply-course-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="accepted-form" >
                    @csrf

                    <div class="modal-header"><h3 id="" class="modal-title cambria-bold ml-auto">Schedule Test Form </h3>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Credential Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless w-100">
                                            <tbody>
                                                <input type="hidden" name="firefighter_certificates_id">
                                                <input type="hidden" name="certificate_id">
                                                <input type="hidden" name="certification_firefighter_id">
                                                <input type="hidden" name="status" value="none">
                                                <tr>
                                                    <th width="160">
                                                        <label>Credential Code</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_prefix_id" disabled>
                                                        <div id="certification_prefix_id" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Credential Title</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_title" disabled>
                                                        <div id="certification_title" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Personnel Name</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_firefighter_name" disabled>
                                                        <div id="certification_firefighter_name" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Test Date</label>
                                                    </th>
                                                    <td>
                                                        <input type="date"  min="<?php echo date('Y-m-d'); ?>"  class="form-control" name="test_date">
                                                        <div id="test_date" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Test Time</label>
                                                    </th>
                                                    <td>
                                                        <select class="form-control form-group-sm d-inline-block" name="start_hour" style="width: 73px;">
                                                            @for($i=0; $i<24; $i++)
                                                                <option value="{{ $i<10 ? '0'.$i : $i  }}">{{ $i<10 ? '0'.$i : $i  }}</option>
                                                            @endfor
                                                        </select>
                                                        <select class="form-control form-group-sm d-inline-block" name="start_minute" style="width: 73px;">
                                                            @for($i=0; $i<60; $i+=5)
                                                                <option value="{{ $i<10 ? '0'.$i : $i  }}">{{ $i<10 ? '0'.$i : $i  }}</option>
                                                            @endfor
                                                        </select>
                                                        <span class="text-muted pl-2">(hh:mm)</span>
                                                        <div id="start_time" class="invalid-feedback"></div>
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
            url = url ? url : `{{ route('certificate.view-firefighters-paginate', $certificate_id) }}?${form}&page=${page}`;
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
            url = `{{ route('certificate.view-firefighters-paginate',$certificate_id) }}?${req}&page=${page}`;
            load_records(page,url);
        }

        $(document).on('change','[name*=status]',function () {
            $(document).find($(this).data('toggle')).prop('checked',true);
            let value = $(this).val();
            if(value == 'rejected'){
                $('#rejected-modal').modal('show');

                id = $(this).data('id');
                certification_firefighter_id = $(this).data('certification_firefighter_id');
                certificate_id = $(this).data('certificate_id');

                $('[name=id]').val(id);
                $('[name=certification_firefighter_id]').val(certification_firefighter_id);
                $('[name=certificate_id]').val(certificate_id);
            }

            if(value == 'accepted'){
                $('#accepted-modal').modal('show');
                id = $(this).data('id');
                certification_prefix_id = $(this).data('certification_prefix_id');
                certificate_id = $(this).data('certificate_id');
                certification_title = $(this).data('certification_title');
                certification_firefighter_name = $(this).data('certification_firefighter_name');
                certification_firefighter_id = $(this).data('certification_firefighter_id');

                $('[name=firefighter_certificates_id]').val(id);
                $('[name=certificate_id]').val(certificate_id);
                $('[name=certification_prefix_id]').val(certification_prefix_id);
                $('[name=certification_title]').val(certification_title);
                $('[name=certification_firefighter_name]').val(certification_firefighter_name);
                $('[name=certification_firefighter_id]').val(certification_firefighter_id);
            }

        });

        // for chechbox 
        $(document).on('change','#bulk-checkbox',function () {
            $(document).find('[name*="firefighter"]').prop('checked',$(this).is(':checked'));
        });
        
        $(document).on('change','select[name=bulk_selection]',function () {
            $(document).find('[name*=status]').val($(this).val());
        });

        
        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');

            axios.post("{{ route('certificate.approved-firefighters-certificate') }}",$(this).serialize()).then((response)=>{
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

            axios.post("{{ route('certificate.firefighters.reject.certificate') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });

                    $('#rejected-modal').modal('hide');
                    $("#add").submit();
                    ('[name=id]').val('');
                    ('[name=reason]').val('');
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

        $('#accepted-form').on('submit',function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');

            axios.post("{{ route('certificate.firefighters.accept.certificate') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });

                    $('#accepted-modal').modal('hide');
                    $("#add").submit();
                    $("#accepted-form").trigger('reset');
                    location.reload();
                    // reload_current_page();

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