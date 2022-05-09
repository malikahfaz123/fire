@extends('layouts.app',[ 'title' => $title ])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3> Credit Types Courses</h3>
            <div class="row mb-4">
                <div class="col-md-6">
                    <span class="segoe-ui-italic"> Training Details >  Credit Types Courses</span>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Total Count:'=> $courses->count ]])
            </div>
        </div>

        <div class="filter-container">
            <div class="mb-3">
                <h5>Data Filters</h5>
            </div>
            <form id="filter" action="#" novalidate>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id">Credit Types ID</label>
                            <input type="search" name="prefix_id" class="form-control">
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="form-group selectpicker-custom-style">
                            <label for="type">Course Name</label>
                            <input type="search" name="course_name" class="form-control">
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Credit Types Description</label>
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
            <div class="col-12 col-xl-7 m-auto">
                <div id="table-content">
                    @include('partials/loading-table')
                </div>
            </div>
        </div>
    </div>
@endsection


@section('modals')

    <div id="reason-modal" tabindex="1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true"
         class="modal fade">
        <div role="document" class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header"><h5 id="delete-modal-title" class="modal-title cambria-bold">View Courses</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body" id="modal-body">
                </div>
            
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        let loading;
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('courses-credit-types.paginate') }}?${form}&page=${page}`;
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
            $(".selectpicker").selectpicker("refresh");
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
            url = `{{ route('courses-credit-types.paginate') }}?${req}&page=${page}`;
            load_records(page,url);
        }


        $(document).on('click','#view-reason',function (e) {
            e.preventDefault();
            let modal = $('#reason-modal');
            reason = $(this).data('viewreason');
            url = "{{ route('view-courses-credit-types.index', '') }}/"+reason;

            // console.log(url);
 


            axios.get(url).then((response)=>{
                $('#modal-body').html(response.data);
            })


            // modal.find('[id=show-view-reason]').html(reason);
            modal.modal('show');
        });
   
    </script>
@endpush