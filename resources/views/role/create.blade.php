@extends('layouts.app',['title'=>$title,'sidebar'=>'partials.settings-sidebar'])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add User</h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Roles & Permissions > Add Role</span>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <form id="add">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Role Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="150">
                                        <label class="required">Role</label>
                                    </th>
                                    <td>
                                        <input type="text" name="name" class="form-control alpha-only">
                                        <div id="name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($permissions->count())
                        <div class="row">
                            @foreach($permissions as $key=>$permission)
                                @php
                                    $single_permission = strtolower(str_replace('.create','',$permission->name));

                                @endphp
                                <div class="col-md-5">
                                    <table class="table table-borderless w-100">
                                        <tbody>
                                        <tr>
                                            <th width="150">

                                                <label class="text-capitalize">{{ str_replace('_',' ',$single_permission) }}</label>
                                            </th>
                                            <td>
                                                <div class="text-left">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="{{$single_permission}}.create" name="{{$single_permission}}[create]" value="1">
                                                        <label class="custom-control-label" for="{{$single_permission}}.create">Create</label>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="{{$single_permission}}.read" name="{{$single_permission}}[read]" value="1">
                                                        <label class="custom-control-label" for="{{$single_permission}}.read">View</label>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="{{$single_permission}}.update" name="{{$single_permission}}[update]" value="1">
                                                        <label class="custom-control-label" for="{{$single_permission}}.update">Update</label>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="{{$single_permission}}.delete" name="{{$single_permission}}[delete]" value="1">
                                                        <label class="custom-control-label" for="{{$single_permission}}.delete">Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @if($key%2)
                                    <div class="offset-md-2"></div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Create</button>
                <a href="<?php echo route('settings.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
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
            axios.post("{{ route('role.store') }}",$(this).serialize()).then((response)=>{
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
    </script>
@endpush