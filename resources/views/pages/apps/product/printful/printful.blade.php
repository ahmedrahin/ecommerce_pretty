<x-default-layout>

    @section('title')
        Printful Products
    @endsection

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Printful Product Listing</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th class="text-center">Thumbnail</th>
                        <th>Product Name</th>
                        <th class="text-center">Variants</th>
                        <th class="text-center">Details</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td class="text-center">
                                <img src="{{ $product['thumbnail'] }}"
                                     alt="thumbnail"
                                     width="60"
                                     class="rounded">
                            </td>

                            <td>
                                <strong>{{ $product['name'] }}</strong><br>
                                <small class="text-muted">
                                    Store ID: {{ $product['store_product_id'] }}
                                </small>
                            </td>

                            <td class="text-center">
                                {{ $product['variants_count'] }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('product-management.printful.details', $product['store_product_id']) }}" class="btn btn-sm btn-primary">
                                    Variants
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                No Printful products found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-default-layout>
