<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Sequence No.</th>
            <th>Start Date</th>
            <th>Attendance</th>
        </tr>
    </thead>
    <tbody>
    @if($course_classes && $course_classes->count())
        @php $current_date = date('Y-m-d') @endphp
        @foreach($course_classes as $course_class)
            <tr>
                <td>{{ $course_class->id }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\Helper::date_format($course_class->start_date) }}</td>
                <td>
                    <div class="show-field">{{ ucfirst($course_class->attendance) }}</div>
                    @can('firefighters.update')
                    <div class="edit-field d-none">
                        <select class="form-control form-control-sm m-auto" name="attendance[{{ $course_class->id }}]" {{ $current_date<$course_class->start_date ? 'disabled' : '' }} style="width: 100px;">
                            <option {{ $course_class->attendance == 'completed' ? 'selected' : '' }} value="completed">Completed</option>
                            <option {{ $course_class->attendance == 'enrolled' ? 'selected' : '' }} value="enrolled">Enrolled</option>
                            <option {{ $course_class->attendance == 'withdraw' ? 'selected' : '' }} value="withdraw">Withdraw</option>
                            <option {{ $course_class->attendance == 'no show' ? 'selected' : '' }} value="no show">No show</option>
                            <option {{ $course_class->attendance == 'stand by' ? 'selected' : '' }} value="stand by">Stand by</option>
                        </select>
                    </div>
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
    {{ $course_classes->links('partials.pagination') }}
</div>