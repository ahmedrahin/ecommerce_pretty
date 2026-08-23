<div class="container py-5" style="display: flex; justify-content: center; margin: 50px 0;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <!-- Title -->
            <h2 class="text-center fw-bold mb-3 text-dark">Get in Touch</h2>
            <p class="text-center text-muted mb-4">
                Fill out the form below and we’ll respond as soon as possible.
            </p>

            <!-- Contact Form -->
            <form wire:submit.prevent="submit" class="contact-us-form">
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Your Name</label>
                    <input type="text" id="username" name="name"
                        class="form-control form-control-lg rounded-3 @error('name') error-border @enderror"
                        placeholder="John Doe" wire:model="name">
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email_1" class="form-label fw-semibold">Your Email</label>
                    <input type="email" id="email_1" name="email"
                        class="form-control form-control-lg rounded-3 @error('email') error-border @enderror"
                        placeholder="example@email.com" wire:model="email">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone_1" class="form-label fw-semibold">Your Phone</label>
                    <input type="text" id="phone_1" name="phone"
                        class="form-control form-control-lg rounded-3 @error('phone') error-border @enderror"
                        placeholder="+880 123 456 789" wire:model="phone">
                    @error('phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subject_1" class="form-label fw-semibold">Subject</label>
                    <input type="text" id="subject_1" name="subject" class="form-control form-control-lg rounded-3"
                        placeholder="Subject of your subject" wire:model="subject">
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label fw-semibold">Your Message</label>
                    <textarea id="message" name="message" rows="5"
                        class="form-control form-control-lg rounded-3 @error('message') error-border @enderror"
                        placeholder="Write your message here..." wire:model="message"></textarea>
                    @error('message')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary" style="width: 200px;">
                        <span wire:loading.remove wire:target="submit">Send Message</span>
                        <span wire:loading wire:target="submit" class="formloader"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
