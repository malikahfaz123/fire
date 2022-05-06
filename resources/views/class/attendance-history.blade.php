<table class="table table-sm">
    <thead class="roboto-bold">
    <tr>
        <td>S.No</td>
        <td>DFSID</td>
        <td>Firefighter Name</td>
        <td>From</td>
        <td>Date</td>
    </tr>
    </thead>
    <tbody>
    @foreach($histories as $key=>$history)
        <tr>
            <td><span class="toggle-row pointer" data-toggle=".history-{{$history->id}}"></span></td>
            <td style="padding-right: 20px;" class="text-center">{{ $key+1 }}</td>
            <td>{{ $history->firefighter->prefix_id }}</td>
            <td>{{ \App\Http\Helpers\FirefighterHelper::get_full_name($history->firefighter) }}</td>
            <td class="text-capitalize"><small>{{ $history->user->name }}</small></td>
            <td><small>{{ \App\Http\Helpers\Helper::date_format($history->created_at) }}</small></td>
        </tr>
        <tr class="toggle-row-item d-none history-{{$history->id}}">
            <th class="border-0"></th>
            <th>Field</th>
            <th>Prev Value</th>
            <th>New Value</th>
        </tr>
        @php
            $data = is_array($history->data) ? $history->data : json_decode($history->data,true);
        @endphp
        @foreach($data as $row)
            <tr class="toggle-row-item d-none history-{{$history->id}}">
                <th class="border-0"></th>
                <td class="text-capitalize"><small>{{ str_replace('_',' ',$row['label']) }}</small></td>
                <td>
                    <small>
                        @if(is_array($row['prev']))
                            {!! implode(',<br>',$row['prev']) !!}
                        @else
                            {{ $row['prev'] ? $row['prev'] : '-' }}
                        @endif
                    </small>
                </td>
                <td>
                    <small>
                        @if(is_array($row['new']))
                            {!! implode(',<br>',$row['new']) !!}
                        @else
                            {{ $row['new'] ? $row['new'] : '-' }}
                        @endif
                    </small>
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>