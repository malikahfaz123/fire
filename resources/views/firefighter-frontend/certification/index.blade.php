@extends('layouts.firefighters-app',['title'=> $title])
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Credentials</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Total Credential:'=> $certifications->count]])
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
                            <label for="id">Credential Code</label>
                            <input type="search" name="prefix_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Credential Name</label>
                            <input type="search" name="title" class="form-control">
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
            <div class="col-12 col-xl-7 m-auto">
                <div id="table-content">
                    @include('partials/loading-table')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    <div id="apply-certificate-modal" tabindex="1" role="dialog" aria-labelledby="apply-course-modal-title" aria-hidden="true" class="modal fade">
        <div role="document" class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="add" >
                    @csrf

                    <div class="modal-header"><h3 id="" class="modal-title cambria-bold ml-auto">Application Form </h3>
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
                                                    <label>Credential ID</label>
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
    <script>
        let loading;
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('firefighters.certification.paginate') }}?${form}&page=${page}`;
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
            url = `{{ route('firefighters.certification.paginate') }}?${req}&page=${page}`;
            load_records(page,url);
        }

        $(document).on('click','.apply',function (e) {
            e.preventDefault();

            let modal = $('#apply-certificate-modal');
            let certification_id    =  $(this).data('certification_id');
            let certification_prefix_id  =  $(this).data('certification_prefix_id');
            let certification_title      =  $(this).data('certification_title');
            let firefighter_id      =  $(this).data('firefighter_id');

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

        axios.post("{{ route('firefighters.apply.certificates') }}",$(this).serialize()).then((response)=>{
            if(response.data.status){

                $('#apply-course-modal').modal('hide');

                Toast.fire({
                    icon: 'success',
                    title: response.data.msg
                });
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