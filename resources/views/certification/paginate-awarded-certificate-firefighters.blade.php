@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
<table class="table table-hover app-table text-center mb-0" id="cert-table">
    <thead>
    <tr>
        <th>DFSID</th>
        <th>Personnel Name</th>
        <th>Issue Date</th>
        <th>Lapse Date</th>
        <th>TY</th>
    </tr>
    </thead>
    <tbody>
    @if($awardedCertPers->count() > 0)
        @foreach($awardedCertPers as $rec)
        <tr>
            <td>{{ \App\Http\Helpers\FirefighterHelper::prefix_id($rec->firefighter_id) }}</td>
            <td>{{ \App\Http\Helpers\FirefighterHelper::get_full_name($rec->firefighter_id) }}</td>
            <td>{{ \App\Http\Helpers\Helper::date_format($rec->issue_date) }}</td>
            <td>{{ \App\Http\Helpers\Helper::date_format($rec->lapse_date) }}</td>
            <td>{{ $rec->stage }}</td>
        </tr>
        @endforeach
    @else
        <tr class="text-center">
            <td colspan="100%">No record found.</td>
        </tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">

</div>
