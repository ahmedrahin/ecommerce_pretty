<div class="col-xl-12">
    <div class="card card-flush h-xl-100">
        <div class="card-header pt-7">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">Stock Report</span>
                <span class="text-gray-400 mt-1 fw-semibold fs-6">
                    Total Stock {{ $product->stockHistories->sum('quantity') }} Pcs &
                    Total Amount {{ format_price($product->stockHistories->sum('total_amount')) }}৳
                </span>
            </h3>

            {{-- ✅ Variation Filter --}}
            <div class="card-toolbar">
                <select id="variation_filter" class="form-select form-select-sm w-200px">
                    <option value="">All Variations</option>
                    <option value="single">Single Product</option>
                    @foreach ($product->stockHistories->whereNotNull('variation_label')->pluck('variation_label')->unique() as $label)
                        <option value="{{ $label }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-3" id="stock_history_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase">
                            <th>Date</th>
                            <th>Variation</th>
                            <th class="text-center">Selling Price</th>
                            <th class="text-center">Wholesale Price</th>
                            <th class="text-center">Total Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Qty</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($product->stockHistories as $stock)
                            <tr data-variation="{{ $stock->variation_label ?? 'single' }}">
                                <td>
                                    {{ \Carbon\Carbon::parse(
                                        $stock->stock === 'stock_in' ? $stock->stocked_at : $stock->created_at
                                    )->format('d M, Y') }}
                                </td>
                                <td>
                                    @if ($stock->variation_label)
                                        <span class="badge badge-light-primary">
                                            {{ $stock->variation_label }}
                                        </span>
                                    @else
                                        <span class="badge badge-light-secondary">Single</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $stock->stock === 'stock_in' ? format_price($stock->product_price) . '৳' : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $stock->stock === 'stock_in' ? format_price($stock->wholesale_price) . '৳' : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $stock->stock === 'stock_in' ? format_price($stock->wholesale_price * $stock->quantity) . '৳' : '-' }}
                                </td>
                                <td class="text-center">
                                    @if ($stock->stock === 'out_of_stock')
                                        <span class="badge badge-light-danger">Out of Stock</span>
                                    @else
                                        <span class="badge badge-light-primary">In Stock</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="text-dark fw-bold">{{ $stock->quantity }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

