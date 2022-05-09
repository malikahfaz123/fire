@extends('layouts.firefighters-app')
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Tomorrow's Classes</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Dashboard > Tomorrow's Classes</span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Start Date:'=>\App\Http\Helpers\Helper::date_format($start_date),'Total Classes:'=> $classes->count]])
            </div>
            <div class="col-md-6 text-right">
                @include('partials.back-button')
            </div>
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
    <script>
        let loading;
        function load_records(page, url){
            $('#table-content').html(loading);
            url = url ? url : `{{ route('firefighters.tomorrow-classes.paginate') }}?page=${page}`;
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

    </script>
@endpush