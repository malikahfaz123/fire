@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>View Class</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Training Details > Courses > View Course > View Classes > View Class Details</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Course ID:'=>$course->prefix_id,'Class Sequence No:'=>$class->id]])
                @if($last_updated)
                    @include('partials.meta-box',['icon'=>'schedule','labels'=>['Last updated on:'=>\App\Http\Helpers\Helper::date_format($last_updated->created_at),'Last updated by:'=>ucwords($last_updated->user->name)],'bg_class'=>'bg-gradient-dark'])
                @endif
            </div>
            <div class="col-md-6 text-right">
                <div class="pb-1">
                    @include('partials.history-button')
                    @include('partials.back-button')
                </div>
                @can('courses.create')
                    @if(date('Y-m-d') >= $class->start_date)
                        <a href="{{ route('class.attendance',[$semester_id,$course->id,$class->id]) }}" class="btn btn-primary btn-wd"><span class="material-icons">schedule</span> Add Attendance</a>
                    @else
                        <button class="btn btn-primary btn-wd" disabled=""><span class="material-icons">schedule</span> Add Attendance</button>
                    @endif
                @endcan
                @can('courses.update')
                <button class="btn btn-secondary archive {{ $class->is_archive ? 'd-none' : '' }}" data-archive="{{ $class->id }}"><span class="material-icons">archive</span> Archive</button>
                <button class="btn btn-secondary unarchive {{ $class->is_archive ? '' : 'd-none' }}" data-archive="{{ $class->id }}"><span class="material-icons">unarchive</span> Unarchive</button>
                @endcan
                @can('courses.delete')
                    <button data-delete="{{ $class->id }}" class="btn btn-danger delete" title="Delete"><span class="material-icons">delete_outline</span> Delete</button>
                @endcan
            </div>
        </div>
        @can('courses.update')
            <div class="text-right mt-3 mb-3">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="toggle_edit" {{ !$class_alterable ? 'disabled' : ''  }} name="toggle_edit">
                    <label class="custom-control-label" for="toggle_edit">Edit</label>
                </div>
            </div>
        @endcan
        <form id="add">
            @csrf
            @method('put')
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
                                        <div class="show-field" title="{{ $class->organization_type ==='EO' ? 'Eligible Organization' : 'Fire Department' }}">{{ $class->organization_type }}</div>
                                        <div class="edit-field d-none">
                                            <div class="form-check d-inline-block mr-2">
                                                <input class="form-check-input" type="radio" name="organization_type" id="organization_type-eo" value="EO" {{ $class->organization_type === 'EO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="organization_type-eo" title="Eligible Organization">EO</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio" name="organization_type" id="organization_type-fd" value="FD" {{ $class->organization_type === 'FD' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="organization_type-fd" title="Fire Department">FD</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="organization-tr">
                                    <th>
                                        <label class="required">Organization</label>
                                    </th>
                                    <td>
                                        <div class="show-field">
                                            @if(!empty($organization))
                                                {{ $organization->name }} ({{ $organization->prefix_id }})
                                            @endif
                                        </div>
                                        <div class="edit-field d-none">
                                            <select name="organization" class="form-control form-control-sm organization-select2" data-live-search="true">
                                                @if(!empty($all_organizations))
                                                    <option value=""></option>
                                                    @foreach ($all_organizations as $all_organization)
                                                        <option value="{{ $all_organization->id }}"  {{ !empty($organization) && $organization->id == $all_organization->id ? 'selected' : '' }}>{{ $all_organization->name.' ('. $all_organization->prefix_id .')' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div id="organization" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="firedepartment-tr">
                                    <th>
                                        <label class="required">Fire Dept.</label>
                                    </th>
                                    <td>
                                        <div class="show-field">
                                            @if(!empty($fire_department))
                                                {{ $fire_department->name }} ({{ $fire_department->prefix_id }})
                                            @endif
                                            </div>
                                        <div class="edit-field d-none">
                                            <select name="firedepartment" class="form-control firedepartment-select2" data-live-search="true">
                                                @if(!empty($all_fire_departments))
                                                    <option value=""></option>
                                                    @foreach ($all_fire_departments as $all_fire_department)
                                                        <option value="{{ $all_fire_department->id }}"  {{ !empty($fire_department) && $fire_department->id == $all_fire_department->id ? 'selected' : '' }}>{{ $all_fire_department->name.' ('. $all_fire_department->prefix_id .')' }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div id="firedepartment" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Instructor</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ \App\Http\Helpers\FirefighterHelper::get_full_name($instructor) }} ({{ $instructor->prefix_id }})</div>
                                        <div class="edit-field d-none">
                                            <select name="instructor" class="form-control instructor-select2" data-live-search="true">
                                                <option value=""></option>
                                                @foreach ($instructors_lists as $instructors_list)
                                                    <option value="{{ $instructors_list->id }}"  {{ !empty($instructor) && $instructors_list->id == $instructor->id ? 'selected' : '' }}>{{ $instructors_list->f_name.' '.$instructors_list->l_name.' ('. $instructors_list->prefix_id .')' }}</option>
                                                @endforeach
                                            </select>
                                            <div id="instructor" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Facility</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->name }} ({{ $facility->prefix_id }})</div>
                                        <div class="edit-field d-none">
                                            <select name="facility" class="form-control facility-select2" data-live-search="true">
                                                <option value=""></option>
                                                @foreach ($facilities_lists as $facilities_list)
                                                    <option value="{{ $facilities_list->id }}"  {{ !empty($facility) && $facilities_list->id == $facility->id ? 'selected' : '' }}>{{ $facilities_list->name.' ('. $facilities_list->prefix_id .')' }}</option>
                                                @endforeach
                                            </select>
                                            <div id="facility" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Admin CEU</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ number_format($class->admin_ceu, 1) }}</div>
                                        <div class="edit-field d-none">
                                            <select class="form-control" name="admin_ceu">
                                                <option disabled {{ $class->admin_ceu == null ? 'selected' : null }}>Choose an option</option>
                                                <option value="1.0" {{ $class->admin_ceu == '1.0' ? 'selected' : null }} >1.0</option>
                                                <option value="2.0" {{ $class->admin_ceu == '2.0' ? 'selected' : null }}>2.0</option>
                                                <option value="3.0" {{ $class->admin_ceu == '3.0' ? 'selected' : null }}>3.0</option>
                                                <option value="4.0" {{ $class->admin_ceu == '4.0' ? 'selected' : null }}>4.0</option>
                                                <option value="5.0" {{ $class->admin_ceu == '5.0' ? 'selected' : null }}>5.0</option>
                                            </select>
                                        </div>
                                        <div id="admin_ceu" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Tech CEU</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ number_format($class->tech_ceu, 1) }}</div>
                                        <div class="edit-field d-none">
                                            <select class="form-control" name="tech_ceu">
                                                <option disabled {{ $class->tech_ceu == null ? 'selected' : null }}>Choose an option</option>
                                                <option value="1.0" {{ $class->tech_ceu == '1.0' ? 'selected' : null }} >1.0</option>
                                                <option value="2.0" {{ $class->tech_ceu == '2.0' ? 'selected' : null }}>2.0</option>
                                                <option value="3.0" {{ $class->tech_ceu == '3.0' ? 'selected' : null }}>3.0</option>
                                                <option value="4.0" {{ $class->tech_ceu == '4.0' ? 'selected' : null }}>4.0</option>
                                                <option value="5.0" {{ $class->tech_ceu == '5.0' ? 'selected' : null }}>5.0</option>
                                            </select>
                                        </div>
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
                                        <div class="show-field">{{ $class->no_of_facility_types }}</div>
                                        <div class="edit-field d-none">
                                            <select name="no_of_facility_types" class="form-control form-control-sm">
                                                <option value="">Choose an option</option>
                                                <option {{ $class->no_of_facility_types == 1 ? 'selected' : '' }} value="1">1</option>
                                                <option {{ $class->no_of_facility_types == 2 ? 'selected' : '' }} value="2">2</option>
                                                <option {{ $class->no_of_facility_types == 3 ? 'selected' : '' }} value="3">3</option>
                                                <option {{ $class->no_of_facility_types == 4 ? 'selected' : '' }} value="4">4</option>
                                                <option {{ $class->no_of_facility_types == 5 ? 'selected' : '' }} value="5">5</option>
                                                <option {{ $class->no_of_facility_types == 6 ? 'selected' : '' }} value="6">6</option>
                                            </select>
                                            <div id="no_of_facility_types" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div id="facility-types-container">
                                <div class="show-field">
                                    <table class="table table-borderless w-100 mb-0">
                                        <tr>
                                            <th width="190"><label>Facility Types</label></th>
                                            <td class="text-capitalize">{!! implode(',<br>',$facility_types) !!}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="edit-field d-none">
                                    <table class="table table-borderless w-100 mb-0">
                                        @if($facility_types)
                                            @php $prefix = ['1st','2nd','3rd','4th','5th','6th']; $count = 0; @endphp
                                            <table class="table table-borderless w-100 mb-0">
                                                @foreach($facility_types as $key=>$facility_type)
                                                    <tr>
                                                        <th width="190" class="text-right">
                                                            @php
                                                                echo $prefix[$count];
                                                                $count++;
                                                            @endphp
                                                        </th>
                                                        <td>
                                                            <select name="facility_types[]" class="form-control facility-type-select2" data-live-search="true">
                                                                <option value=""></option>
                                                                @foreach ($all_facility_types as $all_facility_type)
                                                                    <option value="{{ $all_facility_type->id }}" {{ $all_facility_type->id == $key ? 'selected' : '' }}>{{ $all_facility_type->description }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="190">
                                        <label class="required">Date</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ \App\Http\Helpers\Helper::date_format($class->start_date) }}</div>
                                        <div class="edit-field d-none">
                                            <input type="date" name="start_date" class="form-control" value="{{ $class->start_date }}">
                                            <div id="start_date" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label class="required">End Date</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ \App\Http\Helpers\Helper::date_format($class->end_date) }}</div>
                                        <div class="edit-field d-none">
                                            <input type="date" name="end_date" class="form-control" value="{{ $class->end_date }}">
                                            <div id="end_date" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label class="required">Start Time</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ "$class->start_hour:$class->start_minute" }}<span class="text-muted pl-2">(hh:mm)</span></div>
                                        <div class="edit-field d-none">
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
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Duration</label>
                                    </th>
                                    <td>
                                        @php
                                            $duration = (int)($class->end_time) - (int)($class->start_time);
                                        @endphp
                                        <div class="show-field">{{ $duration }}<span class="text-muted pl-2">(hh)</span></div>
                                        <div class="edit-field d-none">
                                            <select class="form-control form-group-sm d-inline-block" name="duration" style="width: 70px;">
                                                @for($i=1; $i<24; $i++)
                                                    <option value="{{ $i<10 ? '0'.$i : $i  }}" {{ $i == $duration ? 'selected' : '' }}>{{ $i<10 ? '0'.$i : $i  }}</option>
                                                @endfor
                                            </select>
                                            <span class="text-muted pl-2">(hh)</span>
                                            <div id="duration" class="invalid-feedback"></div>
                                        </div>
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
                                        <div class="show-field">{{ $class->maximum_students }}</div>
                                        <div class="edit-field d-none">
                                            <input type="number" name="maximum_students" class="form-control" value="{{ $class->maximum_students }}">
                                            <div id="maximum_students" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label class="required">Semester</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ ucfirst($semester->semester).' '."($semester->year)" }}</div>
                                        <div class="edit-field d-none">
                                            <select name="semester" class="form-control form-control-sm semester-select2" data-live-search="true">
                                                <option value="{{ $semester->id }}">{{ ucfirst($semester->semester).' '."($semester->year)" }}</option>
                                            </select>
                                            <div id="semester" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="180">
                                        <label class="required">DFSID</label>
                                    </th>
                                    <td>
                                        <div class="show-field">
                                            {{-- {{ implode(', ',$selected_firefighters) }} --}}
                                            @foreach ($firefighters as $firefighter)
                                                <option value="{{ $firefighter->firefighter_id }}" selected>{{ $firefighter->f_name .' '."($firefighter->prefix_id)" }}</option>
                                            @endforeach
                                        </div>
                                        <div class="edit-field d-none">
                                            {{-- <select name="firefighter[]" class="form-control form-control-sm firefighter-select2" multiple data-live-search="true">
                                                @foreach($selected_firefighters as $key=>$selected_firefighter)
                                                    <option selected value="{{ $key }}">{{ $selected_firefighter }}</option>
                                                @endforeach
                                            </select> --}}
                                            {{-- <select name="firefighter[]" class="form-control firefighter-select2" multiple>
                                                @foreach ($firefighters as $firefighter)
                                                    <option value="{{ $firefighter->firefighter_id }}" selected>{{ $firefighter->f_name .' '."($firefighter->prefix_id)" }}</option>
                                                @endforeach
                                            </select> --}}
                                            <select name="firefighter[]" class="form-control firefighter-select2" multiple disabled>
                                                @foreach ($firefighters as $firefighter)
                                                    <option value="{{ $firefighter->firefighter_id }}" selected>{{ $firefighter->f_name .' '."($firefighter->prefix_id)" }}</option>
                                                @endforeach
                                            </select>

                                            <select name="firefighter[]" class="form-control firefighter-select2-hidden" multiple>
                                                @foreach ($firefighters as $firefighter)
                                                    <option value="{{ $firefighter->firefighter_id }}" selected>{{ $firefighter->f_name .' '."($firefighter->prefix_id)" }}</option>
                                                @endforeach
                                            </select>
                                            <div id="firefighter" class="invalid-feedback"></div>
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('courses.update')
                <div class="edit-field d-none text-center">
                    <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                    <a href="{{ route('class.index',[$semester->id,$course->id]) }}" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
                </div>
            @endcan
        </form>
    </div>
@endsection

@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    @include('partials.message-modal',['id'=>'history-modal','title'=>'History','max_width'=>750])
    @can('courses.update')
    <div id="archive-modal" tabindex="1" role="dialog" aria-labelledby="archive-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="archive-form" novalidate>
                    @csrf
                    <input type="hidden" name="archive">
                    <div class="modal-header"><h5 id="archive-modal-title" class="modal-title cambria-bold">Notice</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="archive-modal-content" class="modal-body">Are you sure you want to archive this record ?</div>
                    <div class="modal-footer">
                        <button type="submit" id="archive-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="unarchive-modal" tabindex="1" role="dialog" aria-labelledby="unarchive-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="unarchive-form" novalidate>
                    @csrf
                    <input type="hidden" name="archive">
                    <div class="modal-header"><h5 id="unarchive-modal-title" class="modal-title cambria-bold">Notice</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="unarchive-modal-content" class="modal-body">Are you sure you want to unarchive this record ?</div>
                    <div class="modal-footer">
                        <button type="submit" id="unarchive-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
    @can('courses.delete')
        <div id="delete-modal" tabindex="1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true" class="modal fade">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <form id="delete-form" novalidate>
                        @csrf
                        @method('delete')
                        <input type="hidden" name="delete">
                        <div class="modal-header"><h5 id="delete-modal-title" class="modal-title cambria-bold">Confirmation Required</h5>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div id="delete-modal-content" class="modal-body">Are you sure you want to delete this record ?</div>
                        <div class="modal-footer">
                            <button type="submit" id="delete-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                            <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('js')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>

    $(document).ready(function () {
        $('select[name=start_hour]').val('{{ $class->start_hour }}');
        $('select[name=start_minute]').val('{{ $class->start_minute }}');
        $('.firefighter-select2').select2();
        $(".firefighter-select2-hidden").hide();
        $(".instructor-select2").select2({ placeholder: "Select Instructor" });
        $(".facility-select2").select2({ placeholder: "Select Facility" });
        $(".organization-select2").select2({ placeholder: "Select Organization" });
        $(".firedepartment-select2").select2({ placeholder: "Select Fire Department" });

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

    // old instructor work
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

    // old facility work
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

    $(".facility-type-select2").select2({ placeholder: "Select Facility Type" });

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
        };

      for (let i=0; i<types; i++){
            if(typeof values[i] !== "undefined" && Object.size(values)){
                option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
            }else{
                var all_facility_types = '@php echo json_encode($all_facility_types); @endphp';
                var all_facility_types = JSON.parse(all_facility_types);
                option = '';
                option +=`<option value=""></option>`;
                for (let i=0; i < all_facility_types.length; i++){
                    option +=`<option value="${all_facility_types[i].id}">${all_facility_types[i].description}</option>`;
                }
            }

            html+=`<tr>
                        <th width="189" class="text-right">${prefixes[i]}</th>
                        <td><select name="facility_types[]" class="form-control facility-type-select2" data-live-search="true">${option}</select></td>
                   </tr>`
        }
        html+= '<tr><th></th><td><div id="facility_types" class="invalid-feedback"></div></td></tr></table>';
        $('#facility-types-container').html(html);
        // initFacilityTypeSearch();
        $(".facility-type-select2").select2({ placeholder: "Select Facility Type" });
    });

    // function initFacilityTypeSearch(){
    //     $(document).find(".facility-type-select2").select2({
    //         minimumInputLength: 2,
    //         placeholder: 'Search Facility Type',
    //         ajax: {
    //             url: '{{ route('class.search-facility-type') }}',
    //             dataType: 'json',
    //             type: "GET",
    //             quietMillis: 50,
    //             data: function (search) {
    //                 return {
    //                     search: search.term
    //                 };
    //             },
    //             processResults: function (facility_types) {
    //                 return {
    //                     results: $.map(facility_types, function (facility_type) {
    //                         return {
    //                             text: facility_type.description,
    //                             id: facility_type.id
    //                         }
    //                     })
    //                 };
    //             }
    //         }
    //     });
    // }
    // initFacilityTypeSearch();
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

    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');
        axios.post("{{ route('class.update',[$class->course_id,$class->id]) }}",$(this).serialize()).then((response)=>{
            if(response.data.status){
                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });
                setTimeout(()=>{
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

    $(document).on('click','.view-history',function () {
        let html = '<h5 class="text-center"><div class="spinner mb-2"></div> Loading...</h5>';
        $('#history-modal-content').html(html);
        $('#history-modal').modal('show');
        $.ajax({
            url: '{{ route('class.history',$class->id) }}',
            dataType: 'html',
            success: function (response) {
                if(!response){
                    response = '<h5 class="text-center">No history was found.</h5>';
                }
                $('#history-modal-content').html(response);
            },
            failure: function () {
                alert('Operation Failed');
            }
        });
    })

    /*============================
               ARCHIVE
   *=============================*/
    $(document).on('click','.archive',function (e) {
        e.preventDefault();
        let id = $(this).data('archive');
        let modal = $('#archive-modal');
        modal.modal('show');
        modal.find('[name=archive]').val(id);
    });

    $('#archive-form').on('submit',function (e) {
        e.preventDefault();
        let submit_btn = $(this).find('[type=submit]');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        axios.post("{{ route('class.archive-create') }}",$(this).serialize()).then((response)=>{
            if(response.data.status){
                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });
                $('.archive').addClass('d-none');
                $('.unarchive').removeClass('d-none');
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: response.data.msg,
                });
            }
            $('#archive-modal').modal('hide');
            submit_btn.prop('disabled', false);
            submit_btn.removeClass('disabled');
        })
    });

    /*============================
            UNARCHIVE
    *=============================*/
    $(document).on('click','.unarchive',function (e) {
        e.preventDefault();
        let id = $(this).data('archive');
        let modal = $('#unarchive-modal');
        modal.modal('show');
        modal.find('[name=archive]').val(id);
    });

    $('#unarchive-form').on('submit',function (e) {
        e.preventDefault();
        let submit_btn = $(this).find('[type=submit]');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        axios.post("{{ route('class.unarchive') }}",$(this).serialize()).then((response)=>{
            if(response.data.status){
                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });
                $('.archive').removeClass('d-none');
                $('.unarchive').addClass('d-none');
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: response.data.msg,
                });
            }
            $('#unarchive-modal').modal('hide');
            submit_btn.prop('disabled', false);
            submit_btn.removeClass('disabled');
        })
    })

    /*============================
            DELETE
    *=============================*/
    $(document).on('click','.delete',function (e) {
        e.preventDefault();
        let id = $(this).data('delete');
        let modal = $('#delete-modal');
        modal.modal('show');
        modal.find('[name=delete]').val(id);
    });
    $('#delete-form').on('submit',function (e) {
        e.preventDefault();
        let submit_btn = $(this).find('[type=submit]');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        axios.delete("{{ \Illuminate\Support\Facades\URL::to('class') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
            if(response.data.status){
                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });
                setTimeout(function () {
                    window.location.href = '{{ route('class.index',[$semester->id,$course->id]) }}';
                },1500)
            }else{
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: response.data.msg,
                });
            }
            $('#delete-modal').modal('hide');
            submit_btn.prop('disabled', false);
            submit_btn.removeClass('disabled');
        })
    });
</script>
@endpush
