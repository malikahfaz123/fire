@extends('layouts.app',['title'=>$title,'sidebar'=>'partials.settings-sidebar'])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Other Settings</h3>
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
                                    <th><label>Records Per Page:</label></th>
                                    <td>
                                        <input type="text" class="form-control" name="per_page" value="{{ isset($settings['per_page']) ? $settings['per_page'] : '' }}">
                                        <p class="text-muted"><small>If empty, default value treated will be {{ config('constant.per_page') }}.</small></p>
                                    </td>
                                </tr>
                                <tr>
                                    @php
                                        $helper = new \App\Http\Helpers\Helper();
                                        $month = '';
                                        $day = '';
                                        $arr = explode('-',config('constant.fall_start'));
                                        if(isset($settings['fall_start'])){
                                            $fall_start = explode('-',$settings['fall_start']);
                                            $month = $fall_start[0];
                                            $day = $fall_start[1];
                                        }
                                    @endphp
                                    {{-- <th><label>Semester Fall Start:</label></th> --}}
                                    {{-- <td>
                                        <select style="width: 80px;" class="form-control d-inline-block" name="fall_start_day">
                                            <option value="">Day</option>
                                            @for($i=1; $i<=31; $i++)
                                                @php $leading_zero = $i < 10 ? '0'.$i : $i @endphp
                                                <option {{ $leading_zero == $day ? 'selected' : '' }} value="{{ $leading_zero }}">{{ $leading_zero }}</option>
                                            @endfor
                                        </select>
                                        <select class="form-control d-inline-block" name="fall_start_month" style="width: 100px;">
                                            <option value="">Month</option>
                                            @for($i=1; $i<=12; $i++)
                                                @php $leading_zero = $i < 10 ? '0'.$i : $i @endphp
                                                <option {{ $leading_zero == $month ? 'selected' : '' }} value="{{ $leading_zero }}">{{ $helper->months[$i-1] }}</option>
                                            @endfor
                                        </select>
                                        <p class="text-muted"><small>If empty, default value treated will be {{ date('jS',$arr[1]) }} {{ $helper->months[(int) $arr[0]-1] }}.</small></p>
                                        <div id="fall_start" class="invalid-feedback"></div>
                                    </td> --}}
                                </tr>
                                <tr>
                                    <th><label>Min. attendance(%):</label></th>
                                    <td>
                                        <input type="text" class="form-control" name="min_attendance_perc" value="{{ isset($settings['min_attendance_perc']) ? $settings['min_attendance_perc'] : '' }}">
                                        <p class="text-muted"><small>If empty, default value treated will be {{ config('constant.min_attendance_perc') }}.</small></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Logo:</label></th>
                                    <td>
                                        <div id="logo-container" class="form-group">
                                            <div class="position-relative preview">
                                                <span data-toggle="#logo-container" class="material-icons position-absolute fa fa-times remove_preview text-danger" style="display:none; right: -20px; cursor: pointer;">close</span>
                                                <label for="logo_image" class="pointer mb-0">
                                                    <img style="max-width: 250px;" class="preview_image" src="{{ $logo_url }}" alt="{{ \App\Http\Helpers\Helper::get_app_name() }}"/>
                                                </label>
                                                <input class="delete_image" type="hidden" name="delete_logo" value="">
                                                <input id="logo_image" class="userfile d-none" type="file" name="logo" onchange="readURL(this,'#logo-container');">
                                            </div>
                                            <div class="text-danger upload_msg"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Favicon:</label></th>
                                    <td>
                                        <div id="favicon-container" class="form-group">
                                            <div class="position-relative preview">
                                                <span data-toggle="#favicon-container" class="material-icons position-absolute fa fa-times remove_preview text-danger" style="display:none; right: -20px; cursor: pointer;">close</span>
                                                <label for="favicon_image" class="pointer mb-0">
                                                    <img style="max-width: 250px;" class="preview_image" src="{{ $favicon_url }}" alt="{{ \App\Http\Helpers\Helper::get_app_name() }}"/>
                                                </label>
                                                <input class="delete_image" type="hidden" name="delete_favicon" value="">
                                                <input id="favicon_image" class="userfile d-none" type="file" name="favicon" onchange="readURL(this,'#favicon-container');">
                                            </div>
                                            <div class="text-danger upload_msg"></div>
                                        </div>
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

            let form = new FormData(this);

            // let logo = document.querySelector('#logo_image');
            // form.append("logo", logo.files[0]);
            // let favicon = document.querySelector('#favicon_image');
            // form.append("favicon", favicon.files[0]);

            axios.post("{{ route('settings.save-other-settings') }}",form).then((response)=>{
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

        /*===============================
               Image Upload
       ===============================*/
        @if($logo_url)
        $(document).ready(function () {
            setUserImage('{{ $logo_url }}','#logo-container');
        });
        @endif

        @if($favicon_url)
        $(document).ready(function () {
            setUserImage('{{ $favicon_url }}','#favicon-container');
        });
        @endif
        function readURL(input,form_name) {
            let upload_msg = $(form_name).find('.upload_msg');
            let preview_image = $(form_name).find('.preview_image');
            let remove_preview = $(form_name).find('.remove_preview');
            let userfile = $(form_name).find('.userfile');

            if(typeof input.files[0] === 'undefined'){
                $('.remove_preview').trigger('click');
                return false;
            }

            if (input.files && input.files[0]) {
                let limit =  parseInt("{{ config('constant.max_img_size') }}");
                if(input.files[0].size > limit){
                    userfile.val('');
                    preview_image.prop('src', '{{ asset('storage/no_image.jpg') }}');
                    remove_preview.css('display','none');
                    upload_msg.html('<p class="upload_error">File size should not exceed 1MB.</p>');
                    return false;
                }

                let reader = new FileReader();
                let ext = input.files[0].name.split('.').pop().toLowerCase();
                if(ext === 'jpg' || ext === 'jpeg' || ext === 'png' || ext === 'gif') {
                    if(upload_msg.find('p').hasClass('upload_error')) {
                        upload_msg.find('p').remove();
                    }
                    reader.onload = function (e) {
                        let image = new Image();
                        image.src = e.target.result;
                        image.onload = function() {
                            preview_image.prop('src', e.target.result);
                            remove_preview.css('display','block');
                        };
                    };
                    reader.readAsDataURL(input.files[0]);
                }else{
                    userfile.val('');
                    preview_image.prop('src', '{{ asset('storage/no_image.jpg') }}');
                    remove_preview.css('display','none');
                    upload_msg.html('<p class="upload_error">Only JPEG and PNG formats are allowed.</p>');
                }
            }
        }

        function setUserImage(url,target) {
            $(target).find('.remove_preview').show();
            $(target).prop('src',url);
        }

        $('.remove_preview').on('click', function () {
            let target = $($(this).data('toggle'));
            target.find('.preview_image').prop('src', '{{ asset('storage/no_image.jpg') }}');
            $(this).css('display', 'none');
            target.find('.userfile').val('');
            if ($(this).data('toggle') === $(this).data('toggle')) {
                target.find('.delete_image').val('1');
            }
        });
    </script>
@endpush