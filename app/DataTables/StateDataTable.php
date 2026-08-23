<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Upazila;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class StateDataTable extends DataTable
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
            ->editColumn('name', function (Upazila $state) {
                return '<span class="text-gray-800 fs-5 fw-bold">' . $state->name . '</span>' ;
            })
            ->editColumn('district_id', function (Upazila $state) {
                return '<span class="badge badge-light-primary">' . ($state->district->name ?? '') . '</span>';
            })
            ->addColumn('active', function (Upazila $state) {
                return view('pages.apps.shipping.state.columns._active_status', compact('state'));
            })
            ->addColumn('actions', function (Upazila $state) {
                return view('pages.apps.shipping.state.columns._actions', compact('state'));
            })
            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['name', 'district_id', 'actions']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Upazila $model): QueryBuilder
    {
        $cacheKey = config('dbcachekey.shipping_state');
    
        $state = Cache::rememberForever($cacheKey, function () use ($model) {
            return $model->newQuery()->orderByDesc('id')->get(); // Sort by id descending
        });
        return $model->newQuery()->whereIn('id', $state->pluck('id'));
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('state-table')
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
            ->orderBy(1, 'desc')
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/shipping/state/columns/_draw-scripts.js')) . "}");
    }
    
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('ID')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('name')->title('Name'),
            Column::make('district_id')->title('District Name')->addClass('text-center'),
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
        return 'States_' . date('YmdHis');
    }
}