@extends('layouts.app')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Personnel Credentials Details</h3>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Credentials > View Credential > View Personnel Credential Details</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-8">

           
            </div>
            <div class="col-md-4 text-right">
                @include('partials.back-button')
            </div>
        </div>
      
        <form id="add" novalidate>
            <div class="record-container">
                <div class="col-12 col-xl-8 m-auto">
                    <div id="table-content">
                        @include('partials/loading-table')
                    </div>
                </div>
            </div>
            <br>
        </form>
    </div>
@endsection



