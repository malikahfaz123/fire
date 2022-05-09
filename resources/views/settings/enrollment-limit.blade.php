@extends('layouts.app',['title'=>$title,'sidebar'=>'partials.settings-sidebar'])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Enrollment Limit</h3>
        </div>
        <form id="add">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="170"><label>Enrollment Limit:</label></th>
                                    <td>
                                        <input type="text" class="form-control" name="enrollment_limit" value="{{ isset($settings['enrollment_limit']) ? $settings['enrollment_limit'] : '' }}">
                                        <p class="text-muted"><small>If empty, default value treated will be {{ config('constant.enrollment_limit') }}.</small></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>PBOARD:</label></th>
                                    <td>
                                        <input type="text" class="form-control" name="pboard" value="{{ isset($settings['pboard']) ? $settings['pboard'] : '' }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>IFSAC:</label></th>
                                    <td>
                                        <input type="text" class="form-control" name="ifsac" value="{{ isset($settings['ifsac']) ? $settings['ifsac'] : '' }}">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('settings.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('modals')

@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('settings.save-enrollment-limit') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
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