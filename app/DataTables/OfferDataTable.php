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
use App\Models\Offer;

class OfferDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // Include the `withCount` for orders in the query


        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('image', function (Offer $data) {
                $imagePath = $data->image ? $data->image : 'uploads/blank-image.svg';
                $imageUrl  = asset($imagePath);
                return '<img src="' . $imageUrl . '" width="50" height="50" class="table-image">';
            })
            ->editColumn('title', function (Offer $data) {
                return '<span class="text-gray-800 fs-5 fw-bold">' . $data->title . '</span>';
            })
            ->editColumn('start_at', function (Offer $data) {
                return Carbon::parse($data->start_at)->format('d M, Y');
            })
            ->editColumn('expire_date', function (Offer $data) {
                return Carbon::parse($data->expire_date)->format('d M, Y');
            })

            ->addColumn('active', function (Offer $data) {
                return view('pages.apps.offer.columns._active_status', compact('data'));
            })
            ->addColumn('actions', function (Offer $data) {
                return view('pages.apps.offer.columns._actions', compact('data'));
            })
            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['image', 'status', 'title', 'start_at', 'expire_date', 'actions', 'order_summaries', 'orders','order_amount']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Offer $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('offer-table')
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
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/offer/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('image')->title('Image'),
            Column::make('title')->title('Title'),
            Column::make('start_at')->title('Star_at'),
            Column::make('expire_date')->title('Expired Date'),

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
        return 'Offers_' . date('YmdHis');
    }
}
