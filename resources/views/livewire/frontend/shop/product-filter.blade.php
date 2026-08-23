<aside class="sidebar shop-sidebar sticky-sidebar-wrapper sidebar-fixed">
    <!-- Start of Sidebar Overlay -->
    <div class="sidebar-overlay"></div>
    <a class="sidebar-close" href="#"><i class="close-icon"></i></a>

    <!-- Start of Sidebar Content -->
    <div class="sidebar-content scrollable">
        <!-- Start of Sticky Sidebar -->
        <div class="sticky-sidebar">
            
            <!-- Start of Collapsible Widget -->
            <div class="widget widget-collapsible">
                <h3 class="widget-title"><label>Categories</label></h3>
                <ul class="widget-body filter-items item-check mt-1">
                    @foreach($categories as $category)
                    <li>
                        <div>
                            <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}"
                                id="cat-{{ $category->id }}" class="custom-checkbox">
                            <label for="cat-{{ $category->id }}">
                                {{ $category->name }}
                                @if(config('website_settings.product_count_enabled'))
                                <span>({{ $category->product()->activeProducts()->count() }})</span>
                                @endif
                            </label>
                        </div>
                    </li>

                    {{-- Subcategories --}}
                    @if($category->subcategories->isNotEmpty())
                    <ul class="subcategory-list" style="margin-left: 15px;">
                        @foreach($category->subcategories as $subcategory)
                            <li
                                style="{{ $subcategory->subsubcategories->count() > 0 ? 'display: flex; flex-direction: column;' : '' }}">
                                <div>
                                    <input type="checkbox" wire:model="selectedSubCategories" value="{{ $subcategory->id }}"
                                        id="subcat-{{ $subcategory->id }}" class="custom-checkbox">
                                    <label for="subcat-{{ $subcategory->id }}">
                                        {{ $subcategory->name }}
                                        @if(config('website_settings.product_count_enabled'))
                                        <span>({{ $subcategory->products()->activeProducts()->count() }})</span>
                                        @endif
                                    </label>
                                </div>

                                {{-- Subsubcategories --}}
                                @if($subcategory->subsubcategories->isNotEmpty())
                                <ol class="subsubcategory-list" style="margin-left: 20px;">
                                    @foreach($subcategory->subsubcategories as $subsubcategory)
                                    <li>
                                        <div>
                                            <input type="checkbox" wire:model="selectedSubSubCategories"
                                                value="{{ $subsubcategory->id }}" id="subsubcat-{{ $subsubcategory->id }}"
                                                class="custom-checkbox">
                                            <label for="subsubcat-{{ $subsubcategory->id }}">
                                                {{ $subsubcategory->name }}
                                                @if(config('website_settings.product_count_enabled'))
                                                <span>({{ $subsubcategory->products()->activeProducts()->count() }})</span>
                                                @endif
                                            </label>
                                        </div>
                                    </li>
                                    @endforeach
                                </ol>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @endif
                    @endforeach
                </ul>

            </div>
            <!-- End of Collapsible Widget -->

        </div>
        <!-- End of Sidebar Content -->
    </div>
    <!-- End of Sidebar Content -->
</aside>