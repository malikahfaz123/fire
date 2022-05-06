@extends('layouts.app',['title'=>$title,'sidebar'=>'partials.settings-sidebar'])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>View User</h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Users > View User</span>
            </div>
        </div>
        <div class="row mb-5">
            @can('settings.update')
            <div class="col-12 text-right">
                <button class="btn btn-secondary archive {{ $user->is_archive ? 'd-none' : '' }}" data-archive="{{ $user->id }}"><span class="material-icons">archive</span> Archive</button>
                <button class="btn btn-secondary unarchive {{ $user->is_archive ? '' : 'd-none' }}" data-archive="{{ $user->id }}"><span class="material-icons">unarchive</span> Unarchive</button>
                @include('partials.back-button')
            </div>
            @endcan
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">User Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <table class="table table-borderless w-100">
                            <tbody>
                            <tr>
                                <th width="150">
                                    <label>Full Name</label>
                                </th>
                                <td class="text-capitalize">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Email</label>
                                </th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Role</label>
                                </th>
                                <td>{{ $user->role->name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
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
    <div id="unarchive-modal" tabindex="1" role="dialog" aria-labelledby="unarchive-modal-title" aria-hidden="true"
         class="modal fade">
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
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script type="text/javascript">

        function formReset(){
            document.getElementById("add").reset();
        }

        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('user.store') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    formReset();
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

            axios.post("{{ route('user.archive-create') }}",$(this).serialize()).then((response)=>{
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

            axios.post("{{ route('user.unarchive') }}",$(this).serialize()).then((response)=>{
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
        })
    </script>
@endpush