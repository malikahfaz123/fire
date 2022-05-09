@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Add Class</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Training Details > Course > View Course > View Classes > Add Class</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Course ID:'=>$course->prefix_id]])
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('course.index') }}" class="btn bg-white text-secondary" ><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
            </div>
        </div>
        <form id="add">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Class Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="160">
                                        <label>Organization Type</label>
                                    </th>
                                    <td>
                                        <div class="form-check d-inline-block mr-2">
                                            <input class="form-check-input" type="radio" name="organization_type" id="organization_type-eo" value="EO" checked>
                                            <label class="form-check-label" for="organization_type-eo" title="Eligible Organization">EO</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="organization_type" id="organization_type-fd" value="FD">
                                            <label class="form-check-label" for="organization_type-fd" title="Fire Department">FD</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="organization-tr">
                                    <th>
                                        <label class="required">Organization</label>
                                    </th>
                                    <td>
                                        <select name="organization" class="form-control organization-select2" data-live-search="true">
                                            <option value=""></option>
                                            @foreach ($organizations as $organization)
                                                <option value="{{ $organization->id }}">{{ $organization->name.' ('. $organization->prefix_id .')' }}</option>
                                            @endforeach
                                        </select>
                                        <div id="organization" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr id="firedepartment-tr">
                                    <th>
                                        <label class="required">Fire Dept.</label>
                                    </th>
                                    <td>
                                        <select name="firedepartment" class="form-control firedepartment-select2" data-live-search="true">
                                            <option value=""></option>
                                            @foreach ($fire_departments as $fire_department)
                                                <option value="{{ $fire_department->id }}">{{ $fire_department->name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="firedepartment" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                {{-- instructor old work --}}
                                {{-- <tr>
                                    <th>
                                        <label class="required">Instructor</label>
                                    </th>
                                    <td>
                                        <select name="instructor" class="form-control instructor-select2" data-live-search="true"></select>
                                        <div id="instructor" class="invalid-feedback"></div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label class="required">Instructor</label>
                                    </th>
                                    <td>
                                   
                                        <select name="instructor" class="form-control instructor-select2" data-live-search="true">
                                            <option value=""></option>
                                            @foreach ($instructors as $instructor)
                                                <option value="{{ $instructor->id }}">{{ $instructor->f_name.' '.$instructor->l_name.' ('. $instructor->prefix_id .')' }}</option>
                                            @endforeach
                                        </select>
                                        <div id="instructor" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label class="required">Facility</label>
                                    </th>
                                    <td>
                                        <select name="facility" class="form-control facility-select2" data-live-search="true"></select>
                                        <div id="facility" class="invalid-feedback"></div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label class="required">Facility</label>
                                    </th>
                                    <td>
                                        <select name="facility" class="form-control facility-select2" data-live-search="true">
                                            <option value=""></option>
                                            @foreach ($facilities as $facilities)
                                                <option value="{{ $facilities->id }}">{{ $facilities->name.' ('. $facilities->prefix_id .')' }}</option>
                                            @endforeach
                                        </select>
                                        <div id="facility" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Admin CEU</label>
                                    </th>
                                    <td>
                                        <select class="form-control" name="admin_ceu">
                                            <option selected disabled>Choose an option</option>
                                            <option value="1.0">1.0</option>
                                            <option value="2.0">2.0</option>
                                            <option value="3.0">3.0</option>
                                            <option value="4.0">4.0</option>
                                            <option value="5.0">5.0</option>
                                        </select>
                                        <div id="admin_ceu" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Tech CEU</label>
                                    </th>
                                    <td>
                                        <select class="form-control" name="tech_ceu">
                                            <option selected disabled>Choose an option</option>
                                            <option value="1.0">1.0</option>
                                            <option value="2.0">2.0</option>
                                            <option value="3.0">3.0</option>
                                            <option value="4.0">4.0</option>
                                            <option value="5.0">5.0</option>
                                        </select>
                                        <div id="tech_ceu" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                <tbody>
                                <tr>
                                    <th width="190">
                                        <label class="required">No. of Facility type</label>
                                    </th>
                                    <td>
                                        <select name="no_of_facility_types" class="form-control">
                                            <option value="">Choose an option</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                        </select>
                                        <div id="no_of_facility_types" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div id="facility-types-container"></div>
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="190">
                                        <label class="required">Date</label>
                                    </th>
                                    <td>
                                        <input type="date" name="start_date" class="form-control">
                                        <div id="start_date" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label class="required">End Date</label>
                                    </th>
                                    <td>
                                        <input type="date" name="end_date" class="form-control">
                                        <div id="end_date" class="invalid-feedback"></div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label class="required">Start Time</label>
                                    </th>
                                    <td>
                                        <select class="form-control form-group-sm d-inline-block" name="start_hour" style="width: 70px;">
                                            @for($i=0; $i<24; $i++)
                                                <option value="{{ $i<10 ? '0'.$i : $i  }}">{{ $i<10 ? '0'.$i : $i  }}</option>
                                            @endfor
                                        </select>
                                        <select class="form-control form-group-sm d-inline-block" name="start_minute" style="width: 70px;">
                                            @for($i=0; $i<60; $i+=5)
                                                <option value="{{ $i<10 ? '0'.$i : $i  }}">{{ $i<10 ? '0'.$i : $i  }}</option>
                                            @endfor
                                        </select>
                                        <span class="text-muted pl-2">(hh:mm)</span>
                                        <div id="start_time" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label class="required">End Time</label>
                                    </th>
                                    <td>
                                        <select class="form-control form-group-sm d-inline-block" name="end_hour" style="width: 70px;">
                                            @for($i=0; $i<24; $i++)
                                                <option value="{{ $i<10 ? '0'.$i : $i  }}">{{ $i<10 ? '0'.$i : $i  }}</option>
                                            @endfor
                                        </select>
                                        <select class="form-control form-group-sm d-inline-block" name="end_minute" style="width: 70px;">
                                            @for($i=0; $i<60; $i+=5)
                                                <option value="{{ $i<10 ? '0'.$i : $i  }}">{{ $i<10 ? '0'.$i : $i  }}</option>
                                            @endfor
                                        </select>
                                        <span class="text-muted pl-2">(hh:mm)</span>
                                        <div id="start_time" class="invalid-feedback"></div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label class="required">Duration</label>
                                    </th>
                                    <td>
                                        <select class="form-control form-group-sm d-inline-block" name="duration" style="width: 70px;">
                                            @for($i=1; $i<24; $i++)
                                                <option value="{{ $i<10 ? '0'.$i : $i  }}">{{ $i<10 ? '0'.$i : $i  }}</option>
                                            @endfor
                                        </select>
                                        <span class="text-muted pl-2">(hh)</span>
                                        <div id="duration" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Enroll Students</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="table table-borderless w-100">
                                {{-- <tr>
                                    <th width="180">
                                        <label class="required" for="maximum_students">Maximum Students</label>
                                    </th>
                                    <td>
                                        <input type="number" name="maximum_students" class="form-control">
                                        <div id="maximum_students" class="invalid-feedback"></div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label class="required">Semester</label>
                                    </th>
                                    <td>
                                        <select name="semester" class="form-control semester-select2" data-live-search="true" disabled>
                                            @if($semester)
                                                <option value="{{ $semester->id }}" selected>{{ ucfirst($semester->semester).' '."($semester->year)" }}</option>
                                            @endif
                                        </select>
                                        <select name="semester" class="form-control semester-select2-hidden" data-live-search="true" >
                                            @if($semester)
                                                <option value="{{ $semester->id }}" selected>{{ ucfirst($semester->semester).' '."($semester->year)" }}</option>
                                            @endif
                                        </select>
                                        <div id="semester" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="180">
                                        <label class="required">DFSID</label>
                                    </th>
                                    <td>
                                        {{-- <select name="firefighter[]" class="form-control firefighter-select2" multiple data-live-search="true"></select> --}}
                                        <select name="firefighter[]" class="form-control firefighter-select2" multiple disabled>
                                            @foreach ($firefighters as $firefighter)
                                                <option value="{{ $firefighter->id }}" selected>{{ $firefighter->f_name .' '."($firefighter->prefix_id)" }}</option>
                                            @endforeach
                                        </select>
                                        <select name="firefighter[]" class="form-control firefighter-select2-hidden" multiple >
                                            @foreach ($firefighters as $firefighter)
                                            <option value="{{ $firefighter->firefighter_id }}" selected>{{ $firefighter->f_name .' '."($firefighter->prefix_id)" }}</option>
                                            @endforeach
                                        </select>
                                        <div id="firefighter" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Create</button>
                <a href="<?php echo route('class.index',[$semester_id,$course->id]) ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('js')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.firefighter-select2').select2();
        $(".firefighter-select2-hidden").hide();
        $(".semester-select2-hidden").hide();
        $(".instructor-select2").select2({ placeholder: "Select Instructor" });
        $(".facility-select2").select2({ placeholder: "Select Facility" });
        $(".organization-select2").select2({ placeholder: "Select Oragnization" });
        $(".firedepartment-select2").select2({ placeholder: "Select Fire Dept." });

        // organization type radio button work
        let organization_type = $('input[name="organization_type"]:checked').val();

        if(organization_type == "EO"){
            $("#firedepartment-tr").hide();
        }

        if(organization_type == "FD"){
            $("#organization-tr").hide();
        }

        $('input[name="organization_type"]').change(function() {
            if (this.value == 'EO') {
                $("#firedepartment-tr").hide();
                $("#organization-tr").show();
            }
            else if (this.value == 'FD') {
                $("#organization-tr").hide();
                $("#firedepartment-tr").show();
            }
        });
    });


    // $(document).find(".firedepartment-select2").select2({
    //     minimumInputLength: 2,
    //     placeholder: 'Search Fire Department',
    //     ajax: {
    //         url: '{{ route('class.search-firedepartment') }}',
    //         dataType: 'json',
    //         type: "GET",
    //         quietMillis: 50,
    //         data: function (search) {
    //             return {
    //                 search: search.term
    //             };
    //         },
    //         processResults: function (firedepartments) {
    //             return {
    //                 results: $.map(firedepartments, function (firedepartment) {
    //                     return {
    //                         text: firedepartment.name+' '+`(${firedepartment.prefix_id})`,
    //                         id: firedepartment.id
    //                     }
    //                 })
    //             };
    //         }
    //     }
    // });


    // $(document).find(".organization-select2").select2({
    //     minimumInputLength: 2,
    //     placeholder: 'Search Organization',
    //     ajax: {
    //         url: '{{ route('class.search-organization') }}',
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

    // instructor old work
    // $(document).find(".instructor-select2").select2({
    //     minimumInputLength: 3,
    //     placeholder: 'Search Instructor',
    //     ajax: {
    //         url: '{{ route('class.search-instructor') }}',
    //         dataType: 'json',
    //         type: "GET",
    //         quietMillis: 50,
    //         data: function (search) {
    //             return {
    //                 search: search.term,
    //                 course_id: '{{ $course->id }}',
    //             };
    //         },
    //         processResults: function (instructors) {
    //             return {
    //                 results: $.map(instructors, function (instructor) {
    //                     return {
    //                         text: instructor.name+' '+`(${instructor.prefix_id})`,
    //                         id: instructor.id
    //                     }
    //                 })
    //             };
    //         }
    //     }
    // });

    // facility old work
    // $(document).find(".facility-select2").select2({
    //     minimumInputLength: 2,
    //     placeholder: 'Search Facility',
    //     ajax: {
    //         url: '{{ route('class.search-facility') }}',
    //         dataType: 'json',
    //         type: "GET",
    //         quietMillis: 50,
    //         data: function (search) {
    //             return {
    //                 search: search.term
    //             };
    //         },
    //         processResults: function (facilities) {
    //             return {
    //                 results: $.map(facilities, function (facility) {
    //                     return {
    //                         text: facility.name+' '+`(${facility.prefix_id})`,
    //                         id: facility.id
    //                     }
    //                 })
    //             };
    //         }
    //     }
    // });

    $('[name=no_of_facility_types]').on('change',function () {

        let types = $(this).val(),records = $(document).find('#facility-types-container [name*=facility_types]'),length = records.length,values = {};
        if(length){
            for (let i=0; i<length; i++){
                values[i] = {
                    [records[i].value] : typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                }
            }
        }

        html = '<table class="table table-borderless w-100 mb-0">';
        prefixes = {
            '0':'1st',
            '1':'2nd',
            '2':'3rd',
            '3':'4th',
            '4':'5th',
            '5':'6th',
        };

        for (let i=0; i<types; i++){
            // if(typeof values[i] !== "undefined" && Object.size(values)){
            //     option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
            // }else{
                var facility_types = '@php echo json_encode($facility_types); @endphp';
                var facility_types = JSON.parse(facility_types);
                option = '';
                option +=`<option value=""></option>`;
                for (let i=0; i < facility_types.length; i++){
                    option +=`<option value="${facility_types[i].id}">${facility_types[i].description}</option>`;
                }
            // }

            html+=`<tr>
                        <th width="189" class="text-right">${prefixes[i]}</th>
                        <td><select name="facility_types[]" class="form-control facility-type-select2" data-live-search="true">${option}</select></td>
                   </tr>`
        }
        html+= '<tr><th></th><td><div id="facility_types" class="invalid-feedback"></div></td></tr></table>';
        $('#facility-types-container').html(html);

        $(".facility-type-select2").select2({ placeholder: "Select Facility Type" });
        // $(document).find(".facility-type-select2").select2({
        //     minimumInputLength: 2,
        //     placeholder: 'Search Facility Type',
        //     ajax: {
        //         url: '{{ route('class.search-facility-type') }}',
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
    });

    // $(document).find(".firefighter-select2").select2({
    //     minimumInputLength: 2,
    //     placeholder: 'Search Firefighter',
    //     ajax: {
    //         url: '{{ route('class.search-firefighter') }}',
    //         dataType: 'json',
    //         type: "GET",
    //         quietMillis: 50,
    //         data: function (search) {
    //             return {
    //                 search: search.term
    //             };
    //         },
    //         processResults: function (firefighters) {
    //             return {
    //                 results: $.map(firefighters, function (firefighter) {
    //                     return {
    //                         text: firefighter.prefix_id,
    //                         id: firefighter.id
    //                     }
    //                 })
    //             };
    //         }
    //     }
    // });

    function formReset(){
        document.getElementById("add").reset();
        // $(".semester-select2").empty();
        // $(".firefighter-select2").empty();
        $(".facility-select2").empty();
        $(".facility-type-select2").empty();
        $(".organization-select2").empty();
        $(".instructor-select2").empty();
        $("#facility-types-container").html('');
    }

    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');
        axios.post("{{ route('class.store',[$semester_id,$course->id]) }}",$(this).serialize()).then((response)=>{
            if(response.data.status){
                // formReset();
                setTimeout(function(){location.reload()}, 2600);
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

    $(document).find(".semester-select2").select2({
        minimumInputLength: 4,
        placeholder: 'Search Semester',
        ajax: {
            url: '{{ route('class.search-semester') }}',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            data: function (search) {
                return {
                    year: search.term
                };
            },
            processResults: function (semesters) {
                return {
                    results: $.map(semesters, function (semester) {
                        return {
                            text: `${semester.semester} (${semester.year})`,
                            id: semester.id
                        }
                    })
                }
            }
        }
    });

</script>
@endpush
