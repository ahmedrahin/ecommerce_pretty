<div class="dz-info" style="border: none;"> 
    <ul> 
      <li>
        <div class="d-flex align-items-center gap-2"> 
          <h6>Sku:</h6>
          <p> {{$product->sku_code}} </p>
        </div>
      </li>
      <li> 
        <div class="d-flex align-items-center gap-2"> 
          <h6>Quantity: </h6>
          
            @if($product->quantity > 0)
                <p class="text-success">Available: {{ $product->quantity }}</p>
            @else
                <p class="text-danger">Out of stock</p>
            @endif

        </div>
      </li>
      {{-- category --}}
      @if( !is_null($product->category_id) && isset($product->category) )
        <li> 
          <div class="d-flex align-items-center gap-2"> 
            <h6>Category: </h6>
            
            @if ($product->category)
                {!! $product->category->name !!}
                @if ($product->subcategory)
                    <i class="bi bi-arrow-right-short"></i>{!! $product->subcategory->name !!}
                    @if ($product->subsubcategory)
                    <i class="bi bi-arrow-right-short"></i>{!! $product->subsubcategory->name !!}
                    @endif
                @endif
            @else
                <span class="no">Uncategorized</span>
            @endif

          </div>
        </li>
      @endif
      {{-- brand --}}
      @if( !is_null($product->brand_id) && isset($product->brand) )
         <li>
            <div class="d-flex align-items-center gap-2"> 
              <h6>Brand: </h6>
              <p>{{$product->brand->name}}</p>
            </div>
         </li>
      @endif
    </ul>
  </div>