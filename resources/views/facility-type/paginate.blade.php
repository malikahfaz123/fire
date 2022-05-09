@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Description</th>
            @if($user->can('facilities.update') || $user->can('facilities.delete'))
            <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @if($facility_types && $facility_types->count())
        @foreach($facility_types as $facility_type)
            <tr>
                <td>{{ $facility_type->id }}</td>
                <td class="text-capitalize">{{ $facility_type->description }}</td>
                @if($user->can('facilities.update') || $user->can('facilities.delete'))
                    <td>
                        @can('facilities.update')
                            <a href="javascript:void(0)" data-edit="{{ $facility_type->id }}" data-description="{{ $facility_type->description }}"  class="edit" title="Edit"><span class="material-icons">create</span></a>
                        @endcan
                        @can('facilities.delete')
                            <a href="javascript:void(0)" data-delete="{{ $facility_type->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $facility_types->links('partials.pagination') }}
</div>