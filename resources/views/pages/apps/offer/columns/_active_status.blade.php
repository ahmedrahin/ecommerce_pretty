@if( !is_null($data->expire_date) && Carbon\Carbon::parse($data->expire_date)->lt(now()) )
    <span class="badge badge-light-danger">expire</span>
@else
    <label class="form-check form-switch form-check-custom form-check-solid status-toggle">
        <input
            class="form-check-input change-status"
            type="checkbox"
            data-id="{{ $data->id }}"
            {{ $data->status == 1 ? 'checked' : '' }}
        >
    </label>
@endif

