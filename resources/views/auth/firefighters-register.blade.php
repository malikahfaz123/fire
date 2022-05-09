
@extends('layouts.firefighters-app',['title'=> 'Firefighters Registeration'])

@section('content')
    <div class="container" style="margin-top: 39px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-light">{{ __('Student Register Form') }}</div>

                    <div class="card-body">  
                        <form id="add" autocomplete="off">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Personal Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-borderless w-100">
                                                <tbody>
                                                <tr>
                                                    <th width="160">
                                                        <label class="required">First Name</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control alpha-only" name="f_name">
                                                        <div id="f_name" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label>Middle Name</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control alpha-only" name="m_name">
                                                        <div id="m_name" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">Last Name</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control alpha-only" name="l_name">
                                                        <div id="l_name" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">Email</label>
                                                    </th>
                                                    <td>
                                                        <input type="email" class="form-control" name="email">
                                                        <div id="email" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>
                                                        <label class="required">Password</label>
                                                    </th>
                                                    <td>
                                                        <input type="password" class="form-control" name="password">
                                                        <div id="password" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>
                                                        <label class="">Confirm Password</label>
                                                    </th>
                                                    <td>
                                                        <input type="password" class="form-control" name="confirm_password">
                                                        <div id="confirm_password" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <th>
                                                        <label class="required">Gender</label>
                                                    </th>
                                                    <td>
                                                        <select name="gender" class="form-control" title="Choose an option">
                                                            <option value="">Choose an option</option>
                                                            <option value="male">Male</option>
                                                            <option value="female">Female</option>
                                                            <option value="transgender">Transgender</option>
                                                            <option value="other">Other</option>
                                                        </select>
                                                        <div id="gender" class="invalid-feedback d-block"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">Date of Birth</label>
                                                    </th>
                                                    <td>
                                                        <input type="date" class="form-control" max="<?php echo date('Y-m-d'); ?>"  value="<?php echo date('Y-m-d'); ?>" name="dob">
                                                        <div id="dob" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
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
                                        <div class="col-md-12">
                                            <table class="table table-borderless w-100 mb-0">
                                                <tbody>
                                                <tr>
                                                    <th width="160">
                                                        <label class="required">Address title</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control" name="address_title">
                                                        <div id="address_title" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">Address</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" id="google-autocomplete-places" class="form-control" name="address">
                                                        <div id="address" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">City</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control alpha-only" name="city">
                                                        <div id="city" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">State</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control alpha-only" name="state">
                                                        <div id="state" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">Zip code</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control alphanumeric-only" name="zipcode">
                                                        <div id="zipcode" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">Cell Phone</label>
                                                    </th>
                                                    <td>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                            </div>
                                                            <input type="text" maxlength="10" class="form-control d-inline-block numeric-only" name="cell_phone">
                                                        </div>
                                                        <div id="cell_phone" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <label class="required">Work Email</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" class="form-control d-inline-block" name="work_email">
                                                        <div id="work_email" class="invalid-feedback"></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button id="submit-btn"  type="button" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Register</button>
                                <a href="<?php echo route('firefighters.login') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('constant.google_places_api') }}&libraries=places" defer></script>
    <script src="{{ asset('js/initMap.js') }}" defer></script>
    <script defer>
        document.addEventListener("DOMContentLoaded", ()=>{
            initMap('google-autocomplete-places','[name=zipcode]','[name=city]','[name=state]');
            initMap('google-autocomplete-places-2','[name=zipcode_2]','[name=city_2]','[name=state_2]');
            initMap('google-autocomplete-places-3','[name=zipcode_3]','[name=city_3]','[name=state_3]');
        });
    </script>
    <script>
        function formReset(){
            document.getElementById("add").reset();
            $('#second-address-pair, #third-address-pair').hide();
            $('#first-add-address-field').removeClass('d-none').find('button').prop('disabled',false);
        }

        $('#submit-btn').on('click',function (e) {
            e.preventDefault();

            let form = $('#add');
            form.find('.invalid-feedback').text('');
            form.find('.is-invalid').removeClass('is-invalid');

            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('firefighters.register.submit') }}",form.serialize()).then((response)=>{
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