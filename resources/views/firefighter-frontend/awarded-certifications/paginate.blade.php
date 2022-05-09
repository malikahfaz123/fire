<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Credential Code</th>
            <th>Credential Title</th>
            <th>Organization</th>
{{--            <th>Receiving Date</th>--}}
            <th>Issue Date</th>
            <th>Lapse Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($awarded_certificates && $awarded_certificates->count())
        @foreach($awarded_certificates as $awarded_certificate)
            <tr style="{{ $awarded_certificate->firefighters_read_status == 0 ? "background-color:rgba(0,0,0,.075);" : '' }}">
                <td>{{ $awarded_certificate->certifications_prefix_id }}</td>
                <td class="text-capitalize">{{ $awarded_certificate->title }}</td>
                <td class="text-capitalize">{{ $awarded_certificate->organization_name }}</td>
{{--                <td>{{ $awarded_certificate->receiving_date ? \App\Http\Helpers\Helper::date_format($awarded_certificate->receiving_date) : 'N/A' }}</td>--}}
                <td>{{ \App\Http\Helpers\Helper::date_format($awarded_certificate->issue_date) }}</td>
                <td>{{ $awarded_certificate->lapse_date ? \App\Http\Helpers\Helper::date_format($awarded_certificate->lapse_date) : 'N/A' }}</td>
                <td class="text-capitalize">{{ $awarded_certificate->stage }}</td>
                <td>
                    <a href="{{ route('firefighters.view-all-certification',$awarded_certificate->id) }}" target="_blank" title="View"><span class="material-icons">visibility</span></a>
                    @if(\App\Http\Helpers\Helper::certification_history_count($awarded_certificate->firefighter_id,$awarded_certificate->certificate_id) > 1)
                        <a href="{{ route('firefighters.certifications-past-records',$awarded_certificate->certificate_id) }}" title="Certification History"><span class="material-icons">history</span></a>
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
    {{ $awarded_certificates->links('partials.pagination') }}
</div>
