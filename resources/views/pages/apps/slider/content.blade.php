<x-default-layout>

    @section('title') Banner Content @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('banner_content') }}
    @endsection

        <livewire:content.banner-conter></livewire:content.banner-conter>

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                
                Livewire.on('success', function () {
                    $('.modal').modal('hide');
                });
            });
        </script>
    @endpush

</x-default-layout>
