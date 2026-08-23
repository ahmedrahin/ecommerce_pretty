@if( !is_null($coupon->expire_date) && Carbon\Carbon::parse($coupon->expire_date)->lt(now()) )
    <span class="badge badge-light-danger">expire</span>
@else
    <label class="form-check form-switch form-check-custom form-check-solid status-toggle">
        <input
            class="form-check-input change-status"
            type="checkbox"
            wire:click="updateStatus"
            data-coupon-id="{{ $coupon->id }}"
            {{ $coupon->status == 1 ? 'checked' : '' }}
        >
    </label>
@endif

