@extends('layouts.app',['title'=>$title])
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>View Personnel</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Personnel Information > View Personnel</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['DFSID:'=>\App\Http\Helpers\FirefighterHelper::prefix_id($firefighter->id)]])
                @if($firefighter->updated_by != null)
                    @include('partials.meta-box',['icon'=>'schedule','labels'=>['Last updated on:'=>\App\Http\Helpers\Helper::date_format($firefighter->updated_at),'Last updated by:'=>ucwords($firefighterUpdated->f_name.' '.$firefighterUpdated->l_name)],'bg_class'=>'bg-gradient-dark'])
                @endif
            </div>
            <div class="col-md-6 text-right">
                <div class="pb-1">
                    <a href="{{ route('firefighter.index') }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
                </div>
                <a href="{{ route('firefighter.course',$firefighter->id) }}" class="btn btn-primary btn-wd"><span class="material-icons mr-2">school</span>Courses</a>
                <a href="{{ route('firefighter.certifications',$firefighter->id) }}" class="btn btn-primary btn-wd"><span class="material-icons mr-2">folder_special</span>Credentials</a>
                <button class="btn btn-secondary archive {{ $firefighter->is_archive ? 'd-none' : '' }}" data-archive="{{ $firefighter->id }}"><span class="material-icons">archive</span> Archive</button>
                <button class="btn btn-secondary unarchive {{ $firefighter->is_archive ? '' : 'd-none' }}" data-archive="{{ $firefighter->id }}"><span class="material-icons">unarchive</span> Unarchive</button>
                @can('firefighters.delete')
                    <button data-delete="{{ $firefighter->id }}" class="btn btn-danger delete"><span class="material-icons">delete_outline</span> Delete</button>
                @endcan
            </div>
        </div>
        @can('firefighters.update')
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
                    <h4 class="mb-0">CEU Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                            
                                
                                <tr>
                                    <th>
                                        <label>Admin CEUs</label>
                                    </th>
                                    <td id="adminceu">
                                    {{$total_admin_ceu}}
                <div id="type" class="invalid-feedback"></div>
                        </div>
                     </td>
                     </tr>


                     <tr>
                                    <th>
                                        <label>Tech CEUs</label>
                                    </th>
                                    <td>
                                    {{$total_tech_ceu}}
                <div id="type" class="invalid-feedback"></div>
                        </div>
                     </td>
                     </tr>



                     <tr>
                                    <th>
                                        <label>Expiry Date</label>
                                    </th>
                                    <td>
                                       12/april/2023 dummy ? 
                <div id="type" class="invalid-feedback"></div>
                        </div>
                     </td>
                     </tr>



<!-- 
                                <tr>
                                    <th>
                                        <label>UCC</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->ucc }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="ucc" value="{{ $firefighter->ucc }}">
                                            <div id="ucc" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr> -->
                              
                            </table>
                        </div>


                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                <div class="container">
    <div class="row">
      <div class="col">
        <a style = "color:white"class="btn btn-primary form-control btn-block" id="ceu_post" data-id="{{ $firefighter->id }}" >Add CEU,s</a></div>
        <div class="col">
        <a style = "color:white"class="btn btn-primary form-control btn-block"> CEU,s History</a></div>
    </div>
  </div>
                                </tr>
</table>
</div>






                    </div>
                </div>
            </div>




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
                                        <label>Name Suffix</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->name_suffix }}</div>
                                        <div class="edit-field d-none">
                                            <select class="form-control" name="name_suffix">
                                                <option selected disabled>Choose an option</option>
                                                <option {{ $firefighter->name_suffix==='Jr' ? 'selected' : '' }} value="Jr">Jr</option>
                                                <option {{ $firefighter->name_suffix==='Sr' ? 'selected' : '' }} value="Sr">Sr</option>
                                                <option {{ $firefighter->name_suffix==='III' ? 'selected' : '' }} value="III">III</option>
                                                <option {{ $firefighter->name_suffix==='IV' ? 'selected' : '' }} value="IV">IV</option>
                                                <option {{ $firefighter->name_suffix==='V' ? 'selected' : '' }} value="V">V</option>
                                            </select>
                                            <div id="name_suffix" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="160">
                                        <label>First Name</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->f_name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control alpha-only" name="f_name" value="{{ $firefighter->f_name }}" {{ $user->role->name !=='admin' ? 'disabled' : '' }}>
                                            <div id="f_name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Middle Name</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->m_name ? $firefighter->m_name : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control alpha-only" name="m_name" value="{{ $firefighter->m_name }}" {{ $user->role->name !=='admin' ? 'disabled' : '' }}>
                                            <div id="m_name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Last Name</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->l_name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control alpha-only" name="l_name" value="{{ $firefighter->l_name }}" {{ $user->role->name !=='admin' ? 'disabled' : '' }}>
                                            <div id="l_name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th width="150">
                                        <label class="required">Email</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->email }}</div>
                                        <div class="edit-field d-none">
                                            <input type="email" class="form-control" name="email" value="{{ $firefighter->email }}" disabled>
                                            <div id="email" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label>Date of Birth</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ \App\Http\Helpers\Helper::date_format($firefighter->dob) }}</div>
                                        <div class="edit-field d-none">
                                            <input type="date" class="form-control dob" name="dob" value="{{ $firefighter->dob }}">
                                            <div id="dob" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Age</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->age }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control age" name="age" value="{{ $firefighter->age }}" readonly>
                                            <div id="age" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Gender</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ ucfirst($firefighter->gender) }}</div>
                                        <div class="edit-field d-none">
                                            <select name="gender" class="form-control" title="Choose an option">
                                                <option value="" selected disabled>Choose an option</option>
                                                <option {{ $firefighter->gender==='male' ? 'selected' : '' }} value="male">Male</option>
                                                <option {{ $firefighter->gender==='female' ? 'selected' : '' }} value="female">Female</option>
                                                <option {{ $firefighter->gender==='transgender' ? 'selected' : '' }} value="transgender">Transgender</option>
                                                <option {{ $firefighter->gender==='other' ? 'selected' : '' }} value="other">Other</option>
                                            </select>
                                            <div id="gender" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Race</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ ucfirst($firefighter->race) }}</div>
                                        <div class="edit-field d-none">
                                            <select class="form-control" name="race">
                                                <option value="" selected disabled>Choose an option</option>
                                                <option {{ $firefighter->race == 'american indian or alaskan native' ? 'selected' : '' }} value="american indian or alaskan native">American Indian or Alaskan Native</option>
                                                <option {{ $firefighter->race == 'asian or pacific islander' ? 'selected' : '' }} value="asian or pacific islander">Asian or Pacific Islander</option>
                                                <option {{ $firefighter->race == 'black, not of hispanic origin' ? 'selected' : '' }} value="black, not of hispanic origin">Black, not of Hispanic origin</option>
                                                <option {{ $firefighter->race == 'white, not of hispanic origin' ? 'selected' : '' }} value="white, not of hispanic origin">White, not of Hispanic origin</option>
                                                <option {{ $firefighter->race == 'hispanic' ? 'selected' : '' }} value="hispanic">Hispanic</option>
                                            </select>
                                            <div id="race" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Appointed</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->appointed ? 'Yes' : 'No' }}</div>
                                        <div class="edit-field d-none">
                                            <div class="form-check d-inline-block mr-2">
                                                <input class="form-check-input" type="radio" name="appointed" id="appointed-yes" value="1" {{ $firefighter->appointed ? 'checked' : '' }}>
                                                <label class="form-check-label" for="appointed-yes">Yes</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio" name="appointed" id="appointed-no" value="0" {{ !$firefighter->appointed ? 'checked' : '' }}>
                                                <label class="form-check-label" for="appointed-no">No</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Type</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{!! ucwords(implode('<br>',$type)) !!}</div>
                                        <div class="edit-field d-none">
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
                                        </div>
                                    </td>
                                </tr>
                                <tr id="instructor-level-row" class="{{ in_array('fire instructor',$type) ? '' : 'd-none' }}">
                                    <th>
                                        <label>Instructor Level</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->instructor_level }}</div>
                                        <div class="edit-field d-none">
                                            <select class="form-control" name="instructor_level">
                                                <option value="" selected disabled>Any</option>
                                                <option {{ $firefighter->instructor_level == 1 ? 'selected' : '' }} value="1">1</option>
                                                <option {{ $firefighter->instructor_level == 2 ? 'selected' : '' }} value="2">2</option>
                                                <option {{ $firefighter->instructor_level == 3 ? 'selected' : '' }} value="3">3</option>
                                                <option {{ $firefighter->instructor_level == 4 ? 'selected' : '' }} value="4">4</option>
                                                <option {{ $firefighter->instructor_level == 5 ? 'selected' : '' }} value="5">5</option>
                                            </select>
                                            <div id="instructor_level" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tr>
                                    <th width="100">
                                        <label>SSN</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->ssn }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control numeric-only bg-light" maxlength="11" name="ssn" value="{{ $firefighter->ssn }}" readonly>
                                            <div id="ssn" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>UCC</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->ucc }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="ucc" value="{{ $firefighter->ucc }}">
                                            <div id="ucc" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>NICET</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->nicet }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="nicet" value="{{ $firefighter->nicet }}">
                                            <div id="nicet" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>FEMA</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->fema }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="fema" value="{{ $firefighter->fema }}">
                                            <div id="fema" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="show-field">
                                            <label>MUNI</label>
                                        </div>
                                        <div class="edit-field d-none">
                                            <label data-toggle="tooltip" data-placement="right" title="Municipality will be auto-fetched when zip code is entered">MUNI <span class="material-icons">help</span> </label>
                                        </div>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->muni }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="muni" value="{{ $firefighter->muni }}" readonly>
                                            <div id="muni" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>VOL</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->vol }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="vol" value="{{ $firefighter->vol }}">
                                            <div id="vol" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>CAR</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->car }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="car" value="{{ $firefighter->car }}">
                                            <div id="car" class="invalid-feedback"></div>
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
                                        <div class="show-field">{{ $firefighter->postal_mail ? 'Allowed' : 'Not allowed' }}</div>
                                        <div class="edit-field d-none">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="postal_mail" id="postal-mail" value="1" {{ $firefighter->postal_mail ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="postal-mail"></label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Primary Address Title</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->address_title }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="address_title" value="{{ $firefighter->address_title }}">
                                            <div id="address_title" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Address</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->address }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" id="google-autocomplete-places" class="form-control" name="address" value="{{ $firefighter->address }}">
                                            <div id="address" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->city }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control alpha-only" name="city" value="{{ $firefighter->city }}">
                                            <div id="city" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->state }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control alpha-only" name="state" value="{{ $firefighter->state }}">
                                            <div id="state" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zip code</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->zipcode }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control alphanumeric-only" name="zipcode" value="{{ $firefighter->zipcode }}" maxlength="5">
                                            <div id="zipcode" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td id="first-add-address-field" class="text-right {{ $firefighter->address_title_2 || $firefighter->address_title_3 ? 'd-none' : '' }}">
                                        <button type="button" class="btn btn-sm btn-primary add-address-field edit-field d-none" {{ $firefighter->address_title_2 && $firefighter->address_title_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table id="second-address-pair" class="table table-borderless w-100 mb-0 {{ !$firefighter->address_title_2 ? 'd-none' : '' }}">
                                <tbody>
                                <tr>
                                    <th width="160"><label>Allow Postal Email</label></th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->postal_mail_2 ? 'Allowed' : 'Not allowed' }}</div>
                                        <div class="edit-field d-none">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="postal_mail_2" id="postal_mail_2" value="1" {{ $firefighter->postal_mail_2 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="postal_mail_2"></label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Seconday Address # 01 Title</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->address_title_2 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="address_title_2" value="{{ $firefighter->address_title_2 }}">
                                            <div id="address_title_2" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Address</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->address_2 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" id="google-autocomplete-places-2" class="form-control" name="address_2" value="{{ $firefighter->address_2 }}">
                                            <div id="address_2" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->city_2 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="city_2" value="{{ $firefighter->city_2 }}">
                                            <div id="city_2" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->state_2 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="state_2" value="{{ $firefighter->state_2 }}">
                                            <div id="state_2" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zip code</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->zipcode_2 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="zipcode_2" value="{{ $firefighter->zipcode_2 }}" maxlength="5">
                                            <div id="zipcode_2" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-primary add-address-field edit-field d-none" {{ $firefighter->address_title_2 && $firefighter->address_title_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                        <button type="button" class="btn btn-sm btn-primary remove-display edit-field d-none" data-display="#second-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table id="third-address-pair" class="table table-borderless w-100 mb-0 {{ !$firefighter->address_title_3 ? 'd-none' : '' }}">
                                <tbody>
                                <tr>
                                    <th width="160"><label>Allow Postal Email</label></th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->postal_mail_3 ? 'Allowed' : 'Not allowed' }}</div>
                                        <div class="edit-field d-none">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="postal_mail_3" id="postal_mail_3" value="1" {{ $firefighter->postal_mail_3 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="postal_mail_3"></label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Seconday Address # 02 Title</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->address_title_3 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="address_title_3" value="{{ $firefighter->address_title_3 }}">
                                            <div id="address_title_3" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Address</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->address_3 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" id="google-autocomplete-places-3" class="form-control" name="address_3" value="{{ $firefighter->address_3 }}">
                                            <div id="address_3" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>City</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->city_3 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="city_3" value="{{ $firefighter->city_3 }}">
                                            <div id="city_3" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>State</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->state_3 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="state_3" value="{{ $firefighter->state_3 }}">
                                            <div id="state_3" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Zip code</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->zipcode_3 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" class="form-control" name="zipcode_3" value="{{ $firefighter->zipcode_3 }}" maxlength="5">
                                            <div id="zipcode_3" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-primary add-address-field edit-field d-none" {{ $firefighter->address_title_2 && $firefighter->address_title_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                        <button type="button" class="btn btn-sm btn-primary remove-display edit-field d-none" data-display="#third-address-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
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
                                        <div class="show-field">{{ $firefighter->home_phone ? \App\Http\Helpers\Helper::format_phone_number($firefighter->home_phone) : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text" maxlength="10" class="form-control numeric-only" name="home_phone" value="{{ \App\Http\Helpers\Helper::separate_phone_code($firefighter->home_phone) }}">
                                            </div>
                                            <div id="home_phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Cell Phone</label>
                                    </th>
                                    <td>
                                        <div class="show-field">
                                            <div>{{ \App\Http\Helpers\Helper::format_phone_number($firefighter->cell_phone) }}
                                                {{-- @if(!$firefighter->cell_phone_verified)
                                                    <button id="verify-phone-btn" type="button" class="pl-2 bg-transparent text-primary p-0 border-0 submit-btn verify-email" value="{{ $firefighter->id }}">
                                                        <span class="material-icons loader rotate">autorenew</span>
                                                        @if($firefighter->phone_token)
                                                            <span>Reverify</span>
                                                        @else
                                                            <span>Verify</span>
                                                        @endif
                                                    </button>
                                                @endif --}}
                                            </div>
                                        </div>
                                        <div class="edit-field d-none">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text" maxlength="10" class="form-control d-inline-block numeric-only" name="cell_phone" value="{{ \App\Http\Helpers\Helper::separate_phone_code($firefighter->cell_phone) }}">
                                            </div>
                                            <div id="cell_phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                    <tr>
                                        <th>
                                            <label>Primary Email</label>
                                        </th>
                                        <td>
                                            <div class="show-field">{{ $firefighter->email }}</div>
                                            <div class="edit-field d-none">
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
                                            <div class="show-field">{{ $firefighter->email_2 }}</div>
                                            <div class="edit-field d-none">
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
                                            <div class="show-field">{{ $firefighter->email_3 }}</div>
                                            <div class="edit-field d-none">
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
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-borderless w-100 mb-0">
                                <tbody>
                                    <tr>
                                        <th>
                                            <label>Comments:</label>
                                        </th>
                                        <td>
                                            <div class="show-field">
                                                {{ $firefighter->comment }}
                                            </div>
                                            <div class="edit-field d-none">
                                                <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $firefighter->comment }}</textarea>
                                            </div>
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
                                        <div class="show-field"><label>Role</label></div>
                                        <div class="edit-field d-none"><label class="required">Role</label></div>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $firefighter->role }}</div>
                                        <div class="edit-field d-none">
                                            <select type="text" name="role" class="form-control">
                                                <option value="" disabled {{$firefighter->role == null ? 'selected' : ''}}>Choose an option</option>
                                                <option value="DCA-Firefighters" {{$firefighter->role == "DCA-Firefighters" ? 'selected' : ''}}>DCA-Firefighters</option>
                                            </select>
                                            <div id="role" class="invalid-feedback"></div>
                                        </div>
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
                                        <div class="show-field">
                                            {{ $firefighter->role_manager == 'yes' ? 'Yes' : 'No'  }}
                                        </div>
                                        <div class="edit-field d-none">
                                            <input class="form-check-input role_manager" type="checkbox" name="role_manager" id="role_manager" style="transform: scale(1.5);" {{$firefighter->role_manager == "yes" ? 'checked' : ''}} value="yes" style="transform: scale(1.5);">
                                            <input type="hidden" name="role_manager_value" class="role_manager_value" value="no">
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('firefighters.update')
            <div class="edit-field d-none text-center">
                <button id="submit-btn" type="button" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('firefighter.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
            @endcan
        </form>
    </div>
@endsection

@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    @include('partials.message-modal',['id'=>'history-modal','title'=>'History','max_width'=>750])
    @can('firefighters.update')
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
    @can('firefighters.delete')
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
    @endif








 <!-- Modal -->
 <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
    
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 style="padding-left:175px;font-weight:50px;font-style:bold"><b>Add CEUs</b></h4>
        
        </div>
    
        <div class="modal-body">
        <div class="container">

  <form id="postForm" name="postForm"  class="form-horizontal">
      @csrf
      <input type="hidden" name="post_id" id="post_id">
    <div class="form-group"style="display:flex; flex-direction: row; justify-content: center; align-items: center">
      <label class="control-label col-sm-2" for="email"><b>Admin CEUs:</b></label>
      <div class="col-sm-5">
        <input type="number" class="form-control" id="admin" placeholder="Enter Admin CEUs" name="admin">
      </div>
    </div>
    <div class="form-group"style="display:flex; flex-direction: row; justify-content: center; align-items: center">
      <label class="control-label col-sm-2" for="pwd"><b>Tech CEUs:</b></label>
      <div class="col-sm-5">          
        <input type="number" class="form-control" id="tech" placeholder="Enter Tech CEUs" name="tech">
      </div>
    </div>
   
    <div class="form-group"style="display:flex; flex-direction: row; justify-content: center; align-items: center">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" id="btn-subm" class="btn btn-primary form-control btn-block ">Add</button>
      </div>
    </div>
  </form>
</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
   $=jQuery;

$(document).ready(function () {
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
 // edit
 $('body').on('click', '#ceu_post', function () {
      var post_id = $(this).data('id');
      $('#myModal2').modal('show');
          $('#post_id').val(post_id); 
// });
   });


});



$("#btn-subm").click(function(e){
  
  e.preventDefault(); 
  var data = $("#postForm").serialize();
  $.ajax({
     type:'post',
     url:"{{ route('manual.ceu.store') }}",
     data: data,
     success:function(result){
   
        if(result){
                    Toast.fire({
                        icon: 'success',
                        title: 'CEUs added sucessfully',
                    });
                    

                    $('#myModal2').modal('hide');
                    $('#adminceu').html(result);
                  
                 

                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: result,
                    });
                }
     }
  });

});





 </script>
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

        $('#verify-email-btn').on('click',function (e){
            e.preventDefault();

            let submit_btn = $(this);
            submit_btn.prop('disabled',true);
            submit_btn.addClass('disabled');

            let object = {
                _method:'put',
                _token: $('input[name=_token]').val(),
                module:'firefighters',
            };
            axios.put(`{{ route('confirm.email',$firefighter->id) }}`,object).then((response)=>{
                submit_btn.prop('disabled',false);
                submit_btn.removeClass('disabled');
                if(response.data.status){
                    submit_btn.find('span:last-child').text('Reverify');
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                }else{
                    $('#message-modal-content').html(response.data.msg);
                    $('#message-modal').modal('show');
                }
            });
        })

        $(document).on('click','.view-history',function () {
            let html = '<h5 class="text-center"><div class="spinner mb-2"></div> Loading...</h5>';
            $('#history-modal-content').html(html);
            $('#history-modal').modal('show');
            $.ajax({
                url: '{{ route('firefighter.history',$firefighter->id) }}',
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
        });

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

            axios.post("{{ route('firefighter.archive-create') }}",$(this).serialize()).then((response)=>{
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

            axios.post("{{ route('firefighter.unarchive') }}",$(this).serialize()).then((response)=>{
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

            axios.delete("{{ route('firefighter.index') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    setTimeout(function () {
                        window.location.href = '{{ route('firefighter.index') }}';
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
