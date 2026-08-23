@php
    $categories = Cache::rememberForever(config('dbcachekey.menu_category'), function () {
        return \App\Models\Category::with('subcategories')->where('status', 1)->where('featured',1)->get();
    });
@endphp

@foreach ($categories as $category)
    <div class="col-2">
        <div class="mega-menu-item">
            <h4 class="menu-heading">
                <a href="{{ route('category.products', $category->slug) }}">{{ $category->name }}</a>
            </h4>
            @if($category->subcategories->where('status',1)->where('featured',1)->count() > 0)
                <ul class="sub-menu_list">
                    <li class="for-mobile">
                        <a href="{{ route('category.products', $category->slug) }}">All {{ $category->name }}'s Products</a>  
                    </li>
                    @foreach ($category->subcategories->where('status',1)->where('featured',1) as $sub)
                        <li>
                            <a href="{{ route('category.products', [$category->slug, $sub->slug]) }}">
                                {{ $sub->name }}
                            </a>    
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endforeach