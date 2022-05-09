@extends('layouts.app',[ 'title' => $title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Personnel Credentials Details</h3>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Credentials > View Credential > View Personnel Credential Details</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-8">

                @include('partials.meta-box',['labels'=>['DFSID : '=> $prefix_id[0]->firefighters_prefix_id ]])
                @include('partials.meta-box',['labels'=>['Credential Code:'=>$prefix_id[0]->certifications_prefix_id],'bg_class'=>'bg-gradient-dark','icon'=>'stars'])
            </div>
            <div class="col-md-4 text-right">
                @include('partials.back-button')
            </div>
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
  
    <div id="reshedule-modal" tabindex="1" role="dialog" aria-labelledby="apply-course-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="failed-form" >
                    @csrf

                    <div class="modal-header"><h3 id="" class="modal-title cambria-bold ml-auto">Re-Schedule Test Form </h3>
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
                                                <input type="hidden" name="certification_id">
                                                <input type="hidden" name="certification_status_id">
                                                <input type="hidden" name="certificate_statuses_firefighter_certificates_id">
                                                <input type="hidden" name="certificate_statuses_firefighter_id">
                                                {{-- <input type="hidden" name="status" value="failed"> --}}
                                                <tr>
                                                    <th width="160">
                                                        <label>Credential Code</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_status_prefix_id" disabled>
                                                        <div id="certification_status_prefix_id" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Credential Title</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_status_title" disabled>
                                                        <div id="certification_status_title" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Personnel Name</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_status_firefighter_name" disabled>
                                                        <div id="certification_status_firefighter_name" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Re-Schedule Test Date</label>
                                                    </th>
                                                    <td>
                                                        <input type="date" class="form-control" name="test_date">
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
                        <button type="submit" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Submit</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
        
            </div>
        </div>
    </div>
    <div id="award-modal" tabindex="1" role="dialog" aria-labelledby="apply-course-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="award-form" >
                    @csrf

                    <div class="modal-header"><h3 id="" class="modal-title cambria-bold ml-auto">Award Credential Form </h3>
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
                                                <input type="hidden" name="certification_id">
                                                <input type="hidden" name="certification_status_id">
                                                <input type="hidden" name="certificate_statuses_firefighter_certificates_id">
                                                <input type="hidden" name="certificate_statuses_firefighter_id">
                                                
                                                <tr>
                                                    <th width="160">
                                                        <label>Credential Code</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_status_prefix_id" disabled>
                                                        <div id="certification_status_prefix_id" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Credential Title</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_status_title" disabled>
                                                        <div id="certification_status_title" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Personnel Name</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control text-capitalize" name="certification_status_firefighter_name" disabled>
                                                        <div id="certification_status_firefighter_name" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Organization Name</label>
                                                    </th>
                                                    <td>
                                                        <select name="organization" class="form-control organizations-select2" data-live-search="true"></select>
                                                        <div id="organization" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="160">
                                                        <label>Issue Date</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control" value="<?php echo date("M d, Y",strtotime(date('Y-m-d'))) ?>" name="issue_date" disabled>
                                                        <div id="issue_date" class="invalid-feedback"></div>
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
                        <button type="submit" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Award Certificate</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
        
            </div>
        </div>
    </div>

    <div id="failed-certificate-modal" tabindex="1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="failed-certificate-modal-form" novalidate>
                    @csrf
                    <input type="hidden" name="certification_status_id">
                    <input type="hidden" name="certificate_statuses_firefighter_certificates_id">
                    <input type="hidden" name="certification_id">
                    <input type="hidden" name="certificate_statuses_firefighter_id">
                    <div class="modal-header"><h5 id="delete-modal-title" class="modal-title cambria-bold">Confirmation Required</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="failed-certificate-modal-content" class="modal-body">Are you sure you want to Failed this Firefighter ?</div>
                    <div class="modal-footer">
                        <button type="submit" id="failed-certificate-modal-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="failed-option-certificate-modal" tabindex="1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="failed-certificate-modal-form" novalidate>
                    @csrf
                    <input type="hidden" name="certification_status_id">
                    <div class="modal-header"><h5 id="delete-modal-title" class="modal-title cambria-bold">Confirmation Required</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="failed-certificate-modal-content" class="modal-body">Do you want to Re-Schedule Test ?</div>
                    <div class="modal-footer">
                        <button type="button" id="failed-modal-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Re-Schedule Test </button>
                        <button type="button" id="failed-certificate-modal-btn" class="btn btn-danger submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Failed</button>
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
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>

    $(document).find(".organizations-select2").select2({
        minimumInputLength: 2,
        placeholder: 'Search Organization',
        ajax: {
            url: '{{ route('certification.search-organization') }}',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            data: function (search) {
                return {
                    search: search.term
                };
            },
            processResults: function (organizations) {
                return {
                    results: $.map(organizations, function (organization) {
                        return {
                            text: organization.name+' '+`(${organization.prefix_id})`,
                            id: organization.id
                        }
                    })
                };
            }
        }
    });


        let loading, selectpicker = $(".selectpicker");
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('certificate.view-firefighters.status.paginte',$firefighter_certificate_id) }}?${form}&page=${page}`;
            axios.get(url).then((response)=>{
                $('#table-content').html(response.data);
            })
        }

        document.addEventListener("DOMContentLoaded", ()=>{
            loading = $('#table-content').html();
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
            url = `{{ route('certificate.view-firefighters.status.paginte',$firefighter_certificate_id) }}?${req}&page=${page}`;
            load_records(page,url);
        }

        $(document).on('change',"select[name=status]:eq(0)",function () {
            $(document).find($(this).data('toggle')).prop('checked',true);
            let value = $(this).val();

            if(value == 'failed'){
                $('#failed-option-certificate-modal').modal('show');
            }
        });

        $(document).on('click',"#failed-modal-btn",function () {
            $('#failed-option-certificate-modal').modal('hide');
            $('#reshedule-modal').modal('show');

            certification_id = $("select[name=status]:eq(0)").data('certification_id');
            certification_status_id = $("select[name=status]:eq(0)").data('certification_status_id');
            certification_status_firefighter_name = $("select[name=status]:eq(0)").data('certification_status_firefighter_name');
            certification_status_prefix_id = $("select[name=status]:eq(0)").data('certification_status_prefix_id');
            certification_status_title = $("select[name=status]:eq(0)").data('certification_status_title');
            certificate_statuses_firefighter_certificates_id = $("select[name=status]:eq(0)").data('certificate_statuses_firefighter_certificates_id');
            certificate_statuses_firefighter_id = $("select[name=status]:eq(0)").data('certificate_statuses_firefighter_id');
        
            $('[name=certification_id]').val(certification_id);
            $('[name=certification_status_id]').val(certification_status_id);
            $('[name=certification_status_firefighter_name]').val(certification_status_firefighter_name);
            $('[name=certification_status_prefix_id]').val(certification_status_prefix_id);
            $('[name=certification_status_title]').val(certification_status_title);
            $('[name=certificate_statuses_firefighter_certificates_id]').val(certificate_statuses_firefighter_certificates_id);
            $('[name=certificate_statuses_firefighter_id]').val(certificate_statuses_firefighter_id);
        });

        $(document).on('click',"#failed-certificate-modal-btn",function () {
            $('#failed-option-certificate-modal').modal('hide');

            certificate_statuses_firefighter_certificates_id = $("select[name=status]:eq(0)").data('certificate_statuses_firefighter_certificates_id');
            certification_status_id = $("select[name=status]:eq(0)").data('certification_status_id');
            certification_id = $("select[name=status]:eq(0)").data('certification_id');
            certificate_statuses_firefighter_id = $("select[name=status]:eq(0)").data('certificate_statuses_firefighter_id');
             
            $('[name=certification_status_id]').val(certification_status_id);
            $('[name=certificate_statuses_firefighter_certificates_id]').val(certificate_statuses_firefighter_certificates_id);
            $('[name=certification_id]').val(certification_id);
            $('[name=certificate_statuses_firefighter_id]').val(certificate_statuses_firefighter_id);

            $('#failed-certificate-modal').modal('show');
        });

        $(document).on('change',"select[name=status]:eq(1)",function () {
            $(document).find($(this).data('toggle')).prop('checked',true);
            let value = $(this).val();

            if(value == 'failed'){
                $('#failed-certificate-modal').modal('show');
                certification_status_id = $(this).data('certification_status_id');
                certificate_statuses_firefighter_certificates_id = $("select[name=status]:eq(0)").data('certificate_statuses_firefighter_certificates_id');
                $('[name=certification_status_id]').val(certification_status_id);
                $('[name=certificate_statuses_firefighter_certificates_id]').val(certificate_statuses_firefighter_certificates_id);
            }
        });

        $(document).on('change',"select[name=status]",function () {

            $(document).find($(this).data('toggle')).prop('checked',true);
            let value = $(this).val();

            if(value == 'passed'){

                $('#award-modal').modal('show');

                certification_id = $(this).data('certification_id');
                certification_status_id = $(this).data('certification_status_id');
                certification_status_firefighter_name = $(this).data('certification_status_firefighter_name');
                certification_status_prefix_id = $(this).data('certification_status_prefix_id');
                certification_status_title = $(this).data('certification_status_title');
                certificate_statuses_firefighter_certificates_id = $(this).data('certificate_statuses_firefighter_certificates_id');
                certificate_statuses_firefighter_id = $(this).data('certificate_statuses_firefighter_id');
            
                $('[name=certification_id]').val(certification_id);
                $('[name=certification_status_id]').val(certification_status_id);
                $('[name=certification_status_firefighter_name]').val(certification_status_firefighter_name);
                $('[name=certification_status_prefix_id]').val(certification_status_prefix_id);
                $('[name=certification_status_title]').val(certification_status_title);
                $('[name=certificate_statuses_firefighter_certificates_id]').val(certificate_statuses_firefighter_certificates_id);
                $('[name=certificate_statuses_firefighter_id]').val(certificate_statuses_firefighter_id);
            }
        });

        // for chechbox 
        $(document).on('change','#bulk-checkbox',function () {
            $(document).find('[name*="firefighter"]').prop('checked',$(this).is(':checked'));
        });
        
        $(document).on('change','select[name=bulk_selection]',function () {
            $(document).find('[name*=status]').val($(this).val());
        });

        
        $('#award-form').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');

            axios.post("{{ route('certificate.view-firefighters.award.certificate') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    $('#award-modal').modal('hide');
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

        $('#failed-form').on('submit',function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');

            axios.post("{{ route('certificate.view-firefighters.status.reshedule') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });

                    $('#reshedule-modal').modal('hide');
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

        $('#failed-certificate-modal-form').on('submit',function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');

            axios.post("{{ route('certificate.view-firefighters.status.failed.certificate') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });

                    $('#failed-certificate-modal').modal('hide');
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
    </script>
@endpush