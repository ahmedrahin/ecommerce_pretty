<?php

namespace App\DataTables\Order;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Services;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ServiceDataTable extends DataTable
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
            ->editColumn('client_name', function (Services $data) {
                return $data->client_name;
            })
            ->editColumn('mobile', function ($data) {
                return $data->mobile;
            })

            ->editColumn('recive_by', function ($data) {
                return $data->recive_by;
            })
             ->editColumn('sale_date', function ($data) {
                return Carbon::parse($data->sale_date)->format('d M, Y');
            })
            ->editColumn('date_of', function ($data) {
                return Carbon::parse($data->date_of)->format('d M, Y');
            })
            ->editColumn('status', function ($data) {
                return '<span class="badge badge-light-primary">' . $data->status . '</span>';
            })
            ->editColumn('order_id', function (Services $data) {
                return '<span class="badge badge-light-primary">' . $data->order_id . '</span>';
            })

             ->editColumn('service_cost', function (Services $data) {
                return  $data->service_cost;
            })

            ->addColumn('actions', function (Services $data) {
                return view('pages.apps.service.columns._actions', compact('data'));
            })
            ->filterColumn('product_info', function($query, $keyword) {
                $query->whereHas('productInfo', function($q) use ($keyword) {
                    $q->where('product_name', 'like', "%{$keyword}%")
                      ->orWhere('serial_no', 'like', "%{$keyword}%")
                      ->orWhere('remarks', 'like', "%{$keyword}%")
                      ->orWhere('model', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('sale_date', function ($query, $keyword) {
                if ($keyword) {
                    $query->whereRaw("DATE_FORMAT(sale_date, '%d %b %Y') LIKE ?", ["%{$keyword}%"])
                        ->orWhereRaw("DATE_FORMAT(sale_date, '%e %b') LIKE ?", ["%{$keyword}%"])
                        ->orWhereDate('sale_date', 'like', "%{$keyword}%");
                }
            })
            ->filterColumn('date_of', function ($query, $keyword) {
                if ($keyword) {
                    $query->whereRaw("DATE_FORMAT(date_of, '%d %b %Y') LIKE ?", ["%{$keyword}%"])
                        ->orWhereRaw("DATE_FORMAT(date_of, '%e %b') LIKE ?", ["%{$keyword}%"])
                        ->orWhereDate('date_of', 'like', "%{$keyword}%");
                }
            })      

            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['order_id', 'actions', 'status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Services $model): QueryBuilder
    {
        $year = request()->route('year') ?? Carbon::now()->year;
        $monthName = request()->route('month') ?? Carbon::now()->format('M');

        // Convert month name to a number
        $month = Carbon::parse("1 $monthName")->month;

        return $model->newQuery()->with('productInfo')
            ->whereYear('date_of', $year)
            ->whereMonth('date_of', $month)
            ->orderBy('id', 'desc');
    }



    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('service-table')
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
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/service/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center'),
            Column::make('order_id')->title('Order Id'),
            Column::make('client_name')->title('Client Name'),
            Column::make('mobile')->title('Mobile'),
            Column::make('recive_by')->title('Recive By')->addClass('text-center'),
            Column::make('service_cost')->title('Service Cost')->addClass('text-center'),
            Column::make('sale_date')->title('Sale Date')->addClass('text-center'),
            Column::make('date_of')->title('Received Date')->addClass('text-center'),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::make('product_info')->title('Products')->visible(false),
            Column::make('actions')
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
        return 'Services_' . date('YmdHis');
    }
}
