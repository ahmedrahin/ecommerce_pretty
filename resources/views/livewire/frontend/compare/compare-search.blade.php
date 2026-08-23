<div class="cmpr-field">
    <i class="material-icons">search</i>
    <input
        type="text"
        class="cmpr-product"
        placeholder="Search and Select Product"
        wire:model="search"
    />

    {{-- Suggestions Dropdown --}}
   @if(!empty($suggestions))
    <ul class="cmpr-dropdown">
        @foreach($suggestions as $suggestion)
            <li class="cmpr-option" wire:click="selectProduct({{ $suggestion->id }})">
                <img src="{{ asset($suggestion->thumb_image) }}" width="40" height="40" />
                {{ $suggestion->name }}
            </li>
        @endforeach
    </ul>
@endif
</div>
