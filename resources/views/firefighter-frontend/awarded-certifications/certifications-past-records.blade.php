@extends('layouts.firefighters-app',[ 'title' => $title ])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Awarded Credentials </h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic"> Awarded Credentials > Credential History</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-9">
                @include('partials.meta-box',['labels'=>['Total Records:'=> $awarded_certificates->count]])
                @include('partials.meta-box',['labels'=>['Credential Code:'=> $certification->prefix_id,'Credential Name:'=>$certification->title],'bg_class'=>'bg-gradient-dark','icon'=>'stars'])
            </div>
            <div class="col-md-3 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <div class="record-container">
            <div id="table-content">
                @include('partials/loading-table')
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>

        let loading, selectpicker = $(".selectpicker");
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('firefighters.paginate-certifications-past-records',$certification->id) }}?${form}&page=${page}`;
            axios.get(url).then((response)=>{
                $('#table-content').html(response.data);
            })
        }

        document.addEventListener("DOMContentLoaded", ()=>{
            loading = $('#table-content').html();
            load_records(1);
        });

        $(document).on('click','.page-item:not(.active) .page-link',function (e) {
            e.preventDefault();
            let href = $(this).prop('href');
            load_records(null,href);
        });

        function reload_current_page(){
            let url,page = 1;
            if($(document).find('.page-item.active .page-link').length){
                page = parseInt($(document).find('.page-item.active .page-link').text());
            }
            req = $('#filter').serialize();
            url = `{{ route('firefighters.paginate-certifications-past-records',$certification->id ) }}?${req}&page=${page}`;
            load_records(page,url);
        }
    </script>
@endpush