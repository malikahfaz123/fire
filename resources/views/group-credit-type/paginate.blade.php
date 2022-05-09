<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>Group Code</th>
            <th>List of Credit Types</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($group_credit_types && $group_credit_types->count())
        @foreach($group_credit_types as $group_credit_type)
            <tr>
                <td class="">{{ $group_credit_type->credit_code }}</td>
                <td class="text-capitalize">{!! implode(',<br>',$group_credit_type->credit_types) !!}</td>
                <td>
                    @can('courses.update')
                        <a href="{{ route('group-credit-types.edit',$group_credit_type->credit_code) }}" title="Edit"><span class="material-icons">create</span></a>
                    @endcan
                    @can('courses.update')
                        <a href="javascript:void(0)" data-delete="{{$group_credit_type->credit_code }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $group_credit_types->links('partials.pagination') }}
</div>