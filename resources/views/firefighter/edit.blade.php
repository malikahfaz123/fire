@extends('layouts.app',['title'=>$title])
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Edit Personnel</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Personnel Information > Edit Personnel</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['DFSID:'=>\App\Http\Helpers\FirefighterHelper::prefix_id($firefighter->id)]])
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('firefighter.index') }}" class="btn bg-white text-secondary">
                    <span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back
                </a>
            </div>
        </div>
        <div class="row mb-4">
            @if($firefighter->updated_by != null)
            <div class="col-md-6">
                @include('partials.meta-box',['icon'=>'schedule','labels'=>['Last updated on:'=>\App\Http\Helpers\Helper::date_format($firefighter->updated_at),'Last updated by:'=>ucwords($firefighterUpdated->f_name.' '.$firefighterUpdated->l_name)],'bg_class'=>'bg-gradient-dark'])
                
            </div>
            @endif
        </div>
        <form id="add">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="160">
                                        <label class="required">Name Suffix</label>
                                    </th>
                                    <td>
                                        <select class="form-control" name="name_suffix">
                                            <option selected disabled>Choose an option</option>
                                            <option {{ $firefighter->name_suffix==='Jr' ? 'selected' : '' }} value="Jr">Jr</option>
                                            <option {{ $firefighter->name_suffix==='Sr' ? 'selected' : '' }} value="Sr">Sr</option>
                                            <option {{ $firefighter->name_suffix==='III' ? 'selected' : '' }} value="III">III</option>
                                            <option {{ $firefighter->name_suffix==='IV' ? 'selected' : '' }} value="IV">IV</option>
                                            <option {{ $firefighter->name_suffix==='V' ? 'selected' : '' }} value="V">V</option>
                                        </select>
                                        <div id="name_suffix" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="160">
                                        <label class="required">First Name</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control alpha-only" name="f_name" value="{{ $firefighter->f_name }}" {{ $user->role->name !=='admin' ? 'disabled' : '' }}>
                                        <div id="f_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Middle Name</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control alpha-only" name="m_name" value="{{ $firefighter->m_name }}" {{ $user->role->name !=='admin' ? 'disabled' : '' }}>
                                        <div id="m_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Last Name</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control alpha-only" name="l_name" value="{{ $firefighter->l_name }}" {{ $user->role->name !=='admin' ? 'disabled' : '' }}>
                                        <div id="l_name" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Date of Birth</label>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control dob" name="dob" max="<?php echo date('Y-m-d'); ?>" value="{{ $firefighter->dob }}">
                                        <div id="dob" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Age</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control age" name="age" value="{{ $firefighter->age }}" readonly>
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
                                            <option {{ $firefighter->gender==='male' ? 'selected' : '' }} value="male">Male</option>
                                            <option {{ $firefighter->gender==='female' ? 'selected' : '' }} value="female">Female</option>
                                            <option {{ $firefighter->gender==='transgender' ? 'selected' : '' }} value="transgender">Transgender</option>
                                            <option {{ $firefighter->gender==='other' ? 'selected' : '' }} value="other">Other</option>
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
                                            <option {{ $firefighter->race == 'american indian or alaskan native' ? 'selected' : '' }} value="american indian or alaskan native">American Indian or Alaskan Native</option>
                                            <option {{ $firefighter->race == 'asian or pacific islander' ? 'selected' : '' }} value="asian or pacific islander">Asian or Pacific Islander</option>
                                            <option {{ $firefighter->race == 'black, not of hispanic origin' ? 'selected' : '' }} value="black, not of hispanic origin">Black, not of Hispanic origin</option>
                                            <option {{ $firefighter->race == 'white, not of hispanic origin' ? 'selected' : '' }} value="white, not of hispanic origin">White, not of Hispanic origin</option>
                                            <option {{ $firefighter->race == 'hispanic' ? 'selected' : '' }} value="hispanic">Hispanic</option>
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
                                            <input class="form-check-input" type="radio" name="appointed" id="appointed-yes" value="1" {{ $firefighter->appointed ? 'checked' : '' }}>
                                            <label class="form-check-label" for="appointed-yes">Yes</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="appointed" id="appointed-no" value="0" {{ !$firefighter->appointed ? 'checked' : '' }}>
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
                                            <input type="checkbox" class="custom-control-input" name="type[]" id="fire-inspector" value="fire inspector" {{ in_array('fire inspector',$type) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="fire-inspector">Fire Inspector</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="type[]" id="fire-official" value="fire official" {{ in_array('fire official',$type) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="fire-official">Fire Official</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="type[]" id="fire-instructor" value="fire instructor" {{ in_array('fire instructor',$type) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="fire-instructor">Fire Instructor</label>
                                        </div>
                                        <div id="type" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr id="instructor-level-row" class="{{ in_array('fire instructor',$type) ? '' : 'd-none' }}">
                                    <th>
                                        <label>Instructor Level</label>
                                    </th>
                                    <td>
                                        <select class="form-control" name="instructor_level">
                                            <option value="" selected disabled>Any</option>
                                            <option {{ $firefighter->instructor_level == 1 ? 'selected' : '' }} value="1">1</option>
                                            <option {{ $firefighter->instructor_level == 2 ? 'selected' : '' }} value="2">2</option>
                                            <option {{ $firefighter->instructor_level == 3 ? 'selected' : '' }} value="3">3</option>
                                            <option {{ $firefighter->instructor_level == 4 ? 'selected' : '' }} value="4">4</option>
                                            <option {{ $firefighter->instructor_level == 5 ? 'selected' : '' }} value="5">5</option>
                                        </select>
                                        <div id="instructor_level" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="100">
                                        <label class="required">SSN</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control numeric-only bg-light" maxlength="11" name="ssn" value="{{ $firefighter->ssn }}" readonly>
                                        <div id="ssn" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>UCC</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="ucc" value="{{ $firefighter->ucc }}">
                                        <div id="ucc" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>NICET</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="nicet" value="{{ $firefighter->nicet }}">
                                        <div id="nicet" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>FEMA</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="fema" value="{{ $firefighter->fema }}">
                                        <div id="fema" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label data-toggle="tooltip" data-placement="right" title="Municipality will be auto-fetched when zip code is entered">MUNI <span class="material-icons">help</span> </label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="muni" value="{{ $firefighter->muni }}" readonly>
                                        <div id="muni" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">VOL</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="vol" value="{{ $firefighter->vol }}">
                                        <div id="vol" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">CAR</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="car" value="{{ $firefighter->car }}">
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
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="160"><label>Allow Postal Email</label></th>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="postal_mail" id="postal-mail" value="1" {{ $firefighter->postal_mail ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="postal-mail"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Primary Address Title</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="address_title" value="{{ $firefighter->address_title }}">
                                        <div id="address_title" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Address</label>
                                    </th>
                                    <td>
                                        <input type="text" id="google-autocomplete-places" class="form-control" name="address" value="{{ $firefighter->address }}">
                                        <div id="address" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">City</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control alpha-only" name="city" value="{{ $firefighter->city }}">
                                        <div id="city" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">State</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control alpha-only" name="state" value="{{ $firefighter->state }}">
                                        <div id="state" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Zip code</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control alphanumeric-only" name="zipcode" value="{{ $firefighter->zipcode }}" maxlength="5">
                                        <div id="zipcode" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td id="first-add-address-field" class="text-right {{ $firefighter->address_title_2 || $firefighter->address_title_3 ? 'd-none' : '' }}">
                                        <button type="button" class="btn btn-sm btn-primary add-address-field" {{ $firefighter->address_title_2 && $firefighter->address_title_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table id="second-address-pair" class="table table-borderless w-100 mb-0 {{ !$firefighter->address_title_2 ? 'd-none' : '' }}">
                                <tbody>
                                <tr>
                                    <th width="160"><label>Allow Postal Email</label></th>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="postal_mail_2" id="postal_mail_2" value="1" {{ $firefighter->postal_mail_2 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="postal_mail_2"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Seconday Address # 01 Title</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="address_title_2" value="{{ $firefighter->address_title_2 }}">
                                        <div id="address_title_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Address</label>
                                    </th>
                                    <td>
                                        <input type="text" id="google-autocomplete-places-2" class="form-control" name="address_2" value="{{ $firefighter->address_2 }}">
                                        <div id="address_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="city_2" value="{{ $firefighter->city_2 }}">
                                        <div id="city_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="state_2" value="{{ $firefighter->state_2 }}">
                                        <div id="state_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zip code</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="zipcode_2" value="{{ $firefighter->zipcode_2 }}" maxlength="5">
                                        <div id="zipcode_2" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-primary add-address-field" {{ $firefighter->address_title_2 && $firefighter->address_title_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                        <button type="button" class="btn btn-sm btn-primary remove-display" data-display="#second-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table id="third-address-pair" class="table table-borderless w-100 mb-0 {{ !$firefighter->address_title_3 ? 'd-none' : '' }}">
                                <tbody>
                                <tr>
                                    <th width="160"><label>Allow Postal Email</label></th>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="postal_mail_3" id="postal_mail_3" value="1" {{ $firefighter->postal_mail_3 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="postal_mail_3"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Seconday Address # 02 Title</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="address_title_3" value="{{ $firefighter->address_title_3 }}">
                                        <div id="address_title_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Address</label>
                                    </th>
                                    <td>
                                        <input type="text" id="google-autocomplete-places-3" class="form-control" name="address_3" value="{{ $firefighter->address_3 }}">
                                        <div id="address_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="city_3" value="{{ $firefighter->city_3 }}">
                                        <div id="city_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="state_3" value="{{ $firefighter->state_3 }}">
                                        <div id="state_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zip code</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name="zipcode_3" value="{{ $firefighter->zipcode_3 }}" maxlength="5">
                                        <div id="zipcode_3" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-primary add-address-field" {{ $firefighter->address_title_2 && $firefighter->address_title_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                        <button type="button" class="btn btn-sm btn-primary remove-display" data-display="#third-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
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
                                            <input type="text" maxlength="10" class="form-control numeric-only" name="home_phone" value="{{ \App\Http\Helpers\Helper::separate_phone_code($firefighter->home_phone) }}">
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
                                            <input type="text" maxlength="10" class="form-control d-inline-block numeric-only" name="cell_phone" value="{{ \App\Http\Helpers\Helper::separate_phone_code($firefighter->cell_phone) }}">
                                        </div>
                                        <div id="cell_phone" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label class="required">Primary Email</label></th>
                                    <td>
                                        <input type="email" class="form-control" name="email" value="{{ $firefighter->email }}">
                                        <div id="email" class="invalid-feedback"></div>
                                        <div id="first-add-email-field" class="text-right mt-2 {{ $firefighter->email_2 || $firefighter->email_3 ? 'd-none' : '' }}">
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
                            <table id="second-email-pair" class="table table-borderless w-100 mb-0 {{ !$firefighter->email_2 ? 'd-none' : '' }}">
                                <tbody>
                                    <tr>
                                        <th width="185"><label>Secondary Email # 01</label></th>
                                        <td>
                                            <input type="email" class="form-control" name="email_2" value="{{ $firefighter->email_2 }}">
                                            <div id="email_2" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-email-field" {{ $firefighter->email_3 ? 'disabled' : '' }}>
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
                            <table id="third-email-pair" class="table table-borderless w-100 mb-0 {{ !$firefighter->email_3 ? 'd-none' : '' }}">
                                <tbody>
                                    <tr>
                                        <th width="185"><label>Secondary Email # 02</label></th>
                                        <td>
                                            <input type="email" class="form-control" name="email_3" value="{{ $firefighter->email_3 }}">
                                            <div id="email_3" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-email-field" {{ $firefighter->email_3 ? 'disabled' : '' }}>
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
                                            <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $firefighter->comment }}</textarea>
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
                                            <option value="" disabled {{$firefighter->role == null ? 'selected' : ''}}>Choose an option</option>
                                            <option value="DCA-Firefighters" {{$firefighter->role == "DCA-Firefighters" ? 'selected' : ''}}>DCA-Firefighters</option>
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
                                        <input class="form-check-input role_manager" type="checkbox" name="role_manager" id="role_manager" style="transform: scale(1.5);" {{$firefighter->role_manager == "yes" ? 'checked' : ''}} value="yes" style="transform: scale(1.5);">
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
                <button id="submit-btn" type="button" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('constant.google_places_api') }}&libraries=places" defer></script>
    <script src="{{ asset('js/initMap.js') }}" defer></script>
    <script defer>
        document.addEventListener("DOMContentLoaded", ()=>{
            initMap('google-autocomplete-places','[name=zipcode]','[name=city]','[name=state]');
            initMap('google-autocomplete-places-2','[name=zipcode_2]','[name=city_2]','[name=state_2]');
            initMap('google-autocomplete-places-3','[name=zipcode_3]','[name=city_3]','[name=state_3]');
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
            axios.post("{{ route('firefighter.update',$firefighter->id) }}",form.serialize()).then((response)=>{
                if(response.data.status){
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
        })

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
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Revoked admin rights of this person?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, make it!'
                }).then((result) => {
                    if (result.value) {
                        $(".role_manager_value").val('no');
                        $(this).prop('checked',false);
                    }
                    else
                    {
                        $(".role_manager_value").val('yes');
                        $(this).prop('checked',true);
                    }
                });
            }
        });

    </script>
@endpush
