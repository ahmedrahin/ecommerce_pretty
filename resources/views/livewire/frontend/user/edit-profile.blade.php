<form class="form-change_pass" wire:submit.prevent="submit">
    <div class="">
        <h2 class="account-title type-semibold">Account Setting</h2>
        <div class="form_content">
            <div class="cols tf-grid-layout sm-col-2">
                <fieldset>
                    <input type="text" 
                           placeholder="Your Name *" 
                           class="@error('name') error-border @enderror"
                           wire:model="name"
                           >
                    @error('name')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset>
                    <input type="tel" 
                           placeholder="Phone No. *" 
                           class="@error('phone') error_border @enderror"
                           wire:model="phone">
                    @error('phone')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </fieldset>
            </div>
            <fieldset>
                <input type="text" 
                       placeholder="Email *" 
                       class="@error('email') error_border @enderror"
                       wire:model="email">
                @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </fieldset>
            <fieldset>
                <textarea placeholder="Write your Address..." 
                          class="@error('address_line1') error_border @enderror"
                          wire:model="address_line1"
                          rows="3"></textarea>
                @error('address_line1')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </fieldset>
        </div>
    </div>

    <button class="tf-btn animate-btn btn-add-to-cart" type="submit">
        <span wire:loading.remove wire:target="submit">Save Changes</span>
        <span wire:loading wire:target="submit" class="formloader"></span>
    </button>
</form>