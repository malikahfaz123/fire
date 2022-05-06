@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>View Fire Department</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Fire Departments > View Fire Department</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Fire Dept. ID:'=>$fire_department->prefix_id]])
                @if($last_updated)
                    @include('partials.meta-box',['icon'=>'schedule','labels'=>['Last updated on:'=>\App\Http\Helpers\Helper::date_format($last_updated->created_at),'Last updated by:'=>ucwords($last_updated->user->name)],'bg_class'=>'bg-gradient-dark'])
                @endif
            </div>
            <div class="col-md-6 text-right">
                <div class="pb-1">
                    @include('partials.history-button')
                    <a href="{{ route('fire-department.index') }}" class="btn bg-white text-secondary"><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
                </div>
                @can('fire_departments.update')
                <button class="btn btn-secondary archive btn-wd {{ $fire_department->is_archive ? 'd-none' : '' }}" data-archive="{{ $fire_department->id }}"><span class="material-icons">archive</span> Archive</button>
                <button class="btn btn-secondary unarchive btn-wd {{ $fire_department->is_archive ? '' : 'd-none' }}" data-archive="{{ $fire_department->id }}"><span class="material-icons">unarchive</span> Unarchive</button>
                @endcan
                @can('fire_departments.delete')
                    <button data-delete="{{ $fire_department->id }}" class="btn btn-danger delete" title="Delete"><span class="material-icons">delete_outline</span> Delete</button>
                @endcan
            </div>
        </div>
        @can('fire_departments.update')
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
                    <h4 class="mb-0">Fire Department Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="170">
                                        <label class="required">Name</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->name }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="name" class="form-control" value="{{ $fire_department->name }}">
                                            <div id="name" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="180">
                                        <label class="required">Address</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->address }}</div>
                                        <div class="edit-field d-none">
                                            <input id="google-autocomplete-places" type="text" name="address" class="form-control" value="{{ $fire_department->address }}">
                                            <div id="address" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">City</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->city }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="city" class="form-control" value="{{ $fire_department->city }}">
                                            <div id="city" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">State</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->state }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="state" class="form-control" value="{{ $fire_department->state }}">
                                            <div id="state" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="required">Zipcode</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->zipcode }}</div>
                                        <div class="edit-field d-none">
                                            <input type="text" name="zipcode" class="form-control" value="{{ $fire_department->zipcode }}" maxlength="5">
                                            <div id="zipcode" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Comments:</label>
                                    </th>
                                    <td>
                                        <div class="show-field">
                                            {{ $fire_department->comment }}
                                        </div>
                                        <div class="edit-field d-none">
                                            <textarea name="comment" class="form-control" rows="5" style="resize: none;">{{ $fire_department->comment }}</textarea>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th width="170">
                                        <label class="required">First Phone No.</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ \App\Http\Helpers\Helper::format_phone_number($fire_department->phone) }}</div>
                                        <div class="edit-field d-none">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text"  maxlength="10" name="phone" class="form-control numeric-only" value="{{ \App\Http\Helpers\Helper::separate_phone_code($fire_department->phone) }}">
                                            </div>
                                            <div id="phone" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Second Phone No.</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->phone2 ? \App\Http\Helpers\Helper::format_phone_number($fire_department->phone2) : 'N/A' }}</div>
                                        <div class="edit-field d-none">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{ \App\Http\Helpers\Helper::get_phone_code() }}</div>
                                                </div>
                                                <input type="text"  maxlength="10" name="phone2" class="form-control numeric-only" value="{{ \App\Http\Helpers\Helper::separate_phone_code($fire_department->phone2) }}">
                                            </div>
                                            <div id="phone2" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label class="required">Primary Email</label></th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->email }}</div>
                                        <div class="edit-field d-none">
                                            <input type="email" class="form-control" name="email" value="{{ $fire_department->email }}">
                                            <div id="email" class="invalid-feedback"></div>
                                            <div id="first-add-email-field" class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary add-email-field"><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table id="second-email-pair" class="table table-borderless w-100 mb-0 {{ !$fire_department->email_2 ? 'd-none' : '' }}">
                                <tbody>
                                    <tr>
                                        <th width="185"><label>Secondary Email # 02</label></th>
                                        <td>
                                            <div class="show-field">{{ $fire_department->email_2 }}</div>
                                            <div class="edit-field d-none">
                                                <input type="email" class="form-control" name="email_2" value="{{ $fire_department->email_2 }}">
                                                <div id="email_2" class="invalid-feedback"></div>
                                                <div class="text-right mt-2">
                                                    <button type="button" class="btn btn-sm btn-primary m-1 add-email-field" {{ $fire_department->email_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                    <button type="button" class="btn btn-sm btn-primary m-1 remove-display" data-display="#second-email-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="third-email-pair" class="table table-borderless w-100 mb-0 {{ !$fire_department->email_3 ? 'd-none' : '' }}">
                                <tbody>
                                <tr>
                                    <th width="185"><label>Secondary Email # 03</label></th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->email_3 }}</div>
                                        <div class="edit-field d-none">
                                            <input type="email" class="form-control" name="email_3" value="{{ $fire_department->email_3 }}">
                                            <div id="email_3" class="invalid-feedback"></div>
                                            <div class="text-right mt-2">
                                                <button type="button" class="btn btn-sm btn-primary m-1 add-email-field" {{ $fire_department->email_3 ? 'disabled' : '' }}><span class="material-icons text-white" style="font-size: initial !important;">add</span> Add</button>
                                                <button type="button" class="btn btn-sm btn-primary remove-display" data-display="#third-email-pair"><span class="material-icons text-white" style="font-size: initial !important;">remove</span> Remove</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th width="170">
                                        <label class="required">No. of Fire Dept. type</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $fire_department->no_of_dept_types }}</div>
                                        <div class="edit-field d-none">
                                            <select name="no_of_dept_types" class="form-control">
                                                <option value="">Choose an option</option>
                                                <option {{ $fire_department->no_of_dept_types==1 ? 'selected' : '' }} value="1">1</option>
                                                <option {{ $fire_department->no_of_dept_types==2 ? 'selected' : '' }} value="2">2</option>
                                                <option {{ $fire_department->no_of_dept_types==3 ? 'selected' : '' }} value="3">3</option>
                                                <option {{ $fire_department->no_of_dept_types==4 ? 'selected' : '' }} value="4">4</option>
                                                <option {{ $fire_department->no_of_dept_types==5 ? 'selected' : '' }} value="5">5</option>
                                                <option {{ $fire_department->no_of_dept_types==6 ? 'selected' : '' }} value="6">6</option>
                                            </select>
                                            <div id="no_of_dept_types" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="show-field">
                                <table class="table table-borderless w-100 mb-0">
                                    <tr>
                                        <th width="170">
                                            <label>Fire Dept. types</label>
                                        </th>
                                        <td>
                                            {!! implode(',<br>',$foreign_relations) !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="edit-field d-none">
                                <div id="fire-department-types-container">
                                    @if($foreign_relations)
                                        @php $prefix = ['1st','2nd','3rd','4th','5th','6th']; $count = 0; @endphp
                                        <table class="table table-borderless w-100 mb-0">
                                            @foreach($foreign_relations as $key=>$foreign_relation)
                                                <tr>
                                                    <th width="170" class="text-right">
                                                        @php
                                                            echo $prefix[$count];
                                                            $count++;
                                                        @endphp
                                                    </th>
                                                    <td class="selectpicker-custom-style">
                                                        <select name="fire_department_types[]" class="form-control fire-department-types-select2" data-live-search="true">
                                                            @foreach($db_fire_department_types as $fire_department_type)
                                                                <option {{ $fire_department_type['id']==$key ? 'selected' : '' }} value="{{ $fire_department_type['id'] }}">{{ $fire_department_type['description'] }} ({{ $fire_department_type['prefix_id'] }})</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @can('fire_departments.update')
                <div class="edit-field d-none text-center">
                    <button id="submit-btn" type="button" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                    <a href="<?php echo route('fire-department.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
                </div>
            @endcan
        </form>
    </div>
@endsection

@section('modals')
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    @include('partials.message-modal',['id'=>'history-modal','title'=>'History','max_width'=>750])
    @can('fire_departments.update')
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
    @can('fire_departments.delete')
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('constant.google_places_api') }}&libraries=places" defer></script>
    <script src="{{ asset('js/initMap.js') }}" defer></script>
    <script defer>
        document.addEventListener("DOMContentLoaded", ()=>{
            initMap('google-autocomplete-places','[name=zipcode]','[name=city]','[name=state]');
        });
    </script>

    <script type="text/javascript">

        $('#submit-btn').on('click',function (e) {
            e.preventDefault();

            let form = $('#add');
            form.find('.invalid-feedback').text('');
            form.find('.is-invalid').removeClass('is-invalid');

            let submit_btn = $(this);
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('fire-department.update',$fire_department->id) }}",form.serialize()).then((response)=>{
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

        $(document).on('click','.view-history',function () {
            let html = '<h5 class="text-center"><div class="spinner mb-2"></div> Loading...</h5>';
            $('#history-modal-content').html(html);
            $('#history-modal').modal('show');
            $.ajax({
                url: '{{ route('fire-department.history',$fire_department->id) }}',
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

        $(".fire-department-types-select2").select2({ placeholder: "Select Fire Dept." });

        $('[name=no_of_dept_types]').on('change',function () {

            let types = $(this).val(),records = $(document).find('#fire-department-types-container [name*=fire_department_types]'),length = records.length,values = {};
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
                if(typeof values[i] !== "undefined" && Object.size(values)){
                    option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
                }else{
                    var all_fireDepartment_types = '@php echo json_encode($all_fireDepartment_types); @endphp';
                    var all_fireDepartment_types = JSON.parse(all_fireDepartment_types);
                    option = '';
                    option +=`<option value=""></option>`;
                    for (let i=0; i < all_fireDepartment_types.length; i++){
                        option +=`<option value="${all_fireDepartment_types[i].id}">${all_fireDepartment_types[i].description}</option>`;
                    }
                }

                html+=`<tr class="selectpicker-custom-style">
                            <th width="170" class="text-right">${prefixes[i]}</th>
                            <td><select name="fire_department_types[]" class="form-control fire-department-types-select2" data-live-search="true">${option}</select></td>
                       </tr>`
            }
            html+= '<tr><th></th><td class="pt-0"><div id="fire_department_types" class="invalid-feedback"></div></td></tr></table>';
            $('#fire-department-types-container').html(html);

            $(".fire-department-types-select2").select2({ placeholder: "Select Fire Dept." });
            // initSelect2();
        });

        // function initSelect2(){
        //     $(document).find(".fire-department-types-select2").select2({
        //         minimumInputLength: 2,
        //         placeholder: 'Search department type',
        //         ajax: {
        //             url: '{{ route('fire-department.search-fire-department-type') }}',
        //             dataType: 'json',
        //             type: "GET",
        //             quietMillis: 50,
        //             data: function (search) {
        //                 return {
        //                     search: search.term
        //                 };
        //             },
        //             processResults: function (fire_department_types) {
        //                 return {
        //                     results: $.map(fire_department_types, function (fire_department_type) {
        //                         return {
        //                             text: `${fire_department_type.description} (${fire_department_type.prefix_id})`,
        //                             id: fire_department_type.id
        //                         }
        //                     })
        //                 };
        //             }
        //         }
        //     });
        // }

        $(document).ready(function () {
            initSelect2()
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

            axios.post("{{ route('fire-department.archive-create') }}",$(this).serialize()).then((response)=>{
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

            axios.post("{{ route('fire-department.unarchive') }}",$(this).serialize()).then((response)=>{
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

            axios.delete("{{ route('fire-department.index') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    setTimeout(function () {
                        window.location.href = '{{ route('fire-department.index') }}';
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