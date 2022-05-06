@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>View Eligible Organization</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Eligible Organizations > View Eligible Organization</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['EO ID:'=>$organization->prefix_id]])
            </div>
            <div class="col-md-6 text-right">
                @can('organizations.update')
                    <button class="btn btn-secondary archive {{ $organization->is_archive ? 'd-none' : '' }}" data-archive="{{ $organization->id }}"><span class="material-icons">archive</span> Archive</button>
                    <button class="btn btn-secondary unarchive {{ $organization->is_archive ? '' : 'd-none' }}" data-archive="{{ $organization->id }}"><span class="material-icons">unarchive</span> Unarchive</button>
                @endcan
                @can('organizations.delete')
                    <button data-delete="{{ $organization->id }}" class="btn btn-danger delete" title="Delete"><span class="material-icons">delete_outline</span> Delete</button>
                @endcan
                <a href="{{ route('organization.index') }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
            </div>
        </div>
        @can('organizations.update')
        <div class="text-right mb-3 mt-3">
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
                                        <div class="show-field text-capitalize">{{ $organization->country_municipal_code }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="country_municipal_code" class="form-control" value="{{ $organization->country_municipal_code }}">
                                            <div id="country_municipal_code" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Name</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $organization->name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="name" class="form-control" value="{{ $organization->name }}">
                                            <div id="name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="other-container" class="{{ $organization->type == 'other' ? '' : 'd-none' }}">
                                    <th>
                                        <label>Other</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $organization->other_type }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="other_type" class="form-control" value="{{ $organization->other_type }}">
                                            <div id="other_type" class="invalid-feedback"></div>
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
                                        <label class="required">Type</label>
                                    </th>
                                    <td class="selectpicker-custom-style">
                                        <div class="show-field text-capitalize">{{ $organization->type }}</div>
                                        <div class="edit-field d-none">
                                            <select name="type" class="form-control selectpicker">
                                                <option {{ $organization->type == 'other' ? 'selected' : '' }} value="">Choose an option</option>
                                                <option {{ $organization->type == 'fire department' ? 'selected' : '' }} value="fire department">Fire Department</option>
                                                <option {{ $organization->type == 'government' ? 'selected' : '' }} value="government">Government</option>
                                                <option {{ $organization->type == 'voc-tech' ? 'selected' : '' }} value="voc-tech">Voc-Tech</option>
                                                <option {{ $organization->type == 'higher education' ? 'selected' : '' }} value="higher education">Higher Education</option>
                                                <option {{ $organization->type == 'other' ? 'selected' : '' }} value="other">Other</option>
                                            </select>
                                            <div id="type" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="210">
                                        <label>Phone No.</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ \App\Http\Helpers\Helper::format_phone_number($organization->phone) }}</div>
                                        <div class="edit-field d-none">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text" name="phone" maxlength="10" class="form-control numeric-only" value="{{ \App\Http\Helpers\Helper::separate_phone_code($organization->phone) }}">
                                            </div>
                                            <div id="phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label>Fax No.</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $organization->fax }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="fax" class="form-control" value="{{ $organization->fax }}">
                                            <div id="fax" class="invalid-feedback"></div>
                                        </div>
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
                                        <div class="show-field text-capitalize">{{ $organization->mail_address }}</div>
                                        <div class="edit-field d-none">
                                            <input id="google-autocomplete-places" type="text" name="mail_address" class="form-control" value="{{ $organization->mail_address }}">
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
                                        <div class="show-field text-capitalize">{{ $organization->mail_municipality }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="mail_municipality" class="form-control" value="{{ $organization->mail_municipality }}" readonly>
                                            <div id="mail_municipality" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $organization->mail_state }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="mail_state" class="form-control" value="{{ $organization->mail_state }}">
                                            <div id="mail_state" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zipcode</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $organization->mail_zipcode }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="mail_zipcode" class="form-control" value="{{ $organization->mail_zipcode }}" maxlength="5">
                                            <div id="mail_zipcode" class="invalid-feedback"></div>
                                        </div>
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
                                        <div class="show-field text-capitalize">{{ $organization->physical_address }}</div>
                                        <div class="edit-field d-none">
                                            <input id="google-autocomplete-places-2" type="text" name="physical_address" class="form-control" value="{{ $organization->physical_address }}">
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
                                        <div class="show-field text-capitalize">{{ $organization->physical_municipality }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="physical_municipality" class="form-control" value="{{ $organization->physical_municipality }}" readonly>
                                            <div id="physical_municipality" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $organization->physical_state }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="physical_state" class="form-control" value="{{ $organization->physical_state }}">
                                            <div id="physical_state" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zipcode</label>
                                    </th>
                                    <td>
                                        <div class="show-field text-capitalize">{{ $organization->physical_zipcode }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="physical_zipcode" class="form-control" value="{{ $organization->physical_zipcode }}" maxlength="5">
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
                            <h6><b>Fire Chief Director</b></h6>
                            <table class="table table-borderless w-100">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label class="required">Name</label>
                                        </th>
                                        <td>
                                            <div class="show-field text-capitalize">
                                                {{ $organization->chief_dir_name }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <input type="text" name="chief_dir_name" class="form-control" value="{{ $organization->chief_dir_name }}">
                                                <div id="chief_dir_name" class="invalid-feedback"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="required">Primary Email</label>
                                        </th>
                                        <td>
                                            <div class="show-field">
                                                {{ $organization->chief_dir_email }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <input type="email" name="chief_dir_email" class="form-control" value="{{ $organization->chief_dir_email }}">
                                                <div id="chief_dir_email" class="invalid-feedback"></div>
                                                <div id="first-add-address-field" class="text-right mt-2 {{ $organization->chief_dir_email_2 || $organization->chief_dir_email_3 ? 'd-none' : '' }}">
                                                    <button type="button" class="btn btn-sm btn-primary add-address-field"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="second-address-pair" class="table table-borderless w-100 mb-0 {{ !$organization->chief_dir_email_2 ? 'd-none' : '' }}">
                                <tbody>
                                    <tr>
                                        <th width="210"><label>Secondary Email # 01</label></th>
                                        <td>
                                            <div class="show-field">
                                                {{ $organization->chief_dir_email_2 }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <input type="email" class="form-control" name="chief_dir_email_2" value="{{ $organization->chief_dir_email_2 }}">
                                                <div id="chief_dir_emai_2" class="invalid-feedback"></div>
                                                <div class="text-right mt-2">
                                                    <button type="button" class="btn btn-sm btn-primary m-1 add-address-field" {{ $organization->chief_dir_email_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                    <button type="button" class="btn btn-sm btn-primary m-1 remove-display" data-display="#second-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="third-address-pair" class="table table-borderless w-100 mb-0 {{ !$organization->chief_dir_email_3 ? 'd-none' : '' }}">
                                <tbody>
                                    <tr>
                                        <th width="210"><label>Secondary Email # 02</label></th>
                                        <td>
                                            <div class="show-field">
                                                {{ $organization->chief_dir_email_3 }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <input type="text" class="form-control" name="chief_dir_email_3" value="{{ $organization->chief_dir_email_3 }}">
                                                <div id="chief_dir_email_3" class="invalid-feedback"></div>
                                                <div class="text-right mt-2">
                                                    <button type="button" class="btn btn-sm btn-primary m-1 add-address-field" {{ $organization->chief_dir_email_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                    <button type="button" class="btn btn-sm btn-primary remove-display" data-display="#third-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                                </div>
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
                                            <div class="show-field">
                                                {{ $organization->chief_dir_phone }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                    </div>
                                                    <input type="text" maxlength="10" class="form-control numeric-only" name="chief_dir_phone" value="{{ $organization->chief_dir_phone }}">
                                                </div>
                                                <div id="chief_dir_phone" class="invalid-feedback"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Comments:</label>
                                        </th>
                                        <td>
                                            <div class="show-field">
                                                {{ $organization->comment }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $organization->comment }}</textarea>
                                            </div>
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
                                            <div class="show-field text-capitalize">
                                                {{ $organization->auth_sign_name }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <input type="text" name="auth_sign_name" class="form-control" value="{{ $organization->auth_sign_name }}">
                                                <div id="auth_sign_name" class="invalid-feedback"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label class="required">Primary Email</label>
                                        </th>
                                        <td>
                                            <div class="show-field">
                                                {{ $organization->auth_sign_email }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <input type="email" name="auth_sign_email" class="form-control" value="{{ $organization->auth_sign_email }}">
                                                <div id="auth_sign_email" class="invalid-feedback"></div>
                                                <div id="first-add-address-field_1" class="text-right mt-2 {{ $organization->auth_sign_email_2 || $organization->auth_sign_email_3 ? 'd-none' : '' }}">
                                                    <button type="button" class="btn btn-sm btn-primary add-address-field_1"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="second-address-pair_1" class="table table-borderless w-100 mb-0 {{ !$organization->auth_sign_email_2 ? 'd-none' : '' }}">
                                <tbody>
                                    <tr>
                                        <th width="210"><label>Secondary Email # 01</label></th>
                                        <td>
                                            <div class="show-field">
                                                {{ $organization->auth_sign_email }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <input type="email" class="form-control" name="auth_sign_email_2" value="{{ $organization->auth_sign_email_2 }}">
                                                <div id="auth_sign_email_2" class="invalid-feedback"></div>
                                                <div class="text-right mt-2">
                                                    <button type="button" class="btn btn-sm btn-primary m-1 add-address-field_1" {{ $organization->auth_sign_email_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                    <button type="button" class="btn btn-sm btn-primary m-1 remove-display_1" data-display="#second-address-pair_1"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="third-address-pair_1" class="table table-borderless w-100 mb-0 {{ !$organization->auth_sign_email_3 ? 'd-none' : '' }}">
                                <tbody>
                                    <tr>
                                        <th width="210"><label>Secondary Email # 02</label></th>
                                        <td>
                                            <div class="show-field">
                                                {{ $organization->auth_sign_email }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <input type="text" class="form-control" name="auth_sign_email_3" value="{{ $organization->auth_sign_email_3 }}">
                                                <div id="auth_sign_email_3" class="invalid-feedback"></div>
                                                <div class="text-right mt-2">
                                                    <button type="button" class="btn btn-sm btn-primary m-1 add-address-field_1" {{ $organization->auth_sign_email_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                    <button type="button" class="btn btn-sm btn-primary remove-display_1" data-display="#third-address-pair_1"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                                </div>
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
                                            <div class="show-field">
                                                {{ $organization->auth_sign_phone }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                    </div>
                                                    <input type="text" maxlength="10" class="form-control numeric-only" name="auth_sign_phone" value="{{ $organization->auth_sign_phone }}">
                                                </div>
                                                <div id="auth_sign_phone" class="invalid-feedback"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('organizations.update')
            <div class="edit-field d-none text-center">
                <button id="submit-btn" type="button" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('organization.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
            @endcan
        </form>
    </div>
@endsection


@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    @can('organizations.update')
    <div id="archive-modal" tabindex="1" role="dialog" aria-labelledby="archive-modal-title" aria-hidden="true"
         class="modal fade">
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
    <div id="unarchive-modal" tabindex="1" role="dialog" aria-labelledby="unarchive-modal-title" aria-hidden="true"
         class="modal fade">
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
    @can('organizations.delete')
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

        $('#submit-btn').on('click',function (e) {
            e.preventDefault();

            let form = $('#add');
            form.find('.invalid-feedback').text('');
            form.find('.is-invalid').removeClass('is-invalid');

            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('organization.update',$organization->id) }}",form.serialize()).then((response)=>{
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

        $('select[name=type]').on('change',function () {
            if($(this).val().toLowerCase() === 'other'){
                $('#other-container').removeClass('d-none');
            }else{
                $('#other-container').addClass('d-none');
            }
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

            axios.post("{{ route('organization.archive-create') }}",$(this).serialize()).then((response)=>{
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

            axios.post("{{ route('organization.unarchive') }}",$(this).serialize()).then((response)=>{
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

            axios.delete("{{ route('organization.index') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    setTimeout(function () {
                        window.location.href = '{{ route('organization.index') }}';
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
            if($('#second-address-pair').hasClass('d-none') || ($('#second-address-pair').hasClass('d-none') && $('#third-address-pair').hasClass('d-none'))){
                $('#first-add-address-field').removeClass('d-none')
            }
        })

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