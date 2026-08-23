<form class="form_sub has_check" id="subscribe-form" 
      wire:submit.prevent="submit">

    <div class="f-content" id="subscribe-content">
        <fieldset class="col">
            <input class="style-stroke"
                id="subscribe-email"
                type="text"
                placeholder="Enter your email"
                wire:model.defer="email"
                >
        </fieldset>

        <button type="submit" class="tf-btn animate-btn type-small-2" style="width: 155px;">
            <span wire:loading.remove wire:target="submit">Subscribe <i class="icon icon-arrow-right"></i></span>
            <span wire:loading wire:target="submit" class="formloader"></span>
        </button>
    </div>
</form>
