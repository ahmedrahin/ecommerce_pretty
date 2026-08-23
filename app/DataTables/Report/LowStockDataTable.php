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

class LowStockDataTable extends DataTable
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
            
            ->addColumn('created_at', function (Product $row) {
                return Carbon::parse($row->created_at)->format('d M, Y') . ' - ' . Carbon::parse($row->created_at)->diffForHumans();
            })

            ->addColumn('quantity', function (Product $row) {
                return '<span class="badge badge-light-warning fs-7 fw-bold">' . $row->quantity . '</span>';
            })

            ->filterColumn('product_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('quantity', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('quantity', 'like', "%{$keyword}%");
                });
            })

            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['last_stock_out', 'quantity', 'product_name']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->whereBetween('quantity', [1, 10])
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
           Column::make('quantity')->title('Current Stock')->addClass('text-center'),
            Column::make('created_at')->title('Published At')->addClass('text-center'),
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
