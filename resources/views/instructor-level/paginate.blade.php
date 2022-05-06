<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Instr. Lvl</th>
            <th>Courses</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($instructor_levels && $instructor_levels->count())
        @foreach($instructor_levels as $instructor_level)
            <tr>
                <td class="text-capitalize">{{ $instructor_level->instructor_level }}</td>
                <td class="text-capitalize">{!! implode(',<br>',$instructor_level->courses) !!}</td>
                <td>
                    @can('firefighters.update')
                        <a href="{{ route('instructor-level.edit',$instructor_level->instructor_level) }}" title="Edit"><span class="material-icons">create</span></a>
                    @endcan
                    @can('firefighters.delete')
                        <a href="javascript:void(0)" data-delete="{{$instructor_level->instructor_level }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $instructor_levels->links('partials.pagination') }}
</div>