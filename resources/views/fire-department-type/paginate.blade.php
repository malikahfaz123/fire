@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp

<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Description</th>
            @if($user->can('fire_departments.update') || $user->can('fire_departments.delete'))
            <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @if($fire_department_types && $fire_department_types->count())
        @foreach($fire_department_types as $fire_department_type)
            <tr>
                <td class="text-capitalize">{{ $fire_department_type->prefix_id }}</td>
                <td class="text-capitalize">{{ $fire_department_type->description }}</td>
                @if($user->can('fire_departments.update') || $user->can('fire_departments.delete'))
                <td>
                    @can('fire_departments.update')
                    <a href="javascript:void(0)" data-edit="{{ $fire_department_type->id }}" class="edit" title="Edit"><span class="material-icons">create</span></a>
                    @endcan
                    @can('fire_departments.delete')
                        <a href="javascript:void(0)" data-delete="{{ $fire_department_type->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $fire_department_types->links('partials.pagination') }}
</div>