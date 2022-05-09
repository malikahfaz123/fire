<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Semester</th>
            <th>Year</th>
            <th>No. of Courses</th>
            <th>Archived On</th>
            <th>Archived By</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($semesters && $semesters->count())
        @foreach($semesters as $semester)
            <tr>
                <td class="text-capitalize">{{ \App\Http\Helpers\Helper::prefix_id($semester->id) }}</td>
                <td class="text-capitalize">{{ $semester->semester }}</td>
                <td class="text-capitalize">{{ $semester->year }}</td>
                <td class="text-capitalize">{{ $semester->courses->count() }}</td>
                <td>{{ \App\Http\Helpers\Helper::date_format($semester->archived_at) }}</td>
                <td class="text-capitalize">{{ $semester->archived['name'] }}</td>
                <td>
                    @can('semesters.read')
                        <a href="{{ route('semester.show',$semester->id) }}" data-edit="{{ $semester->id }}" class="edit" title="View"><span class="material-icons">visibility</span></a>
                    @endcan
                    @can('semesters.update')
                        <a href="javascript:void(0)" title="Unarchive" data-archive="{{ $semester->id }}" class="unarchive"><span class="material-icons">unarchive</span></a>
                    @endcan
                    @can('semesters.delete')
                        <a href="javascript:void(0)" data-delete="{{ $semester->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $semesters->links('partials.pagination') }}
</div>