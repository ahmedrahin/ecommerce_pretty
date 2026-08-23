<?php

namespace App\DataTables\Report;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Product;
use App\Models\ProductStockManage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;

class StockOutDataTable extends DataTable
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
            ->addColumn('product_name', function (Product $row) {
                return '<a href="' . route('product-management.show', $row->id) . '" class="text-gray-800 text-hover-primary mb-1">' . Str::limit($row->name, 50) . '</a>';
            })

            ->addColumn('last_stock_in', function ($row) {
                if ($row->latestStockIn) {
                    $qty = $row->latestStockIn->quantity . ' pcs ';
                    return '<span class="badge badge-light-info me-2">'. $qty . '</span>' . '<span class="badge badge-light-success">'
                        . Carbon::parse($row->latestStockIn->created_at)->format('d M, Y') . ' - ' . Carbon::parse($row->latestStockIn->created_at)->diffForHumans() . '</span>';
                }
                return '';
            })

            ->addColumn('created_at', function (Product $row) {
                return Carbon::parse($row->created_at)->format('d M, Y') . ' - ' . Carbon::parse($row->created_at)->diffForHumans();
            })

            ->addColumn('last_stock_out', function (Product $row) {
                if ($row->latestStockOut) {
                    return '<span class="badge badge-light-danger">'
                        . Carbon::parse($row->latestStockOut->created_at)->format('d M, Y')
                        . ' - ' . Carbon::parse($row->latestStockOut->created_at)->diffForHumans()
                        . '</span>';
                }
                return '';
            })

            ->filterColumn('product_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            ->filterColumn('last_stock_out', function ($query, $keyword) {
                if ($keyword) {
                    $dates = explode(' - ', $keyword);
                    if (count($dates) === 2) {
                        try {
                            $start = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                            $end = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();

                            $query->whereHas('latestStockOut', function ($q) use ($start, $end) {
                                $q->whereBetween('created_at', [$start, $end]);
                            });
                        } catch (\Exception $e) {
                            // optional: log error
                        }
                    }
                }
            })


            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['last_stock_out', 'last_stock_in', 'product_name']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['latestStockIn', 'latestStockOut'])
            ->where('quantity', '==', 0)
            ->orderByDesc('created_at');
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('out-of-stock-table')
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
        ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('product_name')->title('Product Name')->addClass('text-start'),
            Column::make('last_stock_in')->title('Last Stock In')->addClass('text-center'),
            Column::make('last_stock_out')->title('Stock Out')->addClass('text-center'),
            Column::make('created_at')->title('Published At')->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Out of stock Products_' . date('YmdHis');
    }
}
