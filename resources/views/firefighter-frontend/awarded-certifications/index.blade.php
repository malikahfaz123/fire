@extends('layouts.firefighters-app',['title' => $title ])
@push('head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
@endpush
@section('content')
    <div class="pl-3">
        <div class="page-title mb-4">
            <h3> Awarded Credentials</h3>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                @include('partials.meta-box',['labels'=>['Awarded Credentials:'=> $awarded_certificates->count]])
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
                            <label for="type">Receiving Date</label>
                            <input type="date" name="receiving_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Issue Date</label>
                            <input type="date" name="issue_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Lapse Date</label>
                            <input type="date" name="lapse_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-info btn-wd"><span class="material-icons">filter_alt</span> Filter</button>
                        <button id="clear" type="reset" class="btn btn-wd"><span class="material-icons">refresh</span> Reset</button>

                        <button type="button" id="historyy" class="btn btn-primary">
    View All Credential History
  </button>
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

    <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Credential History</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-bodyy">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <!-- <tr>
                <th>Certificate</th>
                <th>Operation</th>
                <th>Date</th>
                
            </tr> -->
        </thead>
        <tbody>
      
        <tr>
            <td id = "mod"></td>
</tr>

      

           
            
            
        </tbody>
        <tfoot>
            <!-- <tr>
            <th>Certificate</th>
                <th>Operation</th>
                <th>Date</th>
            </tr> -->
        </tfoot>
    </table>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger closer" data-dismiss="modal">Close</button>
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
    <div id="archive-modal" tabindex="1" role="dialog" aria-labelledby="archive-modal-title" aria-hidden="true"
         class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form id="archive-form" novalidate>
                    @csrf
                    <input type="hidden" name="archive">
                    <div class="modal-header"><h5 id="archive-modal-title" class="modal-title cambria-bold">Notice</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div id="archive-modal-content" class="modal-body">Are you sure you want to unarchive this record ?</div>
                    <div class="modal-footer">
                        <button type="submit" id="archive-form-btn" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary submit-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('partials.message-modal',['id'=>'message-modal','title'=>'Notice'])
    <div id="renew-cert-modal" tabindex="1" role="dialog" aria-labelledby="renew-cert-modal-title" aria-hidden="true"
         class="modal fade">
        <div role="document" class="modal-dialog">
            <form id="renew-cert" class="modal-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="id">
                <div class="modal-header"><h5 id="renew-cert-modal-title" class="modal-title cambria-bold">Renew Credential</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div id="renew-cert-modal-content" class="modal-body">
                    <p>Are you sure you want to renew this credential ?</p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="notification" name="send_email" value="1">
                        <label class="form-check-label" for="notification">Send email notification</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit-btn"><span class="material-icons loader rotate mr-1">autorenew</span> Confirm</button>
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script> 


$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '.closer', function () {
    $('#mod').empty();
    });
    $(document).click((event) => {
        $('#mod').empty();      
});
 // edit
    $('body').on('click', '#historyy', function () {
    

 $.ajax({
    type:'POST',
    url:"{{ route('ajaxRequest.post') }}",
    success:function(data){
       console.log(data);
       
       $('.modal-body').html(data);
       $('#mod').append(`<th>Credential</th>`);
       $('#mod').append(`<th>Action</th>`);
       $('#mod').append(`<th>Date</th>`);
       $.each(data, function( index, value ) {
       
         $('#mod').append(`<tr>`);
     $('#mod').append(`<td>${value.certificate.title}</td>`);
     $('#mod').append(`<td>${value.operation}</td>`);
     $('#mod').append(`<td>${value.date}</td>`);
 $('#mod').append(`</tr>`);
 

         +'</br>'+
              
         
         // Display Modal
            $('#myModal').modal('show');
                                                                      
                                  });
                                  



    }

   
 });

   });

    // end edit
  });



    </script>













@endsection

@push('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>

        let loading, selectpicker = $(".selectpicker");
        function load_records(page, url){
            $('#table-content').html(loading);
            let form = $('#filter').serialize();
            url = url ? url : `{{ route('firefighters.awarded-certificates.paginate') }}?${form}&page=${page}`;
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
            url = `{{ route('firefighters.awarded-certificates.paginate') }}?${req}&page=${page}`;
            load_records(page,url);
        }
    </script>
@endpush