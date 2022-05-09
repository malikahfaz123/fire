<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($facilities && $facilities->count())
        @foreach($facilities as $facility)
            <tr>
                <td class="text-capitalize">{{ $facility->prefix_id }}</td>
                <td class="text-capitalize">{{ $facility->name }}</td>
                <td class="text-capitalize">{{ $facility->category }}</td>
                <td>
                    @can('facilities.read')
                        <a href="{{ route('facility.show',$facility->id) }}" data-edit="{{ $facility->id }}" class="edit" title="View"><span class="material-icons">visibility</span></a>
                    @endcan
                    @can('facilities.update')
                        <a href="{{ route('facility.edit',$facility->id) }}" title="Edit"><span class="material-icons">create</span></a>
                        <a href="" title="Archive" data-archive="{{ $facility->id }}" class="archive"><span class="material-icons">archive</span></a>
                    @endcan
                    @can('facilities.delete')
                        <a href="javascript:void(0)" data-delete="{{ $facility->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $facilities->links('partials.pagination') }}
</div>