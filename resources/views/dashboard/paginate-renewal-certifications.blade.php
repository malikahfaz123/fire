
<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>DFSID</th>
            <th>Personnel Name</th>
            <th>Credential Code</th>
            <th>Credential Name</th>
            <th>Lapse Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if($certifications && $certifications->count())
        @foreach($certifications as $certification)
            <tr>
                <td>{{ \App\Http\Helpers\FirefighterHelper::prefix_id($certification->firefighter_id) }}</td>
                 <td>
                    <a href="{{ route('firefighter.show',$certification->firefighter_id) }}">
                        {{ \App\Http\Helpers\FirefighterHelper::get_full_name($certification->firefighter_id) }}
                    </a> 
                </td> 
                <td>{{ $certification->prefix_id }}</td>
                <td class="text-capitalize">{{ $certification->title }}</td>
                <td>{{ \App\Http\Helpers\Helper::date_format($certification->lapse_date) }}</>
                 <td>
                    <a href="{{ route('firefighter.certifications',$certification->firefighter_id) }}"  title="View"><span class="material-icons">visibility</span></a>
                </td> 
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>