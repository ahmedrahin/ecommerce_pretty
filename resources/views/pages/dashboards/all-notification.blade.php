<x-default-layout>

    @section('title')
        Dashboard
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('notification') }}
    @endsection

    @push('styles')
        <style>
           .card .card-header{
                min-height: 58px;
           }
           .bg-gradient-blue {
                background: linear-gradient(45deg, #6a11cb , #2575fc)!important;
                opacity: .8;
           }
           .bt-gradient-green {
                background: linear-gradient(45deg, #00b09b, #96c93d)!important;
                opacity: .8;
           }
           .bt-gradient-red {
                background: #F1416C;
                opacity: .8;
           }
           .bt-gradient-black {
                background: linear-gradient(to right, #0f2027, #203a43, #2c5364)!important;
                opacity: .8;
           }
           .text-custom {
            color: #ffffffc4 !important;
           }


           .apexcharts-canvas, .apexcharts-canvas svg {
                width: 100% !important;
           }
        </style>
    @endpush

    <livewire:notifications.all-notification />



</x-default-layout>
