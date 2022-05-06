<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>Course Hours</th>
            {{-- <th>Instr. Lvl</th> --}}
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($courses && $courses->count())
        @foreach($courses as $course)
            <tr>
                <td class="text-capitalize">{{ $course->prefix_id }}</td>
                <td class="text-capitalize">{{ $course->course_name }}</td>
                <td class="text-capitalize">{{ $course->course_hours }}</td>
                
                {{-- <td class="text-capitalize">{{ $course->instructor_level }}</td> --}}
                <td>
                    {{-- @can('courses.read') --}}
                        {{-- <a href="{{ route('class.create',$course->id) }}" title="Add Class"><span class="material-icons">add</span></a> --}}
                        <a href="{{ route('firefighters.course.show',$course->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                    {{-- @endcan --}}
                    {{-- @can('courses.update')
                        <a href="{{ route('course.edit',$course->id) }}" title="Edit"><span class="material-icons">create</span></a>
                        <a href="javascript:void(0)" title="Archive" data-archive="{{ $course->id }}" class="archive"><span class="material-icons">archive</span></a>
                    @endcan --}}
                    {{-- @can('courses.delete')
                        <a href="javascript:void(0)" data-delete="{{ $course->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
                    @endcan --}}
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $courses->links('partials.pagination') }}
</div>