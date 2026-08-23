
<div class="d-flex align-items-center">
    <!--begin::Thumbnail-->
    <span class="symbol">
        @php
            $url = route('product-management.show', $product->id);
            $imagePath = $product->thumb_image ? $product->thumb_image : 'uploads/blank-image.svg'; 
            $imageUrl = asset($imagePath);
            $truncatedName = Str::limit($product->name, 20, '...');
        @endphp
        <a href="{{$url}}" target="_blank">
            <img src="{{$imageUrl}}" class="table-product-image" style="object-fit: cover; margin-right: 6px;">
        </a>
    </span>
    <!--end::Thumbnail-->
    <div class="ms-1">
        <!--begin::Title-->
        <a href="{{$url}}" target="_blank" class="text-gray-800 text-hover-primary fs-5 fw-bold">
            <span>{{$truncatedName}}</span>
        </a>
        <div class="text-muted fs-9 fw-bold" style="padding-top: 2px;">created: {{$product->created_at->diffForHumans()}}</div>
        
    </div>
</div>
