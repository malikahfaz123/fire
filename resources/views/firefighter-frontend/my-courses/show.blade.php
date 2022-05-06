@extends('layouts.firefighters-app',['title'=> $title ])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')

    <div class="pl-3">
        <div class="page-title">
            <h3>View Course</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Training Details > My Courses > View Course</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Courses ID:'=>$course->prefix_id]])
            </div>
            <div class="col-md-6 text-right">
                <div class="pb-1">
                    @include('partials.back-button')
                </div>
                @if(!empty($firefighter_courses) && ($firefighter_courses->status == 'enrolled'))
                <a href="{{ route('firefighters.classes.index',$course->id) }}" class="btn btn-primary btn-wd"><span class="material-icons">school</span> View Classes</a>
                @endif
            </div>
        </div>
        <div class="text-right mb-3 mt-3">
        </div>
        <form id="add">
            @csrf
            @method('put')
        
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Course Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100">
                                <tbody>
                                <tr>
                                    <th width="160">
                                        <label>FEMA Course</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $course->fema_course ? $course->fema_course : 'N/A' }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Course Name</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $course->course_name }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>NFPA STD</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $course->nfpa_std ? $course->nfpa_std : 'N/A' }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Admin CEU's</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $course->admin_ceu ? $course->admin_ceu : 'N/A' }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Tech CEU's</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $course->tech_ceu ? $course->tech_ceu : 'N/A' }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Course Hours</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $course->course_hours }}</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                {{-- <tr>
                                    <th width="170">
                                        <label>Instructor Level</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $course->instructor_level }}</div>
                                        <div class="edit-field d-none">
                                            <select name="instructor_level" class="form-control">
                                                <option value="">Choose an option</option>
                                                <option {{ $course->instructor_level== 1 ? 'selected' : '' }} value="1">1</option>
                                                <option {{ $course->instructor_level== 2 ? 'selected' : '' }} value="2">2</option>
                                                <option {{ $course->instructor_level== 3 ? 'selected' : '' }} value="3">3</option>
                                                <option {{ $course->instructor_level== 4 ? 'selected' : '' }} value="4">4</option>
                                                <option {{ $course->instructor_level== 5 ? 'selected' : '' }} value="5">5</option>
                                            </select>
                                            <div id="instructor_level" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr> --}}
                            </table>
                            <table class="table table-borderless w-100 mb-0">
                                {{-- <tr>
                                    <th width="170">
                                        <label>No. of Credit type</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $course->no_of_credit_types }}</div>
                                        <div class="edit-field d-none">
                                            <select name="no_of_credit_types" class="form-control">
                                                <option value="">Choose an option</option>
                                                @for($i=1; $i<=20; $i++)
                                                    <option {{ $course->no_of_credit_types== $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <div id="no_of_credit_types" class="invalid-feedback"></div>
                                        </div>
                                    </td>
                                </tr> --}}
                            </table>
                            <div class="show-field">
                                <table class="table table-borderless w-100 mb-0">
                                    <tr>
                                        <th width="170">
                                            <label>Credit types</label>
                                        </th>
                                        <td>
                                            {!! implode(',<br>',$foreign_relations) !!}
                                        </td>
                                    </tr>

                                    @if(!empty($firefighter_courses) )

                                        <tr>
                                            <th width="170">
                                                <label>Application Status</label>
                                            </th>
                                            <td>
                                                {{ ucfirst($firefighter_courses->status) }}
                                            </td>
                                        </tr>
                                        @if(!empty($firefighter_courses) && ($firefighter_courses->status == 'rejected'))
                                            <tr>
                                                <th width="170">
                                                    <label>Rejected Reason</label>
                                                </th>
                                                <td style="max-width: 250px;">
                                                    <p style="word-wrap: break-word;">{{ ucfirst($firefighter_courses->reason) }}</p>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </table>
                         
                            </div>
                            <div class="edit-field d-none">
                                <div id="credit-types-container">
                                    @if($foreign_relations)
                                        @php $count = 1; @endphp
                                        <table class="table table-borderless w-100 mb-0">
                                            @foreach($foreign_relations as $key=>$foreign_relation)
                                                <tr>
                                                    <th width="170" class="text-right">
                                                        @php
                                                            echo \App\Http\Helpers\Helper::ordinal_suffix_of($count);
                                                            $count++;
                                                        @endphp
                                                    </th>
                                                    <td class="selectpicker-custom-style">
                                                        <select name="credit_types[]" class="form-control courses-select2" data-live-search="true">
                                                            @foreach($db_credit_types as $credit_type)
                                                                <option {{ $credit_type['id']==$key ? 'selected' : '' }} value="{{ $credit_type['id'] }}">{{ $credit_type['description'] }} ({{ $credit_type['prefix_id'] }})</option>
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
        </form>
    </div>
@endsection
@push('js')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>

<script type="text/javascript">

    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');
        axios.post("{{ route('course.update',$course->id) }}",$(this).serialize()).then((response)=>{
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

    $('[name=no_of_credit_types]').on('change',function () {

        let types = $(this).val(),records = $(document).find('#credit-types-container [name*=credit_types]'),length = records.length,values = {};
        if(length){
            for (let i=0; i<length; i++){
                values[i] = {
                    [records[i].value] : typeof records[i].selectedOptions[0] !== "undefined" ? records[i].selectedOptions[0].innerHTML.trim() : ''
                }
            }
        }

        html = '<table class="table table-borderless w-100 mb-0">';
        for (let i=0; i<types; i++){
            if(typeof values[i] !== "undefined" && Object.size(values)){
                option = `<option selected value="${Object.keys(values[i])[0]}">${Object.values(values[i])[0]}</option>`;
            }else{
                option = '';
            }
            html+=`<tr class="selectpicker-custom-style">
                        <th width="170" class="text-right">${ordinal_suffix_of(i+1)}</th>
                        <td><select name="credit_types[]" class="form-control courses-select2" data-live-search="true">${option}</select></td>
                   </tr>`;
        }
        html+= '<tr><th></th><td class="pt-0"><div id="credit_types" class="invalid-feedback"></div></td></tr></table>';
        $('#credit-types-container').html(html);
        initSelect2();
    });

    function initSelect2(){
        $(document).find(".courses-select2").select2({
            minimumInputLength: 2,
            placeholder: 'Search credit type',
            ajax: {
                url: '{{ route('course.search-credit-type') }}',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function (search) {
                    return {
                        search: search.term
                    };
                },
                processResults: function (credit_types) {
                    return {
                        results: $.map(credit_types, function (credit_type) {
                            return {
                                text: `${credit_type.description} (${credit_type.prefix_id})`,
                                id: credit_type.id
                            }
                        })
                    };
                }
            }
        });
    }

    $(document).ready(function () {
        initSelect2()
    })

    $(document).on('click','.view-history',function () {
        let html = '<h5 class="text-center"><div class="spinner mb-2"></div> Loading...</h5>';
        $('#history-modal-content').html(html);
        $('#history-modal').modal('show');
        $.ajax({
            url: '{{ route('course.history',$course->id) }}',
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
</script>
@endpush