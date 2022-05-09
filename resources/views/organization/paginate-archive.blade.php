<table class="table table-hover app-table text-center mb-0">
    <thead>
    <tr>
        <th>ID</th>
        <th>Eligible Organization</th>
        <th>Municipality</th>
        <th>Archived On</th>
        <th>Archived By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if($organizations && $organizations->count())
        @foreach($organizations as $organization)
            <tr>
                <td class="text-capitalize">{{ $organization->prefix_id }}</td>
                <td class="text-capitalize">{{ $organization->name }}</td>
                <td class="text-capitalize">{{ $organization->physical_municipality }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\Helper::date_format($organization->archived_at) }}</td>
                <td class="text-capitalize">{{ $organization->archived['name'] }}</td>
                <td>
                    @can('organizations.read')
                        <a href="{{ route('organization.show',$organization->id) }}" data-edit="{{ $organization->id }}" class="edit" title="View"><span class="material-icons">visibility</span></a>
                    @endcan
                    @can('organizations.update')
                        <a href="javascript:void(0)" title="Unarchive" data-archive="{{ $organization->id }}" class="unarchive"><span class="material-icons">unarchive</span></a>
                    @endcan
                    @can('organizations.delete')
                        <a href="javascript:void(0)" data-delete="{{ $organization->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
                    @endcan
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $organizations->links('partials.pagination') }}
</div>