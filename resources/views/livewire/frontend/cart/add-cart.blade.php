<div>
    @php
        $productStocks = $product->productStock ?? collect();
        $attributesList = $attributes->keyBy('id');
        $attributesValuesList = $attributesValues->keyBy('id');
        $groupedAttributes = [];

        // group only single-attribute stocks (like before)
        $singleVariationStocks = $productStocks->filter(function ($productStock) {
            return $productStock->attributeOptions->count() === 1;
        });

        foreach ($singleVariationStocks as $stock) {
            foreach ($stock->attributeOptions as $opt) {
                // group by attribute_id -> option_id => valueModel
                $groupedAttributes[$opt->attribute_id][$opt->id] = $attributesValuesList[$opt->attribute_value_id] ?? null;
            }
        }

        // map value_id => product image (if any)
        $valueImageMap = [];
        foreach ($productStocks as $stock) {
            foreach ($stock->attributeOptions as $opt) {
                if (!empty($stock->image)) {
                    $valueImageMap[$opt->attribute_value_id] = $stock->image;
                }
            }
        }

        // $selectedAttributes expected as ['Size' => 'M', 'Color' => 'Blue'] etc.
        $selectedAttributes = $selectedAttributes ?? [];
        $attributeErrors = $attributeErrors ?? [];
    @endphp

    @if($productStocks->count() != 0)
        <div class="tf-product-variant">
            @php
                $ordered = [];
                $colorAttrId = null;
                foreach ($groupedAttributes as $aid => $vals) {
                    $attr = $attributesList[$aid] ?? null;
                    if (!$attr) continue;
                    if (strtolower($attr->attr_name) === 'color') {
                        $colorAttrId = $aid;
                        continue;
                    }
                    $ordered[$aid] = $vals;
                }
                if ($colorAttrId) {
                    $ordered[$colorAttrId] = $groupedAttributes[$colorAttrId];
                }
            @endphp

            @foreach ($ordered as $attribute_id => $values)
                @php
                    $attribute = $attributesList[$attribute_id] ?? null;
                    if (!$attribute) continue;
                    $attrName = $attribute->attr_name;
                    $selectedValue = $selectedAttributes[$attrName] ?? null;
                @endphp

                @if (strtolower($attrName) === 'color')
                    {{-- ================= COLOR DESIGN (exact given markup) ================= --}}
                    <div class="variant-picker-item variant-color">
                        <div class="variant-picker-label">
                            <div class="h4 fw-semibold">
                                Colors
                                <span class="variant-picker-label-value value-currentColor">{{ $selectedValue ?? '' }}</span>
                            </div>
                        </div>

                        <div class="variant-picker-values">
                            @foreach ($values as $optionId => $value)
                                @if ($value)
                                    @php
                                        // determine background (hex/class/text)
                                        $bg = $value->option ?? $value->attr_value;
                                        $imgPath = $valueImageMap[$value->id] ?? null;
                                        $imgUrl = $imgPath ? asset($imgPath) : null;
                                        $isActive = ($selectedValue == $value->attr_value);
                                    @endphp

                                    <div class="hover-tooltip tooltip-bot color-btn {{ $isActive ? 'active' : '' }}"
                                        data-attribute="{{ $attrName }}"
                                        data-value="{{ $value->attr_value }}"
                                        data-image="{{ $imgUrl ?? '' }}"
                                        @if(method_exists($this, 'emit')) wire:click="$emit('selectAttribute', '{{ $attrName }}', '{{ $value->attr_value }}')" @endif>
                                        <span class="check-color" style="background: {{ $bg }};"></span>
                                        <span class="tooltip">{{ $value->attr_value }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if (!empty($attributeErrors[$attrName]))
                            <div class="text-danger mt-1">{{ $attributeErrors[$attrName] }}</div>
                        @endif
                    </div>

                @else
                    {{-- ================= SIZE / OTHER ATTRIBUTES (exact given markup) ================= --}}
                    <div class="variant-picker-item variant-size">
                        <div class="variant-picker-label">
                            <div class="h4 fw-semibold">
                                {{ $attrName }}
                                <span class="variant-picker-label-value value-currentSize">{{ $selectedValue ?? '' }}</span>
                            </div>
                            {{-- optional: keep size-guide only for Size attribute --}}
                            @if (strtolower($attrName) === 'size')
                                {{-- <a href="#size-guide" data-bs-toggle="modal" class="size-guide link h6 fw-medium">
                                    <i class="icon icon-ruler"></i>
                                    Size Guide
                                </a> --}}
                            @endif
                        </div>

                        <div class="variant-picker-values">
                            @foreach ($values as $optionId => $value)
                                @if ($value)
                                    @php
                                        $imgPath = $valueImageMap[$value->id] ?? null;
                                        $imgUrl = $imgPath ? asset($imgPath) : null;
                                        $isActive = ($selectedValue == $value->attr_value);
                                    @endphp

                                    <span class="size-btn {{ $isActive ? 'active' : '' }}"
                                        data-attribute="{{ $attrName }}"
                                        data-value="{{ $value->attr_value }}"
                                        data-image="{{ $imgUrl ?? '' }}"
                                        @if(method_exists($this, 'emit')) wire:click="$emit('selectAttribute', '{{ $attrName }}', '{{ $value->attr_value }}')" @endif>
                                        {{ $value->attr_value }}
                                    </span>
                                @endif
                            @endforeach
                        </div>

                        @if (!empty($attributeErrors[$attrName]))
                            <div class="text-danger mt-1">{{ $attributeErrors[$attrName] }}</div>
                        @endif
                    </div>
                @endif

            @endforeach
        </div>
    @endif

    <div class="tf-product-total-quantity">
        <div class="group-btn">
            <div class="wg-quantity" wire:ignore>
                <button type="button" class="btn-quantity btn-decrease" aria-label="Decrease quantity">
                    <i class="icon icon-minus"></i>
                </button>

                <input
                    class="quantity-product"
                    type="text"
                    wire:model.lazy="quantity"
                    value="{{ $quantity }}"
                    data-quantity="{{ $product->quantity }}"
                    inputmode="numeric"
                    pattern="[0-9]*"
                />

                <button type="button" class="btn-quantity btn-increase" aria-label="Increase quantity">
                    <i class="icon icon-plus"></i>
                </button>
            </div>

            <livewire:frontend.wishlist.towishlist :productId="$product->id"></livewire>
            <livewire:frontend.wishlist.add-wishlist></livewire>

            @if($product->stock_out == 1 || $product->quantity == 0)
                <button class="tf-btn btn-add-to-cart" style="background: #626262;" disabled>
                    Out Of Stock
                </button>
            @elseif ($product->pre_order == 1)
                <button class="tf-btn btn-add-to-cart" style="background: #626262;" disabled>
                    Up Coming
                </button>
            @else
                <div class="btnBuy d-flex gap-2">
                    <button class="tf-btn animate-btn btn-add-to-cart" wire:click="addToCart">
                        <span wire:loading.remove wire:target="addToCart">ADD TO CART</span>
                        <span wire:loading wire:target="addToCart" class="formloader"></span>
                    </button>
                    <button class="tf-btn btn-primary" wire:click="directCheckout">
                        <span wire:loading.remove wire:target="directCheckout">BUY IT NOW</span>
                        <span wire:loading wire:target="directCheckout" class="formloader"></span>
                    </button>
                </div>
            @endif


        </div>

    </div>


    @section('addcart-js')
        {{-- qty increse & decrease --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const quantityInputs = document.querySelectorAll('.wg-quantity');

                quantityInputs.forEach((wrapper) => {
                    const addButton = wrapper.querySelector('.btn-increase');
                    const subButton = wrapper.querySelector('.btn-decrease');
                    const inputEl = wrapper.querySelector('.quantity-product');

                    if (inputEl && inputEl.dataset.quantity) {
                        const maxQuantity = parseInt(inputEl.dataset.quantity);

                        // Update quantity display
                        function updateQuantityDisplay() {
                            const currentQuantity = document.getElementById('current-quantity');
                            if (currentQuantity) {
                                currentQuantity.textContent = inputEl.value;
                            }
                        }

                        // Update button states
                        function updateButtonStates() {
                            const currentValue = Number(inputEl.value);
                            addButton.disabled = (currentValue >= maxQuantity);
                            subButton.disabled = (currentValue <= 1);
                        }

                        // Increase quantity
                        addButton?.addEventListener('click', function() {
                            let currentValue = Number(inputEl.value);
                            if (currentValue < maxQuantity) {
                                inputEl.value = currentValue + 1;
                                // Dispatch input event for Livewire
                                inputEl.dispatchEvent(new Event('input', { bubbles: true }));
                                inputEl.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                            updateButtonStates();
                            updateQuantityDisplay();
                        });

                        // Decrease quantity
                        subButton?.addEventListener('click', function() {
                            let currentValue = Number(inputEl.value);
                            if (currentValue > 1) {
                                inputEl.value = currentValue - 1;
                                // Dispatch input event for Livewire
                                inputEl.dispatchEvent(new Event('input', { bubbles: true }));
                                inputEl.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                            updateButtonStates();
                            updateQuantityDisplay();
                        });

                        // Handle direct input
                        inputEl.addEventListener('input', function() {
                            let value = Number(inputEl.value);

                            // Validate input
                            if (isNaN(value) || value < 1) {
                                inputEl.value = 1;
                            } else if (value > maxQuantity) {
                                inputEl.value = maxQuantity;
                            }

                            updateButtonStates();
                            updateQuantityDisplay();
                        });

                        // Handle blur event for final validation
                        inputEl.addEventListener('blur', function() {
                            if (inputEl.value === '' || isNaN(Number(inputEl.value))) {
                                inputEl.value = 1;
                            }
                            updateButtonStates();
                            updateQuantityDisplay();
                        });

                        // Initial button state
                        updateButtonStates();
                        updateQuantityDisplay();
                    }
                });
            });
        </script>
    @endsection
</div>
