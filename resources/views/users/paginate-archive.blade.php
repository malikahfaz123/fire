<table class="table table-hover app-table text-center mb-0">
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if($users && $users->count())
        @foreach($users as $user)
            <tr>
                <td class="text-capitalize">{{ $user['name'] }}</td>
                <td><a href="mailto:{{ $user['email'] }}">{{ $user['email'] }}</a></td>
                <td class="text-capitalize">{{ $user->role->name }}</td>
                <td>
                    <a href="{{ route('user.show',$user['id']) }}"  title="View"><span class="material-icons">visibility</span></a>
                    @can('settings.update')
                    <a href="" title="Unarchive" data-archive="{{ $user['id'] }}" class="unarchive"><span class="material-icons">unarchive</span></a>
                    @endcan
                    @can('settings.delete')
                    <a href="javascript:void(0)" data-delete="{{ $user['id'] }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $users->links('partials.pagination') }}
</div>