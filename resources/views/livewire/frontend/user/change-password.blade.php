<form class="account-details-form mt-4" wire:submit.prevent="ChnagePassword">

    <div class="form-group required mb-3">
        <label class="form-label">Current Password</label>
        <input type="password"
               placeholder="******"
               autocomplete="off"
               wire:model="current_password"
               class="form-control @error('current_password') error-border @enderror">
        @error('current_password')
            <div class="text-danger pt-2">{{ $message }}</div>
        @enderror
    </div>

    <!-- New Password -->
    <div class="form-group required mb-3">
        <label class="form-label">New Password</label>
        <input type="password"
               placeholder="Enter a new password"
               autocomplete="off"
               wire:model="new_password"
               class="form-control @error('new_password') error-border @enderror">
        @error('new_password')
            <div class="text-danger pt-2">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="form-group required mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password"
               placeholder="Confirm new password"
               autocomplete="off"
               wire:model="new_password_confirmation"
               class="form-control @error('new_password_confirmation') error-border @enderror">
        @error('new_password_confirmation')
            <div class="text-danger pt-2">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary" style="width: 180px;">
        <span wire:loading.remove wire:target="ChnagePassword">Save Password</span>
        <span wire:loading wire:target="ChnagePassword" class="formloader"></span>
    </button>
</form>
