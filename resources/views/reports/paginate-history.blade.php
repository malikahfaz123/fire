<table class="table table-hover app-table text-center mb-0">
    <thead class="roboto-bold">
        <tr>
            <td></td>
            <td>ID</td>
            <td>Module</td>
            <td>From</td>
            <td>Date</td>
        </tr>
    </thead>
    <tbody>
    @if($histories && $histories->count())
        @foreach($histories as $key=>$history)
            @php
                $data = is_array($history->data) ? $history->data : json_decode($history->data,true);
            @endphp
            <tr>
                <td><span class="toggle-row pointer" data-toggle=".history-{{$history->id}}"></span></td>
                <td>{{ $history->foreign_id }}</td>
                <td class="text-capitalize">{{ str_replace('_',' ',$history->module) }}</td>
                <td class="text-capitalize">{{ $history->user->name }}</td>
                <td>{{ \App\Http\Helpers\Helper::datetime_format($history->created_at) }}</td>
            </tr>
            <tr class="toggle-row-item d-none history-{{$history->id}}">
                <th class="border-0"></th>
                <th>Field</th>
                @if($history->module == 'course_classes')
                    <th>Class Seq.No</th>
                    <th>Firefighter Name</th>
                @endif
                <th>Prev Value</th>
                <th>New Value</th>
            </tr>
            @foreach($data as $row)
                <tr class="toggle-row-item d-none history-{{$history->id}}">
                    <th class="border-0"></th>
                    <td>{{ ucfirst(str_replace('_',' ',$row['label'])) }}</td>
                    @if($history->module == 'course_classes')
                        <td>{{ $row['class'] }}</td>
                        <td>{{ \App\Http\Helpers\FirefighterHelper::get_full_name($row['firefighter']) }}</td>
                    @endif
                    <td>
                        @if(is_array($row['prev']))
                            {!! implode(',<br>',$row['prev']) !!}
                        @else
                            {{ $row['prev'] ? $row['prev'] : '-' }}
                        @endif
                    </td>
                    <td>
                        @if(is_array($row['new']))
                            {!! implode(',<br>',$row['new']) !!}
                        @else
                            {{ $row['new'] ? $row['new'] : '-' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $histories->links('partials.pagination') }}
</div>