<div class="row gy-3 theme-scrollbar" style="overflow-y: scroll;max-height: 500px;"> 
    @foreach ($categories as $category)
        <div class="col-xl-2">
            <h5 style="font-size: 15px;">
                <a href="{{ url('/shop') }}?categories[0]={{ $category->id }}">
                    {{ $category->name}}
                </a>
            </h5>
            <ul>
                @foreach($category->subcategories as $subcategory)
                    <li>
                        <a href="{{ url('/shop') }}?subcategories[0]={{ $subcategory->id }}" style="color:#00000091; padding: 0;font-size:14px;" class="pb-1">
                            {{$subcategory->name}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
  </div>
