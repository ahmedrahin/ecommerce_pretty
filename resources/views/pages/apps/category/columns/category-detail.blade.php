<div class="d-flex align-items-center">
    <!--begin::Thumbnail-->
    <span class="symbol symbol-50px">
        @php
            $imagePath = $category->image ? $category->image : 'uploads/blank-image.svg'; 
            $imageUrl = asset($imagePath);
        @endphp
        <span class="symbol-label" style="background-image:url({{$imageUrl}});"></span>
    </span>
    <!--end::Thumbnail-->
    <div class="ms-3">
        <!--begin::Title-->
        <span class="text-gray-800 fs-5 fw-bold mb-1">{{$category->name}}</span>
        <!--end::Title-->
        <!--begin::Description-->
       @if(!empty($category->description))
            <div class="text-muted fs-7 fw-bold">{{ Str::limit($category->description, 30) }}</div>
        @else
        @endif
        
        <!--end::Description-->
    </div>
</div>

<!-- details modal -->
<div class="modal fade" id="kt_modal_category_details{{$category->id}}" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_category_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ $category->name }} Details</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 mb-4 mt-0">
                <dl class="row">
                    <div class="col-12">
                        <img src="{{$imageUrl}}" alt="" class="modal-image">
                    </div>
                    <dt class="col-sm-3">Name:</dt>
                    <dd class="col-sm-9">{{ $category->name }}</dd>
                
                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9">
                    {{ $category->description ? $category->description : 'N/A' }}
                    </dd>
                
                    <dt class="col-sm-3">Product_sum:</dt>
                    <dd class="col-sm-9">
                        @php 
                        $productCount = $category->product->count(); 
                            if ($productCount == 0) {
                                $count = '<span class="badge badge-light-danger">0</span>';
                            } elseif ($productCount > 0 && $productCount <= 5) {
                                $count = '<span class="badge badge-light-warning">' . $productCount . '</span>';
                            } else {
                                $count = '<span class="badge badge-light-primary">' . $productCount . '</span>';
                            }
                            echo $count;
                        @endphp
                    </dd>
            
                    <dt class="col-sm-3">Subcategory_sum:</dt>
                    <dd class="col-sm-9">
                        @php 
                            $subCatCount = $category->subcategories->count(); 
                                if ($subCatCount == 0) {
                                    $count = '<span class="badge badge-light-danger">0</span>';
                                } elseif ($subCatCount > 0 && $subCatCount <= 5) {
                                    $count = '<span class="badge badge-light-warning">' . $subCatCount . '</span>';
                                } else {
                                    $count = '<span class="badge badge-light-primary">' . $subCatCount . '</span>';
                                }
                                echo $count;
                        @endphp
                    </dd>
                </dl>

                <div class="subcategorize" class="mt-15">
                    <h4>Sub-categories List </h4>
                    @if( $category->subcategories->count() > 0 )
                        <ul>
                            @foreach( $category->subcategories as $index => $subcategory )
                            <li>
                                <div class="d-flex justify-content-between">
                                    <div>
                                    <span class="badge badge-light-primary" style="margin-right: 3px;">{{$index+1}}</span>
                                    {{ $subcategory->name }}
                                    </div>
                                    <button class="delsubCat" data-kt-sub-id="{{$subcategory->id}}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="no-found">No Subcategory available</div>
                    @endif
                </div>
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>

