<div >
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h2>Create product ({{$total}})</h2>
            </div>
            <div class="card-toolbar">
                <a href="{{route('product-management.create')}}" class="btn btn-primary" style="font-size: 12px;padding: 10px;font-weight: 700;">Create new product</a>
            </div>
        </div>

        <div class="card-body pt-0">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" >
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th>Sl.</th>
                            <th class="min-w-150px">Name</th>
                            <th class="min-w-150px text-center">Price</th>
                            <th class="min-w-150px text-center">Sku_code</th>
                            <th class="min-w-150px text-center">Qty</th>
                            <th class="min-w-100px text-center">Order</th>
                            <th class="text-end ">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse($products as $product)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    <a target="_blank" class="text-gray-800 text-hover-primary fs-5 fw-bold" href="{{ route('product-management.show', $product->id) }}" style="font-size: 13px !important;">
                                        {{ \Illuminate\Support\Str::limit($product->name, 17, '...') }}
                                    </a>                                
                                </td>

                                <td class="text-center">
                                    @if ($product->discount_option == 1)
                                        {{ $product->base_price }}৳
                                    @else
                                        {{ $product->offer_price }}৳
                                        <br>
                                        <del style="color: #f1416cad">{{ $product->base_price }}৳</del>
                                    @endif
                                </td>

                                <td class="text-center">{{$product->sku_code}}</td>

                                <td class="text-center">
                                    @if ($product->quantity == 0)
                                        <span class="badge badge-light-danger">Sold out</span>
                                    @elseif ($product->quantity > 0 && $product->quantity <= 5)
                                        <span class="badge badge-light-warning">Low stock: {{ $product->quantity }}</span>
                                    @else
                                        <span class="badge badge-light-primary">{{ $product->quantity }}</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{$product->orderItems->count()}}
                                </td>
                                
                                <td class="text-center  text-end text-nowrap">
                                    
                                    <a href="{{route('product-management.show', $product->id)}}" class="btn btn-icon btn-active-light-primary w-30px h-30px" style="margin-right: -10px !important;" target="_blank">
                                        <i class="ki-duotone ki-eye fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </a>
                                    
                                    {{-- @can('delete product')
                                    <button class="btn btn-icon btn-active-light-primary w-30px h-30px" data-kt-action="delete_product" data-product-id="{{$product->id}}">
                                        <i class="ki-duotone ki-trash fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                    @else
                                        <button class="btn btn-icon btn-active-light-primary w-30px h-30px" onclick="errorAccess('You do not have permission to delete product.')">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    @endif --}}

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!--end::Table body-->
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center" style="padding: 20px 0 10px;">
                <!-- Total and current records count -->
                <p style="margin: 0; font-weight: 500; color: #99A1B7;">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $total }} entries.
                </p>
                
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
</div>
