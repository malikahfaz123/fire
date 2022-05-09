<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Credential Code</th>
            <th>Credential Name</th>
            <th>Personnel Name</th>
            <th>Test Date</th>
            <th>Test Time</th>
            <th>Test Status</th>
        </tr>
    </thead>
    <tbody>
    @if($view_firefighters && $view_firefighters->count())
        @foreach($view_firefighters as $view_firefighter)
            <tr>
                <td class="text-capitalize"> {{ $view_firefighter->prefix_id }} </td>
                <td class="text-capitalize"> {{ $view_firefighter->title }} </td>
                <td class="text-capitalize"> {{ $view_firefighter->f_name.' '.$view_firefighter->m_name.' '.$view_firefighter->l_name }}</td>
                <td class="text-capitalize"> {{ \App\Http\Helpers\Helper::date_format($view_firefighter->certificate_statuses_test_date) }}</td>
                <td class="text-capitalize"> {{ $view_firefighter->certificate_statuses_test_time }} </td>
                <td>
                    <select class="form-control" name="status" data-toggle="#firefighter-{{$view_firefighter->certificate_statuses_id}}" data-certification_id="{{ $view_firefighter->certification_id }}" data-certification_status_id={{ $view_firefighter->certificate_statuses_id }} data-certificate_statuses_firefighter_certificates_id={{ $view_firefighter->certificate_statuses_firefighter_certificates_id }} data-certification_status_firefighter_name="{{ $view_firefighter->f_name.' '.$view_firefighter->m_name.' '.$view_firefighter->l_name }}" data-certification_status_prefix_id={{ $view_firefighter->prefix_id }} data-certification_status_title="{{ $view_firefighter->title }}"  data-certificate_statuses_firefighter_certificates_id="{{ $view_firefighter->certificate_statuses_firefighter_certificates_id  }}" data-certificate_statuses_firefighter_id="{{ $view_firefighter->certificate_statuses_firefighter_id  }}" {{ !empty($view_firefighter->certificate_statuses_test_date) &&  date('Y-m-d') <= $view_firefighter->certificate_statuses_test_date  ? 'disabled' : '' }} {{ !empty($view_firefighter->certificate_statuses_status) && $view_firefighter->certificate_statuses_status == 'passed' ||  $view_firefighter->certificate_statuses_status == 'failed' ? 'disabled' : '' }} >
                        <option value="none"  {{ !empty($view_firefighter->certificate_statuses_status) && $view_firefighter->certificate_statuses_status == 'none'  ? 'selected' : '' }} >None</option>
                        <option value="passed" {{ !empty($view_firefighter->certificate_statuses_status) && $view_firefighter->certificate_statuses_status == 'passed' ? 'selected' : '' }} >Passed</option>
                        <option value="failed" {{ !empty($view_firefighter->certificate_statuses_status) && $view_firefighter->certificate_statuses_status == 'failed' ? 'selected' : '' }} >Failed</option>
                    </select>
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{-- {{ $view_firefighters->links('partials.pagination') }} --}}
</div>