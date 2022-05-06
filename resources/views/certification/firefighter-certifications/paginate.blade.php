<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>DFSID</th>
            <th>Credential Name</th>
            <th>Personnel Name</th>
            <th >Test Status</th>
            <th colspan="2">Action</th>
        </tr>
    </thead>
    <tbody>
    @if($view_firefighters && $view_firefighters->count())
        @foreach($view_firefighters as $view_firefighter)
            <tr>
                <td class="text-capitalize"> {{ $view_firefighter->firefighter_prefix_id }} </td>
                <td class="text-capitalize"> {{ $view_firefighter->title }} </td>
                <td class="text-capitalize"> {{ $view_firefighter->f_name.' '.$view_firefighter->m_name.' '.$view_firefighter->l_name }}</td>
                <td class="text-capitalize">
                    @if(!empty($view_firefighter->test_result) &&  ($view_firefighter->test_result == 'passed') )
                        <span class="text-success">{{ $view_firefighter->test_result }} </span>

                    @elseif(!empty($view_firefighter->test_result) &&  ($view_firefighter->test_result == 'failed') )
                        <span class="text-danger">{{ $view_firefighter->test_result }} </span>

                    @elseif(!empty($view_firefighter->test_result) &&  ($view_firefighter->test_result == 'none') )
                        <span class="text-dark">{{ $view_firefighter->test_result }} </span>

                    @else
                        N/A
                    @endif
                </td>
                <td class="text-capitalize">
                    @if($view_firefighter->email != Auth::user()->email)
                    <select id="status" data-id={{ $view_firefighter->id }} name="status[{{ $view_firefighter->id }}]"  data-toggle="#firefighter-{{$view_firefighter->id}}" class="form-control text-center" data-certificate_id={{ $view_firefighter->certificate_id }} data-certification_prefix_id={{ $view_firefighter->prefix_id }} data-certification_title="{{ $view_firefighter->title  }}" data-certification_firefighter_id="{{ $view_firefighter->firefighter_id  }}" data-certification_firefighter_name="{{ $view_firefighter->f_name.' '.$view_firefighter->m_name.' '.$view_firefighter->l_name }}" {{ !empty($view_firefighter->status) && $view_firefighter->status == 'accepted' || $view_firefighter->status == 'rejected' ? 'disabled' : '' }} >
                        <option value="applied"  {{ !empty($view_firefighter->status) && $view_firefighter->status == 'applied'  ? 'selected' : '' }} >Applied</option>
                        <option value="accepted" {{ !empty($view_firefighter->status) && $view_firefighter->status == 'accepted' ? 'selected' : '' }} >Accepted</option>
                        <option value="rejected" {{ !empty($view_firefighter->status) && $view_firefighter->status == 'rejected' ? 'selected' : '' }} >Rejected</option>
                    </select>
                    @endif
                </td>

                <td class="text-capitalize" valign="middle">
                    @if(!empty($view_firefighter->reason) &&  ($view_firefighter->status == 'rejected') )
                        <a href="" id="view-reason" data-viewreason="{{$view_firefighter->reason}}" class="edit " title="View"><span class="material-icons">visibility</span></a>
                    @endif

                    @if(!empty($view_firefighter->test_status) &&  ($view_firefighter->status == 'accepted') )
                        <a href="{{ route('certificate.view-firefighters.status.index',$view_firefighter->id) }}" class="edit " title="View"><span class="material-icons">visibility</span></a>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $view_firefighters->links('partials.pagination') }}
</div>
