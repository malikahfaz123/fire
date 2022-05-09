@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
<table class="table table-hover app-table text-center mb-0">
    <thead>
    <tr>
        <th>Fire Dept. ID</th>
        <th>Fire Dept. Name</th>
        <th>City</th>
        <th>Archived On</th>
        <th>Archived By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if($fire_departments && $fire_departments->count())
        @foreach($fire_departments as $fire_department)
            <tr>
                <td>{{ $fire_department->prefix_id }}</td>
                <td class="text-capitalize">{{ $fire_department->name }}</td>
                <td class="text-capitalize">{{ $fire_department->city }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\Helper::date_format($fire_department->archived_at) }}</td>
                <td class="text-capitalize">{{ $fire_department->archived['name'] }}</td>
                @if($user->can('fire_departments.read') || $user->can('fire_departments.update') || $user->can('fire_departments.delete'))
                <td>
                    @can('fire_departments.read')
                        <a href="{{ route('fire-department.show',$fire_department->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                    @endcan
                    @can('fire_departments.update')
                        <a href="javascript:void(0)" title="Unarchive" data-archive="{{ $fire_department->id }}" class="unarchive"><span class="material-icons">unarchive</span></a>
                    @endcan
                    @can('fire_departments.delete')
                        <a href="javascript:void(0)" data-delete="{{ $fire_department->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
                    @endcan
                </td>
                @endif
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $fire_departments->links('partials.pagination') }}
</div>