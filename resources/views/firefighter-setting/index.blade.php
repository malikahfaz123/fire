@extends('layouts.app',['title'=>$title,'sidebar'=>'partials.settings-sidebar'])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Invite Personnel</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Total Record(s):'=> $firefighter ]])
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('firefighter.setting.invite-firefighter') }}" class="btn btn-primary btn-wd"><span class="material-icons">add</span> Invite Personnel</a>
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
                            <label for="id">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id">Date</label>
                            <input type="date" name="date" class="form-control">
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
                    <input type="hidden" name="function">
                    <div class="modal-header"><h5 id="delete-modal-title" class="modal-title cambria-bold">Confirmation Required</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="delete-modal-content" class="modal-body"><p class="para"></p></div>
                    <div class="modal-footer">
                        <button type="submit" id="delete-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="confirm-modal" tabindex="1" role="dialog" aria-labelledby="confirm-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="confirm-form" novalidate>
                    @csrf
                    @method('put')
                    <input type="hidden" name="confirm">
                    <div class="modal-header"><h5 id="confirm-modal-title" class="modal-title cambria-bold">Notice</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="confirm-modal-content" class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="submit" id="confirm-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
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
        let loading;
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('firefighter.setting.paginate') }}?${form}&page=${page}`;
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
            url = `{{ route('firefighter.setting.paginate') }}?${req}&page=${page}`;
            load_records(page,url);
        }

        /*========================================
                REVOKED INVITE OR DELETE INVITE
        *=========================================*/
        $(document).on('click','.delete',function (e) {
            e.preventDefault();
            let id = $(this).data('delete');
            let modal = $('#delete-modal');
            modal.modal('show');
            modal.find('[name=delete]').val(id);
            modal.find('[name=function]').val('deleteInvite');
            $('.para').text('Are you sure you want to delete this record ?');
        });

        $(document).on('click','.revokeInvite',function (e) {
            e.preventDefault();
            let id = $(this).data('delete');
            let modal = $('#delete-modal');
            modal.modal('show');
            modal.find('[name=delete]').val(id);
            modal.find('[name=function]').val('revokeInvite');
            $('.para').text('Are you sure you want to cancel this invitation ?');
        });

        $(document).on('click','.changeRole',function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Assign admin rights to this person?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, make it!'
            }).then((result) => {
                if(result.value) {
                    $.ajax({
                        url: "{{ url('/firefighter/change_role/') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "firefighter_id": $(this).data('firefighter_id'),
                            "role": $(this).data('role'),
                        },
                        success: function (response) {
                            if(response.status){
                                Toast.fire({
                                    icon: 'success',
                                    title: response.msg
                                });
                                reload_current_page();
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Error',
                                    text: response.msg,
                                });
                            }
                        },
                    });
                }
            });
        });

        $('#delete-form').on('submit',function (e) {
            e.preventDefault();
            let functionn = $('[name=function]').val();
            let submit_btn = $(this).find('[type=submit]');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            $.ajax({
                url: functionn == "revokeInvite" ? "{{ url('/user/revoke_invitation/') }}" : "{{ url('/user/delete_invitation/') }}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    "data": $('input[name=delete]').val()
                },
                success:function(response){
                    var json = $.parseJSON(response);
                    if(json.status)
                    {
                        Toast.fire({
                            icon: 'success',
                            title: json.msg
                        });
                        reload_current_page();
                    }
                    else
                    {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Error',
                            text: json.msg,
                        });
                    }
                    $('#delete-modal').modal('hide');
                    submit_btn.prop('disabled', false);
                    submit_btn.removeClass('disabled');
                },
            });
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


        /* Upgarde Or Downgrade User */
        $(document).on('click', '.manageRole', function() {
            // console.log('click');
            if($(this).data('role') == 'student')
            {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Assign admin rights to this person?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, make it!'
                }).then((result) => {
                    if (result.value) {
                        // console.log(result.value);
                        if(result.value == true){
                            var id = $(this).data('firefighter_id');
                            var email = $(this).data('firefighter_email');
                            // console.log(id)
                            $.ajax({
                                type: "POST",
                                url: "{{route('firefighter.setting.manage-role-firefighter')}}",//this is only changes
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    'id': id,
                                    'admin' : 'yes',
                                    'email' : email,
                                },
                                success: function(data) {
                                    $('.role .roles-'+id).text('Downgrade to Student');
                                    console.log('success')
                                    // console.log(id);
                                },
                                error: function(error) {
                                    console.log('error');

                                }
                            });
                        }
                    }
                });
            }
            else
            {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Revoked admin rights of this person?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, make it!'
                }).then((result) => {
                    if (result.value) {
                        // console.log(result.value);
                        if(result.value == true){
                            var id = $(this).data('firefighter_id');
                            var email = $(this).data('firefighter_email');
                            // console.log($(this).data('firefighter_id'))
                            $.ajax({
                                type: "POST",
                                url: "{{route('firefighter.setting.manage-role-firefighter')}}",//this is only changes
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    'id': id,
                                    'admin': 'no',
                                    'email' : email,
                                },
                                success: function(data) {
                                    $('.role .roles-'+id).text('Upgrade to Admin');
                                    console.log("success");
                                },
                                error: function(error) {
                                    console.log(error);

                                }
                            });
                        }
                    }
                });
            }
        });
    </script>
@endpush
