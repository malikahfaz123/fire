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
                <span class="segoe-ui-italic">My Credentials > Applied Credentials > View Credential</span>
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

                                    @if(!empty($firefighter_certificates) )

                                        <tr>
                                            <th width="170">
                                                <label>Application Status</label>
                                            </th>
                                            <td>
                                                {{ ucfirst($firefighter_certificates->status) }}
                                            </td>
                                        </tr>
                                        @if(!empty($firefighter_certificates) && ($firefighter_certificates->status == 'rejected'))
                                            <tr>
                                                <th width="170">
                                                    <label>Rejected Reason</label>
                                                </th>
                                                <td>
                                                    {{ ucfirst($firefighter_certificates->reason) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection


@section('modals')
    @can('certifications.delete')
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
    @include('partials.message-modal',['id'=>'history-modal','title'=>'History','max_width'=>750])
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script type="text/javascript">
    </script>
@endpush