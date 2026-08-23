@if(request()->is('admin/*'))
    @include('pages.system.not_found')
@else
    @include('frontend.pages.error.404')
@endif
