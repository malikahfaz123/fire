@extends('layouts.firefighters-app',['title'=> $title ])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>View Credential</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">My Credentials > Failed Credentials > View Credential</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Credential Code:'=>$certificate->prefix_id]])
            </div>
            <div class="col-md-6 text-right">
                <div class="pb-1">
                    @include('partials.back-button')
                </div>
            </div>
        </div>
        <div class="text-right mt-3 mb-3">
        </div>
        <form id="add">
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
                                        <label>Credential Code</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $certificate->prefix_id }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label>Credential Title</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $certificate->title }}</div>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label>Short Title</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $certificate->short_title }}</div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>
                                        <label>Renewable</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $certificate->renewable ? 'Yes' : 'No' }}</div>
                                    </td>
                                </tr>
                                <tr id="renewal-period-container" class="{{ $certificate->renewal_period ? '' : 'd-none' }}">
                                    <th>
                                        <label>Renewal Period</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $certificate->renewal_period }}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="show-field">
                                <table class="table table-borderless w-100 mb-0">
                                    <tr class="{{ $certificate->renewal_period ? '' : 'd-none' }}">
                                        <th width="170">
                                            <label>Credit types</label>
                                        </th>
                                        <td>
                                            {!! implode(',<br>',$foreign_relations) !!}
                                        </td>
                                    </tr>

                                    @if(!empty($firefighter_certificates) && ($firefighter_certificates->status == 'accepted' ))

                                        <tr>
                                            <th width="170">
                                                <label>Application Status</label>
                                            </th>
                                            <td>
                                                {{ ucfirst($firefighter_certificates->status) }}
                                            </td>
                                        </tr>
                                            <tr>
                                                @if($firefighter_certificates->test_status == 'failed' )
                                                    <th width="170">
                                                        <label>Test Status</label>
                                                    </th>
                                                    <td class="text-danger">
                                                        {{ ucfirst($firefighter_certificates->test_status) }}
                                                        @if($firefighter_certificates->test_status == 'failed' && $firefighter_certificates_details->count() == 1 )
                                                            <br>
                                                            <a href="javascript:void(0)" class="apply" data-certification_id="{{ $certificate->id }}" data-certification_prefix_id="{{ $certificate->prefix_id }}"  data-certification_title="{{ $certificate->title }}" data-firefighter_id="{{ Auth::guard('firefighters')->user()->id }}" title="Apply"> Apply supply for this Credential Again</a>
                                                        @endif
                                                    </td>
                                                @endif
                                                @if($firefighter_certificates->test_status == 'passed')
                                                    <th width="170">
                                                        <label>Test Status</label>
                                                    </th>
                                                    <td class="text-success">
                                                        {{ ucfirst($firefighter_certificates->test_status) }}
                                                    </td>
                                                @endif
                                                @if($firefighter_certificates->test_status == 'none')
                                                    <th width="170">
                                                        <label>Test Status</label>
                                                    </th>
                                                    <td class="">
                                                        You have applied for the supply
                                                    </td>
                                                @endif
                                            </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Credential Test Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover app-table text-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Credential Code</th>
                                        <th>Credential Name</th>
                                        <th>Test Date</th>
                                        <th>Test Time</th>
                                        <th>Test Status</th>
                                    </tr> 
                                </thead>
                                <tbody >
                                @if($firefighter_certificates_details && $firefighter_certificates_details->count())
                                    @foreach($firefighter_certificates_details as $firefighter_certificates_details)
                                        <tr>
                                            <td class="text-capitalize"> {{ $firefighter_certificates_details->prefix_id }} </td>
                                            <td class="text-capitalize"> {{ $firefighter_certificates_details->title }} </td>
                                            <td class="text-capitalize"> {{ $firefighter_certificates_details->test_date }} </td>
                                            <td class="text-capitalize"> {{ $firefighter_certificates_details->test_time }} </td>
                                            <td class="text-capitalize">
                                                {{ $firefighter_certificates_details->test_status }}
                                                {{-- {{ $firefighter_certificates_details->count() == 1 }} --}}
                                                {{-- @if($firefighter_certificates_details->test_status == 'failed')
                                                    AFS
                                                @endif --}}
                                                
                                             
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr align="center"><td colspan="100%">No record found.</td></tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                       
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('modals')

    <div id="apply-certificate-modal" tabindex="1" role="dialog" aria-labelledby="apply-course-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="add" >
                    @csrf

                    <div class="modal-header"><h3 id="" class="modal-title cambria-bold ml-auto">Supply Application Form </h3>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Credential Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-borderless w-100">
                                            <tbody>
                                                <input type="hidden" name="certification_id">
                                                <input type="hidden" name="firefighter_id">
                                            <tr>
                                                <th width="160">
                                                    <label>Credential Code</label>
                                                </th>
                                                <td>
                                                    <input type="text" class="form-control text-capitalize" name="certification_prefix_id" disabled>
                                                    <div id="certification_prefix_id" class="invalid-feedback"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="160">
                                                    <label>Credential Title</label>
                                                </th>
                                                <td>
                                                    <input type="text" class="form-control text-capitalize" name="certification_title" disabled>
                                                    <div id="certification_title" class="invalid-feedback"></div>
                                                </td>
                                            </tr>
                                
                                            </tbody>
                                        </table>
                                    </div>
                            
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Apply</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection
@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script type="text/javascript">

    $(document).on('click','.apply',function (e) {
        e.preventDefault();

        let modal = $('#apply-certificate-modal');
        let certification_id         =  $(this).data('certification_id');
        let certification_prefix_id  =  $(this).data('certification_prefix_id');
        let certification_title      =  $(this).data('certification_title');
        let firefighter_id           =  $(this).data('firefighter_id');

        modal.modal('show');
        modal.find('[name=certification_id]').val(certification_id);
        modal.find('[name=certification_prefix_id]').val(certification_prefix_id);
        modal.find('[name=certification_title]').val(certification_title);
        modal.find('[name=firefighter_id]').val(firefighter_id);
    });

    $('#add').on('submit', function (e) {
        e.preventDefault();
        let submit_btn = $('.submit-btn');
        submit_btn.prop('disabled', true);
        submit_btn.addClass('disabled');

        axios.post("{{ route('firefighters.supply.apply.certificates') }}",$(this).serialize()).then((response)=>{
            if(response.data.status){

                $('#apply-certificate-modal').modal('hide');

                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });

                setTimeout(() => location.reload(), 2000);
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
    </script>
@endpush