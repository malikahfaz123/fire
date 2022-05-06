<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Credential Code</th>
            <th>Credential Title</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($firefighter_certificates && $firefighter_certificates->count())
        @foreach($firefighter_certificates as $firefighter_certificate)
            <tr style="{{ $firefighter_certificate->read_status == 0 ? "background-color:rgba(0,0,0,.075);" : '' }}" >
                
                <td class="text-capitalize">{{ $firefighter_certificate->prefix_id }}</td>
                <td class="text-capitalize">{{ $firefighter_certificate->title }}</td>
                <td class="text-capitalize">{{ $firefighter_certificate->status }}</td>
                <td>
                    <a href="{{ route('firefighters.reject-certificates.show',$firefighter_certificate->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $firefighter_certificates->links('partials.pagination') }}
</div>