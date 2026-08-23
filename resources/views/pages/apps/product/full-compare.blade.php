<x-default-layout>

    @section('title') Product Compare @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('compare') }}
    @endsection


    <livewire:frontend.compare.full-compare></livewire:frontend.compare.full-compare>

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                const modal = document.querySelector('#kt_modal_add_compare');
                modal.addEventListener('show.bs.modal', (e) => {
                    const categoryId = e.relatedTarget.getAttribute('data-category-id');
                    const subcategoryId = e.relatedTarget.getAttribute('data-subcategory-id');
                    Livewire.emit('modal.show.subcategory', subcategoryId);
                    Livewire.emit('modal.show.categoryId', categoryId);

                    Livewire.emit('open_add_modal');
                });

                Livewire.on('success', function () {
                    $('#kt_modal_add_compare').modal('hide');
                });
            });
        </script>
    @endpush

</x-default-layout>