<div>
    <ul class="tags">
        @forelse($selectedCategoryNames as $name)
            <li>
                <span class="selected-item" style="">Cats: {{ $name }} <i class="w-icon-times-solid" wire:click="removeCategory('{{ $name }}')"></i></span>
            </li>
        @empty
            
        @endforelse
    </ul>
</div>
