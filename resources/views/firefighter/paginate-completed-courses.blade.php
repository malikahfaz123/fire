<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Completion Date</th>
            <th>Transcript Sent</th>
            <th>Issue date</th>
            @can('firefighters.update')
            <th>Action</th>
            @endcan
        </tr>
    </thead>
    <tbody>
    @if($completed_courses && $completed_courses->count())
        @foreach($completed_courses as $completed_course)
            <tr>
                <td>{{ $completed_course->prefix_id }}</td>
                <td class="text-capitalize">{{ $completed_course->course_name }}</td>
                <td>{{ \App\Http\Helpers\Helper::date_format($completed_course->created_at) }}</td>
                <td class="{{ $completed_course->transcript_sent ? 'text-success' : 'text-danger' }}">{{ $completed_course->transcript_sent ? 'Sent' : 'Not sent' }}</td>
                <td>{{ $completed_course->issue_date ? \App\Http\Helpers\Helper::date_format($completed_course->issue_date) : 'N/A' }}</td>
                @can('firefighters.update')
                <td>
                    @if($completed_course->is_archive)
                        <a href="javascript:void(0)" title="Unarchive" data-archive="{{ $completed_course->id }}" class="unarchive"><span class="material-icons">unarchive</span></a>
                    @else
                        <a id="open-transcript-modal" data-firefighter-id="{{ $completed_course->firefighter_id }}" data-semester-id="{{ $completed_course->semester_id }}" data-course-id="{{ $completed_course->course_id }}" href="javascript:void(0)" title="Transcript options"><span class="material-icons">receipt</span></a>
                        <a href="javascript:void(0)" title="Archive" data-archive="{{ $completed_course->id }}" class="archive"><span class="material-icons">archive</span></a>
                    @endif
                </td>
                @endcan
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $completed_courses->links('partials.pagination') }}
</div>