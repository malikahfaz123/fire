@extends('layouts.app',['title'=>$title])
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Renewal of Credentials</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Dashboard > Renewal of Credentials</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Total Classes:'=> $certifications->count()]])
            </div>
            <div class="col-md-6 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <div class="filter-container">
            <div class="mb-3">
                <h5>Data Filters</h5>
            </div>
            <form id="filter" action="#" novalidate>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group selectpicker-custom-style">
                            <label for="name">Name</label>
                            <input type="search" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-info btn-wd"><span class="material-icons">filter_alt</span> Filter</button>
                        <button id="clear" type="reset" class="btn btn-wd"><span class="material-icons">refresh</span> Reset</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="record-container">
            <div class="col-md-11 m-auto">
                <div id="table-content">
                    @include('partials/loading-table')
                </div>
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
            url = url ? url : `{{ route('dashboard.paginate-renewal-certifications') }}?${form}&page=${page}`;
            axios.get(url).then((response)=>{
                $('#table-content').html(response.data);
            })
        }

        document.addEventListener("DOMContentLoaded", ()=>{
            loading = $('#table-content').html();
            load_records(1);
        });

        document.getElementById('clear').addEventListener('click',function(){
            document.getElementById("filter").reset();
            selectpicker.val('default');
            selectpicker.selectpicker("refresh");
            load_records(1);
        });

        document.getElementById("filter").addEventListener('submit',(e)=>{
            e.preventDefault();
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
            url = `{{ route('dashboard.paginate-renewal-certifications') }}?${req}&page=${page}`;
            load_records(page,url);
        }

    </script>
@endpush