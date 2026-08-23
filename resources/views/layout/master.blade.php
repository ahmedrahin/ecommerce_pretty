<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! printHtmlAttributes('html') !!}>
<!--begin::Head-->

<head>
    <base href="" />
    <title> @yield('title', config('app.name')) | {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <link rel="canonical" href="" />

    {!! includeFavicon() !!}

    <!--begin::Fonts-->
    {!! includeFonts() !!}
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    @foreach (getGlobalAssets('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Vendor Stylesheets(used by this page)-->
    @foreach (getVendors('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Vendor Stylesheets-->

    <!--begin::Custom Stylesheets(optional)-->
    @foreach (getCustomCss() as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Custom Stylesheets-->
    <link rel="stylesheet" href="{{ asset('custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- page-css --}}
    @yield('custom-css')
    @stack('styles')

    @livewireStyles
</head>
<!--end::Head-->

<!--begin::Body-->

<body {!! printHtmlClasses('body') !!} {!! printHtmlAttributes('body') !!}>

    @include('partials/theme-mode/_init')

    @yield('content')

    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    @foreach (getGlobalAssets() as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Global Javascript Bundle-->

    <!--begin::Vendors Javascript(used by this page)-->
    @foreach (getVendors('js') as $path)
        {!! sprintf('<script src="%s"></script>', asset($path)) !!}
    @endforeach
    <!--end::Vendors Javascript-->

    <!--end::Custom Javascript-->
    @stack('scripts')
    <!--end::Javascript-->

    @if(session()->has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

    <script>
        function errorAccess(message){
            toastr.error(message)
        }
     </script>

    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.on('success', (message) => {
                toastr.success(message);
            });
            Livewire.on('info', (message) => {
                toastr.info(message);
            });
            Livewire.on('warning', (message) => {
                toastr.warning(message);
            });
            Livewire.on('error', (message) => {
                toastr.error(message);
            });

            Livewire.on('swal', (message, icon, confirmButtonText) => {
                if (typeof icon === 'undefined') {
                    icon = 'success';
                }
                if (typeof confirmButtonText === 'undefined') {
                    confirmButtonText = 'Ok, got it!';
                }
                Swal.fire({
                    text: message,
                    icon: icon,
                    buttonsStyling: false,
                    confirmButtonText: confirmButtonText,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            });
        });

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>


    <script>
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('kt_menu_notifications');
            const trigger = document.getElementById('kt_menu_item_wow');

            // If clicked outside both the menu and the trigger button
            if (!menu.contains(event.target) && !trigger.contains(event.target)) {
                menu.classList.remove('show');
            }
        });

       document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', (message, component) => {
                const notificationBody = document.getElementById('notificationList');

                if (notificationBody) {
                    if (notificationBody.scrollHeight > 500) {
                        notificationBody.classList.add('scroll-y');
                    } else {
                        notificationBody.classList.remove('scroll-y');
                    }
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const notificationBody = document.getElementById('notificationList');

            if (notificationBody) {
                if (notificationBody.scrollHeight > 500) {
                    notificationBody.classList.add('scroll-y');
                } else {
                    notificationBody.classList.remove('scroll-y');
                }
            }
        });

        window.addEventListener('notification-count-updated', event => {
        const count = event.detail.count;
        if (count > 0) {
            document.title = `(${count}) New Notification${count > 1 ? 's' : ''}`;
        } else {
            document.title = 'Your Default Title';
        }
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const originalTitle = document.title
            Livewire.on('notificationCountUpdated', function (count) {
                if (count > 0) {
                    document.title = `(${count}) New Notification${count > 1 ? 's' : ''}`;
                } else {
                    document.title = originalTitle;
                }
            });
        });
    </script>

    @livewireScripts
</body>
<!--end::Body-->

</html>
