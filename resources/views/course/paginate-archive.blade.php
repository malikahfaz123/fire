<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>Course Hours</th>
            <th>Instr. Lvl</th>
            <th>Archived On</th>
            <th>Archived By</th>
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
                <td class="text-capitalize">{{ $course->instructor_level }}</td>
                <td>{{ \App\Http\Helpers\Helper::date_format($course->archived_at) }}</td>
                <td class="text-capitalize">{{ $course->archived['name'] }}</td>
                <td>
                    @can('courses.read')
                        <a href="{{ route('course.show',$course->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                    @endcan
                    @can('courses.update')
                        <a href="javascript:void(0)" title="Unarchive" data-archive="{{ $course->id }}" class="unarchive"><span class="material-icons">unarchive</span></a>
                    @endcan
                    @can('courses.delete')
                        <a href="javascript:void(0)" data-delete="{{ $course->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $courses->links('partials.pagination') }}
</div>