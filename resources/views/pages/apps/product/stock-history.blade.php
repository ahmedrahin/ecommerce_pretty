<div class="col-xl-12">
    <!--begin::Table Widget 5-->
    <div class="card card-flush h-xl-100">
        <!--begin::Card header-->
        <div class="card-header pt-7">
            <!--begin::Title-->
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">Stock Report</span>
                <span class="text-gray-400 mt-1 fw-semibold fs-6">Total Stock {{ $product->stockHistories->sum('quantity') }} Psc & Total stock amount ${{ format_price($product->stockHistories->sum('total_amount')) }} </span>
            </h3>

        </div>
        <div class="card-body">
            <div id="kt_table_widget_5_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-3 dataTable no-footer"
                        id="kt_table_widget_5_table">

                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-start pe-3 min-w-150px sorting" tabindex="0"
                                    aria-controls="kt_table_widget_5_table" rowspan="1" colspan="1"
                                    aria-label="Date Added: activate to sort column ascending"
                                    style="width: 151.312px;">Date</th>
                                <th class="text-center pe-3 min-w-100px sorting" tabindex="0"
                                    aria-controls="kt_table_widget_5_table" rowspan="1" colspan="1"
                                    aria-label="Price: activate to sort column ascending" style="width: 100.922px;">
                                    Selling Price</th>
                                <th class="text-center pe-3 min-w-100px sorting" tabindex="0"
                                    aria-controls="kt_table_widget_5_table" rowspan="1" colspan="1"
                                    aria-label="Price: activate to sort column ascending" style="width: 100.922px;">
                                    Wholesale Price</th>
                                <th class="text-center pe-3 min-w-100px sorting" tabindex="0"
                                    aria-controls="kt_table_widget_5_table" rowspan="1" colspan="1"
                                    aria-label="Status: activate to sort column ascending" style="width: 100.922px;">
                                    Total Amount</th>
                                <th class="text-center pe-3 min-w-100px sorting" tabindex="0"
                                    aria-controls="kt_table_widget_5_table" rowspan="1" colspan="1"
                                    aria-label="Status: activate to sort column ascending" style="width: 100.922px;">
                                    Status</th>
                                <th class="text-end pe-0 min-w-75px sorting" tabindex="0"
                                    aria-controls="kt_table_widget_5_table" rowspan="1" colspan="1"
                                    aria-label="Qty: activate to sort column ascending" style="width: 75.6719px;">Qty
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600">
                            @foreach ($product->stockHistories as $stock)
                                <tr class="odd">
                                    <td class="text-start">
                                        @if ($stock->stock === 'out_of_stock')
                                            {{ \Carbon\Carbon::parse($stock->created_at)->format('d M, Y') }}
                                        @elseif($stock->stock === 'stock_in')
                                            {{ \Carbon\Carbon::parse($stock->stocked_at)->format('d M, Y') }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $stock->stock === 'stock_in' ? '$' . format_price($stock->product_price) : '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $stock->stock === 'stock_in' ? '$' . format_price($stock->wholesale_price) : '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $stock->stock === 'stock_in' ? '$' . format_price($stock->wholesale_price * $stock->quantity) : '' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($stock->stock === 'out_of_stock')
                                            <span class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
                                        @elseif($stock->stock === 'stock_in')
                                            <span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                                        @endif
                                    </td>
                                    <td class="text-end" data-order="58">
                                        <span class="text-dark fw-bold">{{ $stock->quantity }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="row">
                    <div
                        class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                    </div>
                    <div
                        class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                    </div>
                </div>
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Table Widget 5-->
</div>
