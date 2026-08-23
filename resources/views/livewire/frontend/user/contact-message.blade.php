<section class="s-contact-form flat-spacing" style="padding-top: 20px;">
    <div class="container">
        <div class="sect-title text-center">
            <p class="h1 title mb-8">Contact Form</p>
            <p class="s-subtitle h6">Contact us using the form below, we will contact you as soon as possible</p>
        </div>
        <form class="form-contact-2" wire:submit.prevent="submit">
            <div class="form-content">
                <div class="tf-grid-layout md-col-3">
                    <fieldset>
                        <input type="text" 
                               placeholder="Your name" 
                               class="@error('name') error_border @enderror"
                               wire:model="name"
                               >
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    <fieldset>
                        <input type="text" 
                               placeholder="Your phone number" 
                               class="@error('phone') error_border @enderror"
                               wire:model="phone"
                               >
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    <fieldset>
                        <input type="text" 
                               placeholder="Your email" 
                               class="@error('email') error_border @enderror"
                               wire:model="email"
                               >
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>
                <fieldset>
                    <textarea placeholder="Your message"
                              class="@error('message') error_border @enderror"
                              wire:model="message"
                              rows="5"
                              ></textarea>
                    @error('message')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </fieldset>
            </div>
            <div class="form-action" style="margin-top: 25px;">
                <button type="submit" class="h6 tf-btn animate-btn">
                    <span wire:loading.remove wire:target="submit">Send message</span>
                    <span wire:loading wire:target="submit" class="formloader"></span>
                </button>
            </div>
        </form>
    </div>
</section>