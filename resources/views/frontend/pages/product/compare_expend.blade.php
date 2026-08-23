{{-- product attributes --}}
@if($allAttributes->isNotEmpty())
    <thead>
        <tr>
            <td colspan="3">Product Variants <i class="material-icons">expand_more</i></td>
        </tr>
    </thead>
    <tbody>
        @foreach($allAttributes as $attrId => $attrName)
        <tr>
            <td class="name">{{ $attrName }}</td>
            @foreach($products as $product)
                @php
                    $values = [];
                    foreach ($product->productStock as $stock) {
                        foreach ($stock->attributeOptions as $option) {
                            if ($option->attribute_id == $attrId) {
                                $values[] = $option->attributeValue->attr_value ?? null;
                            }
                        }
                    }

                    $values = array_filter(array_unique($values));
                @endphp
                <td class="value" style="text-align: center;">
                    {{ !empty($values) ? implode(', ', $values) : '-' }}
                </td>
            @endforeach

            @for ($i = $products->count(); $i < 3; $i++) 
                <td class="value" style="text-align: center;">-</td>
            @endfor
        </tr>
        @endforeach
    </tbody>
@endif

{{-- product specifications --}}
@if($allSpecifications->isNotEmpty())
    @foreach($allSpecifications as $group => $specs)
    <thead>
        <tr>
            <td colspan="3">{{ $group ?? 'General' }} <i class="material-icons">expand_more</i></td>
        </tr>
    </thead>
    <tbody>
        @foreach($specs as $specName)
        <tr>
            <td class="name">{{ $specName }}</td>
            @foreach($products as $product)
                @php
                    $spec = $product->specifications->firstWhere('name', $specName);
                @endphp
                <td class="value" style="text-align: center;">
                    {{ $spec->value ?? '-' }}
                </td>
            @endforeach

            {{-- fill missing product columns up to 3 --}}
            @for ($i = $products->count(); $i < 3; $i++) 
                <td class="value" style="text-align: center;">-</td>
            @endfor
        </tr>
        @endforeach
    </tbody>
    @endforeach
@endif