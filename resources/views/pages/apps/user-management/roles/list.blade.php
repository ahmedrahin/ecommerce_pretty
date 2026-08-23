<x-default-layout>
    
    @section('title')
    Roles
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('admin-management.roles.index') }}
    @endsection

    <style>
        .ki-duotone.ki-trash {
            color: #ff0000b3;
            font-size: 20px !important;
        }
        .card-title {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
    </style>

    <!--begin::Content container-->
    <div  class="mt-3">
        <livewire:permission.role-list></livewire:permission.role-list>
    </div>
    <!--end::Content container-->

    <!--begin::Modal-->
    <livewire:permission.role-modal></livewire:permission.role-modal>
    <!--end::Modal-->

</x-default-layout>