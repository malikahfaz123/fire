<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>DFSID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Type</th>
            <th>Instructor Level</th>
            <th>Appointed?</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($firefighters && $firefighters->count())
        @foreach($firefighters as $firefighter)
        @if($firefighter->email == Auth::user()->email)
            @continue
        @endif
            <tr>
                <td class="text-capitalize">{{ $firefighter->prefix_id }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\FirefighterHelper::get_full_name($firefighter) }}</td>
                <td><a href="mailto:{{ $firefighter->email }}">{{ $firefighter->email }}</a></td>
                <td><span class="badge badge-success"> {{ $firefighter->role_manager != "yes" ? 'Student' : 'Manager | Student' }}</span> </td>
                <td class="text-capitalize">{{ $firefighter->type }}</td>
                <td>{{ $firefighter->instructor_level }}</td>
                <td>{{ $firefighter->appointed ? 'Yes' : 'No' }}</td>
                <td>
                    @can('firefighters.read')
                        <a href="{{ route('firefighter.show',$firefighter->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                    @endcan
                    @can('firefighters.update')
                        <a href="{{ route('firefighter.edit',$firefighter->id) }}" title="Edit"><span class="material-icons">create</span></a>
                        <a href="" title="Archive" data-archive="{{ $firefighter->id }}" class="archive"><span class="material-icons">archive</span></a>
                    @endcan
                    @can('firefighters.delete')
                        <a href="javascript:void(0)" data-delete="{{ $firefighter->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $firefighters->links('partials.pagination') }}
</div>
