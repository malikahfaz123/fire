<title>{{ $certificate->title }} | Credential</title>
<style>
    td,th{
        padding: 5px;
    }
</style>
<h1 style="text-align: center;">{{ $title }}</h1><hr>
<h2 style="text-align: center;"><u>Credential</u></h2>
<div style="text-align: right;margin-top: 15px;">
    <strong>Issue Date:</strong> {{ $issue_date }}
</div>
<table style="margin-top: 25px;">
    <tbody>
        <tr>
            <th align="left">Candidate Number</th>
            <th>:</th>
            <td style="padding-left: 25px;">{{ $firefighter->prefix_id }}</td>
        </tr>
        <tr>
            <th align="left" width="140">Candidate Name</th>
            <th>:</th>
            <td style="padding-left: 25px;">{{ \App\Http\Helpers\FirefighterHelper::get_full_name($firefighter) }}</td>
        </tr>
        <tr>
            <th align="left">Credential Title</th>
            <th>:</th>
            <td style="padding-left: 25px;">{{ $certificate->title }}</td>
        </tr>
        @if($lapse_date)
        <tr>
            <th align="left">Lapse Date</th>
            <th>:</th>
            <td style="padding-left: 25px;">{{ $lapse_date }}</td>
        </tr>
        @endif
    </tbody>
</table>