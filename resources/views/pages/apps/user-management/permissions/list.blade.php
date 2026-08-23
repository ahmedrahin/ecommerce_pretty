<x-default-layout>

    @section('title')
    Permissions
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('admin-management.permissions.index') }}
    @endsection

    <style>
        .edit {
            margin-right: -7px;
        }
        .head-title{
            font-weight: 700;
        }
        td {
            font-weight: 600 !important;
        }
        .card {
            height: 500px;
            overflow-y: scroll;
        }
    </style>

<div class="card-toolbar">
    <!--begin::Toolbar-->
    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
        <button type="button" class="btn btn-light-primary" data-bs-toggle="modal"
            data-bs-target="#kt_modal_update_permission">
            {!! getIcon('plus-square','fs-3', '', 'i') !!}
            Add Permission
        </button>
    </div>
    <!--end::Toolbar-->

    
</div>


    <div class="row">

        <div class="col-md-6 col-ld-6 mb-8">

            <div class="card">
    
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="head-title">
                                {{ ucwords(str_replace('-', ' ', $adminCatalouge->first()->group_name ?? '')) }}
                            </span>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
        
                </div>
                <!--end::Card header-->
        
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Permission Name</th>
                                    <th>Assigned to</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adminCatalouge as $item)
                                    <tr>
                                        <td>{{ucwords($item->name)}}</td>
                                        <td>
                                            @if( $item->roles->count() > 0 )
                                                @foreach($item->roles as $role)
                                                    <a href="{{ route('admin-management.roles.show', $role) }}" class="badge fs-7 m-1 {{ app(\App\Actions\GetThemeType::class)->handle('badge-light-?', $role->name) }}">
                                                        {{ $role->name }}
                                                    </a>
                                                @endforeach
                                            @else
                                                <span style="
                                                        color: #ff00009c;
                                                        font-style: italic;
                                                        font-weight: 600;
                                                ">No assign</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>

        </div>


        <div class="col-md-6 col-ld-6 mb-8">
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="head-title">
                                {{ ucwords(str_replace('-', ' ', $productCatalouge->first()->group_name ?? '')) }}
                            </span>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
        
                </div>
                <!--end::Card header-->
        
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Permission Name</th>
                                    <th>Assigned to</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productCatalouge as $item)
                                    <tr>
                                        <td>{{ucwords($item->name)}}</td>
                                        <td>
                                            @if( $item->roles->count() > 0 )
                                                @foreach($item->roles as $role)
                                                    <a href="{{ route('admin-management.roles.show', $role) }}" class="badge fs-7 m-1 {{ app(\App\Actions\GetThemeType::class)->handle('badge-light-?', $role->name) }}">
                                                        {{ $role->name }}
                                                    </a>
                                                @endforeach
                                            @else
                                                <span style="
                                                        color: #ff00009c;
                                                        font-style: italic;
                                                        font-weight: 600;
                                                ">No assign</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
        

        </div>


        <div class="col-md-6 col-ld-6 mb-8">
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="head-title">
                                {{ ucwords(str_replace('-', ' ', $OrderCatalouge->first()->group_name ?? '')) }}
                            </span>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
        
                </div>
                <!--end::Card header-->
        
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Permission Name</th>
                                    <th>Assigned to</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($OrderCatalouge as $item)
                                    <tr>
                                        <td>{{ucwords($item->name)}}</td>
                                        <td>
                                            @if( $item->roles->count() > 0 )
                                                @foreach($item->roles as $role)
                                                    <a href="{{ route('admin-management.roles.show', $role) }}" class="badge fs-7 m-1 {{ app(\App\Actions\GetThemeType::class)->handle('badge-light-?', $role->name) }}">
                                                        {{ $role->name }}
                                                    </a>
                                                @endforeach
                                            @else
                                                <span style="
                                                        color: #ff00009c;
                                                        font-style: italic;
                                                        font-weight: 600;
                                                ">No assign</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
        

        </div>

    </div>
   
    
    <livewire:permission.permission-modal></livewire:permission.permission-modal>

   
</x-default-layout>