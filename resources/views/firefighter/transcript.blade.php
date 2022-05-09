<style>
    td,th{
        padding: 5px;
    }
</style>
<h1 style="text-align: center;">{{ $title }}</h1><hr>
<div style="text-align: right;margin-top: 15px;">
    <strong>Issue Date:</strong> {{ $print_date }}
</div>
<table style="margin-top: 25px;">
    <tbody>
        <tr>
            <th align="left">Transcript Code</th>
            <th>:</th>
            <td style="padding-left: 25px;">{{ $code }}</td>
        </tr>
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
            <th align="left">Course Title</th>
            <th>:</th>
            <td style="padding-left: 25px;">{{ $course->course_name }}</td>
        </tr>
    </tbody>
</table>