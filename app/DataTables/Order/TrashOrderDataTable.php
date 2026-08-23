<?php

namespace App\DataTables\Order;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Order;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TrashOrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('name', function (Order $order) {
                $name = $order->name;
                $phone = $order->phone;

                return '<div class="d-flex flex-column">
                    <span " class="text-gray-800 fs-5 fw-bold">
                        ' . $name . '
                    </span>
                    <span>' . $phone . '</span>
                </div>';
            })
            ->editColumn('order_id', function (Order $order) {
                return '<span class="badge badge-light-primary">' . $order->order_id . '</span>' ;
            })
            ->editColumn('user_type', function (Order $order) {
                $user_type = $order->user_type;
                $statusClass = $user_type === 'author' ? 'text-warning' : '';

                // Default: no link
                $isUser = null;

                // Check if relation exists first
                if ($order->user) {
                    if ($user_type === 'author' && $order->user->isAdmin == 1) {
                        $isUser = route('admin-management.admin-list.show', $order->user_id);
                    } elseif ($user_type === 'customer' && $order->user_id && $order->user->isAdmin == 2) {
                        $isUser = route('user-management.users.show', $order->user_id);
                    }
                }

                if ($isUser) {
                    return '<a href="' . e($isUser) . '" target="_blank">
                        <span class="' . e($statusClass) . '" style="font-weight: 700; font-size: 10px; padding: 0;">'
                                    . e($user_type) . '</span>
                    </a>';
                }

                return '<span class="' . e($statusClass) . '" style="font-weight: 700; font-size: 10px; padding: 0;">'
                    . e($user_type) . '</span>';
            })

            ->editColumn('delivery_status', function (Order $order) {
                $status = $order->delivery_status;
                $badgeClass = '';

                if ($status === 'pending') {
                    $badgeClass = 'badge badge-light-warning';
                } elseif ($status === 'processing') {
                    $badgeClass = 'badge badge-light-primary';
                } elseif ($status === 'shipped') {
                    $badgeClass = 'badge badge-light-warning';
                }  elseif ($status === 'delivered') {
                    $badgeClass = 'badge badge-light-dark';
                } elseif ($status === 'canceled' || $status === 'fake') {
                    $badgeClass = 'badge badge-light-danger';
                }
                elseif ($status === 'completed') {
                    $badgeClass = 'badge badge-light-success';
                } else {
                    $badgeClass = 'badge badge-light';
                }

                return '<span class="' . $badgeClass . '">' . $status . '</span>';
            })
            ->editColumn('grand_total', function (Order $order) {
                return format_price($order->grand_total) . '৳';
            })
            ->editColumn('qty', function (Order $order) {
                return $order->orderItems->sum('quantity');
            })
            ->editColumn('order_date', function (Order $order) {
                $formattedDate = Carbon::parse($order->order_date)->format('d M Y') . '<br>';
                $time = Carbon::parse($order->order_date)->diffForHumans();
                $statusClass = 'text-primary';

                if ($order->delivery_status === 'completed') {
                    $statusClass = 'text-success';
                } elseif ($order->delivery_status !== 'completed' && Carbon::parse($order->order_date)->diffInDays(Carbon::now()) > 3) {
                    $statusClass = 'text-danger';
                }

                return $formattedDate . ' <span class="' . $statusClass . '" style="font-weight: 700; font-size: 10px; padding: 0;">' . $time . '</span>';
            })
            ->addColumn('viewed', function (Order $order) {
                if ($order->viewed != 0) {
                    $data = '<i class="ki-duotone ki-eye fs-3 text-success">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                             </i>';
                } else {
                    $data = '<i class="ki-duotone ki-eye fs-3" style="opacity: .5;">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                             </i>';
                }

                return $data;
            })
            ->addColumn('actions', function (Order $order) {
                return view('pages.apps.order.columns.trash_action', compact('order'));
            })

            ->filterColumn('name', function ($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })

            ->filterColumn('delivery_status', function ($query, $keyword) {
                if ($keyword) {
                    $query->where('delivery_status', $keyword);
                }
            })

            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['name', 'id', 'delivery_status','order_date','viewed', 'actions', 'user_type', 'qty','order_id']);
    }

    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()
                     ->onlyTrashed()
                     ->orderBy('id', 'desc');
    }    


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('trashorder-table')
            ->columns($this->getColumns())

            ->dom(
                "rt" . "<'row align-items-center'<'col-sm-12 col-md-6 d-flex gap-3'l i><'col-sm-12 col-md-6 d-flex justify-content-end'p>>"
            )
            ->buttons([
                [
                    'extend' => 'copy',
                    'exportOptions' => ['columns' => ':not(.no-export)']
                ],
                [
                    'extend' => 'excel',
                    'exportOptions' => ['columns' => ':not(.no-export)']
                ],
                [
                    'extend' => 'csv',
                    'exportOptions' => ['columns' => ':not(.no-export)']
                ],
                [
                    'extend' => 'pdf',
                    'exportOptions' => ['columns' => ':not(.no-export)'],
                ],
            ])
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/order/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center'),
            Column::make('name')->title('Customer'),
            Column::make('order_id')->title('Order Id'),
            Column::make('user_type')->title('User Type')->addClass('text-center'),

            Column::make('grand_total')->title('Total')->addClass('text-center'),
            Column::computed('qty')->title('Total Qty')->addClass('text-center'),
            Column::make('order_date')->title('Order Date')->addClass('text-center'),
             Column::make('delivery_status')->title('Status')->addClass('text-center'),
            Column::make('viewed')->title('Viewed')->addClass('text-center'),
            Column::computed('actions')
                ->title('Actions')
                ->addClass('text-end text-nowrap no-export')
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
