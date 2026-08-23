<div class="col-md-12 col-lg-3">
    <div class="mdl-compare">
        <h4>Compare Products</h4>
        <p>Choose Two Products to Compare</p>
        <form wire:submit.prevent="viewComparison" class="form-cmpr">
            {{-- Hidden fields you can use on submit later --}}
            <input type="hidden" name="product_ids[]" value="{{ $selectedId1 }}">
            <input type="hidden" name="product_ids[]" value="{{ $selectedId2 }}">

            {{-- Field 1 --}}
            <div class="cmpr-field relative">
                <i class="material-icons">search</i>

                <input class="cmpr-product" type="text" placeholder="Search and Select Product"
                    wire:model.debounce.300ms="query1" autocomplete="off" @focus="$wire.showDropdown1 = true" />

                {{-- Selected chip / clear --}}
                @if($selectedId1)
                <div class="cmpr-chip">
                    {{ $selectedLabel1 }}
                    <button type="button" class="cmpr-clear" wire:click="clearSelection('left')">&times;</button>
                </div>
                @endif

                {{-- Dropdown --}}
                @if($showDropdown1 && !$selectedId1)
                <ul class="cmpr-dropdown">
                    @forelse($results1 as $p)
                    <li class="cmpr-option" wire:click="selectProduct('left', {{ $p['id'] }})">
                        {{ $p['name'] }}
                    </li>
                    @empty
                    <li class="cmpr-empty">No products found</li>
                    @endforelse
                </ul>
                @endif
            </div>

            {{-- Field 2 --}}
            <div class="cmpr-field relative">
                <i class="material-icons">search</i>

                <input class="cmpr-product" type="text" placeholder="Search and Select Product"
                    wire:model.debounce.300ms="query2" autocomplete="off" @focus="$wire.showDropdown2 = true" />

                {{-- Selected chip / clear --}}
                @if($selectedId2)
                <div class="cmpr-chip">
                    {{ $selectedLabel2 }}
                    <button type="button" class="cmpr-clear" wire:click="clearSelection('right')">&times;</button>
                </div>
                @endif

                {{-- Dropdown --}}
                @if($showDropdown2 && !$selectedId2)
                <ul class="cmpr-dropdown">
                    @forelse($results2 as $p)
                    <li class="cmpr-option" wire:click="selectProduct('right', {{ $p['id'] }})">
                        {{ $p['name'] }}
                    </li>
                    @empty
                    <li class="cmpr-empty">No products found</li>
                    @endforelse
                </ul>
                @endif
            </div>

            <button class="btn st-outline" type="submit" >
                View Comparison
            </button>
        </form>

    </div>

    @php
        $latestOffer = App\Models\Offer::orderBy('created_at', 'desc')->where('expire_date', '>', today())->where('status', 1)->first();
    @endphp
    @if ($latestOffer)
        <div class="ads loaded">
            <a href="{{ route('offer.details',$latestOffer->id) }}"><img src="{{ asset($latestOffer->image) }}" width="300" style="height: 190px;object-fit:cover;"></a>
        </div>
    @endif
</div>
