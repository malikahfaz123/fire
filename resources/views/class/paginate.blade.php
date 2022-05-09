@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Sequence No.</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Start Time</th>
            <th>Action</th>
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
                @if($user->can('courses.create') || $user->can('courses.update') || $user->can('courses.delete'))
                    <td>
                        @can('courses.create')
                        <a href="{{ route('class.show',[$class->semester_id,$class->course_id,$class->id]) }}"  title="View"><span class="material-icons">visibility</span></a>
                        @endcan
                        @can('courses.update')
                        <a href="{{ route('class.edit',[$class->semester_id,$class->course_id,$class->id]) }}" title="Edit"><span class="material-icons">create</span></a>
                            <a href="javascript:void(0)" title="Archive" data-archive="{{ $class->id }}" class="archive"><span class="material-icons">archive</span></a>
                        @endcan
                        @can('courses.delete')
                            <a href="javascript:void(0)" data-delete="{{ $class->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
                        @endcan
                    </td>
                @endif
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
