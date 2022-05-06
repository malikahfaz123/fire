@extends('layouts.app',[ 'title' => $title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Edit Group Credit Types</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic"> Training Details >  Credit Types Groups</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-right">
                {{-- @include('partials.back-button') --}}
                <a href="{{ route('group-credit-types.index') }}" style="cursor: {{ isset($_SERVER['HTTP_REFERER']) ?  'pointer' : 'not-allowed' }}" class="btn bg-white text-secondary" {{ !isset($_SERVER['HTTP_REFERER']) ?  'disabled' : '' }}><span class="material-icons text-secondary mr-2">keyboard_backspace</span>Back</a>
            </div>
        </div>
        <form id="add">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"> Credit Types Grouping Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>

                                <tr>
                                    <th width="210">
                                        <label class="required">Group Code</label>
                                    </th>
                                    <td class="selectpicker-custom-style">
                                        <input type="text" class="form-control" name="credit_code" value="{{ $credit_code }}" id="group_name">
                                        <div id="credit_code" class="invalid-feedback"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <label class="required">Credit Types</label>
                                    </th>
                                    <td> 
                                        <select id="credit-types-select2" multiple="multiple" name="credit_types[]" class="form-control">
                                            @if(!empty($all_credit_types))
                                                @foreach ($all_credit_types as $key => $all_credit_type) 
                                                    <option value="{{ $all_credit_type->id }}"
                                                        @foreach ($group_credit_types as $item)
                                                        @if($item->credit_type_id == $all_credit_type->id)
                                                        selected
                                                        @endif
                                                        @endforeach
                                                        >{{ $all_credit_type->description  }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div id="credit_types" class="invalid-feedback"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3"><span class="material-icons loader rotate mr-1">autorenew</span> Update</button>
                <a href="<?php echo route('group-credit-types.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

    <script type="text/javascript">

        $('#add').on('submit',function (e) {
            e.preventDefault();

            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('group-credit-types.update',$credit_code) }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    // window.location.href = `{{ route('group-credit-types.index') }}/${response.data.instructor_level}/edit`;
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


$('#credit-types-select2').select2({ placeholder: "Select Credit Types" });


        // $("#courses-select2").select2({
        //     minimumInputLength: 2,
        //     placeholder: 'Search Courses',
        //     ajax: {
        //         url: '{{ route('semester.search-courses') }}',
        //         dataType: 'json',
        //         type: "GET",
        //         quietMillis: 50,
        //         data: function (search) {
        //             return {
        //                 search: search.term
        //             };
        //         },
        //         processResults: function (courses) {
        //             return {
        //                 results: $.map(courses, function (course) {
        //                     return {
        //                         text: course.course_name,
        //                         id: course.id
        //                     }
        //                 })
        //             };
        //         }
        //     }
        // });

    </script>
@endpush