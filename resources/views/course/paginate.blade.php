<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            {{-- <th>Semester ID</th> --}}
            <th>Semester</th>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Course Hours</th>
            <th>Instr. Lvl</th>
            <th>Courses Request</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($courses && $courses->count())
        @foreach($courses as $course)
            <tr>
                {{-- <td class="text-capitalize">
                    {{  !empty($course->semester_id)  ?  \App\Http\Helpers\Helper::prefix_id($course->semester_id)  : 'N/A' }}
                </td> --}}
                <td class="text-capitalize">
                    {{  !empty($course->semester) && !empty($course->semester_year) ? $course->semester.' '.$course->semester_year : 'N/A' }}
                </td>
                <td class="text-capitalize">{{ $course->prefix_id }}</td>
                <td class="text-capitalize">{{ $course->course_name }}</td>
                <td class="text-capitalize">{{ $course->course_hours }}</td>
                <td class="text-capitalize">{{ $course->instructor_level }}</td>
                <td class="text-capitalize">
                    @if(!empty($course->semester_id))
                        <a href="{{ route('course.view-firefighters',[$course->semester_id,$course->id]) }}" class="badge badge-primary">{{ $course->course_request_count }}</a>
                    @else
                        <a  href="javascript:void(0)" class="badge badge-primary">0</a>
                    @endif
                </td>
                <td style="width: 10rem;">
                    @can('courses.read')
                        @if(!empty($course->semester_id) && date("Y-m-d") >= $course->start_date && date("Y-m-d") <= $course->end_date)
                            <a href="{{ route('class.create',[$course->semester_id,$course->id]) }}" title="Add Class"><span class="material-icons">add</span></a>
                        @endif
                        @if(!empty($course->semester_id))
                            <a href="{{ route('course.show',[$course->semester_id,$course->id]) }}"  title="View"><span class="material-icons">visibility</span></a>
                        @else
                            <a href="{{ route('course.course_show',$course->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                        @endif
                    @endcan
                    @can('courses.update')
                        <a href="{{ route('course.edit',$course->id) }}" title="Edit"><span class="material-icons">create</span></a>
                        {{-- <a href="javascript:void(0)" title="Archive" data-archive="{{ $course->id }}" class="archive"><span class="material-icons">archive</span></a> --}}
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