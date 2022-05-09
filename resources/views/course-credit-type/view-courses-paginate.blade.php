<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>   
            <th>Course ID</th>
            <th>Course Name </th>
        </tr>
    </thead>
    <tbody>
    @if($courses && $courses->count())
        @foreach($courses as $course)
            <tr>
                <td>{{ $course->prefix_id }}</td>
                <td>{{ $course->course_name }}</td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
