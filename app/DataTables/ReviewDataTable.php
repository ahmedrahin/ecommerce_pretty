<?php

namespace App\DataTables;

use App\Models\Review;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ReviewDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('product_name', function ($data) {
                return $data->product?->name ?? '-';
            })
           ->editColumn('name', function (Review $data) {
                if (!$data->user_id || !$data->user) {
                    // Guest or deleted user
                    return $data->name . ' (guest)';
                } else {
                    // User exists, determine route
                    $route = $data->user->isAdmin == 1
                        ? route('admin-management.admin-list.show', $data->user_id)
                        : route('user-management.users.show', $data->user_id);
                    
                    return "<a href='" . $route . "' target='_blank' class='text-gray-600 text-hover-primary'>" 
                        . $data->user->name . "</a>";
                }
            })

            ->editColumn('email', fn($data) => $data->email)
            ->editColumn('rating', function (Review $data) {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= $i <= round($data->rating)
                        ? '<i class="ki-duotone ki-star fs-6" style="color:#ffad0f;"></i>'
                        : '<i class="ki-duotone ki-star fs-6" style="opacity:0.3;"></i>';
                }
                return '<div class="rating" style="justify-content: center;">' . $stars . ' (' . $data->rating . ')</div>';
            })
            ->editColumn('comment', fn($data) => "<span style='font-size: 11px !important; display: block; overflow: hidden;'>"
                . $data->comment . "</span>")
            ->addColumn('actions', fn($data) => view('pages.apps.review.columns._actions', compact('data')))
            ->filterColumn('product_name', function ($query, $keyword) {
                $query->whereHas('product', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('rating', function ($query, $keyword) {
                $query->where('rating', (int) $keyword);
            })

            ->rawColumns(['product_name', 'name', 'rating', 'comment'])
            ->setRowId('id');
    }

    public function query(Review $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('product', 'user')
            ->orderBy('id', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('review-table')
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
            Column::make('product_name')->title('Product Name')->addClass('text-center')->orderable(false), // <-- Fixed line
            Column::make('name')->title('Name')->addClass('text-center'),
            Column::make('email')->title('Email')->addClass('text-center'),
            Column::make('rating')->title('Rating')->addClass('text-center'),
            Column::make('comment')->title('Comment')->addClass('coment'),
            Column::computed('actions')
                ->title('Actions')
                ->addClass('text-end text-nowrap no-export')
                ->exportable(false)
                ->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Reviews_' . date('YmdHis');
    }
}
