<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Semester</th>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Course Hours</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($firefighter_courses && $firefighter_courses->count())
        @foreach($firefighter_courses as $firefighter_course)
            <tr>
                <td class="text-capitalize">{{ $firefighter_course->semester.' '.$firefighter_course->year  }}</td>
                <td class="text-capitalize">{{ $firefighter_course->prefix_id }}</td>
                <td class="text-capitalize">{{ $firefighter_course->course_name }}</td>
                <td class="text-capitalize">{{ $firefighter_course->course_hours }}</td>
                <td class="text-capitalize">{{ $firefighter_course->status }}</td>
                <td>
                    <a href="{{ route('firefighters.my-courses.show',$firefighter_course->course_id) }}"  title="View"><span class="material-icons">visibility</span></a>
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $firefighter_courses->links('partials.pagination') }}
</div>