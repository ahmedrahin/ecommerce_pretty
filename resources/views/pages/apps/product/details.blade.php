<x-default-layout>

    @section('title')
        {{ $product->name }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('product-details') }}
    @endsection

    @push('styles')
        <style>
           .delivery-percent .col-xl-3 {
                margin-top: 0;
            }

            .delivery-percent .g-xl-8,
            .gx-xl-8 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }

            .delivery-percent .card .card-body {
                padding: 10px 20px 10px !important;
            }

        </style>
    @endpush

    <!-- font awsome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- produc details section start -->

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div class="" id="kt_app_content_container">
            <div class="card mb-4" style="border: none !important;">
                <div class="px-7 info">
                    <div class="row">
                        <div class="col-md-4 p-0">
                            <div class="product-images">
                                <div class="thumb-img pt-8">
                                    @php
                                        $imagePath = $product->thumb_image ?? 'uploads/blank-image.svg';
                                        $imageUrl = $imagePath;
                                    @endphp
                                    <img src="{{ asset($imageUrl) }}" alt="">
                                </div>
                                <div class="row mb-3 row-cols-auto g-2 justify-content-center mt-4 pb-3 mx-5"
                                    style="overflow: hidden;">
                                    @if ($product->galleryImages->count() > 4)
                                        <div class="swiper-container">
                                            <div class="swiper-wrapper">
                                                @foreach ($product->galleryImages ?? [] as $gallery)
                                                    <div class="swiper-slide">
                                                        <img src="{{ asset($gallery->image) }}" width="70"
                                                            height="70" class="border rounded cursor-pointer"
                                                            alt="" style="object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        @foreach ($product->galleryImages ?? [] as $gallery)
                                            <div class="col">
                                                <img src="{{ asset($gallery->image) }}" width="70" height="70"
                                                    class="border rounded cursor-pointer" alt="">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 p-0">
                            <div class="product-information pt-8">
                                <h2>{{ $product->name }}</h2>
                                @php
                                    $averageRating = round($product->reviews->avg('rating'), 1);
                                    $fullStars = round($averageRating);
                                    $halfStar = $averageRating - $fullStars >= 0.5 ? 1 : 0;
                                    $emptyStars = 5 - $fullStars - $halfStar;
                                @endphp
                                <div class="rating my-4">
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <div class="rating-label checked">
                                            <i class="ki-duotone ki-star fs-6"></i>
                                        </div>
                                    @endfor
                                    @if ($halfStar)
                                        <div class="rating-label checked">
                                            <i class="ki-duotone ki-star-half fs-6"></i>
                                        </div>
                                    @endif
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <div class="rating-label">
                                            <i class="ki-duotone ki-star fs-6"></i>
                                        </div>
                                    @endfor
                                    <span>{{ $product->reviews->count() }} reviews</span>
                                </div>

                                <div class="order-info mb-2">
                                    <ul>
                                        <li>
                                            {{ $product->orderItems()->sum('quantity') }} Orders
                                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                        </li>
                                        {{-- <li>
                                            {{ $product->orderItems()->sum('quantity') }} Orders
                                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                        </li> --}}
                                        <li style="border-right: none;">
                                            {{ $product->wishlist->count() }} Wishlist
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-price">
                                    <h4 class="mt-3">
                                        {{ $product->offer_price }}৳
                                        @if ($product->discount_option == 2 || $product->discount_option == 3)
                                            <span class="text-danger" style="font-weight: 600;">
                                                <del>{{ formatPrice($product->base_price) }}৳</del>
                                                ({{ formatPrice($product->discount_percentage_or_flat_amount) }}{{ $product->discount_option == 2 ? '%' : '' }})
                                            </span>
                                        @endif
                                    </h4>
                                </div>

                                <div class="short-description mb-3">
                                    @if (!is_null($product->short_description) && $product->short_description != '<p><br></p>')
                                        {!! $product->short_description !!}
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="product-details">
                                            <h3>General Information</h3>
                                            <dl class="row">
                                                <dt class="col-sm-4">Sku code:</dt>
                                                <dd class="col-sm-8">{{ $product->sku_code }}</dd>

                                                <dt class="col-sm-4">Category:</dt>
                                                <dd class="col-sm-8">
                                                    @if ($product->category)
                                                        <div class="mb-2">
                                                            <span class="badge bg-primary">{!! $product->category->name !!}</span>
                                                        </div>
                                                        
                                                        @if($product->subcategories && $product->subcategories->count() > 0)
                                                            <div class="mb-2">
                                                                <span class="fw-bold">Subcategories:</span>
                                                                <div class="mt-1">
                                                                    @foreach($product->subcategories as $subcategory)
                                                                        <span class="badge bg-info me-1">{!! $subcategory->name !!}</span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($product->subsubcategories && $product->subsubcategories->count() > 0)
                                                            <div>
                                                                <span class="fw-bold">Subsubcategories:</span>
                                                                <div class="mt-1">
                                                                    @foreach($product->subsubcategories as $subsubcategory)
                                                                        <span class="badge bg-secondary me-1">{!! $subsubcategory->name !!}</span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Uncategorized</span>
                                                    @endif
                                                </dd>
                                                <dt class="col-sm-4">Brand:</dt>
                                                <dd class="col-sm-8">{{ $product->brand->name ?? 'N/A' }}</dd>

                                                <dt class="col-sm-4">Available Quantity:</dt>
                                                <dd class="col-sm-8">
                                                    @if ($product->quantity > 0)
                                                        <span class="text-success">{{ $product->quantity }} pcs</span>
                                                    @else
                                                        <span class="text-danger p-0">Out of stock!</span>
                                                    @endif
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="product-details">
                                            <h3>Others Information</h3>
                                            <dl class="row">
                                                <dt class="col-sm-4">Created at:</dt>
                                                <dd class="col-sm-8">
                                                    {{ \Carbon\Carbon::parse($product->created_at)->format('M-d-Y h:i A') }} ({{ $product->created_at->diffForHumans() }})
                                                </dd>
                                                <dt class="col-sm-4">Last Updated:</dt>
                                                <dd class="col-sm-8">
                                                    @if ($product->created_at != $product->updated_at)
                                                        {{ \Carbon\Carbon::parse($product->updated_at)->format('M-d-Y h:i A') }} ({{ $product->updated_at->diffForHumans() }})
                                                    @else
                                                        No update yet
                                                    @endif
                                                </dd>
                                                <dt class="col-sm-4">Created by:</dt>
                                                <dd class="col-sm-8">
                                                    @if ($product->user_id && $product->user)
                                                        @php
                                                            $route =
                                                                $product->user->isAdmin == 1
                                                                    ? route(
                                                                        'admin-management.admin-list.show',
                                                                        $product->user->id,
                                                                    )
                                                                    : route(
                                                                        'user-management.users.show',
                                                                        $product->user->id,
                                                                    );
                                                        @endphp

                                                        <a href="{{ $route }}" target="_blank">
                                                            {{ $product->user->name }}
                                                        </a>

                                                        @if ($product->user->trashed())
                                                            <span class="text-danger"
                                                                style="font-size: 12px;">(deleted)</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">{{ 'Admin delete' }}</span>
                                                    @endif
                                                </dd>
                                                @if (!is_null($product->expire_date))
                                                    <dt class="col-sm-4">Expire date:</dt>
                                                    <dd class="col-sm-8 text-danger">
                                                        @if (\Carbon\Carbon::now()->lt($product->expire_date))
                                                            {{ \Carbon\Carbon::parse($product->expire_date)->format('M-d-Y h:i A') }}
                                                            ({{ \Carbon\Carbon::now()->diffForHumans($product->expire_date, [
                                                                'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                                                'parts' => 3, // Limit to days, hours, and minutes
                                                            ]) }})
                                                        @else
                                                            Expired
                                                        @endif
                                                    </dd>

                                                @endif
                                            </dl>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-variant mb-8">
                                    @php
                                        $productStocks = $product->productStock ?? collect();
                                    @endphp

                                    @if ($productStocks->isNotEmpty())
                                        <h3 class="mb-4">Product Variants</h3>

                                        <div class="table-responsive">
                                            <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 gy-3">
                                                <thead>
                                                    <tr class="fw-bold text-gray-400 fs-7 text-uppercase">
                                                        <th>Variation</th>
                                                        <th class="text-center">Price</th>
                                                        <th class="text-center">Stock</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($productStocks as $productStock)
                                                        @php
                                                            $label = $productStock->attributeOptions->map(function ($opt) {
                                                                $attr = $opt->attribute->attr_name ?? '';
                                                                $val = $opt->attributeValue->attr_value ?? '';
                                                                return $attr && $val ? $attr . ': ' . $val : ($val ?: $attr);
                                                            })->filter()->implode(' / ');
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <span class="fw-bold text-gray-800">
                                                                    {{ $label ?: 'Default' }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ format_price($productStock->price) }}৳
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="fw-bold {{ $productStock->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $productStock->quantity }} Pcs
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($productStock->quantity > 10)
                                                                    <span class="badge badge-light-success">In Stock</span>
                                                                @elseif ($productStock->quantity > 0)
                                                                    <span class="badge badge-light-warning">Low Stock</span>
                                                                @else
                                                                    <span class="badge badge-light-danger">Out of Stock</span>
                                                                @endif
                                                            </td>
                                                           <td class="text-center">
                                                                <div class="form-check form-switch form-check-custom form-check-solid justify-content-center">
                                                                    <input class="form-check-input toggle-variant-btn" type="checkbox"
                                                                        data-id="{{ $productStock->id }}"
                                                                        {{ $productStock->is_disabled ? '' : 'checked' }} />
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="fw-bold fs-6 border-top">
                                                        <td colspan="2" class="text-end text-gray-600">Total Stock:</td>
                                                        <td class="text-center text-dark">
                                                            {{ $productStocks->sum('quantity') }} Pcs
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
                                        <span class="text-danger" style="font-style:italic; font-weight:600;">
                                            No variant found in this product.
                                        </span>
                                    @endif
                                </div>

                                {{-- <div class="color-indigator-item bg-primary" style="background-color: #000 !important"></div>  --}}
                                <div class="d-flex justify-content-center btn-item">

                                    <a href="{{ route('product-details', $product->slug) }}"
                                        class="btn btn-light-primary me-2" target="_blank">
                                        <i class="fa fa-globe" aria-hidden="true" style="font-size: 15px;"></i>
                                        View Website
                                    </a>
                                    <a href="{{ route('product-management.edit', $product->id) }}"
                                        class="btn btn-primary">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        Edit
                                    </a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- product status -->
                <div class="status" style="text-align: right">
                    @if ($product->status == 1)
                        <span class="badge badge-light-success">Active</span>
                    @elseif($product->status == 0)
                        <span class="badge badge-light-danger">Inactive</span>
                    @elseif($product->status == 2)
                        <span class="badge badge-light-danger">Draf</span>
                    @elseif($product->status == 3)
                        <span class="badge badge-light-warning">Scheduled</span>
                        <p class="text-success"
                            style="margin-bottom: 0;font-weight:600;margin-top: 4px;font-size:11px;">
                            {{ \Carbon\Carbon::parse($product->publish_at)->format('Y-d-M h:i A') }}
                        </p>
                    @endif
                </div>

                <!-- tab -->
                <div class="mb-5 tab-section">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="contact-tab" data-bs-toggle="tab" data-bs-target="#order"
                                type="button" role="tab" aria-controls="contact" aria-selected="true">
                                <i class="bi bi-clipboard-data"></i>
                                Order Analysis
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#stock"
                                type="button" role="tab" aria-controls="contact" aria-selected="true">
                                <i class="bi bi-clipboard-data"></i>
                                Stock History
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                aria-selected="false">
                                <i class="bi bi-chat-left-text"></i>
                                Product Description
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false">
                                <i class="bi bi-bookmark-check"></i>
                                Tags
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                type="button" role="tab" aria-controls="contact" aria-selected="false">
                                <i class="bi bi-star"></i>
                                Reviews
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="home" role="tabpanel"
                            aria-labelledby="home-tab">
                            @if (!is_null($product->long_description) && $product->long_description != '<p><br></p>')
                                {!! $product->long_description !!}
                            @else
                                <span class="no">No description found in this product.</span>
                            @endif
                        </div>
                        <div class="tab-pane tags fade" id="profile" role="tabpanel"
                            aria-labelledby="profile-tab">
                            @if ($product->tags->count() > 0)
                                <dd class="col-sm-9">
                                    @foreach ($product->tags ?? [] as $tag)
                                        <span>{{ $tag->name }}</span>
                                    @endforeach
                                </dd>
                            @else
                                <div class="no">No tags found in this product!</div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <livewire:product.customer-review :productId="$product->id" />
                        </div>
                        <div class="tab-pane fade show active" id="order" role="tabpanel" aria-labelledby="contact-tab">
                            @include('pages.apps.product.order-analysis')
                        </div>
                        <div class="tab-pane fade " id="stock" role="tabpanel" aria-labelledby="contact-tab">
                            @include('pages.apps.product.stock-history')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- produc details section end -->
    @push('scripts')
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            var swiper = new Swiper('.swiper-container', {
                slidesPerView: 4, // Show 4 images at a time
                spaceBetween: 1, // Space between slides
                loop: false, // Disable looping
            });
            $('.d-flex.align-items-center.gap-2.gap-lg-3').html(`<a href="{{ route('product-management.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left fs-2"></i>
                    All Product
                </a>`)
        </script>

        {{-- Filter JS --}}
        <script>
            $('#variation_filter').on('change', function () {
                const selected = $(this).val();

                $('#stock_history_table tbody tr').each(function () {
                    const rowVariation = $(this).data('variation');

                    if (!selected) {
                        $(this).show(); // সব দেখাও
                    } else if (selected === 'single') {
                        $(this).toggle(rowVariation === 'single');
                    } else {
                        $(this).toggle(rowVariation === selected);
                    }
                });
            });

            $(document).on('change', '.toggle-variant-btn', function () {
                const checkbox = $(this);
                const stockId  = checkbox.data('id');

                $.ajax({
                    url:  `/admin/product-stock/${stockId}/toggle`,
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        const isDisabled = response.is_disabled;
                        Swal.fire({ text: response.message, icon: 'success', timer: 1500, showConfirmButton: false });
                    },
                    error: function () {
                        checkbox.prop('checked', !checkbox.prop('checked'));
                        Swal.fire({ text: 'Something went wrong', icon: 'error', timer: 1500, showConfirmButton: false });
                    }
                });
            });
        </script>
    @endpush
</x-default-layout>
