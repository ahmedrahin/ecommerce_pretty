<?php

namespace App\DataTables;

use App\Models\Subscription;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Carbon\Carbon;

class SubscriptionDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('email', function ($data) {
                return $data->email;
            })
            ->editColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->format('d M, Y') . ' - ' . $data->created_at->diffForHumans();
            })

            ->addColumn('action', function ($data) {

            })


            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function query(Subscription $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subscription-table')
            ->columns($this->getColumns())

            ->dom(
                "rt" . "<'row align-items-center'<'col-sm-12 col-md-6 d-flex gap-3'l i><'col-sm-12 col-md-6 d-flex justify-content-end'p>>"
            )
            ->orderBy(1)
            ->buttons([
                ['extend' => 'copy', 'exportOptions' => ['columns' => ':not(.no-export)']],
                ['extend' => 'excel', 'exportOptions' => ['columns' => ':not(.no-export)']],
                ['extend' => 'csv', 'exportOptions' => ['columns' => ':not(.no-export)']],
                ['extend' => 'pdf', 'exportOptions' => ['columns' => ':not(.no-export)']],
            ])
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/review/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('email')->title('Email')->addClass('text-center'),
            Column::make('created_at')->title('Date & Time')->addClass('text-center'),
            Column::computed('action')
                ->title('Actions')
                ->addClass('text-end text-nowrap no-export')
                ->exportable(false)
                ->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Subscription_' . date('YmdHis');
    }
}
