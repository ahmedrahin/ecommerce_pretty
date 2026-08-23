<div class="flat-spacing flat-animate-tab pt-0">
    <div class="container">
        <div class="sect-title wow fadeInUp">
            <div class="h1 title text-center mb-24">Featured Products</div>
        </div>

        @if (!$featuredProducts->isEmpty())
               <div class="row gap-0 px-2" >
                    @foreach ($featuredProducts as $product)
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