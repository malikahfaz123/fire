<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="type[]" id="bulk-checkbox" value="1">
                    <label class="custom-control-label" for="bulk-checkbox"></label>
                </div>
            </th>
            <th>Prefix ID</th>
            <th>Semester</th>
            <th>Course Name</th>
            <th>Firefighter Name</th>
            <th colspan="2">Action</th>
        </tr>
    </thead>
    <tbody>
    @if($view_firefighters && $view_firefighters->count())
        @foreach($view_firefighters as $view_firefighter)
            <tr>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="firefighter[{{$view_firefighter->id}}]" id="firefighter-{{$view_firefighter->prefix_id}}" value="1">
                        <label class="custom-control-label" for="firefighter-{{$view_firefighter->prefix_id}}"></label>
                    </div>
                </td>
                <td class="text-capitalize"> {{ $view_firefighter->prefix_id }} </td>
                <td class="text-capitalize"> {{ $view_firefighter->semester.' '.$view_firefighter->year }} </td>
                <td class="text-capitalize"> {{ $view_firefighter->course_name }} </td>
                <td class="text-capitalize"> {{ $view_firefighter->f_name.' '.$view_firefighter->m_name.' '.$view_firefighter->l_name }}</td>
                <td class="text-capitalize">
                    <select data-id={{$view_firefighter->id}} name="status[{{ $view_firefighter->id }}]" data-firefighters_id="{{$view_firefighter->firefighters_id}}" data-toggle="#firefighter-{{$view_firefighter->prefix_id}}"  class="form-control text-center" {{ !empty($view_firefighter->status) && $view_firefighter->status == 'enrolled' ? 'disabled' : '' }}>
                        <option value="applied"  {{ !empty($view_firefighter->status) && $view_firefighter->status == 'applied'  ? 'selected' : '' }} disabled>Applied</option>
                        <option value="enrolled" {{ !empty($view_firefighter->status) && $view_firefighter->status == 'enrolled' ? 'selected' : '' }} >Enrolled</option>
                        <option  value="rejected" {{ !empty($view_firefighter->status) && $view_firefighter->status == 'rejected' ? 'selected' : '' }} >Rejected</option>
                    </select>
                </td>
                <td class="text-capitalize" valign="middle">
                    @if(!empty($view_firefighter->reason) &&  ($view_firefighter->status == 'rejected') )
                        <a href="" id="view-reason" data-viewreason="{{$view_firefighter->reason}}" class="edit " title="View"><span class="material-icons">visibility</span></a>
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