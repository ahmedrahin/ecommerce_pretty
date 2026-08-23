<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Question;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductQuestionDataTable extends DataTable
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
            ->addColumn('user_name', function (Question $v) {
                if ($v->user) {
                    return '<a href="' . route('user-management.users.show', $v->user_id) . '" target="_blank">' 
                        . e($v->user->name) . '</a>';
                }
                return '<span class="text-danger">Deleted User</span>';
            })

            ->addColumn('product_name', function (Question $v) {
                return '<a href=' . route('product-management.show', $v->product_id) .' target="_blank">' . $v->product->name . '</a>';
            })
            ->addColumn('question', function (Question $v) {
                return Str::limit($v->question, 30);
            })
            ->addColumn('created_at', function (Question $v) {
                return Carbon::parse($v->created_at)->format('d M, Y (h:i a)');
            })
            ->addColumn('is_read', function (Question $v) {
                if ($v->is_read != 0) {
                    $data = '<i class="ki-duotone ki-eye fs-3 text-success">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                             </i>';
                } else {
                    $data = '<i class="ki-duotone ki-eye fs-3" style="opacity: .5;">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                             </i>';
                }

                return $data;
            })
            ->addColumn('is_replied', function (Question $data) {
                $data;
                if( $data->is_replied ){
                    $data = '<span class="badge badge-light-success">Replied</span>';
                }else {
                    $data = '<span class="badge badge-light-danger ">No replied</span>';
                }
                return $data;
            })
            ->addColumn('details', function (Question $data) {
                return '<a href=" ' . route('question.details',$data->id) . ' " target="_blank">
                            <i class="ki-duotone ki-message-edit fs-2 m-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>';
            })

            ->filterColumn('is_read', function ($query, $keyword) {
                if (is_numeric($keyword)) {
                    $query->where('is_read', $keyword);
                }
            })

            ->filterColumn('is_replied', function ($query, $keyword) {
                if (is_numeric($keyword)) {
                    $query->where('is_replied', $keyword);
                }
            })

            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['details', 'is_read', 'is_replied', 'user_name', 'product_name']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Question $model): QueryBuilder
    {
        return $model->newQuery()
            ->leftJoin('users', 'questions.user_id', '=', 'users.id')
            ->leftJoin('products', 'questions.product_id', '=', 'products.id')
            ->select([
                'questions.*',
                'users.name as user_name',
                'products.name as product_name'
            ])
            ->orderBy('questions.id', 'desc');
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('message-table')
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
            Column::make('user_name')->name('users.name')->title('Customer Name'),
            Column::make('product_name')->name('products.name')->title('Product'),
            Column::make('question')->addClass('text-center text-nowrap no-export')->name('Question'),
            Column::make('created_at')->addClass('text-center text-nowrap no-export')->name('created_at')->title('Date'),
            Column::make('is_read')->title('Read')->addClass('text-center'),
            Column::make('is_replied')->title('Replied')->addClass('text-center'),
            Column::computed('details')
                ->title('Details')
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
        return 'Product Questions_' . date('YmdHis');
    }
}
