@extends('layouts.app',['title'=>$title])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Award Credential</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <span class="segoe-ui-italic">Credentials > View Credential > Award Credential</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Certification ID:'=>$certification->prefix_id,'Certification Title:'=>$certification->title]])
            </div>
            <div class="col-md-6 text-right">
                @include('partials.back-button')
            </div>
        </div>
        <form id="add">
            <div class="card">
                <div class="card-body">
                    <div class="col-12 col-md-8 m-auto">
                        <div class="row form-group">
                            <div class="col-md-6 mb-3">
                                <select class="form-control firefighter-select2" data-live-search="true"></select>
                            </div>
                            <div class="col-md-6">
                                <select name="organization" class="form-control organizations-select2" data-live-search="true"></select>
                                <div id="organization" class="invalid-feedback"></div>
                            </div>
                            <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="notification" name="send_email" value="1">
                                    <label class="custom-control-label" for="notification">Send email notification</label>
                                </div>
                            </div>
                        </div>
                        <div id="table-content">
                            <table class="table table-hover app-table text-center mb-0">
                                <thead>
                                <tr>
                                    <th>DFSID</th>
                                    <th>Personnel Name</th>
                                    <th>Eligibility</th>
                                    <th>Awarded</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button id="submit-btn" type="submit" class="btn btn-primary submit-btn btn-wd btn-lg mr-3" disabled>Award</button>
                <a href="<?php echo route('certification.index') ?>" class="btn btn-secondary btn-wd btn-lg submit-btn cancel">Cancel</a>
            </div>
        </form>
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
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>

        $(document).ready(function () {
            let firefighter_select2 = $(".firefighter-select2");
            firefighter_select2.select2({
                minimumInputLength: 2,
                placeholder: 'Search Personnel',
                ajax: {
                    url: '{{ route('certification.search-firefighter') }}',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function (search) {

                        let ids_not_in = [];
                        let input_fields = $('[name*=firefighter_id]');
                        if(input_fields.length){
                            for(let i=0; i<input_fields.length; i++){
                                ids_not_in.push(input_fields[i].value)
                            }
                        }

                        return {
                            search: search.term,
                            certification_id: '{{ $certification->id }}',
                            ids_not_in: ids_not_in,
                        };
                    },
                    processResults: function (firefighters) {
                        return {
                            results: $.map(firefighters, function (firefighter) {
                                return {
                                    text: `${firefighter.name} (${firefighter.prefix_id})`,
                                    id: firefighter.id,
                                    prefix_id: firefighter.prefix_id,
                                    name: firefighter.name,
                                    eligibility: firefighter.eligibility,
                                    awarded: firefighter.awarded,
                                }
                            })
                        };
                    },
                }
            });

            firefighter_select2.on('select2:select',function (e) {
                let data = e.params.data;
                if($(document).find(`#firefighter-${data.id}`).length){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Duplicate entry',
                        text: `Already added Firefighter #${data.prefix_id}.`,
                    });
                    firefighter_select2.empty();
                    return false;
                }
                eligibility = data.eligibility ? 'eligible' : 'not eligible';
                eligibility_class = data.eligibility ? 'text-success' : 'text-danger';
                awarded =  data.awarded ? 'Yes' : 'No';
                let html = `
                        <tr id='row-${data.id}'>
                            <td>
                            <input id='firefighter-${data.id}' type="hidden" name='firefighter_id[]' value='${data.id}'>
                            ${data.prefix_id}</td>
                            <td>${data.name}</td>
                            <td class='${eligibility_class} text-capitalize'>${eligibility}</td>
                            <td>${awarded}</td>
                            <td><a href='javascript:void(0)' class='remove-row' data-row='#row-${data.id}'><span class="material-icons">delete_outline</span></a></td>
                        </tr>
                      `;
                $('#table-content').find('tbody').append(html);
                firefighter_select2.empty();
                $('#submit-btn').prop('disabled',false);
            })
        });

        $(document).find(".organizations-select2").select2({
            minimumInputLength: 2,
            placeholder: 'Search Organization',
            ajax: {
                url: '{{ route('certification.search-organization') }}',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function (search) {
                    return {
                        search: search.term
                    };
                },
                processResults: function (organizations) {
                    return {
                        results: $.map(organizations, function (organization) {
                            return {
                                text: organization.name+' '+`(${organization.prefix_id})`,
                                id: organization.id
                            }
                        })
                    };
                }
            }
        });


        $(document).on('click','.remove-row',function () {
            $($(this).data('row')).remove();
            if($('[name*=firefighter_id]').length){
                $('#submit-btn').prop('disabled',false);
            }else{
            }
        });

        $('#add').on('submit', function (e) {
            e.preventDefault();
            let submit_btn = $('.submit-btn');
            submit_btn.prop('disabled', true);
            axios.post("{{ route('certification.award-firefighters',$certification->id) }}",$(this).serialize()).then((response)=>{
                if(response.data.status){
                    $('#table-content').find('tbody').html('');
                    $(".organizations-select2").empty();
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
                submit_btn.prop('disabled', response.data.status);
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