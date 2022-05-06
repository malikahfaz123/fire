

<table class="table table-hover app-table text-center mb-0" id="cert-table">
    <thead>
        <tr>
            <th></th>
            <th>Credential Code</th>
            <th>Name</th>
            <th>GEO Sent</th>
            <th>Receiving Date</th>
            <th>Issue Date</th>
            <th>Lapse Date</th>
            <th>TY</th>
            <th>History</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($awarded_certificates && $awarded_certificates->count())
        @foreach($awarded_certificates as $awarded_certificate)
            @php
                $total_admin_ceu = \App\Http\Helpers\FirefighterHelper::isEligible($awarded_certificate)->total_admin_ceus;
                $total_tech_ceu = \App\Http\Helpers\FirefighterHelper::isEligible($awarded_certificate)->total_tech_ceus;
                $req_admin_ceu = \App\Http\Helpers\FirefighterHelper::getCertificateCeus($awarded_certificate)->admin_ceu;
                $req_tech_ceu = \App\Http\Helpers\FirefighterHelper::getCertificateCeus($awarded_certificate)->tech_ceu;
            @endphp
            @if($awarded_certificate->email != Auth::user()->email)
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input certificate-checkbox" name="certifications_ids[]" type="checkbox" value="{{ $awarded_certificate->id }}">
                        <label class="form-check-label" for="certificate-{{ $awarded_certificate->id }}"></label>
                    </div>
                </td>
                <td>{{ $awarded_certificate->prefix_id }}</td>
                <td class="text-capitalize">{{ $awarded_certificate->title }}</td>
                <td class="text-capitalize">{{ $awarded_certificate->organization->name }}</td>
                <td>{{ $awarded_certificate->receiving_date ? \App\Http\Helpers\Helper::date_format($awarded_certificate->receiving_date) : 'N/A' }}</td>
                <td>{{ \App\Http\Helpers\Helper::date_format($awarded_certificate->issue_date) }}</td>
                <td id ="lap">{{ $awarded_certificate->lapse_date ? \App\Http\Helpers\Helper::date_format($awarded_certificate->lapse_date) : 'N/A' }}</td>
                <td class="text-capitalize">{{ $awarded_certificate->stage }}</td>
                <td>
                    @if($awarded_certificate->renewable && date('Y-m-d') > $awarded_certificate->lapse_date )
                        Y
                    @else
                        ---
                    @endif
                </td>
                <td>
                    @if($awarded_certificate->renewable && date('Y-m-d') > $awarded_certificate->lapse_date)
                        @if($total_admin_ceu >= $req_admin_ceu && $total_tech_ceu >= $req_tech_ceu)
                            <span class="badge badge-success">Eligible</span>
                        @else
                            <span class="badge badge-warning showEligibilityDetails" data-comp_admin_ceu="{{ $total_admin_ceu ?? '0' }}" data-comp_tech_ceu="{{ $total_tech_ceu ?? '0' }}" data-req_admin_ceu="{{ $req_admin_ceu }}" data-req_tech_ceu="{{ $req_tech_ceu }}" data-certificate_name="{{ $awarded_certificate->title }}" data-from_date="{{ \App\Http\Helpers\Helper::date_format($awarded_certificate->renewed_expiry_date != $awarded_certificate->certification_cycle_end ? $awarded_certificate->certification_cycle_end : $awarded_certificate->certification_cycle_start) }}" data-to_date="{{ \App\Http\Helpers\Helper::date_format($awarded_certificate->renewed_expiry_date != $awarded_certificate->certification_cycle_end ? $awarded_certificate->renewed_expiry_date : $awarded_certificate->certification_cycle_end) }}">Not Eligible</span>
                        @endif
                    @else
                        ---
                    @endif
                </td>
                <td>
                    <a href="{{ route('firefighter.view-certification',[$awarded_certificate->firefighter_id,$awarded_certificate->id]) }}" target="_blank" title="View"><span class="material-icons">visibility</span></a>
                    @if(\App\Http\Helpers\Helper::certification_history_count($awarded_certificate->firefighter_id,$awarded_certificate->certificate_id) > 1)
                        <a href="{{ route('firefighter.certifications-past-records',[$awarded_certificate->firefighter_id,$awarded_certificate->certificate_id]) }}" title="Certification History"><span class="material-icons">history</span></a>
                    @endif
                    @can('firefighters.update')
                        @if($awarded_certificate->renewable && date('Y-m-d') > $awarded_certificate->lapse_date)
                            @if($total_admin_ceu >= $req_admin_ceu && $total_tech_ceu >= $req_tech_ceu)
                                <a href="javascript:void(0)" class="renew-certificate"  data-id="{{ $awarded_certificate->id }}" title="Renew"><span class="material-icons">autorenew</span></a>
                            @endif
                        @endif
                    @endcan

                    <div class="container mt-2">
  
  <!-- Input field to accept user input -->
 <input type="hidden" name="name" value="{{ $awarded_certificate->id }}"
      id="name">

  <!-- Button to invoke the modal -->
  <a href="javascript:void(0)" id="edit-post" data-id="{{ $awarded_certificate->id }}" class="btn btn-info">Edit</a>

  <!-- Modal -->
  



<div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="postCrudModal"></h4>
    </div>
    <div class="modal-body">
        <form id="postForm" name="postForm" class="form-horizontal">
            @csrf
           <input type="hidden" name="post_id" id="post_id">
            <div class="form-group">
                <!-- <label for="name" class="col-sm-2 control-label">Lapse-Date</label> -->
                <div class="col-sm-12">
                    <input type="date" class="form-control datepicker" id="lapse" name="lapse"autocomplete="off" required="">
                </div>
            </div>
 
        
            <div class="col-sm-offset-2 col-sm-10">
             <button type="submit" class="btn btn-primary btn-submit" id="btn-save" value="create">Save
             </button>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        
    </div>
</div>
</div>
</div>


<!-- 
<script>
 $.noConflict();
jQuery(document).ready(function(){
    jQuery('.datepicker').datepicker({ 

startDate: new Date()

});
});
  </script> -->


<script type="text/javascript">


  


    $=jQuery;

 $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // for add modal 
    $('#create-new-post').click(function () {
        $('#btn-save').val("create-post");
        $('#postForm').trigger("reset");
        $('#postCrudModal').html("Add New post");
        $('#ajax-crud-modal').modal('show');

    
    });

   
     // end for add modal
 


 // edit
     $('body').on('click', '#edit-post', function () {
      var post_id = $(this).data('id');
      $.get('lapse/'+post_id+'/edit', function (data) {
         $('#postCrudModal').html("Edit Lapse Date");
          $('#btn-save').val("edit-post");
          $('#ajax-crud-modal').modal('show');
          $('#post_id').val(data.id); 
      })
//       $('#lapse').datepicker({ 

//          startDate: new Date()

// });
   });



//    if ($("#postForm").length > 0) {
//     var data = $('#postForm').serialize();
//     $.get("lapse", data, function( response ){
//         console.log(response); 
//       })
//    };

    // end edit

  
  });
 
  $(".btn-submit").click(function(e){
  
  e.preventDefault();

  var post_id = $("input[name=post_id]").val();
  var lapse = $("input[name=lapse]").val();
 

  $.ajax({
     type:'post',
     url:"{{ route('lapse-date.store') }}",
     data:{post_id:post_id, lapse:lapse},
     success:function(result){
        console.log(result);
        if(result){
                    Toast.fire({
                        icon: 'success',
                        title: 'Lapse-Date updated sucessfully',
                    });
                    

                    $('#ajax-crud-modal').modal('hide');
                    $('#lap').html(result.lapse_date);
                    $("#add").submit();
                 

                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: result,
                    });
                }
     }
  });

});



//  if ($("#postForm").length > 0) {
//       $("#postForm").validate({
 
//      submitHandler: function(form) {
//       var actionType = $('#btn-save').val();
//       $('#btn-save').html('Sending..');
      
//       $.ajax({
//            data: $('#postForm').serialize(),
//           url: "{{ route('lapse-date.store') }}",
//           type: "POST",
//           dataType: 'json',
//           success: function (data) {
//             var post = '<td>' + data.id + '</td>'
               
              
//               if (actionType == "create-post") {
//                   $('#posts-crud').prepend(post);
//               } else {
//                   $("#post_id_" + data.id).replaceWith(post);
//               }
 
//               $('#postForm').trigger("reset");
//               $('#ajax-crud-modal').modal('hide');
//               $('#btn-save').html('Save Changes');
              
//           },
//           error: function (data) {
//               console.log('Error:', data);
//               $('#btn-save').html('Save Changes');
//           }
//       });
//     }
//   })
// }
   
  
</script>


  


   



                </td>
            </tr>
            @endif
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $awarded_certificates->links('partials.pagination') }}
</div>


