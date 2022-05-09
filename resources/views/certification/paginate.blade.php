@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
<table class="table table-hover app-table text-center mb-0" id="cert-table">
    <thead>
        <tr>
            <th></th>
            <th>Credential Code</th>
            <th>Credential Title</th>
            <th>Credential Request</th>
            <th>Renewable</th>
            <th>Renewal Date</th>
            <th>Status</th>
            @if($user->can('certifications.read') || $user->can('certifications.update') || $user->can('certifications.delete'))
                <th>Action</th>
            @endcan
        </tr>
    </thead>
    <tbody>
    @if($certifications && $certifications->count())
        @foreach($certifications as $certification)
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input certificate-checkbox" name="certifications_ids[]" type="checkbox" value="{{ $certification->id }}">
                        <label class="form-check-label" for="certificate-{{ $certification->id }}"></label>
                    </div>
                </td>
                <td>{{ $certification->prefix_id }}</td>
                <td class="text-capitalize">{{ $certification->title }}</td>
                <td class="text-capitalize">
                    <a  href="{{ route('certificate.view-firefighters',$certification->id ) }}" class="badge badge-primary">{{ $certification->certificates_request_count }}</a>
                </td>
                <td>{{ $certification->renewable ? 'Yes' : 'No' }}</td>
                <td>{{ !empty($certification->renewed_expiry_date) ? \App\Http\Helpers\Helper::date_format($certification->renewed_expiry_date) : '---' }}</td>
                <td>
                    @if($certification->renewed_expiry_date >= \Carbon\Carbon::now()->toDateString())
                        <span class="badge badge-info">Renewed</span>
                    @endif
                    @if($certification->certification_cycle_end >= \Carbon\Carbon::now()->toDateString() || $certification->renewable == 0 || $certification->renewed_expiry_date >= \Carbon\Carbon::now()->toDateString())
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Expired</span>
                    @endif
                </td>
                @if($user->can('certifications.read') || $user->can('certifications.update') || $user->can('certifications.delete'))
                    <td>
                        @can('certifications.read')
                            @if($certification->certification_cycle_end <= \Carbon\Carbon::now()->toDateString() && $certification->renewed_expiry_date == null && $certification->renewable == 1)
                                <a href="javascript:void(0)" class="renew-certificate" data-id="{{ $certification->id }}" title="Renew"><span class="material-icons">autorenew</span></a>
                            @endif
                            <a href="{{ route('certification.show',$certification->id) }}"  title="View"><span class="material-icons">visibility</span></a>
                        @endcan
                        @can('certifications.update')
                            <a href="{{ route('certification.edit',$certification->id) }}" title="Edit"><span class="material-icons">create</span></a>
                        @endcan
                        @can('certifications.delete')
                            <a href="javascript:void(0)" data-delete="{{ $certification->id }}" class="delete" title="Delete"><span class="material-icons">delete_outline</span></a>
                        @endcan
                    </td>
                @endif
            </tr>
        @endforeach
    @else
        <tr align="center"><td colspan="100%">No record found.</td></tr>
    @endif
    </tbody>
</table>
<div class="pagination-links">
    {{ $certifications->links('partials.pagination') }}
</div>
