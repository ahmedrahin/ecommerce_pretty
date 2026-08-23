<div class="d-flex align-items-center">
    <!--begin::Thumbnail-->
    <span class="symbol symbol-50px">
        @php
            $imagePath = $subcategory->image ? $subcategory->image : 'uploads/blank-image.svg'; 
            $imageUrl = asset($imagePath);
        @endphp
        <span class="symbol-label" style="background-image:url({{$imageUrl}});"></span>
    </span>
    <!--end::Thumbnail-->
    <div class="ms-3">
        <!--begin::Title-->
        <span class="text-gray-800 fs-5 fw-bold mb-1">{{$subcategory->name}}</span>
        @if(!empty($subcategory->description))
            <div class="text-muted fs-7 fw-bold">{{ Str::limit($subcategory->description, 30) }}</div>
        @else
        @endif
    </div>
</div>

<!-- details modal -->

