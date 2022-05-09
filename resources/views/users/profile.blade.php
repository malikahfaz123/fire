@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>My Profile</h3>
        </div>
        <div class="row mb-4">
            <div class="col-12 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <div class="text-right mt-3 mb-3">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="toggle_edit" name="toggle_edit">
                <label class="custom-control-label" for="toggle_edit">Edit</label>
            </div>
        </div>
        <form id="add">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Profile Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="150">
                                        <label class="required">Full Name</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $user->name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                                            <div id="name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Date of birth</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->dob ? \App\Http\Helpers\Helper::date_format($user->dob) : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="date" class="form-control" name="dob" value="{{ $user->dob }}">
                                            <div id="dob" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Gender</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <select name="gender" class="form-control" title="Choose an option">
                                                <option value="">Choose an option</option>
                                                <option {{ $user->gender==='male' ? 'selected' : '' }} value="male">Male</option>
                                                <option {{ $user->gender==='female' ? 'selected' : '' }} value="female">Female</option>
                                                <option {{ $user->gender==='transgender' ? 'selected' : '' }} value="transgender">Transgender</option>
                                                <option {{ $user->gender==='other' ? 'selected' : '' }} value="other">Other</option>
                                            </select>
                                            <div id="gender" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Race</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->race ? ucfirst($user->race) : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <select class="form-control" name="race">
                                                <option value="">Choose an option</option>
                                                <option {{ $user->race == 'american indian or alaskan native' ? 'selected' : '' }} value="american indian or alaskan native">American Indian or Alaskan Native</option>
                                                <option {{ $user->race == 'asian or pacific islander' ? 'selected' : '' }} value="asian or pacific islander">Asian or Pacific Islander</option>
                                                <option {{ $user->race == 'black, not of hispanic origin' ? 'selected' : '' }} value="black, not of hispanic origin">Black, not of Hispanic origin</option>
                                                <option {{ $user->race == 'white, not of hispanic origin' ? 'selected' : '' }} value="white, not of hispanic origin">White, not of Hispanic origin</option>
                                                <option {{ $user->race == 'hispanic' ? 'selected' : '' }} value="hispanic">Hispanic</option>
                                            </select>
                                            <div id="race" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 text-center">
                            @php $user_image = $user->user_image ? asset('storage/users/medium/'.$user->user_image) : asset('storage/no_image.jpg'); @endphp
                            <div class="show-field">
                                <img src="{{ $user_image }}" width="{{ config('constant.medium_size') }}" alt="{{ $user->name }}">
                            </div>
                            <div class="edit-field d-none">
                                <div class="form-group text-center">
                                    <div class="position-relative text-center preview">
                                        <span data-toggle="#add" class="material-icons position-absolute fa fa-times remove_preview text-danger" style="display:none; right: -20px; cursor: pointer;">close</span>
                                        <label for="user_image" class="pointer mb-0">
                                            <img class="preview_image" width="{{ config('constant.medium_size') }}" src="{{ $user_image }}" alt="your image"/>
                                        </label>
                                        <input type="hidden" name="delete_image" value="">
                                        <input id="user_image" class="userfile d-none" type="file" name="user_image" onchange="readURL(this,'#add');">
                                    </div>
                                    <div class="text-danger upload_msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Contact Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th>
                                        <label>Address</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->address ? $user->address : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" id="google-autocomplete-places" class="form-control" name="address" value="{{ $user->address }}">
                                            <div id="address" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->city ? $user->city : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="city" value="{{ $user->city }}">
                                            <div id="city" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->state ? $user->state : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="state" value="{{ $user->state }}">
                                            <div id="state" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zip code</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->zipcode ? $user->zipcode : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="zipcode" value="{{ $user->zipcode }}">
                                            <div id="zipcode" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="155">
                                        <label>Home Phone</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->home_phone ? $user->home_phone : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="home_phone" value="{{ $user->home_phone }}">
                                            <div id="home_phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Cell Phone</label>
                                    </th>
                                    <td>
                                        <div class="show-field">
                                            <div>{{ $user->cell_phone ? $user->cell_phone : 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control d-inline-block" name="cell_phone" value="{{ $user->cell_phone }}">
                                            <div id="cell_phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Work Phone</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->work_phone ? $user->work_phone : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="work_phone" value="{{ $user->work_phone }}">
                                            <div id="work_phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Work Phone Ext.</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $user->work_phone_ext ? $user->work_phone_ext : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="work_phone_ext" value="{{ $user->work_phone_ext }}">
                                            <div id="work_phone_ext" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Work Email</label>
                                    </th>
                                    <td>
                                        <div class="show-field">
                                            <div>{{ $user->email }}</div>
                                        </div>
                                        <div class="edit-field d-none">
                                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                            <div id="email" class="text-danger" data-required="Email is required" data-valid-email="Invalid email format"></div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="edit-field d-none text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('dashboard') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            let form = new FormData(this);
            var imagefile = document.querySelector('#user_image');
            form.append("user_image", imagefile.files[0]);

            axios.post("{{ route('user.profile') }}",form).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    setTimeout(function () {
                        window.location.reload();
                    },1500)
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
        @if($user->user_image)
        $(document).ready(function () {
            setUserImage('{{ asset('storage/users/medium/'.$user->user_image) }}')
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

        function setUserImage(url) {
            $('.remove_preview').show();
            $('.preview_image').prop('src',url);
        }
        $('.remove_preview').on('click', function () {
            let target = $($(this).data('toggle'));
            target.find('.preview_image').prop('src', '{{ asset('storage/no_image.jpg') }}');
            $(this).css('display', 'none');
            target.find('.userfile').val('');
            if ($(this).data('toggle') === '#add') {
                $('input[name=delete_image]').val('1');
            }
        });
    </script>
@endpush