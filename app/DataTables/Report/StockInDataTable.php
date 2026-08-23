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

class StockInDataTable extends DataTable
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
            ->addColumn('product_name', function (ProductStockManage $row) {
                return '<a href="' . route('product-management.show', $row->product->id) . '" class="text-gray-800 text-hover-primary mb-1">' . Str::limit($row->product->name, 50) . '</a>';
            })

            ->addColumn('product_price', function (ProductStockManage $row) {
                return $row->product->offer_price ? format_price($row->product->offer_price) . '৳' : '-';
            })

            ->addColumn('quantity', function (ProductStockManage $row) {
                return '<span class="badge badge-light-primary">' . $row->quantity . '</span>';
            })

            ->addColumn('wholesale_price', function (ProductStockManage $row) {
                return format_price($row->wholesale_price) . '৳';
            })

            ->addColumn('subtotal', function (ProductStockManage $row) {
                return format_price($row->total_amount) . '৳';
            })

            ->addColumn('created_at', function (ProductStockManage $row) {
                return Carbon::parse($row->created_at)->format('d M, Y') . ' - ' . Carbon::parse($row->created_at)->diffForHumans();
            })
            ->addColumn('stocked_at', function (ProductStockManage $row) {
                return Carbon::parse($row->stocked_at)->format('d M, Y') . ' - ' . Carbon::parse($row->stocked_at)->diffForHumans();
            })


            ->filterColumn('product_name', function ($query, $keyword) {
                $query->whereHas('product', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('quantity', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('quantity', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('delete', function (ProductStockManage $data) {
                return view('pages.apps.report.stock.columns._actions', compact('data'));
            })


            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['last_stock_out', 'quantity', 'product_name', 'delete']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductStockManage $model): QueryBuilder
    {
        $year = request()->route('year') ?? Carbon::now()->year;
        $monthName = request()->route('month') ?? Carbon::now()->format('M');

        // Convert month name to a number
        $month = Carbon::parse("1 $monthName")->month;

        return $model->newQuery()->orderBy('id','desc')->whereYear('stocked_at', $year)->whereMonth('stocked_at', $month)->where('stock', 'stock_in')->with('product')->select('product_stock_manages.*');
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stockin-table')
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
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/report/stock/columns/_draw-scripts.js')) . "}");
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
            Column::make('product_price')->title('Selling Price')->addClass('text-center'),
            Column::make('quantity')->title('Stock In')->addClass('text-center'),
            Column::make('wholesale_price')->title('Wholesale Price')->addClass('text-center'),
            Column::make('subtotal')->title('Total Amount')->addClass('text-center'),
            Column::make('stocked_at')->title('Stock Date')->addClass('text-center'),
            Column::make('created_at')->title('Entry Date')->addClass('text-center'),
            Column::computed('delete')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Low stock Products_' . date('YmdHis');
    }
}
