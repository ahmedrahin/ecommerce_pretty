<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.includes.header')
    @include('frontend.includes.css')
    @livewireStyles
</head>

<body>

    <main id="wrapper">
        @include('frontend.includes.menu')

        @yield('body-content')
        
        @include('frontend.includes.footer')
    </main>   
    
    @include('frontend.includes.cart-sidebar')

    @include('frontend.includes.script')
    @livewireScripts
</body>

</html>
