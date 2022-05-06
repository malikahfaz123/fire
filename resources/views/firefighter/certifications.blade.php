@extends('layouts.app',['title'=>$title])
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@include('sweet::alert')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush



@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Credentials</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Personnel > View Personnel > Credentials</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['DFSID:'=>$firefighter->prefix_id,'Awarded Certificates:'=> $awarded_certificates->count]])
            </div>
            <div class="col-md-6 text-right">
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
                            <label for="id">Credential Code</label>
                            <input type="search" name="prefix_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Credential Name</label>
                            <input type="search" name="title" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Receiving Date</label>
                            <input type="date" name="receiving_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Issue Date</label>
                            <input type="date" name="issue_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Lapse Date</label>
                            <input type="date" name="lapse_date" class="form-control">
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
            <div class="form-group selectpicker-custom-style">
                <label class="roboto-bold">Bulk Select</label>
                <div style="max-width: 300px;">
                    <select name="bulk_selection" class="form-control selectpicker">
                        <option value="">Choose an option</option>
                        <option value="renewed">Renewed</option>
                    </select>
                </div>
            </div>
            <div id="table-content">
                @include('partials/loading-table')
            </div>
        </div>
        <div class="text-center mt-4 renew_btn d-none">
            <a href="javascript:void(0)" class="btn btn-primary submit-btn btn-wd btn-lg mr-3 renew_all">
                <span class="material-icons loader rotate mr-1">
                    autorenew
                </span>
                Renew
            </a>
            <a href="<?php echo route('certification.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">
                Cancel
            </a>
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
                    <div id="archive-modal-content" class="modal-body">Are you sure you want to unarchive this record ?</div>
                    <div class="modal-footer">
                        <button type="submit" id="archive-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    <div id="renew-cert-modal" tabindex="1" role="dialog" aria-labelledby="renew-cert-modal-title" aria-hidden="true"
         class="modal fade">
        <div role="document" class="modal-dialog">
            <form id="renew-cert" class="modal-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="id">
                <div class="modal-header"><h5 id="renew-cert-modal-title" class="modal-title cambria-bold">Renew Credential</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div id="renew-cert-modal-content" class="modal-body">
                    <p>Are you sure you want to renew this credential ?</p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="notification" name="send_email" value="1">
                        <label class="form-check-label" for="notification">Send email notification</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="eligibility-details-modal" tabindex="1" role="dialog" aria-labelledby="eligibility-details-modal" aria-hidden="true"
         class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h5 class="cert_name text-uppercase text-center"></h5>
                </div>
                <div class="modal-body">
                    <h6 class="text-uppercase"><b>Current Cycle:</b>
                        <span id="from_date" class="ml-3"></span> <b class="ml-2">To</b> <span id="to_date" class="ml-2"></span>
                    </h6>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>CEU'S REQ</th>
                                    <th>CEU'S COMP</th>
                                    <th>REQ'S MET</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Admin</td>
                                    <td id="req_admin_ceu"></td>
                                    <td id="comp_admin_ceu"></td>
                                    <td>
                                        <span class="badge" id="admin_status"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tech</td>
                                    <td id="req_tech_ceu"></td>
                                    <td id="comp_tech_ceu"></td>
                                    <td>
                                        <span class="badge" id="tech_status"></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>










    <div id="MyPopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;</button>
                <h4 class="modal-title">
               
                </h4>
            </div>
            <div>

           <center> <h5>Some operation succeed but some operation failed Beacause you dont have much CEU,S to renew your credential </h5>
            <p>Following Credential : </p> </center>
        
            </div>

            <center>
            <div class="modal-bodyy">
        
            </div>

            </center>
            <div class="modal-footer">
                <input type="button" id="btnClosePopup" value="Close" class="btn btn-danger" />
            </div>
        </div>
    </div>
</div>



@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>

        let loading, selectpicker = $(".selectpicker");
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('firefighter.paginate-certifications',$firefighter->id) }}?${form}&page=${page}`;
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
            url = `{{ route('firefighter.paginate-certifications',$firefighter->id) }}?${req}&page=${page}`;
            load_records(page,url);
        }

        /*============================
                RENEW CERTIFICATION
        *=============================*/

        $(document).on('click','.renew-certificate',function () {
            $('#renew-cert-modal').modal('show').find('[name=id]').val($(this).data('id'));
        });

        $('#renew-cert').on('submit',function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled',true);
            axios.put("{{ \Illuminate\Support\Facades\URL::to('/firefighter/renew-certification') }}/"+$('#renew-cert-modal').find('[name=id]').val(),$(this).serialize()).then((response)=>{
                $('#renew-cert-modal').modal('hide');
                submit_btn.prop('disabled',false);
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
                $('#delete-modal').modal('hide');
                submit_btn.prop('disabled', false);
                submit_btn.removeClass('disabled');
            })
        });

        /*============================
                BULK RENEW CERTIFICATE
        *=============================*/

        $(document).on('change','select[name=bulk_selection]',function () {
            $('.renew_btn').addClass('d-none');
            $(".certificate-checkbox").prop('checked', false);
            $('.renew-certificate').removeClass('d-none');
            if($(this).val() == "renewed") {
                $(".certificate-checkbox").prop('checked', true);
                $('.renew_btn').removeClass('d-none');
                $('.renew-certificate').addClass('d-none');
            }
        });

        $(document).on('click', '.renew_all', function () {
            Swal.fire({
                title: 'Are you sure you want to renew?',
                showCancelButton: true,
                confirmButtonText: `Confirm`,
            }).then((result) => {
                if (result.value) {
                    var cert_ids = [];
                    $("#cert-table input:checkbox:checked").map(function () {
                        cert_ids.push($(this).val());
                    });
                    console.log(cert_ids);

                    $.ajax({
                        url: "{{ url('/firefighter/bulk-renew-cert') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "cert_ids": cert_ids,
                        },
                        success: function (resp) {
                            if (resp.status)
                             {
                                if (resp.errorIfAny && resp.errorIfAny.length > 0) 
                                {
                                    setTimeout(function () {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Credentials Renew Successfully ',
                                            html:
                                                '<a href="{{ route('certification.expired') }}">Click to see</a>',
                                        });
                                    });
                                }

                                else if (resp.errorIfAny2.length > 0) 
                                {
                                  
                                    $.each(resp.errorIfAny2, function( index, value ) {
                                        console.log(value)
                                        $('.modal-bodyy').append('<ul>');
                                        $('.modal-bodyy').append(`<li>${value}</li>`);
                                        $('.modal-bodyy').append('</ul>');
                                     +'</br>'+
                                 


                                    $('#MyPopup').modal('show');
                                                                      
                                  });
                                  

                                    $("#btnClosePopup").click(function ()
                                     {
                                    $("#MyPopup").modal("hide");
                                     });
                                    
                                }
                                else {
                                    Toast.fire({
                                        icon: 'success',
                                        title: resp.msg
                                    });
                                }
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Error',
                                    text: resp.msg,
                                });
                            }
                            reload_current_page();
                            $('.renew_btn').addClass('d-none');
                            selectpicker.val('');
                            selectpicker.selectpicker("refresh");
                        },
                    });
                } else {
                    reload_current_page();
                    $('.renew_btn').addClass('d-none');
                    selectpicker.val('');
                    selectpicker.selectpicker("refresh");
                }
            });
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

            axios.delete("{{ route('firefighter.index') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
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
                $('#delete-modal').modal('hide');
                submit_btn.prop('disabled', false);
                submit_btn.removeClass('disabled');
            })
        });
        /*============================
                ARCHIVE
        *=============================*/
        $(document).on('click','.unarchive',function (e) {
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

            axios.post("{{ route('firefighter.unarchive') }}",$(this).serialize()).then((response)=>{
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
        });

        $(document).on('click','.showEligibilityDetails',function(){
            $('.cert_name').text($(this).data('certificate_name'));
            $('#req_admin_ceu').text($(this).data('req_admin_ceu'));
            $('#comp_admin_ceu').text($(this).data('comp_admin_ceu'));
            $('#req_tech_ceu').text($(this).data('req_tech_ceu'));
            $('#comp_tech_ceu').text($(this).data('comp_tech_ceu'));
            $('#admin_status').text('N').addClass('badge-warning');
            if($(this).data('req_admin_ceu') == $(this).data('comp_admin_ceu')) {
                $('#admin_status').text('Y').removeClass('badge-warning').addClass('badge-success');
            }
            $('#tech_status').text('N').addClass('badge-warning');
            if($(this).data('req_tech_ceu') == $(this).data('comp_tech_ceu')) {
                $('#tech_status').text('Y').removeClass('badge-warning').addClass('badge-success');
            }
            $('#from_date').text($(this).data('from_date'));
            $('#to_date').text($(this).data('to_date'));
            $('#eligibility-details-modal').modal('show');
        });
    </script>
@endpush
