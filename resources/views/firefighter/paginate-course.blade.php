<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            @can('firefighters.update')
            <th>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="bulk-checkbox">
                    <label class="form-check-label" for="bulk-checkbox"></label>
                </div>
            </th>
            @endcan
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Semester</th>
            <th>Year</th>
            <th>No. of Classes</th>
            <th>Attendance</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($courses && $courses->count())
        @foreach($courses as $course)
            @php
                $total_classes = \App\Http\Helpers\Helper::total_classes($course->semester_id,$course->course_id,$course->firefighter_id);
                $attended_classes = \App\Http\Helpers\Helper::get_attended_classes($course->semester_id,$course->course_id,$course->firefighter_id);
                $is_course_completed = \App\Http\Helpers\FirefighterHelper::is_course_completed($course->firefighter_id,$course->semester_id,$course->course_id);
                $is_semester_completed = \App\Http\Helpers\Helper::is_semester_completed($course->semester,$course->year);
                $min_attendance = \App\Http\Helpers\Helper::get_min_attendance_perc();
                $attendance = $total_classes && $attended_classes ? number_format(($attended_classes/$total_classes)*100,0) : 0;
            @endphp
            <tr>
                @can('firefighters.update')
                    <td>
                        <div class="form-check">
                            <input class="form-check-input course-checkbox" name="courses[semester_id][]" type="checkbox" value="{{ $course->semester_id }}" id="course-{{ $course->semester_id }}" {{ !$is_semester_completed || $min_attendance>$attendance || $is_course_completed ? 'disabled' : '' }} {{ $is_course_completed ? 'checked' : '' }}>
                            <input class="form-check-input course-checkbox" name="courses[course_id][]" type="hidden" value="{{ $course->course_id }}" id="course-{{ $course->course_id }}" {{ !$is_semester_completed || $min_attendance>$attendance || $is_course_completed ? 'disabled' : '' }} {{ $is_course_completed ? 'checked' : '' }}>
                            <label class="form-check-label" for="course-{{ $course->course_id }}"></label>
                        </div>
                    </td>
                @endcan
                <td class="text-capitalize">{{ $course->prefix_id }}</td>
                <td class="text-capitalize">{{ $course->course_name }}</td>
                <td class="text-capitalize">{{ $course->semester }}</td>
                <td class="text-capitalize">{{ $course->year }}</td>
                <td class="text-capitalize">{{ $total_classes }}</td>
                <td class="text-capitalize {{ $min_attendance>$attendance ? 'text-danger' : '' }}">{{ $attendance }}%</td>
                <td>
                    <a href="{{ route('firefighter.attendance',[$course->course_id,$course->firefighter_id]) }}" title="Attendance"><span class="material-icons">schedule</span></a>
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