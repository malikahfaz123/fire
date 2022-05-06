@extends('layouts.app',['title'=>$title])
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
                <span class="segoe-ui-italic">Personnel > View Personnel > Credentials > Credential History</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-9">
                @include('partials.meta-box',['labels'=>['DFSID:'=>$firefighter->prefix_id,'Credential ID:'=> $certification->prefix_id,'Total Records:'=> $awarded_certificates->count]])
                @include('partials.meta-box',['labels'=>['Certificate Name:'=>$certification->title],'bg_class'=>'bg-gradient-dark','icon'=>'stars'])
            </div>
            <div class="col-md-3 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <div class="record-container">
            <div id="table-content">
                @include('partials/loading-table')
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
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>

        let loading, selectpicker = $(".selectpicker");
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('firefighter.paginate-certifications-past-records',[$firefighter->id,$certification->id]) }}?${form}&page=${page}`;
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
            url = `{{ route('firefighter.paginate-certifications-past-records',[$firefighter->id,$certification->id]) }}?${req}&page=${page}`;
            load_records(page,url);
        }

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

    </script>
@endpush