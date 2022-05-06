@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3>Instructor levels</h3>
        </div>
        <div class="col-12 text-right mb-3">
            @can('firefighters.create')
                <a href="{{ route('instructor-level.create') }}" class="btn btn-primary btn-wd"><span class="material-icons">add</span>Add level</a>
            @endcan
        </div>
        <div class="filter-container">
            <div class="mb-3">
                <h5>Data Filters</h5>
            </div>
            <form id="filter" action="#" novalidate>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group selectpicker-custom-style">
                            <label for="instructor_level">Instructor Level</label>
                            <select id="instructor_level" class="form-control selectpicker" name="instructor_level">
                                <option value="">Any</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
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
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        let loading;
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('instructor-level.paginate') }}?${form}&page=${page}`;
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

        $('.add-facility-type').on('click',function (e) {
            e.preventDefault();
            reset_add_form();
            $('#add-modal').modal('show');
        });

        function reset_add_form(){
            let add = $('#add');
            add.trigger('reset');
            add.find('.invalid-feedback').text('');
            add.find('.is-invalid').removeClass('is-invalid');
        }

        function reset_edit_form(){
            let edit = $('#edit');
            edit.trigger('reset');
            edit.find('.invalid-feedback').text('');
            edit.find('.is-invalid').removeClass('is-invalid');
        }

        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');
            axios.post("{{ route('instructor-level.store') }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    reload_current_page();
                    reset_add_form();
                    $('#add-modal').modal('hide');
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
                }
            })
        });

        $(document).on('click','.edit',function (e) {
            e.preventDefault();
            reset_edit_form();
            let id = $(this).data('edit');
            axios.get("{{ route('instructor-level.index') }}/"+id).then((response)=>{
                console.log(response)
                if(response.data.status){
                    let edit = $('#edit');
                    edit.find('input[name=id]').val(response.data.facility_type.id);
                    edit.find('input[name=description_1]').val(response.data.facility_type.description);
                    $('#edit-modal').modal('show');
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: response.data.msg,
                    });
                }
            });
            $('#edit-modal').modal('show');
        });

        function reload_current_page(){
            let url,page = 1;
            if($(document).find('.page-item.active .page-link').length){
                page = parseInt($(document).find('.page-item.active .page-link').text());
            }
            req = $('#filter').serialize();
            url = `{{ route('instructor-level.paginate') }}?${req}&page=${page}`;
            load_records(page,url);
        }

        /*============================
                DELETE
        *=============================*/
        $(document).on('click','.delete',function (e) {
            e.preventDefault();
            let id = $(this).data('delete');
            let modal = $('#delete-modal');
            modal.modal('show');
            modal.find('[name=delete]').val(id);
        });
        $('#delete-form').on('submit',function (e) {
            e.preventDefault();
            let submit_btn = $(this).find('[type=submit]');
            submit_btn.prop('disabled', true);
            submit_btn.addClass('disabled');

            axios.delete("{{ route('instructor-level.index') }}/"+$('[name=delete]').val(),$(this).serialize()).then((response)=>{
                if(response.data.status){
                    Toast.fire({
                        icon: 'success',
                        title: response.data.msg
                    });
                    reload_current_page();
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: response.data.msg,
                    });
                }
                $('#delete-modal').modal('hide');
                submit_btn.prop('disabled', false);
                submit_btn.removeClass('disabled');
            })
        });
    </script>
@endpush