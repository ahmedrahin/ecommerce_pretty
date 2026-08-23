<div >
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h2>{{$user}}  Order ({{$total}})</h2>
            </div>
            <div>
                <span class="badge badge-light-warning">pending: {{ $orders->where('delivery_status', 'pending')->count() }}</span>
                <span class="badge badge-light-info">processing: {{ $orders->where('delivery_status', 'processing')->count() }}</span>
                <span class="badge badge-light-primary">delivered: {{ $orders->where('delivery_status', 'delivered')->count() }}</span>
                <span class="badge badge-light-success">completed: {{ $orders->where('delivery_status', 'completed')->count() }}</span>
                <span class="badge badge-light-danger">canceled: {{ $orders->where('delivery_status', 'canceled')->count() }}</span>
                <span class="badge badge-light-dark">fake: {{ $orders->where('delivery_status', 'fake')->count() }}</span>
            </div>
        </div>

        <div class="card-body pt-0">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th>Sl.</th>
                            <th class="min-w-100px">Order Id</th>

                            <th class="min-w-150px">Status</th>
                            <th class="min-w-150px">Order Date</th>
                            <th class="min-w-100px">Total Qty</th>
                            <th class="min-w-150px">Total</th>
                            <th class="text-end ">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse($orders as $order)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td><span class="badge badge-light-primary">#{{$order->id}}</span></td>

                                <td>
                                    @php
                                        $status = $order->delivery_status;
                                        if ($order->status === 'Pending') {
                                            $badgeClass = 'badge badge-light-secondary';
                                        } elseif ($order->status === 'processing') {
                                            $badgeClass = 'badge badge-light-primary';
                                        } elseif ($order->status === 'shipped') {
                                            $badgeClass = 'badge badge-light-warning';
                                        }  elseif ($order->status === 'delivered') {
                                            $badgeClass = 'badge badge-light-dark';
                                        } elseif ($order->status === 'canceled') {
                                            $badgeClass = 'badge badge-light-danger';
                                        }
                                        elseif ($order->status === 'completed') {
                                            $badgeClass = 'badge badge-light-success';
                                        } else {
                                            $badgeClass = 'badge badge-light';
                                        }
                                    @endphp
                                    <span class="{{$badgeClass}}">{{$status}}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td class="text-center">
                                    {{$order->orderItems->sum('quantity')}}
                                </td>
                                <td>
                                    ৳{{$order->grand_total}}
                                </td>
                                <td class="  text-end text-nowrap">
                                    <a href="{{route('order-management.order.show', $order->id)}}" class="btn btn-icon btn-active-light-primary w-30px h-30px" style="margin-right: -10px !important;" target="_blank">
                                        <i class="ki-duotone ki-eye fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </a>

                                    <button class="btn btn-icon btn-active-light-primary w-30px h-30px" data-kt-action="delete_row" data-kt-admin-id="13">
                                        <i class="ki-duotone ki-trash fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!--end::Table body-->
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center" style="padding: 20px 0 10px;">
                <!-- Total and current records count -->
                <p style="margin: 0; font-weight: 500; color: #99A1B7;">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $total }} entries.
                </p>

                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
</div>
