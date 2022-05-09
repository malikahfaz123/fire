@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add Credential</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Credentials > Add Credential</span>
            </div>
        </div>
        <div class="text-right mb-3">
            {{-- @include('partials.back-button') --}}
            <a href="{{ route('certification.index') }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
        </div>
        <form id="add">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Credential Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="180">
                                        <label class="required">Credential Code</label>
                                    </th>
                                    <td>
                                        <input type="text" maxlength="5" class="form-control alphanumeric-only" name="prefix_id">
                                        <div id="prefix_id" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Credential Title</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="title">
                                        <div id="title" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label class="required">Short Title</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="short_title">
                                        <div id="short_title" class="invalid-feedback"></div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label>Renewable</label>
                                    </th>
                                    <td>
                                        <div class="form-check d-inline-block mr-2">
                                            <input class="form-check-input" type="radio" name="renewable" id="renewable-yes" value="1">
                                            <label class="form-check-label" for="renewable-yes">Yes</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="renewable" id="renewable-no" value="0" checked>
                                            <label class="form-check-label" for="renewable-no">No</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="renewal-period-container-1" class="d-none">
                                    <th>
                                        <label for="certification_cycle_start">Cycle Start Date</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control" name="certification_cycle_start" id="certification_cycle_start" style="width: 100%;">
                                        <input type="hidden" name="renewal_period" value="" id="renewal_period">
                                        <div id="renewal_period" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr id="renewal-period-container-2" class="d-none">
                                    <th>
                                        <label for="certification_cycle_start">Cycle End Date</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control" name="certification_cycle_end" id="certification_cycle_end" style="width: 100%;">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0 d-none admin_ceus">
                                <tr>
                                    <th><label class="required">Req. Admin CEU's</label></th>
                                    <td>
                                        <!-- <input type="number" step="0.1" name="admin_ceu" class="form-control"> -->
                                        <select class="form-control" name="admin_ceu">
                                            <option value="" selected disabled>Choose an option</option>
                                            <option value="1.0">1.0</option>
                                            <option value="1.5">1.5</option>
                                            <option value="2.0">2.0</option>
                                        </select>
                                        <div id="admin_ceu" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label class="required">Req. Tech CEU's</label></th>
                                    <td>
                                        <!-- <input type="number" step="0.1" name="tech_ceu" class="form-control"> -->
                                        <select class="form-control" name="tech_ceu">
                                            <option value="" selected disabled>Choose an option</option>
                                            <option value="1.0">1.0</option>
                                            <option value="1.5">1.5</option>
                                            <option value="2.0">2.0</option>
                                        </select>
                                        <div id="tech_ceu" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="170">
                                        <label class="required">No. of Credit type</label>
                                    </th>
                                    <td>
                                        <select name="no_of_credit_types" class="form-control">
                                            <option value="">Choose an option</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                        <div id="no_of_credit_types" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                            <div id="credit-types-container" class="d-none"></div>
                            <table class="table table-borderless w-100 mb-0">
                                <tbody>
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
                    </div>
                </div>
            </div>

{{--            <div class="card">--}}
{{--                <div class="card-header">--}}
{{--                    <h4 class="mb-0">Pre-requisites Information</h4>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <table class="table table-borderless w-100 mb-0">--}}
{{--                                <tr>--}}
{{--                                    <th width="170"><label>No. of Pre-requisite Credential Titles</label></th>--}}
{{--                                    <td>--}}
{{--                                        <select name="no_of_pre_req_cert" class="form-control">--}}
{{--                                            <option value="">Choose an option</option>--}}
{{--                                            <option value="1">1</option>--}}
{{--                                            <option value="2">2</option>--}}
{{--                                            <option value="3">3</option>--}}
{{--                                        </select>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                            <div id="pre-req-cert-container"></div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <table class="table table-borderless w-100 mb-0">--}}
{{--                                <tr>--}}
{{--                                    <th width="170"><label>No. of Pre-requisite Course(s)</label></th>--}}
{{--                                    <td>--}}
{{--                                        <select name="no_of_pre_req_course" class="form-control">--}}
{{--                                            <option value="">Choose an option</option>--}}
{{--                                            <option value="1">1</option>--}}
{{--                                            <option value="2">2</option>--}}
{{--                                            <option value="3">3</option>--}}
{{--                                        </select>--}}
{{--                                        <div id="no_of_pre_req_course" class="invalid-feedback"></div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                            <div id="pre-req-course-container"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Create</button>
                <a href="<?php echo route('certification.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('js')

    <script>
        $(".credit-type-select2").select2({placeholder: "Select Credit Type"});
        $('[name=no_of_credit_types]').on('change', function () {
            $('#credit-types-container').removeClass('d-none');
            let types = $(this).val(), records = $(document).find('#credit-types-container [name*=credit_types]'),
                length = records.length, values = {};
            if (length) {
                for (let i = 0; i < length; i++) {
                    values[i] = {
                        [records[i].value]: typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                    }
                }
            }

            html = '<table class="table table-borderless w-100 mb-0">';
            prefixes = {
                '0': '1st',
                '1': '2nd',
                '2': '3rd',
                '3': '4th',
                '4': '5th',
            };
            for (let i = 0; i < types; i++) {
                var credit_types = '@php echo json_encode($credit_types); @endphp';
                var credit_types = JSON.parse(credit_types);
                option = '';
                option += `<option value=""></option>`;
                for (let i = 0; i < credit_types.length; i++) {
                    option += `<option value="${credit_types[i].id}">${credit_types[i].description} (${credit_types[i].prefix_id})</option>`;
                }
                html += `<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="credit_types[]" class="form-control credit-type-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html += '<tr><th></th><td class="pt-0"><div id="credit_types" class="invalid-feedback"></div></td></tr></table>';
            $('#credit-types-container').html(html);

            $(".credit-type-select2").select2({placeholder: "Select Credit Type"});
        });

        function formReset() {
            document.getElementById("add").reset();
            $('#credit-types-container').html('');
            $('#pre-req-cert-container').html('');
            $('#pre-req-course-container').html('');
        }

        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('certification.store') }}", $(this).serialize()).then((response) => {
                if (response.data.status) {
                    console.log(response.data.data);
                    formReset();
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    setTimeout(function(){
                        window.location = '{{ url("certification") }}/'+response.data.data.id;
                    },2000);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: response.data.msg,
                    });
                }
                submit_btn.prop('disabled', false);
                submit_btn.removeClass('disabled');
            }).catch((error) => {
                if (error.response.status === 422) {
                    submit_btn.prop('disabled', false);
                    submit_btn.removeClass('disabled');
                    Toast.fire({
                        icon: 'info',
                        title: 'Please fill form carefully !'
                    });
                }
            })
        });

        $(".courses-select2").select2({placeholder: "Select Courses"});
        $('[name=no_of_pre_req_course]').on('change', function () {
            let types = $(this).val(), records = $(document).find('#pre-req-course-container [name*=pre_req_course]'),
                length = records.length, values = {};
            if (length) {
                for (let i = 0; i < length; i++) {
                    values[i] = {
                        [records[i].value]: typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                    }
                }
            }

            html = '<table class="table table-borderless w-100 mb-0">';
            prefixes = {
                '0': '1st',
                '1': '2nd',
                '2': '3rd',
            };
            for (let i = 0; i < types; i++) {
                var courses = '@php echo json_encode($courses); @endphp';
                var courses = JSON.parse(courses);
                option = '';
                option += `<option value=""></option>`;
                for (let i = 0; i < courses.length; i++) {
                    option += `<option value="${courses[i].id}">${courses[i].course_name}</option>`;
                }
                html += `<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="pre_req_course[]" class="form-control courses-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html += '<tr><th></th><td class="pt-0"><div id="pre_req_course" class="invalid-feedback"></div></td></tr></table>';
            $('#pre-req-course-container').html(html);

            $(".courses-select2").select2({placeholder: "Select Courses"});
        });

        $(".certification-select2").select2({placeholder: "Select Credential"});
        $('[name=no_of_pre_req_cert]').on('change', function () {
            let types = $(this).val(), records = $(document).find('#pre-req-cert-container [name*=pre_req_cert]'),
                length = records.length, values = {};
            if (length) {
                for (let i = 0; i < length; i++) {
                    values[i] = {
                        [records[i].value]: typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                    }
                }
            }

            html = '<table class="table table-borderless w-100 mb-0">';
            prefixes = {
                '0': '1st',
                '1': '2nd',
                '2': '3rd',
            };
            for (let i = 0; i < types; i++) {
                var certifications = '@php echo json_encode($certifications); @endphp';
                var certifications = JSON.parse(certifications);
                option = '';
                option += `<option value=""></option>`;
                for (let i = 0; i < certifications.length; i++) {
                    option += `<option value="${certifications[i].id}">${certifications[i].title} (${certifications[i].prefix_id})</option>`;
                }
                html += `<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="pre_req_cert[]" class="form-control certification-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html += '<tr><th></th><td class="pt-0"><div id="pre_req_cert" class="invalid-feedback"></div></td></tr></table>';
            $('#pre-req-cert-container').html(html);

            $(".certification-select2").select2({placeholder: "Select Credential"});
        });

    </script>

@endpush
