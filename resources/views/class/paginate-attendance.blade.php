<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="type[]" id="bulk-checkbox" value="1">
                    <label class="custom-control-label" for="bulk-checkbox"></label>
                </div>
            </th>
            <th>DFSID</th>
            <th>Firefighter Name</th>
            <th>Admin Ceu</th>
            <th>Tech Ceu</th>
            <th>Attendance</th>
        </tr>
    </thead>
    <tbody>
    @if($firefighters && $firefighters->count())
        @foreach($firefighters as $firefighter)
            <tr>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="firefighter[{{$firefighter->id}}]" id="firefighter-{{$firefighter->prefix_id}}" value="1">
                        <label class="custom-control-label" for="firefighter-{{$firefighter->prefix_id}}"></label>
                    </div>
                </td>
                <td class="text-capitalize">{{ $firefighter->prefix_id }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\FirefighterHelper::get_full_name($firefighter) }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\FirefighterHelper::get_admin_ceu($class_id) }}</td>
                <td class="text-capitalize">{{ \App\Http\Helpers\FirefighterHelper::get_tech_ceu($class_id) }}</td>

                <td class="text-capitalize">
                    <select name="attendance[{{ $firefighter->id }}]" data-toggle="#firefighter-{{$firefighter->prefix_id}}" class="form-control attendance m-auto" {{ $firefighter->attendance === 'completed' ? 'disabled' : '' }} style="width: 150px;">
                        <option {{ $firefighter->attendance === 'completed' ? 'selected' : '' }} value="completed">Completed</option>
                        <option {{ $firefighter->attendance === 'enrolled' ? 'selected' : '' }} value="enrolled">Enrolled</option>
                        <option {{ $firefighter->attendance === 'withdraw' ? 'selected' : '' }} value="withdraw">Withdraw</option>
                        <option {{ $firefighter->attendance === 'no show' ? 'selected' : '' }} value="no show">No show</option>
                        <option {{ $firefighter->attendance === 'stand by' ? 'selected' : '' }} value="stand by">Stand by</option>
                    </select>
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
