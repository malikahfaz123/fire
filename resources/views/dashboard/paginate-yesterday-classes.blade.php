<table class="table table-hover app-table text-center mb-0">
    <thead>
    <tr>
        <th>Sequence No.</th>
        <th>Course Name</th>
        <th>Start Time</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if($classes && $classes->count())
        @foreach($classes as $class)
            <tr>
                <td class="text-capitalize">{{ $class->id }}</td>
                <td class="text-capitalize">{{ $class->course_name }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\Helper::time_format("$class->start_date $class->start_time") }}</td>
                <td>
                    <a href="{{ route('class.attendance',[$class->semester_id,$class->course_id,$class->id]) }}"  title="View"><span class="material-icons">schedule</span></a>
                    <a href="{{ route('class.show',[$class->semester_id,$class->course_id,$class->id]) }}" title="Edit"><span class="material-icons">visibility</span></a>
                </td>
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $classes->links('partials.pagination') }}
</div>