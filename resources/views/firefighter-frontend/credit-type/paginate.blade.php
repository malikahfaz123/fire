{{-- @php $user = \Illuminate\Support\Facades\Auth::user(); @endphp --}}

<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Description</th>
            {{-- @if($user->can('courses.update') || $user->can('courses.delete')) --}}
            {{-- <th>Action</th> --}}
            {{-- @endif --}}
        </tr>
    </thead>
    <tbody>
    @if($credit_types && $credit_types->count())
        @foreach($credit_types as $credit_type)
            <tr>
                <td class="text-capitalize">{{ $credit_type->prefix_id }}</td>
                <td class="text-capitalize">{{ $credit_type->description }}</td>
                {{-- @if($user->can('courses.update') || $user->can('courses.delete')) --}}
                {{-- <td>
                    @can('courses.update')
                    <a href="javascript:void(0)" data-edit="{{ $credit_type->id }}" class="edit" title="Edit"><span class="material-icons">create</span></a>
                    @endcan
                    @can('courses.delete')
                        <a href="javascript:void(0)" data-delete="{{ $credit_type->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
                    @endcan
                </td> --}}
                {{-- @endif --}}
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $credit_types->links('partials.pagination') }}
</div>