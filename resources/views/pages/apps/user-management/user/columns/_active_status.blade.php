@can('update admin')
    <label class="form-check form-switch form-check-custom form-check-solid status-toggle">
        <input 
            class="form-check-input change-status" 
            type="checkbox"  
            data-user-id="{{ $user->id }}"
            data-current-user-id="{{ Auth::id() }}" 
            {{ $user->status == 1 ? 'checked' : '' }}
            @if($user->id == 1) disabled @endif
        >
    </label>
@else
    <label class="form-check form-switch form-check-custom form-check-solid" onclick="errorAccess('You have no permission to change admin status')">
        <input 
            class="form-check-input" 
            type="checkbox"  
            {{ $user->status == 1 ? 'checked' : '' }}
            disabled
        >
    </label>
@endif