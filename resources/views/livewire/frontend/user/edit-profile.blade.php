<form class="form-horizontal" wire:submit.prevent="submit">
    <div class="form-group">
        <div class="form-group required">
            <label for="input-firstname">Name</label>
            <input type="text"
                   id="input-firstname"
                   name="firstname"
                   placeholder="Your Name"
                   class="form-control @error('firstname') error-border @enderror"
                   wire:model="name">
            @error('firstname')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group required">
        <label for="input-email">E-Mail</label>
        <input type="email"
               id="input-email"
               name="email"
               placeholder="E-Mail"
               class="form-control @error('email') error-border @enderror"
               wire:model="email">
        @error('email')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group required">
        <label for="input-telephone">Phone No.</label>
        <input type="tel"
               id="input-telephone"
               name="phone"
               placeholder="Phone No."
               class="form-control @error('phone') error-border @enderror"
               wire:model="phone">
        @error('phone')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="input-address">Address</label>
        <textarea id="input-address"
                  name="address_line1"
                  placeholder="Write your Address..."
                  class="form-control @error('address_line1') error-border @enderror"
                  rows="3"
                  wire:model="address_line1"></textarea>
        @error('address_line1')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary" style="width: 160px;">
        <span wire:loading.remove wire:target="submit">Save Changes</span>
        <span wire:loading wire:target="submit" class="formloader"></span>
    </button>
</form>
