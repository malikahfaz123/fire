@extends('layouts.firefighters-app',['title'=> $title])
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
                <span class="segoe-ui-italic">All Credentials > View Credential</span>
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

        <div class="text-right mt-3 mb-3"></div>

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
                        <div class="col-md-6 {{ $certificate->renewable != '0' ? '' : 'd-none' }} ">
                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th width="170">
                                        <label>No. of Credit type</label>
                                    </th>
                                    <td>
                                        <div class="show-field">{{ $certificate->no_of_credit_types }}</div>
                                    </td>
                                </tr>
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
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Pre-requisites Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th width="170"><label>No. of Pre-requisite Credential Titles</label></th>
                                    <td>
                                        <div class="show-field">{{ $certificate->no_of_pre_req_cert != '' ? $certificate->no_of_pre_req_cert : 'N/A' }}</div>
                                    </td>
                                </tr>
                            </table>
                            <div class="show-field {{ $certificate->no_of_pre_req_cert != '' ? '' : 'd-none' }} ">
                                <table class="table table-borderless w-100 mb-0">
                                    <tr>
                                        <th width="170">
                                            <label>No. of Pre-requisite Credential Titles</label>
                                        </th>
                                        <td>
                                            @if($prereq_certificates)
                                                {!! implode(',<br>',$prereq_certificates) !!}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless w-100 mb-0">
                                <tr>
                                    <th width="170"><label>No. of Pre-requisite Course(s)</label></th>
                                    <td>
                                        <div class="show-field">{{ $certificate->no_of_pre_req_course != '' ? $certificate->no_of_pre_req_course : 'N/A' }}</div>
                                    </td>
                                </tr>
                            </table>
                            <div class="show-field">
                                <table class="table table-borderless w-100 mb-0 {{ $certificate->no_of_pre_req_course != '' ? '' : 'd-none' }} ">
                                    <tr>
                                        <th width="170">
                                            <label>Pre-requisite Course(s)</label>
                                        </th>
                                        <td>
                                            @if($prereq_courses)
                                                {!! implode(',<br>',$prereq_courses) !!}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                </table>
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
    <script type="text/javascript"></script>
@endpush