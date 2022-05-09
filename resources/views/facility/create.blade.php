@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <style id="switch-temporary">.temporary-mode{display:none}.permanent-mode{display:table-row}</style>
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add Facility</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Facilities > Add Facility</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-right">
                <a href="{{ route('facility.index') }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
            </div>
        </div>
        <form id="add">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Facility Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="210">
                                        <label>Category</label>
                                    </th>
                                    <td>
                                        <div class="form-check d-inline-block mr-2">
                                            <input class="form-check-input" type="radio" name="category" id="category-permanent" value="permanent" checked>
                                            <label class="form-check-label" for="category-permanent">Permanent</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="category" id="category-temporary" value="temporary">
                                            <label class="form-check-label" for="category-temporary">Temporary</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
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
                                <tr>
                                    <th>
                                        <label class="">Live Burn Permit</label>
                                    </th>
                                    <td>
                                        <div class="form-check d-inline-block mr-2">
                                            <input class="form-check-input" type="radio" name="live_burn_permit" id="live-burn-permit-yes" value="yes">
                                            <label class="form-check-label" for="live-burn-permit-yes">Yes</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="live_burn_permit" id="live-burn-permit-no" value="no" checked>
                                            <label class="form-check-label" for="live-burn-permit-no">No</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="show_lapse_date d-none">
                                    <th>
                                        <label class="required">Lapse Date</label>
                                    </th>
                                    <td>
                                        <input type="date" name="lapse_date" class="form-control">
                                        <div id="lapse_date" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Organization</label>
                                    </th>
                                    <td>
                                        {{-- <select name="organization" class="form-control organizations-select2" data-live-search="true"></select> --}}
                                        <select name="organization" class="form-control organization-select2" data-live-search="true">
                                            <option value=""></option>
                                            @foreach ($organizations as $organization)
                                                <option value="{{ $organization->id }}">{{ $organization->name.' ('. $organization->prefix_id .')' }}</option>
                                            @endforeach
                                        </select>
                                        <div id="organization" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th>
                                        <label class="required">Select Tier</label>
                                    </th>
                                    <td>
                                        <select name="tier" class="form-control">
                                                <option selected disabled>Choose an option</option>
                                                <option value="Tier 1">Tier 1</option>
                                                <option value="Tier 2">Tier 2</option>
                                                <option value="Tier 3">Tier 3</option>
                                        </select>
                                        <div id="tier" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Facility type</label>
                                    </th>
                                    <td>
                                        <select name="type[]" class="form-control types-select2" data-live-search="true" title="Choose an option" multiple>
                                            <option value=""></option>
                                            @foreach ($facility_types as $facility_type)
                                                <option value="{{ $facility_type->id }}">{{ $facility_type->description }}</option>
                                            @endforeach
                                        </select>
                                        <div id="type" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Status</label>
                                    </th>
                                    <td>
                                        <select name="status" class="form-control">
                                            <option value="">Choose an option</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div id="status" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Vacancy Status</label>
                                    </th>
                                    <td>
                                        <div class="form-check d-inline-block mr-2">
                                            <input class="form-check-input" type="radio" name="vacancy_status" id="vacancy-available" value="available" checked>
                                            <label class="form-check-label" for="vacancy-available">Available</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="vacancy_status" id="vacancy-unavailable" value="unavailable">
                                            <label class="form-check-label" for="vacancy-unavailable">Unavailable</label>
                                        </div>
                                        <div id="vacancy_status" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Start Date</label>
                                    </th>
                                    <td>
                                        <input type="date" name="start_date" class="form-control">
                                        <div id="start_date" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">End Date</label>
                                    </th>
                                    <td>
                                        <input type="date" name="end_date" class="form-control">
                                        <div id="end_date" class="invalid-feedback"></div>
                                    </td>
                                </tr>
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
                                    <th width="180">
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
                                    <th width="180">
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
                            <table class="table table-borderless w-100">
                                <tr class="permanent-mode">
                                    <th width="180">
                                        <label>Owner's Name</label>
                                    </th>
                                    <td>
                                        <input type="text" name="owner_name" class="form-control">
                                        <div id="owner_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Owner's Address</label>
                                    </th>
                                    <td>
                                        <input id="google-autocomplete-places-3" type="text" name="owner_address" class="form-control">
                                        <div id="owner_address" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <input type="text" name="owner_city" class="form-control">
                                        <div id="owner_city" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <input type="text" name="owner_state" class="form-control">
                                        <div id="owner_state" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Zipcode</label>
                                    </th>
                                    <td>
                                        <input type="text" name="owner_zipcode" class="form-control" maxlength="5">
                                        <div id="owner_zipcode" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Authorized Signator</label>
                                    </th>
                                    <td>
                                        <input type="text" name="signator" class="form-control">
                                        <div id="signator" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Signator's No.</label>
                                    </th>
                                    <td>
                                        <input type="text" name="signator_phone" class="form-control">
                                        <div id="signator_phone" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr class="permanent-mode">
                                    <th width="210">
                                        <label>Contact Person</label>
                                    </th>
                                    <td>
                                        <input type="text" name="contact_person_name" class="form-control alpha-with-space">
                                        <div id="contact_person_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Contact Person Phone</label>
                                    </th>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                            </div>
                                            <input type="text" name="contact_person_phone" maxlength="10" class="form-control numeric-only">
                                        </div>
                                        <div id="contact_person_phone" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Facility Representative</label>
                                    </th>
                                    <td>
                                        <input type="text" name="representative_name" class="form-control">
                                        <div id="representative_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Facility Rep. No</label>
                                    </th>
                                    <td>
                                        <input type="text" name="representative_phone" class="form-control">
                                        <div id="representative_phone" class="invalid-feedback"></div>
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button id="submit-btn" type="button" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Create</button>
                <a href="<?php echo route('facility.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection


@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('constant.google_places_api') }}&libraries=places" defer></script>
    <script src="{{ asset('js/initMap.js') }}" defer></script>
    <script defer>
        document.addEventListener("DOMContentLoaded", ()=>{
            initMap('google-autocomplete-places','[name=mail_zipcode]',null,'[name=mail_state]');
            initMap('google-autocomplete-places-2','[name=physical_zipcode]',null,'[name=physical_state]');
            initMap('google-autocomplete-places-3','[name=owner_zipcode]','[name=owner_city]','[name=owner_state]');
        });
    </script>

    <script type="text/javascript">

        function formReset(){
            document.getElementById("add").reset();
            $(".selectpicker").selectpicker("refresh");
            $(".types-select2").empty();
        }

        $('#submit-btn').on('click',function (e) {
            e.preventDefault();

            let form = $('#add');
            form.find('.invalid-feedback').text('');
            form.find('.is-invalid').removeClass('is-invalid');

            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('facility.store') }}",form.serialize()).then((response)=>{
                // console.log(response)
                if(response.data.status){
                    formReset();
                    setTimeout(function(){location.reload()}, 2500);
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

        $(".organization-select2").select2({ placeholder: "Select Oragnization" });
        $(".types-select2").select2({ placeholder: "Select Facility Types" });
        // $(document).find(".organizations-select2").select2({
        //     minimumInputLength: 2,
        //     placeholder: 'Search Organization',
        //     ajax: {
        //         url: '{{ route('facility.search-organization') }}',
        //         dataType: 'json',
        //         type: "GET",
        //         quietMillis: 50,
        //         data: function (search) {
        //             return {
        //                 search: search.term
        //             };
        //         },
        //         processResults: function (organizations) {
        //             return {
        //                 results: $.map(organizations, function (organization) {
        //                     return {
        //                         text: organization.name+' '+`(${organization.prefix_id})`,
        //                         id: organization.id
        //                     }
        //                 })
        //             };
        //         }
        //     }
        // });

        $('[name=category]').on('change',function () {
            if($(this).val().toLowerCase() === 'permanent'){
                $('#switch-temporary').html('.temporary-mode{display:none}.permanent-mode{display:table-row}')
            }else{
                $('#switch-temporary').html('.temporary-mode{display:table-row}.permanent-mode{display:none}')
            }
        });

        $('[name=live_burn_permit]').on('change',function () {
            if($(this).val().toLowerCase() === 'yes'){
                $(".show_lapse_date").removeClass("d-none");
                $("input[name=lapse_date]").val("");
            }else{
                $(".show_lapse_date").addClass("d-none");
                $("input[name=lapse_date]").val("");
            }
        });

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


        // $(document).find(".types-select2").select2({
        //     minimumInputLength: 2,
        //     placeholder: 'Search Facility Types',
        //     ajax: {
        //         url: '{{ route('facility.search-facility-type') }}',
        //         dataType: 'json',
        //         type: "GET",
        //         quietMillis: 50,
        //         data: function (search) {
        //             return {
        //                 search: search.term
        //             };
        //         },
        //         processResults: function (facility_types) {
        //             return {
        //                 results: $.map(facility_types, function (facility_type) {
        //                     return {
        //                         text: facility_type.description,
        //                         id: facility_type.id
        //                     }
        //                 })
        //             };
        //         }
        //     }
        // });
    </script>
@endpush