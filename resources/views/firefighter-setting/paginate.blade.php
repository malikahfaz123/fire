<table class="table table-hover app-table text-center mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Email</th>
            <th>Date</th>
            <th>Role</th>
            <th>Status</th>
            <!-- <th>Action</th> -->
            <th>Manage Role</th>
        </tr>
    </thead>
    <tbody>
    @if($firefighters && $firefighters->count())
        @foreach($firefighters as $key => $firefighter)
            <tr>
                <td>
                    {{$key + 1}}
                </td>
                <td>
                    <a href="mailto:{{ $firefighter->email }}">{{ $firefighter->email }}</a>
                </td>
                <td class="text-capitalize">
                    {{ \App\Http\Helpers\Helper::date_format($firefighter->date) }}
                </td>
                <td>
                    <span class="badge badge-info">
                        {{ $firefighter != null ? \App\Http\Helpers\FirefighterHelper::get_role($firefighter) : 'N/A' }}
                    </span>
                </td>
                <td class="text-capitalize">
                    @if($firefighter->status == 'accepted')
                        <span class="badge badge-success"> {{ $firefighter->status }} </span>
                    @elseif($firefighter->status == 'revoked')
                        <span class="badge badge-danger"> {{ $firefighter->status }} </span>
                    @else
                        <span class="badge badge-info"> {{ $firefighter->status }} </span>
                    @endif
                </td>
                <!-- Comment Action which perform delete user -->
                <!-- <td>
                    @if($firefighter->status == "sent")
                        <a href="javascript:void(0)" data-delete="{{ $firefighter->id }}" class="revokeInvite" title="Cancel">
                        <span class="material-icons">cancel</span>
                    </a>
                    @endif
                    @if($firefighter->status == "revoked")
                    <a href="javascript:void(0)" data-delete="{{ $firefighter->email }}" class="delete" title="Delete">
                        <span class="material-icons">delete</span>
                    </a>
                    @endif
                    @if($firefighter->status == "accepted")
                            <div class="dropdown show">
                                <a href="javascript:void(0)" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="material-icons">settings</span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @if(\App\Http\Helpers\FirefighterHelper::get_role($firefighter) != "Student | Manager")
                                        <a class="changeRole dropdown-item" href="javascript:void(0)" data-firefighter_id="{{ $firefighter->id }}" data-role="admin">Make admin</a>
                                    @else
                                        <a class="changeRole dropdown-item" href="javascript:void(0)" data-firefighter_id="{{ $firefighter->id }}" data-role="student">Remove admin</a>
                                    @endif
                                </div>
                            </div>
                    @endif
                </td> -->
                <td class="role">
                    @if($firefighter->email == Auth::user()->email)
                        <a class="roles-{{$firefighter->id}} badge badge-info" href="javascript:void(0)">Not Allow</a>
                        @continue
                    @endif
                    @if(\App\Http\Helpers\FirefighterHelper::get_role($firefighter) != "Student | Manager") 
                        <a class="manageRole roles-{{$firefighter->id}} badge badge-info" href="javascript:void(0)" data-firefighter_id="{{ $firefighter->id }}" data-firefighter_email="{{ $firefighter->email }}" data-role="student">Upgrade to Admin</a>
                    @else
                        <a class="manageRole roles-{{$firefighter->id}} badge badge-info" href="javascript:void(0)" data-firefighter_id="{{ $firefighter->id }}" data-firefighter_email="{{ $firefighter->email }}" data-role="admin">Downgrade to Student</a>
                    @endif
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
