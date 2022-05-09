@extends('layouts.app',['title'=>$title])
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add Personnel</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Personnel Information > Add Personnel</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 text-right">
                <a href="{{ route('firefighter.index') }}" class="btn bg-white text-secondary">
                    <span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back
                </a>
            </div>
        </div>
        <form id="add" autocomplete="off">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="160">
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
                                        <label class="required">Date of Birth</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control dob" max="<?php echo date('Y-m-d'); ?>"  value="<?php echo date('Y-m-d'); ?>" name="dob">
                                        <div id="dob" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Age</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control age" name="age" readonly>
                                        <div id="age" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
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
                                <tr>
                                    <th>
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
                                <tr>
                                    <th>
                                        <label>Appointed</label>
                                    </th>
                                    <td>
                                        <div class="form-check d-inline-block mr-2">
                                            <input class="form-check-input" type="radio" name="appointed" id="appointed-yes" value="1" checked>
                                            <label class="form-check-label" for="appointed-yes">Yes</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="appointed" id="appointed-no" value="0">
                                            <label class="form-check-label" for="appointed-no">No</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Type</label>
                                    </th>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="type[]" id="fire-inspector" value="fire inspector">
                                            <label class="custom-control-label" for="fire-inspector">Fire Inspector</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="type[]" id="fire-official" value="fire official">
                                            <label class="custom-control-label" for="fire-official">Fire Official</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="type[]" id="fire-instructor" value="fire instructor">
                                            <label class="custom-control-label" for="fire-instructor">Fire Instructor</label>
                                        </div>
                                        <div id="type" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr id="instructor-level-row" class="d-none">
                                    <th>
                                        <label>Instructor Level</label>
                                    </th>
                                    <td>
                                        <select class="form-control" name="instructor_level">
                                            <option value="" selected disabled>Any</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                        <div id="instructor_level" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-5">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="100">
                                        <label class="required">SSN</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control numeric-only" maxlength="11" name="ssn">
                                        <div id="ssn" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>UCC</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="ucc">
                                        <div id="ucc" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>NICET</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="nicet">
                                        <div id="nicet" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>FEMA</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="fema">
                                        <div id="fema" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label data-toggle="tooltip" data-placement="right" title="Municipality will be auto-fetched when zip code is entered">MUNI <span class="material-icons">help</span> </label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="muni" readonly>
                                        <i class="fas fa-fill"></i>
                                        <div id="muni" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">VOL</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="vol">
                                        <div id="vol" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">CAR</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="car">
                                        <div id="car" class="invalid-feedback"></div>
                                    </td>
                                </tr>
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
                                    <th width="160"><label>Allow Postal Email</label></th>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="postal_mail" id="postal-mail" value="1">
                                            <label class="custom-control-label" for="postal-mail"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Primary Address Title</label>
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
                                        <input type="text" class="form-control alphanumeric-only zipcode" name="zipcode" id="zipcodeTest" maxlength="5">
                                        <div id="zipcode" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td  id="first-add-address-field" class="text-right">
                                        <button type="button" class="btn btn-sm btn-primary add-address-field"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table id="second-address-pair" class="table table-borderless w-100 mb-0 d-none">
                                <tbody>
                                <tr>
                                    <th width="160"><label>Allow Postal Email</label></th>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="postal_mail_2" id="postal_mail_2" value="1">
                                            <label class="custom-control-label" for="postal_mail_2"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Secondary Address # 01 Title</label></th>
                                    <td>
                                        <input type="text" class="form-control" name="address_title_2">
                                        <div id="address_title_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label><label>Address</label></label>
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
                                        <input type="text" class="form-control" name="city_2">
                                        <div id="city_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="state_2">
                                        <div id="state_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zip code</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="zipcode_2" maxlength="5">
                                        <div id="zipcode_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-primary m-1 add-address-field"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                        <button type="button" class="btn btn-sm btn-primary m-1 remove-display" data-display="#second-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table id="third-address-pair" class="table table-borderless w-100 mb-0 d-none">
                                <tbody>
                                <tr>
                                    <th width="160"><label>Allow Postal Email</label></th>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="postal_mail_3" id="postal_mail_3" value="1">
                                            <label class="custom-control-label" for="postal_mail_3"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Secondary Address # 02 Title</label></th>
                                    <td>
                                        <input type="text" class="form-control" name="address_title_3">
                                        <div id="address_title_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Address</label>
                                    </th>
                                    <td>
                                        <input type="text" id="google-autocomplete-places-3" class="form-control" name="address_3">
                                        <div id="address_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="city_3">
                                        <div id="city_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="state_3">
                                        <div id="state_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zip code</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="zipcode_3" maxlength="5">
                                        <div id="zipcode_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-primary m-1 add-address-field"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                        <button type="button" class="btn btn-sm btn-primary remove-display" data-display="#third-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
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
                                        <label>Home Phone</label>
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
                                <tr>
                                    <th><label class="required">Primary Email</label></th>
                                    <td>
                                        <input type="email" class="form-control" name="email">
                                        <div id="email" class="invalid-feedback"></div>
                                        <div id="first-add-email-field" class="text-right mt-2">
                                            <button type="button" class="btn btn-sm btn-primary add-email-field" data-original-title="" title="">
                                                <span class="material-icons text-white" style="font-size: initial !important;" data-original-title="" title="">
                                                    add
                                                </span>
                                                Add
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table id="second-email-pair" class="table table-borderless w-100 mb-0 d-none">
                                <tbody>
                                    <tr>
                                        <th width="185"><label>Secondary Email # 01</label></th>
                                        <td>
                                            <input type="email" class="form-control" name="email_2">
                                            <div id="email_2" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-email-field">
                                                    <span class="material-icons text-white" style="font-size: initial !important;">
                                                        add
                                                    </span>
                                                    Add
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary m-1 remove-display" data-display="#second-email-pair">
                                                    <span class="material-icons text-white" style="font-size: initial !important;">
                                                        remove
                                                    </span>
                                                    Remove
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="third-email-pair" class="table table-borderless w-100 mb-0 d-none">
                                <tbody>
                                    <tr>
                                        <th width="185"><label>Secondary Email # 02</label></th>
                                        <td>
                                            <input type="email" class="form-control" name="email_3">
                                            <div id="email_3" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-email-field">
                                                    <span class="material-icons text-white" style="font-size: initial !important;">
                                                        add
                                                    </span>
                                                    Add
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary remove-display" data-display="#third-email-pair">
                                                    <span class="material-icons text-white" style="font-size: initial !important;">
                                                        remove
                                                    </span>
                                                    Remove
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-borderless w-100 mb-0">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label>Comments</label>
                                        </th>
                                        <td>
                                            <textarea name="comment" class="form-control" rows="5" style="resize: none;"></textarea>
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





                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="120">
                                        <label class="required">Application Key</label>
                                    </th>
                                    <td>
                                    <input type="number" class="form-control" name="appkey"required>
                                            <div id="appkey" class="invalid-feedback"></div>
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
                                        <input class="form-check-input role_manager" type="checkbox" name="role_manager" id="role_manager" value="" style="transform: scale(1.5);">
                                        <input type="hidden" name="role_manager_value" class="role_manager_value" value="no">
                                    </td>

                                   





                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button id="submit-btn" type="button" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Create</button>
                <a href="<?php echo route('firefighter.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBdMRhtV4Hf2h6E4EcQPdz6ab_762kliGA&libraries=places" defer></script>
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
            axios.post("{{ route('firefighter.store') }}",form.serialize()).then((response)=>{
                if(response.data.status){
                    formReset();
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    setTimeout(() => {
                        window.location.href = "{{ route('firefighter.index') }}";
                    }, 3000);
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

        $('.add-address-field').on('click',function () {
            let second_pair = $('#second-address-pair'), third_pair = $('#third-address-pair');
            if(second_pair.hasClass('d-none')){
                second_pair.removeClass('d-none');
            }else if(third_pair.hasClass('d-none')){
                third_pair.removeClass('d-none');
            }

            if(!second_pair.hasClass('d-none') && !third_pair.hasClass('d-none')){
                $('.add-address-field').prop('disabled',true);
            }

            $('#first-add-address-field').addClass('d-none');
        });

        $('.remove-display').on('click',function () {
            $($(this).data('display')).addClass('d-none');
            $($(this).data('display')).find('input').val('').removeClass('is-invalid');
            $($(this).data('display')).find('.invalid-feedback').html('');
            $('.add-address-field').prop('disabled',false);
            if($('#second-address-pair').hasClass('d-none') && $('#third-address-pair').hasClass('d-none')){
                $('#first-add-address-field').removeClass('d-none')
            }
        });

        $('[name*=type]').on('change',function () {
            if($(this).val() === 'fire instructor'){
                if($(this).is(':checked')){
                    $('#instructor-level-row').removeClass('d-none');
                }else{
                    $('#instructor-level-row').addClass('d-none');
                }
            }
        })

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

        $('.add-email-field').on('click',function () {
            let second_pair = $('#second-email-pair'), third_pair = $('#third-email-pair');
            if(second_pair.hasClass('d-none')){
                second_pair.removeClass('d-none');
            }else if(third_pair.hasClass('d-none')){
                third_pair.removeClass('d-none');
            }

            if(!second_pair.hasClass('d-none') && !third_pair.hasClass('d-none')){
                $('.add-email-field').prop('disabled',true);
            }

            $('#first-add-email-field').addClass('d-none');
        });

        $('.remove-display').on('click',function () {
            $($(this).data('display')).addClass('d-none');
            $($(this).data('display')).find('input').val('').removeClass('is-invalid');
            $($(this).data('display')).find('.invalid-feedback').html('');
            $('.add-email-field').prop('disabled',false);
            if($('#second-email-pair').hasClass('d-none') && $('#third-email-pair').hasClass('d-none')){
                $('#first-add-email-field').removeClass('d-none')
            }
        });

        $('input[name=address]').on('change', function ()
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
                                title: 'We didn\'t find Muni Code.'
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
                                title: 'We didn\'t find Muni Code.'
                            });
                            $('input[name=muni]').val('');
                        }
                    },
                });
            },
            3000);
        });

        $('.role_manager').on('change', function() {
            if($(this).prop("checked"))
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
                        $(".role_manager_value").val('yes');
                        $(this).prop("checked");
                    }
                    else
                    {
                        $(".role_manager_value").val('no');
                        $(this).prop('checked',false);
                    }
                });
            }
            else
            {
                $(".role_manager_value").val('no');
                $(this).prop('checked',false);
            }
        });
    </script>
@endpush
