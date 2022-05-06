@extends('layouts.app',['title'=>$title,'sidebar'=>'partials.settings-sidebar'])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Invite Personnel</h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Users > Invite Personnel</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-right">
                <!-- @include('partials.back-button') -->
                <a href="{{ route('firefighter.setting.index') }}" class="btn bg-white text-secondary">
                    <span class="material-icons text-secondary mr-2">keyboard_backspace</span>
                    Back
                </a>
            </div>
        </div>
        <form id="add">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"> Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label class="required">DFSID NO#</label>
                                    </th>
                                    <td>
                                        <input type="number" name="prefix_id" class="form-control" onKeyPress="if(this.value.length==6) return false;">
                                        <div id="prefix_id" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th width="170">
                                            <label class="required">Name Suffix</label>
                                        </th>
                                        <td>
                                            <select class="form-control" name="name_suffix">
                                                <option selected disabled>Choose an option</option>
                                                <option value="Jr">Jr</option>
                                                <option value="Sr">Sr</option>
                                                <option value="III">III</option>
                                                <option value="IV">IV</option>
                                                <option value="V">V</option>
                                            </select>
                                            <div id="name_suffix" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label class="required">First Name</label>
                                    </th>
                                    <td>
                                        <input type="text" name="f_name" class="form-control">
                                        <div id="f_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label>Middle Name</label>
                                    </th>
                                    <td>
                                        <input type="text" name="m_name" class="form-control">
                                        <div id="m_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label class="required">Last Name</label>
                                    </th>
                                    <td>
                                        <input type="text" name="l_name" class="form-control">
                                        <div id="l_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label>Business Name</label>
                                    </th>
                                    <td>
                                        <input type="text" name="business_name" class="form-control">
                                        <div id="business_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label class="required">Email</label>
                                    </th>
                                    <td>
                                        <input type="email" name="email" class="form-control">
                                        <div id="email" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label class="required">Phone No#</label>
                                    </th>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                            </div>
                                            <input type="text" maxlength="12" class="form-control d-inline-block numeric-only" name="phone_no">
                                        </div>
                                        <div id="phone_no" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th width="170">
                                            <label class="required">Date of Birth</label>
                                        </th>
                                        <td>
                                            <input type="date" class="form-control dob" max="<?php echo date('Y-m-d'); ?>"  value="<?php echo date('Y-m-d'); ?>" name="dob">
                                            <div id="dob" class="invalid-feedback"></div>
                                            <input type="hidden" class="age" name="age">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th width="170">
                                            <label class="required">SSN</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control numeric-only" maxlength="11" name="ssn">
                                            <div id="ssn" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label class="required">Gender</label>
                                    </th>
                                    <td>
                                        <select name="gender" class="form-control" title="Choose an option">
                                            <option value="" selected disabled>Choose an option</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="transgender">Transgender</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div id="gender" class="invalid-feedback d-block"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label>Race</label>
                                    </th>
                                    <td>
                                        <select class="form-control" name="race">
                                            <option value="" selected disabled>Choose an option</option>
                                            <option value="american indian or alaskan native">American Indian or Alaskan Native</option>
                                            <option value="asian or pacific islander">Asian or Pacific Islander</option>
                                            <option value="black, not of hispanic origin">Black, not of Hispanic origin</option>
                                            <option value="white, not of hispanic origin">White, not of Hispanic origin</option>
                                            <option value="hispanic">Hispanic</option>
                                        </select>
                                        <div id="race" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th width="170">
                                            <label>Career FDID No#</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control numeric-only" maxlength="11" name="cfdid_no">
                                            <div id="cfdid_no" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label>Volunteer FDID No#</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control numeric-only" maxlength="11" name="vfdid_no">
                                        <div id="vfdid_no" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th width="170">
                                            <label>Career FD Name</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="cfd_name">
                                            <div id="cfd_name" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label>Volunteer FD Name</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="vfd_name">
                                        <div id="vfd_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th width="170">
                                            <label>Career FD County</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="cfd_county">
                                            <div id="cfd_county" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label>Volunteer FD County</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="vfd_county">
                                        <div id="vfd_county" class="invalid-feedback"></div>
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
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                <tbody>
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
                                            <input type="text" class="form-control alphanumeric-only zipcode" name="zipcode" id="zipcodeTest" maxlength="5">
                                            <input type="hidden" name="muni">
                                            <div id="zipcode" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>County</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control" name="county">
                                            <div id="county" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                <tbody>
                                    <tr>
                                        <th width="155">
                                            <label class="required">Home Phone</label>
                                        </th>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text" maxlength="10" class="form-control numeric-only" name="home_phone">
                                            </div>
                                            <div id="home_phone" class="invalid-feedback"></div>
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
                                </tbody>
                            </table>
                        </div>

                        {{-- <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label>Address 2</label>
                                        </th>
                                        <td>
                                            <input type="text" id="google-autocomplete-places-2" class="form-control" name="address_2">
                                            <div id="address_2" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>City</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control alpha-only" name="city_2">
                                            <div id="city_2" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>State</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control alpha-only" name="state_2">
                                            <div id="state_2" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Zip code</label>
                                        </th>
                                        <td>
                                            <input type="text" class="form-control alphanumeric-only zipcode" name="zipcode_2" id="zipcodeTest" maxlength="5">
                                            <div id="zipcode_2" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"> Privileges</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="120">
                                        <label class="required">Role</label>
                                    </th>
                                    <td>
                                        <select type="text" name="role" class="form-control">
                                            <option value="" disabled selected>Choose an option</option>
                                            <option value="DCA-Firefighters">DCA-Firefighters</option>
                                        </select>
                                        <div id="role" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label>Role Manager</label>
                                    </th>
                                    <td>
                                            <input class="form-check-input" type="checkbox" name="role_manager" id="role_manager" value="yes" style="transform: scale(1.5);">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Send Invite</button>
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('constant.google_places_api') }}&libraries=places" defer></script>
    <script src="{{ asset('js/initMap.js') }}" defer></script>
    <script defer>
        document.addEventListener("DOMContentLoaded", ()=>{
            initMap('google-autocomplete-places','[name=zipcode]','[name=city]','[name=state]');
            initMap('google-autocomplete-places-2','[name=zipcode_2]','[name=city_2]','[name=state_2]');
        });
    </script>
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
            axios.post("{{ route('firefighter.setting.store-invite-firefighter') }}",$(this).serialize()).then((response)=>{
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

        // getting muni code using zipcode on keyup OR using address //
        $('input[name=address_1]').on('change', function ()
        {
            $('input[name=muni]').val('');
            setTimeout(function ()
            {
                $.ajax({
                    url: "{{ url('/firefighter/get_municode/') }}",
                    type:"GET",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "zipcode": $('input[name=zipcode]').val()
                    },
                    success:function(response){
                        var json = $.parseJSON(response);
                        if(json != null)
                        {
                            $('input[name=muni]').val(json.name);
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'info',
                                title: 'Please enter correct zipcode.'
                            });
                            $('input[name=muni]').val('');
                        }
                    },
                });
            },
            1000);
        });

        $('input[name=zipcode]').on('keyup', function ()
        {
            setTimeout(function ()
            {
                $.ajax({
                    url: "{{ url('/firefighter/get_municode/') }}",
                    type:"GET",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "zipcode": $('input[name=zipcode]').val()
                    },
                    success:function(response){
                        var json = $.parseJSON(response);
                        if(json != null)
                        {
                            $('input[name=muni]').val(json.name);
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'info',
                                title: 'Please enter correct zipcode.'
                            });
                            $('input[name=muni]').val('');
                        }
                    },
                });
            },
            3000);
        });

        // getting age using DOB //
        $('.dob').on('change', function () {
            dob = new Date($(this).val());
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            $('.age').val(age);
            if(age == 0)
            {
                Toast.fire({
                        icon: 'info',
                        title: 'Please enter correct D.O.B'
                    });
                $('.age').val('');
            }
        });
    </script>
@endpush
