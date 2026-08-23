<div class="modal modalCentered fade modal-ask_question" id="askQuestion" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-heading">
                <h2 class="fw-normal">Ask A Question</h2>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            @if(auth()->check())
                <form class="form-ask mb-0" wire:submit.prevent="submit">
                    <div class="form_content" style="gap:10px;">
                        <textarea placeholder="Message" wire:model="question" style="height: 180px;" class="@error('question') error_border @enderror" ></textarea>
                        @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="tf-btn animate-btn w-100" style="margin-top: 20px;">
                        <span wire:loading.remove wire:target="submit">Submit</span>
                        <span wire:loading wire:target="submit" class="formloader"></span>
                    </button>
                </form>
            @else
                <div class="alert alert-danger">Please at first <a style="text-decoration: underline;" href="{{ route('user.login') }}">login</a> for ask question</div>
            @endif    
        </div>
    </div>
</div>