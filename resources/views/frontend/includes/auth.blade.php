@if( !Auth::check() )
  <li class="onhover-div">

    <a href="{{ route('user.login') }}">
        <i class="bi bi-person-fill"></i>
    </a>

  </li>
@else
  <li class="onhover-div">
    @php
        $route = Auth::user()->isAdmin == 1 ? route('admin-management.admin-list.show', Auth::id()) : route('user.dashboard');
    @endphp
    <a href="{{ $route }}">
        <i class="bi bi-person-fill"></i>
    </a>

  </li>
@endif
