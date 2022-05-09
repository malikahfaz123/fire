<table class="table table-hover app-table text-center mb-0">
    <thead>
    <tr>
        <th>DFSID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Type</th>
        <th>Appointed?</th>
        <th>Archived On</th>
        <th>Archived By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if($firefighters && $firefighters->count())
        @foreach($firefighters as $firefighter)
            @php $archived = (array) $firefighter->archived @endphp
            <tr>
                <td class="text-capitalize">{{ $firefighter->prefix_id }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\FirefighterHelper::get_full_name($firefighter) }}</td>
                <td><a href="mailto:{{ $firefighter->work_email }}">{{ $firefighter->work_email }}</a></td>
                <td class="text-capitalize">{{ $firefighter->type }}</td>
                <td>{{ $firefighter->appointed ? 'Yes' : 'No' }}</td>
                <td>{{ \App\Http\Helpers\Helper::date_format($firefighter->archived_at) }}</td>
                <td class="text-capitalize">
                    @foreach($archived as $key=>$object)
                        @if(strpos($key,'attributes'))
                            {{ $object['name'] }}
                        @endif
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('firefighter.show',$firefighter->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                    @can('firefighters.update')
                        @if($firefighter->is_archive)
                            <a href="javascript:void(0)" title="Unarchive" data-archive="{{ $firefighter->id }}" class="unarchive"><span class="material-icons">unarchive</span></a>
                        @endif
                    @endcan
                    @can('firefighters.delete')
                    <a href="javascript:void(0)" data-delete="{{ $firefighter->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
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
    {{ $firefighters->links('partials.pagination') }}
</div>