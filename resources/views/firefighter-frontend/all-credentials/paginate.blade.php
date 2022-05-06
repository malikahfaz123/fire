<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Credential Code</th>
            <th>Credential Title</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($certifications && $certifications->count())
        @foreach($certifications as $certification)
            <tr>
                <td>{{ $certification->prefix_id }}</td>
                <td class="text-capitalize">{{ $certification->title }}</td>
                <td>
                    <a href="javascript:void(0)" class="apply" data-certification_id="{{ $certification->id }}" data-certification_prefix_id="{{ $certification->prefix_id }}"  data-certification_title="{{ $certification->title }}" data-firefighter_id="{{ Auth::guard('firefighters')->user()->id }}" title="Apply"><span class="material-icons">add</span></a>

                    
                    <a href="{{ route('firefighters.all.certification.show',$certification->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $certifications->links('partials.pagination') }}
</div>