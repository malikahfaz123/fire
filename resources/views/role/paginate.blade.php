@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($roles && $roles->count())
        @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td class="text-capitalize">{{ $role->name }}</td>
                <td>
                    <a href="{{ route('role.show',$role->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                    @can('settings.update')
                        @if($role->id !== config('constant.system_role_id'))
                            <a href="{{ route('role.edit',$role->id) }}"  title="Edit"><span class="material-icons">create</span></a>
                        @endif
                    @endcan
                    @if($user->can('settings.create') && $user->can('settings.update'))
                        <a href="javascript:void(0)" title="Clone" class="clone" data-role="{{ $role->id }}"><span class="material-icons">content_copy</span></a>
                    @endif
                    @can('settings.delete')
                        @if($role->id !== config('constant.system_role_id'))
                            <a href="javascript:void(0)" data-delete="{{ $role->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
                        @endif
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
    {{ $roles->links('partials.pagination') }}
</div>