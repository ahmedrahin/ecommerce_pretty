<x-default-layout>
    @section('title') Printful Product Details @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $product['name'] }}</h3>
        </div>

        <div class="card-body">
            <div class="mb-4">
                <img src="{{ $product['thumbnail_url'] }}" alt="thumbnail" width="150">
                <p>Store Product ID: {{ $product['id'] }}</p>
                <p>Total Variants: {{ count($variants) }}</p>
            </div>

            <h5>Variants</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Variant ID</th>
                        <th>Name</th>
                        <th class="text-center">Size</th>
                        <th class="text-center">Color</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">SKU</th>
                        <th class="text-center">Thread Colors</th>
                        <th class="text-center">Embroidery Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variants as $v)
                    <tr>
                        <td class="text-center">{{ $v['printful_variant_id'] }}</td>
                        <td>{{ $v['name'] }}</td>
                        <td class="text-center">{{ $v['size'] ?? '-' }}</td>
                        <td class="text-center">{{ $v['color'] ?? '-' }}</td>
                        <td class="text-center">{{ $v['price'] }}</td>
                        <td class="text-center">{{ $v['sku'] }}</td>
                        <td class="text-center">
                            @if($v['thread_colors'])
                                {{ implode(', ', $v['thread_colors']) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">{{ $v['embroidery_type'] ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-default-layout>
