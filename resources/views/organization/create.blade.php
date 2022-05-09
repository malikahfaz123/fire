@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add Eligible Organization</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Eligible Organizations > Add Eligible Organization</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <form id="add">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Eligible Organization Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="210">
                                        <label class="required">Country/Municipal Code</label>
                                    </th>
                                    <td>
                                        <input type="text" name="country_municipal_code" class="form-control">
                                        <div id="country_municipal_code" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Name</label>
                                    </th>
                                    <td>
                                        <input type="text" name="name" class="form-control">
                                        <div id="name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr id="other-container" class="d-none">
                                    <th>
                                        <label>Other</label>
                                    </th>
                                    <td>
                                        <input type="text" name="other_type" class="form-control">
                                        <div id="other_type" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th>
                                        <label class="required">Type</label>
                                    </th>
                                    <td class="selectpicker-custom-style">
                                        <select name="type" class="form-control selectpicker">
                                            <option value="">Choose an option</option>
                                            <option value="fire department">Fire Department</option>
                                            <option value="government">Government</option>
                                            <option value="voc-tech">Voc-Tech</option>
                                            <option value="higher education">Higher Education</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div id="type" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="210">
                                        <label>Phone No.</label>
                                    </th>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                            </div>
                                            <input type="text" maxlength="10" class="form-control numeric-only" name="phone">
                                        </div>
                                        <div id="phone" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label>Fax No.</label>
                                    </th>
                                    <td>
                                        <input type="text" name="fax" class="form-control">
                                        <div id="fax" class="invalid-feedback"></div>
                                    </td>
                                </tr> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Address Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="210">
                                        <label>Mailing Address</label>
                                    </th>
                                    <td>
                                        <input id="google-autocomplete-places" type="text" name="mail_address" class="form-control">
                                        <div id="mail_address" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label data-toggle="tooltip" data-placement="right" title="Municipality will be auto-fetched when zip code is entered">Municipality <span class="material-icons">help</span></label>
                                    </th>
                                    <td>
                                        <input type="text" name="mail_municipality" class="form-control" readonly>
                                        <div id="mail_municipality" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <input type="text" name="mail_state" class="form-control">
                                        <div id="mail_state" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zipcode</label>
                                    </th>
                                    <td>
                                        <input type="text" name="mail_zipcode" class="form-control" maxlength="5">
                                        <div id="mail_zipcode" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="210">
                                        <label>Physical Address</label>
                                    </th>
                                    <td>
                                        <input id="google-autocomplete-places-2" type="text" name="physical_address" class="form-control">
                                        <div id="physical_address" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label data-toggle="tooltip" data-placement="right" title="Municipality will be auto-fetched when zip code is entered">Municipality <span class="material-icons">help</span></label>
                                    </th>
                                    <td>
                                        <input type="text" name="physical_municipality" class="form-control" readonly>
                                        <div id="physical_municipality" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <input type="text" name="physical_state" class="form-control">
                                        <div id="physical_state" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zipcode</label>
                                    </th>
                                    <td>
                                        <input type="text" name="physical_zipcode" class="form-control" maxlength="5">
                                        <div id="physical_zipcode" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Related Personnel</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><b>Fire Chief Director</b></h6>
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label class="required">Name</label>
                                        </th>
                                        <td>
                                            <input type="text" name="chief_dir_name" class="form-control">
                                            <div id="chief_dir_name" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="required">Primary Email</label>
                                        </th>
                                        <td>
                                            <input type="email" name="chief_dir_email" class="form-control">
                                            <div id="chief_dir_email" class="invalid-feedback"></div>
                                            <div id="first-add-address-field" class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary add-address-field"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="second-address-pair" class="table table-borderless w-100 mb-0 d-none">
                                <tbody>
                                    <tr>
                                        <th width="210"><label>Secondary Email # 01</label></th>
                                        <td>
                                            <input type="email" class="form-control" name="chief_dir_email_2">
                                            <div id="chief_dir_emai_2" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-address-field"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                <button type="button" class="btn btn-sm btn-primary m-1 remove-display" data-display="#second-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="third-address-pair" class="table table-borderless w-100 mb-0 d-none">
                                <tbody>
                                    <tr>
                                        <th width="210"><label>Secondary Email # 02</label></th>
                                        <td>
                                            <input type="text" class="form-control" name="chief_dir_email_3">
                                            <div id="chief_dir_email_3" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-address-field"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                <button type="button" class="btn btn-sm btn-primary remove-display" data-display="#third-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label class="required">Phone No.</label>
                                        </th>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text" maxlength="10" class="form-control numeric-only" name="chief_dir_phone">
                                            </div>
                                            <div id="chief_dir_phone" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Comments:</label>
                                        </th>
                                        <td>
                                            <textarea name="comment" class="form-control" rows="5" style="resize: none;"></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6><b>Authorized Signator</b></h6>
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label class="required">Name</label>
                                        </th>
                                        <td>
                                            <input type="text" name="auth_sign_name" class="form-control">
                                            <div id="auth_sign_name" class="invalid-feedback"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="required">Primary Email</label>
                                        </th>
                                        <td>
                                            <input type="email" name="auth_sign_email" class="form-control">
                                            <div id="auth_sign_email" class="invalid-feedback"></div>
                                            <div id="first-add-address-field_1" class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary add-address-field_1"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="second-address-pair_1" class="table table-borderless w-100 mb-0 d-none">
                                <tbody>
                                    <tr>
                                        <th width="210"><label>Secondary Email # 01</label></th>
                                        <td>
                                            <input type="email" class="form-control" name="auth_sign_email_2">
                                            <div id="auth_sign_email_2" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-address-field_1"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                <button type="button" class="btn btn-sm btn-primary m-1 remove-display_1" data-display="#second-address-pair_1"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="third-address-pair_1" class="table table-borderless w-100 mb-0 d-none">
                                <tbody>
                                    <tr>
                                        <th width="210"><label>Secondary Email # 02</label></th>
                                        <td>
                                            <input type="text" class="form-control" name="auth_sign_email_3">
                                            <div id="auth_sign_email_3" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-address-field_1"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                <button type="button" class="btn btn-sm btn-primary remove-display_1" data-display="#third-address-pair_1"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label class="required">Phone No.</label>
                                        </th>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text" maxlength="10" class="form-control numeric-only" name="auth_sign_phone">
                                            </div>
                                            <div id="auth_sign_phone" class="invalid-feedback"></div>
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
                <a href="<?php echo route('organization.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('constant.google_places_api') }}&libraries=places" defer></script>
    <script src="{{ asset('js/initMap.js') }}" defer></script>
    <script defer>
        document.addEventListener("DOMContentLoaded", ()=>{
            initMap('google-autocomplete-places','[name=mail_zipcode]',null,'[name=mail_state]');
            initMap('google-autocomplete-places-2','[name=physical_zipcode]',null,'[name=physical_state]');
        });
    </script>

    <script type="text/javascript">

        function formReset(){
            document.getElementById("add").reset();
            $(".selectpicker").selectpicker("refresh");
        }

        $('#submit-btn').on('click',function (e) {
            e.preventDefault();

            let form = $('#add');
            form.find('.invalid-feedback').text('');
            form.find('.is-invalid').removeClass('is-invalid');

            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('organization.store') }}",form.serialize()).then((response)=>{
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

        $('select[name=type]').on('change',function () {
            if($(this).val().toLowerCase() === 'other'){
                $('#other-container').removeClass('d-none');
            }else{
                $('#other-container').addClass('d-none');
            }
        })

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
        })

        $('.add-address-field_1').on('click',function () {
            let second_pair = $('#second-address-pair_1'), third_pair = $('#third-address-pair_1');
            if(second_pair.hasClass('d-none')){
                second_pair.removeClass('d-none');
            }else if(third_pair.hasClass('d-none')){
                third_pair.removeClass('d-none');
            }

            if(!second_pair.hasClass('d-none') && !third_pair.hasClass('d-none')){
                $('.add-address-field_1').prop('disabled',true);
            }

            $('#first-add-address-field_1').addClass('d-none');
        });

        $('.remove-display_1').on('click',function () {
            $($(this).data('display')).addClass('d-none');
            $($(this).data('display')).find('input').val('').removeClass('is-invalid');
            $($(this).data('display')).find('.invalid-feedback').html('');
            $('.add-address-field_1').prop('disabled',false);
            if($('#second-address-pair_1').hasClass('d-none') && $('#third-address-pair_1').hasClass('d-none')){
                $('#first-add-address-field_1').removeClass('d-none')
            }
        })

        $('input[name=mail_address]').on('change', function ()
        {
            $('input[name=mail_municipality]').val('');
            setTimeout(function ()
            { 
                $.ajax({
                    url: "{{ url('/firefighter/get_municode/') }}",
                    type:"GET",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "zipcode": $('input[name=mail_zipcode]').val()
                    },
                    success:function(response){
                        var json = $.parseJSON(response);
                        if(json != null)
                        {
                            $('input[name=mail_municipality]').val(json.name);
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'info',
                                title: 'We didn\'t find Muni Code.'
                            });
                            $('input[name=mail_municipality]').val('');
                        }
                    },
                });
            }, 
            1000);
        });

        $('input[name=mail_zipcode]').on('keyup', function ()
        {
            setTimeout(function ()
            {
                $.ajax({
                    url: "{{ url('/firefighter/get_municode/') }}",
                    type:"GET",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "zipcode": $('input[name=mail_zipcode]').val()
                    },
                    success:function(response){
                        var json = $.parseJSON(response);
                        if(json != null)
                        {
                            $('input[name=mail_municipality]').val(json.name);
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'info',
                                title: 'We didn\'t find Muni Code.'
                            });
                            $('input[name=mail_municipality]').val('');
                        }
                    },
                });
            }, 
            3000);
        });

        $('input[name=physical_address]').on('change', function ()
        {
            $('input[name=physical_municipality]').val('');
            setTimeout(function ()
            { 
                $.ajax({
                    url: "{{ url('/firefighter/get_municode/') }}",
                    type:"GET",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "zipcode": $('input[name=physical_zipcode]').val()
                    },
                    success:function(response){
                        var json = $.parseJSON(response);
                        if(json != null)
                        {
                            $('input[name=physical_municipality]').val(json.name);
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'info',
                                title: 'We didn\'t find Muni Code.'
                            });
                            $('input[name=physical_municipality]').val('');
                        }
                    },
                });
            }, 
            1000);
        });

        $('input[name=physical_zipcode]').on('keyup', function ()
        {
            setTimeout(function ()
            {
                $.ajax({
                    url: "{{ url('/firefighter/get_municode/') }}",
                    type:"GET",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "zipcode": $('input[name=physical_zipcode]').val()
                    },
                    success:function(response){
                        var json = $.parseJSON(response);
                        if(json != null)
                        {
                            $('input[name=physical_municipality]').val(json.name);
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'info',
                                title: 'We didn\'t find Muni Code.'
                            });
                            $('input[name=physical_municipality]').val('');
                        }
                    },
                });
            }, 
            3000);
        });
    </script>
@endpush