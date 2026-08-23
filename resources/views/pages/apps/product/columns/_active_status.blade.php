@if(is_null($product->expire_date) || $product->expire_date > now())
    @if($product->status == 1 || $product->status == 0)
        <label class="form-check form-switch form-check-custom form-check-solid status-toggle">
            <input 
                class="form-check-input change-status" 
                type="checkbox"  
                wire:click="update_status"
                data-product-id="{{ $product->id }}"
                {{ $product->status == 1 ? 'checked' : '' }}
            >
        </label>
    @elseif($product->status == 2)
        <span class="badge badge-light-primary">Draft</span>
    @elseif($product->status == 3)
        <span class="badge badge-light-warning" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="{{$product->publish_at}}">Scheduled</span>
    @endif
@elseif($product->expire_date <= now())
    <span class="badge badge-light-danger">Expired</span>
@endif
