@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    @if($facility->category === 'permanent')
        <style id="switch-temporary">.temporary-mode{display:none}.permanent-mode{display:table-row}</style>
    @else
        <style id="switch-temporary">.temporary-mode{display:table-row}.permanent-mode{display:none}</style>
    @endif
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>View Facility</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Facilities > View Facility</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Facility ID:'=>$facility->prefix_id]])
            </div>
            <div class="col-md-6 text-right">
                @can('facilities.update')
                <button class="btn btn-secondary archive {{ $facility->is_archive ? 'd-none' : '' }}" data-archive="{{ $facility->id }}"><span class="material-icons">archive</span> Archive</button>
                <button class="btn btn-secondary unarchive {{ $facility->is_archive ? '' : 'd-none' }}" data-archive="{{ $facility->id }}"><span class="material-icons">unarchive</span> Unarchive</button>
                @endcan
                @can('facilities.delete')
                    <button data-delete="{{ $facility->id }}" class="btn btn-danger delete" title="Delete"><span class="material-icons">delete_outline</span> Delete</button>
                @endcan
                <a href="{{ route('facility.index') }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
            </div>
        </div>
        @can('facilities.update')
        <div class="text-right mt-3 mb-3">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="toggle_edit" name="toggle_edit">
                <label class="custom-control-label" for="toggle_edit">Edit</label>
            </div>
        </div>
        @endcan
        <form id="add">
            @csrf
            @method('put')
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
                                        <div class="show-field text-capitalize">{{ $facility->category }}</div>
                                        <div class="edit-field d-none">
                                            <div class="form-check d-inline-block mr-2">
                                                <input class="form-check-input" type="radio" name="category" id="category-permanent" value="permanent" {{ $facility->category == 'permanent' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="category-permanent">Permanent</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio" name="category" id="category-temporary" value="temporary" {{ $facility->category == 'temporary' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="category-temporary">Temporary</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Country/Municipal Code</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->country_municipal_code }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="country_municipal_code" class="form-control" value="{{ $facility->country_municipal_code }}">
                                            <div id="country_municipal_code" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Name</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="name" class="form-control" value="{{ $facility->name }}">
                                            <div id="name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Organization</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ ($organization) ? array_values($organization)[0] : '' }}</div>
                                        <div class="edit-field d-none">
                                            @php $key = array_key_first($organization); @endphp
                                            <select name="organization" class="form-control organizations-select2" data-live-search="true">
                                                {{-- @if($organization)
                                                    <option value="{{ $key }}">{{ $organization[$key] }}</option>
                                                @endif --}}
                                                @foreach ($all_organizations as $all_organization)
                                                    <option value="{{ $all_organization->id }}"  {{ !empty($organization) && $key == $all_organization->id ? 'selected' : '' }}>{{ $all_organization->name.' ('. $all_organization->prefix_id .')' }}</option>
                                                @endforeach
                                            </select>
                                            <div id="organization" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="210">
                                        <label>Live Burn Permit</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $facility->live_burn_permit }}</div>
                                        <div class="edit-field d-none">
                                            <div class="form-check d-inline-block mr-2">
                                                <input class="form-check-input" type="radio" name="live_burn_permit" id="live-burn-permit-yes" value="yes" {{ $facility->live_burn_permit == 'yes' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="live-burn-permit-yes">Yes</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio" name="live_burn_permit" id="live-burn-permit-no" value="no" {{ $facility->live_burn_permit == 'no' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="live-burn-permit-no">No</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="show_lapse_date {{ !$facility->lapse_date ? 'd-none' : '' }} ">
                                    <th>
                                        <label class="required">Lapse Date</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ date('d-M-Y', strtotime($facility->lapse_date)) }}</div>
                                        <div class="edit-field d-none">
                                            <input type="date" name="lapse_date" class="form-control" value="{{ $facility->lapse_date }}">
                                            <div id="lapse_date" class="invalid-feedback"></div>
                                        </div>
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
                                        <div class="show-field">{{ $facility->tier }}</div>
                                        <div class="edit-field d-none">
                                            <select name="tier" class="form-control">
                                                    <option selected disabled>Choose an option</option>
                                                    <option {{ $facility->tier == "Tier 1" ? 'selected' : '' }} value="Tier 1">Tier 1</option>
                                                    <option {{ $facility->tier == "Tier 2" ? 'selected' : '' }} value="Tier 2">Tier 2</option>
                                                    <option {{ $facility->tier == "Tier 3" ? 'selected' : '' }} value="Tier 3">Tier 3</option>
                                            </select>
                                            <div id="tier" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Type</label>
                                    </th>
                                    <td class="selectpicker-custom-style">
                                        <div class="show-field text-capitalize">{{ implode(', ',$types) }}</div>
                                        <div class="edit-field d-none">
                                            <select name="type[]" multiple class="form-control types-select2" title="Choose an option">
                                                @if(!empty($facility_types) && !empty($types)) 
                                                    @foreach ($facility_types as $id => $key) 
                                                        <option value="{{ $id }}" @foreach ($types as $index) @if ($key == $index) selected @endif @endforeach >{{ $key }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div id="type" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Status</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $facility->status }}</div>
                                        <div class="edit-field d-none">
                                            <select name="status" class="form-control">
                                                <option value="">Choose an option</option>
                                                <option value="yes" {{ $facility->status == 'yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="no" {{ $facility->status == 'no' ? 'selected' : '' }}>No</option>
                                            </select>
                                            <div id="status" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Vacancy Status</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $facility->status }}</div>
                                        <div class="edit-field d-none">
                                            <div class="form-check d-inline-block mr-2">
                                                <input class="form-check-input" type="radio" name="vacancy_status" id="vacancy-available" value="available" {{ $facility->vacancy_status == 'available' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="vacancy-available">Available</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio" name="vacancy_status" id="vacancy-unavailable" value="unavailable" {{ $facility->vacancy_status == 'unavailable' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="vacancy-unavailable">Unavailable</label>
                                            </div>
                                            <div id="vacancy_status" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Start Date</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ \App\Http\Helpers\Helper::date_format($facility->start_date) }}</div>
                                        <div class="edit-field d-none">
                                            <input type="date" name="start_date" class="form-control" value="{{ $facility->start_date }}">
                                            <div id="start_date" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">End Date</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ \App\Http\Helpers\Helper::date_format($facility->end_date) }}</div>
                                        <div class="edit-field d-none">
                                            <input type="date" name="end_date" class="form-control" value="{{ $facility->end_date }}">
                                            <div id="end_date" class="invalid-feedback"></div>
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
                                        <div class="show-field">{{ $facility->mail_address }}</div>
                                        <div class="edit-field d-none">
                                            <input id="google-autocomplete-places" type="text" name="mail_address" class="form-control" value="{{ $facility->mail_address }}">
                                            <div id="mail_address" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="show-field">
                                            <label>Municipality</label>
                                        </div>
                                        <div class="edit-field d-none">
                                            <label data-toggle="tooltip" data-placement="right" title="Municipality will be auto-fetched when zip code is entered">Municipality <span class="material-icons">help</span></label>
                                        </div>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->mail_municipality }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="mail_municipality" class="form-control" value="{{ $facility->mail_municipality }}" readonly>
                                            <div id="mail_municipality" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->mail_state }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="mail_state" class="form-control" value="{{ $facility->mail_state }}">
                                            <div id="mail_state" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zipcode</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->mail_zipcode }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="mail_zipcode" class="form-control" value="{{ $facility->mail_zipcode }}" maxlength="5">
                                            <div id="mail_zipcode" class="invalid-feedback"></div>
                                        </div>
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
                                        <div class="show-field">{{ $facility->physical_address }}</div>
                                        <div class="edit-field d-none">
                                            <input id="google-autocomplete-places-2" type="text" name="physical_address" class="form-control" value="{{ $facility->physical_address }}">
                                            <div id="physical_address" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="show-field">
                                            <label>Municipality</label>
                                        </div>
                                        <div class="edit-field d-none">
                                            <label data-toggle="tooltip" data-placement="right" title="Municipality will be auto-fetched when zip code is entered">Municipality <span class="material-icons">help</span></label>
                                        </div>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->physical_municipality }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="physical_municipality" class="form-control" value="{{ $facility->physical_municipality }}" readonly>
                                            <div id="physical_municipality" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->physical_state }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="physical_state" class="form-control" value="{{ $facility->physical_state }}">
                                            <div id="physical_state" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zipcode</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->physical_zipcode }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="physical_zipcode" class="form-control" value="{{ $facility->physical_zipcode }}" maxlength="5">
                                            <div id="physical_zipcode" class="invalid-feedback"></div>
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
                                        <div class="show-field">{{ $facility->owner_name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="owner_name" class="form-control" value="{{ $facility->owner_name }}">
                                            <div id="owner_name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Owner's Address</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->owner_address }}</div>
                                        <div class="edit-field d-none">
                                            <input id="google-autocomplete-places-3" type="text" name="owner_address" class="form-control" value="{{ $facility->owner_address }}">
                                            <div id="owner_address" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->owner_city }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="owner_city" class="form-control" value="{{ $facility->owner_city }}">
                                            <div id="owner_city" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->owner_state }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="owner_state" class="form-control" value="{{ $facility->owner_state }}">
                                            <div id="owner_state" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Zipcode</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->owner_zipcode }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="owner_zipcode" class="form-control" value="{{ $facility->owner_zipcode }}" maxlength="5">
                                            <div id="owner_zipcode" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th width="180">
                                        <label class="required">Authorized Signator</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->signator }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="signator" class="form-control" value="{{ $facility->signator }}">
                                            <div id="signator" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="temporary-mode">
                                    <th>
                                        <label class="required">Signator's No.</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->signator_phone }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="signator_phone" class="form-control" value="{{ $facility->signator_phone }}">
                                            <div id="signator_phone" class="invalid-feedback"></div>
                                        </div>
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
                                        <div class="show-field">{{ $facility->contact_person_name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="contact_person_name" class="form-control alpha-with-space" value="{{ $facility->contact_person_name }}">
                                            <div id="contact_person_name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Contact Person Phone</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ \App\Http\Helpers\Helper::format_phone_number($facility->contact_person_phone) }}</div>
                                        <div class="edit-field d-none">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text" name="contact_person_phone" maxlength="10" class="form-control numeric-only" value="{{ \App\Http\Helpers\Helper::separate_phone_code($facility->contact_person_phone) }}">
                                            </div>
                                            <div id="contact_person_phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Facility Representative</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->representative_name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="representative_name" class="form-control" value="{{ $facility->representative_name }}">
                                            <div id="representative_name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="permanent-mode">
                                    <th>
                                        <label>Facility Rep. No</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $facility->representative_phone }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="representative_phone" class="form-control" value="{{ $facility->representative_phone }}">
                                            <div id="representative_phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Comments:</label>
                                    </th>
                                    <td>
                                        <div class="show-field">
                                            {{ $facility->comment }}
                                        </div>
                                        <div class="edit-field d-none">
                                            <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $facility->comment }}</textarea>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('facilities.update')
            <div class="edit-field d-none text-center">
                <button id="submit-btn" type="button" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('facility.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
            @endcan
        </form>
    </div>
@endsection


@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    @can('facilities.update')
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
    @can('facilities.delete')
        <div id="delete-modal" tabindex="1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true"
             class="modal fade">
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

        $('#submit-btn').on('click',function (e) {
            e.preventDefault();

            let form = $('#add');
            form.find('.invalid-feedback').text('');
            form.find('.is-invalid').removeClass('is-invalid');

            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('facility.update',$facility->id) }}",form.serialize()).then((response)=>{
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
        
        $(".organizations-select2").select2({ placeholder: "Select Oragnization" });
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

        $(".types-select2").select2({ placeholder: "Select Facility Types" });

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

            axios.post("{{ route('facility.archive-create') }}",$(this).serialize()).then((response)=>{
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

            axios.post("{{ route('facility.unarchive') }}",$(this).serialize()).then((response)=>{
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
        });

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

            axios.delete("{{ route('facility.index') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    setTimeout(function () {
                        window.location.href = '{{  route('facility.index') }}';
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