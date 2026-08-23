<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Coupon;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Order;

class CouponDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // Include the `withCount` for orders in the query
        $query = $query->withCount('orders');

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('code', function (Coupon $coupon) {
                return '<span class="text-gray-800 fs-5 fw-bold">' . $coupon->code . '</span>';
            })
            ->editColumn('discount_amount', function (Coupon $coupon) {
                if ($coupon->discount_type == 'fixed') {
                    $icon = '<i class="ki-duotone ki-dollar fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>';
                } else {
                    $icon = '<i class="ki-duotone ki-percentage fs-3"><span class="path1"></span><span class="path2"></span></i>';
                }
                return $coupon->discount_amount . ' ' . $icon;
            })
            ->editColumn('min_purchase_amount', function (Coupon $coupon) {
                return (!is_null($coupon->min_purchase_amount)) ? $coupon->min_purchase_amount . 'tk' : '-';
            })
            ->editColumn('usage_limit', function (Coupon $coupon) {
                $usedCount = $coupon->orders_count;
                $limit = $coupon->usage_limit;
                if (empty($limit)) {
                    return '-';
                }

                $class = ($usedCount >= $limit) ? 'text-danger' : 'text-success';
                return "<span class='{$class}'>{$usedCount}</span> / {$limit}";
            })
            ->editColumn('used', function (Coupon $coupon) {
                return $coupon->used ?? '-';
            })
            ->editColumn('start_at', function (Coupon $coupon) {
                $formattedDate = Carbon::parse($coupon->start_at)->format('d F, y');
                return $formattedDate;
            })
            ->editColumn('expire_date', function ($coupon) {
                if (!$coupon->expire_date) {
                    return '-';
                }

                $class = Carbon::parse($coupon->expire_date)->lt(now()) ? 'text-danger' : 'text-success';

                // Format the date as mm/dd/yyyy
                $formattedDate = Carbon::parse($coupon->expire_date)->format('d F, y');

                // Return the date with the appropriate class
                return "<span class='{$class}'>{$formattedDate}</span>";
            })
            ->editColumn('orders', function ($coupon) {
                $orderCount = Order::where('cupon_code', $coupon->code)->count();
                $bgClass = $orderCount > 0 ? 'badge badge-light-success' : 'badge badge-light-danger';
                return '<span class="' . $bgClass . ' p-1 rounded">' . $orderCount . '</span>';
            })
            ->editColumn('order_amount', function ($coupon) {
                $amount = Order::where('cupon_code', $coupon->code)->sum('coupon_discount');
               return '৳' . number_format($amount,2);
            })
            ->addColumn('active', function (Coupon $coupon) {
                return view('pages.apps.coupon.columns._active_status', compact('coupon'));
            })
            ->addColumn('actions', function (Coupon $coupon) {
                return view('pages.apps.coupon.columns._actions', compact('coupon'));
            })
            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['code', 'status', 'discount_amount', 'usage_limit', 'expire_date', 'actions', 'order_summaries', 'orders','order_amount']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Coupon $model): QueryBuilder
    {
        $cacheKey = config('dbcachekey.coupon');
        $coupons = Cache::rememberForever($cacheKey, function () use ($model) {
            return $model->newQuery()
                        ->orderBy('id', 'desc')
                        ->get();
        });

        $ids = $coupons->pluck('id')->toArray();
        return $model->newQuery()->whereIn('id', $ids)->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('coupon-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/coupon/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('code')->title('Code'),
            Column::make('discount_amount')->title('Discount')->addClass('text-center'),
            Column::make('min_purchase_amount')->title('Min Amount')->addClass('text-center'),
            Column::make('usage_limit')->title('Usage Limit')->addClass('text-center'),
            Column::make('used')->title('Max_use')->addClass('text-center'),
            Column::make('start_at')->title('Start At')->addClass('text-center'),
            Column::make('expire_date')->title('Expire At')->addClass('text-center'),
            Column::computed('orders')->title('Orders')->addClass('text-center'),
            Column::computed('order_amount')->title('Order Amount')->addClass('text-center'),
            Column::computed('active')
                ->title('Active')
                ->addClass('text-center text-nowrap no-export')
                ->exportable(false)
                ->printable(false),
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
        return 'Coupons_' . date('YmdHis');
    }
}
