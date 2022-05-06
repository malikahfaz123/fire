@extends('layouts.app',['title' => $title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Expired Credentials</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Total Credential:' => $certifications->count]])
            </div>
            @can('certifications.create')
                <div class="col-md-6 text-right">
                    <a href="{{ route('certification.index') }}" class="btn bg-white text-secondary btn-wd"><span class="material-icons">keyboard_backspace</span> Back</a>
                </div>
            @endcan
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
                            <label for="search">Search</label>
                            <input id="search" type="search" class="form-control" name="search">
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
    @can('certifications.read')
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
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    @can('certifications.delete')
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
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection

@push('js')
    <script>
        let loading;
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('certification.paginate_expire') }}?${form}&page=${page}`;
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
            url = `{{ route('certification.paginate_expire') }}?${req}&page=${page}`;
            load_records(page,url);
        }
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

            axios.delete("{{ route('certification.index') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
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
                RENEW CERTIFICATE
        *=============================*/

        $(document).on('click','.renew-certificate',function () {
            $('#renew-cert-modal').modal('show').find('[name=id]').val($(this).data('id'));
        });

        $('#renew-cert').on('submit',function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled',true);
            axios.put("{{ \Illuminate\Support\Facades\URL::to('/certification/renew') }}/"+$('#renew-cert-modal').find('[name=id]').val(),$(this).serialize()).then((response)=>{
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
                $('#renew-cert-modal').modal('hide');
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

        $(document).on('click','.renew_all', function () {
            Swal.fire({
                title: 'Are you sure you want to renew?',
                showCancelButton: true,
                confirmButtonText: `Confirm`,
            }).then((result) => {
                console.log(result.value)
                if (result.value) {
                    var cert_ids = [];
                    $("#cert-table input:checkbox:checked").map(function(){
                        cert_ids.push($(this).val());
                    });
                    console.log(cert_ids);

                    $.ajax({
                        url: "{{ url('/certification/bulk_renew_certification') }}",
                        type:"POST",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            "cert_ids": cert_ids,
                        },
                        success:function(resp){
                            if(resp.status) {
                                Toast.fire({
                                    icon: 'success',
                                    title: resp.msg
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Error',
                                    text: resp.msg,
                                });
                            }
                            reload_current_page();
                            $('.renew_btn').addClass('d-none');
                            $('select[name=bulk_selection]').val('');
                        },
                    });
                } else {
                    reload_current_page();
                    $('.renew_btn').addClass('d-none');
                    $('select[name=bulk_selection]').val('');

                }
            });
        });
    </script>
    @endpush
