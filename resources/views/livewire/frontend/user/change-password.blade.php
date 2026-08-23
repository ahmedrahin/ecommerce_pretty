<form class="account-details-form" wire:submit.prevent="ChnagePassword" style="margin-top: 50px;">
    <div class="">
        <h2 class="account-title type-semibold">Change Password</h2>
        <div class="form_content site-change">
            <!-- Current Password -->
            <fieldset class="password-wrapper">
                <input class="password-field @error('current_password') error_border @enderror" 
                       type="password" 
                       placeholder="Current password *" 
                       autocomplete="off"
                       wire:model="current_password"
                       >
                <span class="toggle-pass icon-show-password"></span>
                @error('current_password')
                    <div class="text-danger pt-2">{{ $message }}</div>
                @enderror
            </fieldset>

            <!-- New Password -->
            <fieldset class="password-wrapper">
                <input class="password-field @error('new_password') error_border @enderror" 
                       type="password" 
                       placeholder="New password *" 
                       autocomplete="off"
                       wire:model="new_password"
                       >
                <span class="toggle-pass icon-show-password"></span>
                @error('new_password')
                    <div class="text-danger pt-2">{{ $message }}</div>
                @enderror
            </fieldset>

            <fieldset class="password-wrapper">
                <input class="password-field @error('new_password_confirmation') error_border @enderror" 
                       type="password" 
                       placeholder="Confirm password *" 
                       autocomplete="off"
                       wire:model="new_password_confirmation"
                       >
                <span class="toggle-pass icon-show-password"></span>
                @error('new_password_confirmation')
                    <div class="text-danger pt-2">{{ $message }}</div>
                @enderror
            </fieldset>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="tf-btn animate-btn btn-add-to-cart savePass">
        <span wire:loading.remove wire:target="ChnagePassword">Save Password</span>
        <span wire:loading wire:target="ChnagePassword" class="formloader"></span>
    </button>
</form>