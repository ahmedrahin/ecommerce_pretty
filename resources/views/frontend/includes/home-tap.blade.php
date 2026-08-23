 <div class="flat-spacing flat-animate-tab pt-0">
    <div class="container">
        <div class="sect-title wow fadeInUp">
            <div class="h1 title text-center mb-24">Most Popular Products</div>
            <ul class="tab-product_list" role="tablist">
                <li class="nav-tab-item" role="presentation">
                    <a href="#trending" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab active"> TRENDING </a>
                </li>
                <li class="nav-tab-item" role="presentation">
                    <a href="#best-seller" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab"> Best selling </a>
                </li>
                <li class="nav-tab-item" role="presentation">
                    <a href="#on-sale" data-bs-toggle="tab" class="tf-btn-line tf-btn-tab"> Top Rated </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane active show" id="trending" role="tabpanel">
                @if (!$trending->isEmpty())
                   <div class="row gap-0 px-2" >
                        @foreach ($trending as $product)
                            <div class="col-md-3 col-6 p-md-3 p-2 productBoxItem">
                                @include('frontend.includes.product-info')
                            </div>  
                        @endforeach
                    </div>
                @else
                    @include('frontend.includes.no-found')
                @endif
            </div>

            <div class="tab-pane" id="best-seller" role="tabpanel">
                @if (!$selling->isEmpty())
                    <div class="row gap-0 px-2" >
                        @foreach ($selling as $product)
                            <div class="col-md-3 col-6 p-md-3 p-2 productBoxItem">
                                @include('frontend.includes.product-info')
                            </div>  
                        @endforeach
                    </div>
                @else
                    @include('frontend.includes.no-found')
                @endif
            </div>

            <div class="tab-pane" id="on-sale" role="tabpanel">
                @if (!$topReviewed->isEmpty())
                    <div class="row gap-0 px-2" >
                        @foreach ($topReviewed as $product)
                            <div class="col-md-3 col-6 p-md-3 p-2 productBoxItem">
                                @include('frontend.includes.product-info')
                            </div>  
                        @endforeach
                    </div>
                @else
                    @include('frontend.includes.no-found')
                @endif
            </div>
        </div>
    </div>
</div>