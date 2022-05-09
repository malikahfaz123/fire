<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>   
            <th>Credit Types ID</th>
            <th>Credit Types Description </th>
            <th>View Related Courses</th>
        </tr>
    </thead>
    <tbody>
    @if($credit_types && $credit_types->count())
        @foreach($credit_types as $credit_type)
            <tr>
                <td class="">{{ $credit_type->prefix_id }}</td>
                <td class="">{{ $credit_type->description }}</td>
                <td class="">
                    <a href="" id="view-reason" data-viewreason="{{$credit_type->id}}" class="edit " title="View"><span class="material-icons">visibility</span></a>
                </td>

                
                {{-- <td class="">{{ $course->no_of_credit_types }}</td> --}}
                {{-- <td class="text-capitalize">{!! implode(',<br> ',$course->credit_types) !!}</td> --}}
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $credit_types->links('partials.pagination') }}
</div>