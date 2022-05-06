<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Receiving Date</th>
            <th>Issue Date</th>
            <th>Lapse Date</th>
            <th>TY</th>
            <th>History</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($awarded_certificates && $awarded_certificates->count())
        @foreach($awarded_certificates as $awarded_certificate)
            <tr>
                <td>{{ $awarded_certificate->receiving_date ? \App\Http\Helpers\Helper::date_format($awarded_certificate->receiving_date) : 'N/A' }}</td>
                <td>{{ \App\Http\Helpers\Helper::date_format($awarded_certificate->issue_date) }}</td>
                <td>{{ $awarded_certificate->lapse_date ? \App\Http\Helpers\Helper::date_format($awarded_certificate->lapse_date) : 'N/A' }}</td>
                <td class="text-capitalize">{{ $awarded_certificate->stage }}</td>
                <td>
                    @if($awarded_certificate->renewable && date('Y-m-d') > $awarded_certificate->lapse_date )
                        Y
                    @endif
                </td>
                <td>
                    <a href="{{ route('firefighter.view-certification',[$awarded_certificate->firefighter_id,$awarded_certificate->id]) }}" target="_blank" title="View"><span class="material-icons">visibility</span></a>
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