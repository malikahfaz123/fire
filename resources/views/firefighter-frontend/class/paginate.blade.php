<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Sequence No.</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Start Time</th>
            <th>Instructor Name</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
    <tbody>
    @if($classes && $classes->count())
        @foreach($classes as $class)
            <tr>
                <td class="text-capitalize">{{ $class->id }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\Helper::date_format($class->start_date) }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\Helper::date_format($class->end_date) }}</td>
                <td class="text-capitalize">{{ $class->start_time }}</td>
                <td class="text-capitalize">{{ $class->instructor_f_name.' '.$class->instructor_m_name.' '.$class->instructor_l_name   }}</td>
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