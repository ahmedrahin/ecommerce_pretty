<div>
   @php
        $productStocks = $product->productStock ?? collect();
        $attributesList = $attributes->keyBy('id');
        $attributesValuesList = $attributesValues->keyBy('id');
        $groupedAttributes = [];

        $singleVariationStocks = $productStocks->filter(function ($productStock) {
            return $productStock->attributeOptions->count() === 1 && !$productStock->is_disabled;
        });

        foreach ($singleVariationStocks as $productStock) {
            foreach ($productStock->attributeOptions as $option) {
                $groupedAttributes[$option->attribute_id][$option->id] =
                    $attributesValuesList[$option->attribute_value_id] ?? null;
            }
        }

        $valueImageMap = [];
        foreach ($productStocks as $stock) {
            if ($stock->is_disabled) continue;
            foreach ($stock->attributeOptions as $opt) {
                if (!empty($stock->image)) {
                    $valueImageMap[$opt->attribute_value_id] = $stock->image;
                }
            }
        }

        $hasVariants = !empty($groupedAttributes);
    @endphp

    {{-- Variant Selects --}}
    @if ($hasVariants)
        @foreach ($groupedAttributes as $attribute_id => $values)
            @php
                $attribute = $attributesList[$attribute_id] ?? null;
                $attributeName = $attribute->attr_name ?? 'Option';
                $selectedValue = $selectedAttributes[$attributeName] ?? null;
                $isSize = strtolower($attributeName) === 'size';
            @endphp

            @if ($attribute && !empty($values))
                <div class="variant-section">

                    {{-- Label + Size Chart Button --}}
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;justify-content:space-between;">
                        <label class="variant-label" style="margin-bottom:0;">Select {{ $attributeName }}</label>

                        @if ($isSize && $product->short_description && $product->short_description != '<p><br></p>')
                            <button type="button" class="sc-size-chart-btn" onclick="scOpenSizeChart()">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 6h18M3 12h18M3 18h18" />
                                </svg>
                                Size Chart
                            </button>
                        @endif
                    </div>

                    {{-- Variant Buttons --}}
                    <div class="variant-options">
                        @foreach ($values as $optionId => $value)
                            @if ($value)
                                @php
                                    $matchedStock = $productStocks->first(function ($stock) use ($value) {
                                        return $stock->attributeOptions->contains('attribute_value_id', $value->id);
                                    });
                                    $isOutOfStock = $matchedStock && $matchedStock->quantity <= 0;
                                    $isSelected   = ($selectedAttributes[$attributeName] ?? null) === $value->attr_value;
                                    $stockQty     = $matchedStock ? (int) $matchedStock->quantity : 0;
                                    $isDisabled   = $matchedStock && $matchedStock->is_disabled;
                                    $isOutOfStock = $stockQty <= 0;
                                @endphp
                                @if(!$isDisabled)    
                                    <button type="button"
                                        class="variant-btn {{ $isSelected ? 'selected' : '' }} {{ $isOutOfStock ? 'disabled-variant' : '' }}"
                                        @if($isOutOfStock) disabled @endif
                                        wire:click="{{ $isOutOfStock ? '' : "selectAttribute('{$attributeName}', '{$value->attr_value}')" }}"
                                        data-attribute-name="{{ $attributeName }}"
                                        data-value="{{ $value->attr_value }}"
                                        data-image="{{ $imgUrl ?? '' }}"
                                        onclick="{{ $isOutOfStock ? 'return false;' : 'scHandleVariantClick(this)' }}">
                                        {{ $value->attr_value }}
                                    </button>
                                @endif
                            @endif
                        @endforeach
                    </div>

                    {{-- Validation Error --}}
                    @if (!empty($attributeErrors[$attributeName]))
                        <div class="text-danger mt-1" style="font-size:12px">
                            {{ $attributeErrors[$attributeName] }}
                        </div>
                    @endif

                </div>
            @endif
        @endforeach
    @endif

    {{-- Qty + Cart --}}
    <div class="qty-cart" style="flex-wrap: wrap;">
        <div class="qty-control">
            <button class="qty-btn" id="sc-qty-minus" onclick="scChangeQty(-1)">-</button>
           <input class="qty-input" type="text" id="sc-qty" :model="quantity"  value="1" min="1"  data-max="{{ $selectedStockQty > 0 ? $selectedStockQty : $product->quantity }}"
                    inputmode="numeric" pattern="[0-9]*">
            <button class="qty-btn" id="sc-qty-plus" onclick="scChangeQty(1)">+</button>
        </div>

        @if ($product->stock_out == 1 || $product->quantity == 0)
            <button class="add-to-cart" disabled style="opacity:0.5;cursor:not-allowed">
                Out of stock
            </button>
        @else
            <div>
                <button class="add-to-cart" id="sc-add-to-cart" style="width: 50px;padding:0 !important;margin-right: 5px;" wire:click="addToCart"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="addToCart"><i class="bi bi-bag-check"></i></span>
                    <span wire:loading wire:target="addToCart" class="formloader"></span>
                </button>
                <button class="add-to-cart" wire:click="directCheckout" style="width: 160px;">
                    <span wire:loading.remove wire:target="directCheckout">Buy Now</span>
                    <span wire:loading wire:target="directCheckout" class="formloader"></span>
                </button>
            </div>
        @endif
    </div>

    <style>
        /* Remove number input arrows */
        .qty-input {
            -webkit-appearance: none;
            -moz-appearance: textfield;
            appearance: none;
        }

        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    @section('addcart-js')
        <script>
            // Store quantity value to prevent reset
            let currentQtyValue = 1;

            window.addEventListener('DOMContentLoaded', function() {
                initVariantControls();
            });

            document.addEventListener('livewire:load', function() {
                initVariantControls();
            });

            document.addEventListener('livewire:update', function() {
                initVariantControls();
            });

            function initVariantControls() {
                const hasVariants = {{ $hasVariants ? 'true' : 'false' }};

                function checkAllSelected() {
                    if (!hasVariants) return true;
                    const selects = document.querySelectorAll('[id^="sc-select-"]');
                    let allSelected = true;
                    selects.forEach(select => {
                        if (!select.value) allSelected = false;
                    });
                    return allSelected;
                }

                function updateUI() {
                    const allSelected = checkAllSelected();
                    const qtyInput = document.getElementById('sc-qty');
                    const addToCartBtn = document.getElementById('sc-add-to-cart');
                    const buyNowBtn = document.querySelector('[wire\\:click="directCheckout"]'); // Select Buy Now button
                    const minusBtn = document.getElementById('sc-qty-minus');
                    const plusBtn = document.getElementById('sc-qty-plus');

                    if (addToCartBtn) {
                        if (allSelected) {
                            addToCartBtn.disabled = false;
                            addToCartBtn.style.opacity = '1';
                            addToCartBtn.style.cursor = 'pointer';
                        } else {
                            addToCartBtn.disabled = true;
                            addToCartBtn.style.opacity = '0.5';
                            addToCartBtn.style.cursor = 'not-allowed';
                        }
                    }

                    // Update Buy Now button state
                    if (buyNowBtn) {
                        if (allSelected) {
                            buyNowBtn.disabled = false;
                            buyNowBtn.style.opacity = '1';
                            buyNowBtn.style.cursor = 'pointer';
                        } else {
                            buyNowBtn.disabled = true;
                            buyNowBtn.style.opacity = '0.5';
                            buyNowBtn.style.cursor = 'not-allowed';
                        }
                    }

                    // Update quantity controls
                    if (qtyInput) {
                        if (allSelected) {
                            qtyInput.disabled = false;
                        } else {
                            qtyInput.disabled = true;
                        }
                    }

                    if (minusBtn) {
                        if (allSelected) {
                            minusBtn.disabled = false;
                            updateQtyButtons(); // Re-enable with proper state
                        } else {
                            minusBtn.disabled = true;
                        }
                    }

                    if (plusBtn) {
                        if (allSelected) {
                            plusBtn.disabled = false;
                            updateQtyButtons(); // Re-enable with proper state
                        } else {
                            plusBtn.disabled = true;
                        }
                    }
                }

                function updateQtyButtons() {
                    const qtyInput = document.getElementById('sc-qty');
                    if (!qtyInput) return;

                    let currentQty = parseInt(qtyInput.value) || 1;
                    const maxQty = parseInt(qtyInput.dataset.max) || 999;
                    const minusBtn = document.getElementById('sc-qty-minus');
                    const plusBtn = document.getElementById('sc-qty-plus');

                    if (minusBtn) minusBtn.disabled = (currentQty <= 1);
                    if (plusBtn) plusBtn.disabled = (currentQty >= maxQty);

                    // Store current quantity
                    currentQtyValue = currentQty;
                }

                function restoreQuantity() {
                    const qtyInput = document.getElementById('sc-qty');
                    if (qtyInput && currentQtyValue > 1) {
                        qtyInput.value = currentQtyValue;
                        updateQtyButtons();
                    }
                }

                window.scHandleSelectChange = function(selectElement) {
                    const attrName = selectElement.getAttribute('data-attribute-name');
                    const attrValue = selectElement.value;
                    const attrId = selectElement.getAttribute('data-attribute-id');

                    const clearLink = document.getElementById('sc-clear-' + attrId);
                    if (clearLink) {
                        clearLink.style.display = attrValue ? 'inline' : 'none';
                    }

                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const imgUrl = selectedOption.getAttribute('data-image');
                    if (imgUrl) {
                        const mainImg = document.getElementById('sc-main-img');
                        const mainLink = document.getElementById('sc-main-link');
                        if (mainImg) {
                            mainImg.src = imgUrl;
                            mainImg.dataset.zoom = imgUrl;
                        }
                        if (mainLink) mainLink.href = imgUrl;
                    }

                    updateUI();

                    // Don't reset quantity, just restore previous value
                    restoreQuantity();
                };

                window.scClearSelect = function(attrId, attrName) {
                    const select = document.getElementById('sc-select-' + attrId);
                    if (select) {
                        select.value = '';
                        const event = new Event('change', {
                            bubbles: true
                        });
                        select.dispatchEvent(event);
                    }
                };

                window.scChangeQty = function(direction) {
                    const qtyInput = document.getElementById('sc-qty');
                    if (!qtyInput) return;

                    // Check if buttons are disabled (either add to cart or buy now - both indicate variant not selected)
                    const addToCartBtn = document.getElementById('sc-add-to-cart');
                    const buyNowBtn = document.querySelector('[wire\\:click="directCheckout"]');
                    if ((addToCartBtn && addToCartBtn.disabled) || (buyNowBtn && buyNowBtn.disabled)) return;

                    let currentQty = parseInt(qtyInput.value) || 1;
                    const maxQty = parseInt(qtyInput.dataset.max) || 999;

                    let newQty = currentQty + direction;
                    if (newQty < 1) newQty = 1;
                    if (newQty > maxQty) newQty = maxQty;

                    if (newQty !== currentQty) {
                        qtyInput.value = newQty;
                        currentQtyValue = newQty;
                        qtyInput.dispatchEvent(new Event('input'));
                        updateQtyButtons();

                        // Update Livewire property
                        if (window.livewire) {
                            @this.set('quantity', newQty);
                        }
                    }
                };

                // Handle manual input
                const qtyInput = document.getElementById('sc-qty');
                if (qtyInput) {
                    qtyInput.addEventListener('input', function(e) {
                        let val = parseInt(this.value) || 1;
                        const max = parseInt(this.dataset.max) || 999;
                        if (val < 1) val = 1;
                        if (val > max) val = max;
                        if (val !== parseInt(this.value)) this.value = val;
                        currentQtyValue = val;
                        updateQtyButtons();

                        // Update Livewire property
                        if (window.livewire) {
                            @this.set('quantity', val);
                        }
                    });

                    // Allow only numbers
                    qtyInput.addEventListener('keypress', function(e) {
                        if (!/[0-9]/.test(e.key)) {
                            e.preventDefault();
                        }
                    });
                }

                // For non-variant products, enable all controls by default
                if (!hasVariants) {
                    const addToCartBtn = document.getElementById('sc-add-to-cart');
                    const buyNowBtn = document.querySelector('[wire\\:click="directCheckout"]');
                    const qtyInput = document.getElementById('sc-qty');
                    const minusBtn = document.getElementById('sc-qty-minus');
                    const plusBtn = document.getElementById('sc-qty-plus');

                    if (addToCartBtn) {
                        addToCartBtn.disabled = false;
                        addToCartBtn.style.opacity = '1';
                        addToCartBtn.style.cursor = 'pointer';
                    }

                    if (buyNowBtn) {
                        buyNowBtn.disabled = false;
                        buyNowBtn.style.opacity = '1';
                        buyNowBtn.style.cursor = 'pointer';
                    }

                    if (qtyInput) qtyInput.disabled = false;
                    if (minusBtn) minusBtn.disabled = false;
                    if (plusBtn) plusBtn.disabled = false;

                    // Update button states based on quantity
                    updateQtyButtons();
                } else {
                    updateUI();
                }

                updateQtyButtons();
                restoreQuantity();
            }

            function updateQtyButtons() {
                const qtyInput = document.getElementById('sc-qty');
                if (!qtyInput) return;

                const currentQty = parseInt(qtyInput.value) || 1;
                const maxQty = parseInt(qtyInput.dataset.max) || 999;
                const minusBtn = document.getElementById('sc-qty-minus');
                const plusBtn = document.getElementById('sc-qty-plus');

                if (minusBtn) minusBtn.disabled = (currentQty <= 1);
                if (plusBtn) plusBtn.disabled = (currentQty >= maxQty);
            }

            window.scHandleVariantClick = function(btn) {
            const attrName  = btn.getAttribute('data-attribute-name');
            const attrValue = btn.getAttribute('data-value');
            const imgUrl    = btn.getAttribute('data-image');

            document.querySelectorAll(`.variant-btn[data-attribute-name="${attrName}"]`).forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');

            if (imgUrl) {
                const mainImg  = document.getElementById('sc-main-img');
                const mainLink = document.getElementById('sc-main-link');
                if (mainImg) {
                    mainImg.src = imgUrl;
                    mainImg.dataset.zoom = imgUrl;
                }
                if (mainLink) mainLink.href = imgUrl;
            }

            updateMaxQtyFromSelectedVariants();
        };

        function updateMaxQtyFromSelectedVariants() {
            const selectedBtns = document.querySelectorAll('.variant-btn.selected');
            let minQty = Infinity;

            selectedBtns.forEach(btn => {
                const stockQty = parseInt(btn.getAttribute('data-stock-qty')) || 0;
                if (stockQty < minQty) {
                    minQty = stockQty;
                }
            });

            if (minQty === Infinity || minQty === 0) {
                minQty = parseInt('{{ $product->quantity }}') || 0;
            }

            const qtyInput = document.getElementById('sc-qty');
            if (qtyInput) {
                qtyInput.dataset.max = minQty;

                const currentQty = parseInt(qtyInput.value) || 1;
                if (currentQty > minQty) {
                    qtyInput.value = minQty > 0 ? minQty : 1;
                    currentQtyValue = parseInt(qtyInput.value);

                    if (window.livewire) {
                        @this.set('quantity', parseInt(qtyInput.value));
                    }
                }

                updateQtyButtons();
            }
        }
        </script>
    @endsection
</div>
