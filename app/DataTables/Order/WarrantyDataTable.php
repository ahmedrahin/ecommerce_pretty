<?php

namespace App\DataTables\Order;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Warenty;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class WarrantyDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('client_name', function (Warenty $data) {
                return $data->client_name;
            })
            ->editColumn('mobile', function ($data) {
                return $data->mobile;
            })

            ->editColumn('recive_by', function ($data) {
                return $data->recive_by;
            })
            ->editColumn('provider', function ($data) {
                return $data->provider;
            })
            ->editColumn('sale_date', function ($data) {
                return Carbon::parse($data->sale_date)->format('d M, Y');
            })
            ->editColumn('delivery_date', function ($data) {
                return $data->delivery_date ? Carbon::parse($data->delivery_date)->format('d M, Y') : '-';
            })
            ->editColumn('date_of', function ($data) {
                return Carbon::parse($data->date_of)->format('d M, Y');
            })
            ->editColumn('status', function ($data) {
                return match ($data->status) {
                    'pending'  => '<span class="badge badge-light-danger">Pending</span>',
                    'complete' => '<span class="badge badge-light-primary">Complete</span>',
                    'delivered' => '<span class="badge badge-light-success">Delivered</span>',
                    default    => '<span class="badge badge-light-secondary">Unknown</span>',
                };
            })
            ->editColumn('order_id', function (Warenty $data) {
                return '<span class="badge badge-light-primary">' . $data->order_id . '</span>';
            })

            ->addColumn('actions', function (Warenty $data) {
                return view('pages.apps.warranty.columns._actions', compact('data'));
            })

            ->filterColumn('status', function ($query, $keyword) {
                if ($keyword) {
                    $query->where('status', $keyword);
                }
            })
            ->filterColumn('product_info', function($query, $keyword) {
                $query->whereHas('productInfo', function($q) use ($keyword) {
                    $q->where('product_name', 'like', "%{$keyword}%")
                      ->orWhere('serial_no', 'like', "%{$keyword}%")
                      ->orWhere('remarks', 'like', "%{$keyword}%")
                      ->orWhere('model', 'like', "%{$keyword}%")
                      ->orWhere('change', 'like', "%{$keyword}%")
                      ->orWhere('problem', 'like', "%{$keyword}%");
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
            ->filterColumn('delivery_date', function ($query, $keyword) {
                if ($keyword) {
                    $query->whereRaw("DATE_FORMAT(delivery_date, '%d %b %Y') LIKE ?", ["%{$keyword}%"])
                        ->orWhereRaw("DATE_FORMAT(delivery_date, '%e %b') LIKE ?", ["%{$keyword}%"])
                        ->orWhereDate('delivery_date', 'like', "%{$keyword}%");
                }
            })         

            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['order_id', 'actions', 'status']);
    }

    public function query(Warenty $model): QueryBuilder
    {
        $year = request()->route('year') ?? Carbon::now()->year;
        $monthName = request()->route('month') ?? Carbon::now()->format('M');

        // Convert month name to a number
        $month = Carbon::parse("1 $monthName")->month;

        return $model->newQuery()
            ->with('productInfo')
            ->whereYear('date_of', $year)
            ->whereMonth('date_of', $month)
            ->orderBy('id', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('warranty-table')
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
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/warranty/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center'),
            Column::make('order_id')->title('Id'),
            Column::make('client_name')->title('Client Name'),
            Column::make('mobile')->title('Mobile'),
            Column::make('provider')->title('Provider')->addClass('text-center'),
            Column::make('recive_by')->title('Recive By')->addClass('text-center'),
            Column::make('sale_date')->title('Sale Date')->addClass('text-center')->visible(false),
            Column::make('date_of')->title('Receive Date')->addClass('text-center'),
            Column::make('delivery_date')->title('Deliver Date')->addClass('text-center'),
            Column::make('status')->title('Status')->addClass('text-center'),
            Column::make('product_info')->title('Products')->visible(false),
            Column::computed('actions')
                ->title('Actions')
                ->addClass('text-end text-nowrap no-export')
                ->exportable(false)
                ->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Warranty_' . date('YmdHis');
    }
}
