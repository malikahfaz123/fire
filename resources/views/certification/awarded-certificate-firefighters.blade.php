@extends('layouts.app', ['title' => $title])

@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Expired Awarded Credential Personnels</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels' => ['Total Credential Personnel:' => $awardedCertPersCount->count, 'Cred. Name:' => ucfirst($awardedCertPersCount->title) ]])
            </div>
            @can('certifications.create')
                <div class="col-md-6 text-right">
                    <a href="{{ route('certification.show',request()->segment(2)) }}" class="btn bg-white text-secondary btn-wd"><span class="material-icons">keyboard_backspace</span> Back</a>
                </div>
            @endcan
        </div>
        <div class="filter-container">
            <div class="mb-3">
                <h5>Data Filters</h5>
            </div>
            <form id="filter" action="#" novalidate>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dfsid">DFSID</label>
                            <input type="search" name="dfsid" class="form-control" id="dfsid">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="firefighter_name">Personnel Name</label>
                            <input type="search" name="firefighter_name" class="form-control" id="firefighter_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input id="search" type="search" class="form-control" name="search">
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
            <div id="table-content">
                @include('partials/loading-table')
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        let loading;
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('certification.paginate_awarded_certificate_personnel') }}?${form}&page=${page}&certificateId={{$currentCredId}}`;
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
            url = `{{ route('certification.paginate_awarded_certificate_personnel') }}?${req}&page=${page}&certificateId={{$currentCredId}}`;
            load_records(page,url);
        }
    </script>
@endpush
