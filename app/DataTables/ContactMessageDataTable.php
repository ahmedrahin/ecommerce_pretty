<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\ContactMessage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ContactMessageDataTable extends DataTable
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
            ->addColumn('name', function (ContactMessage $v) {
                return $v->name;
            })
            ->addColumn('email', function (ContactMessage $v) {
                return $v->email;
            })
            ->addColumn('subject', function (ContactMessage $v) {
                return $v->subject ?? '-';
            })
            ->addColumn('message', function (ContactMessage $v) {
                return Str::limit($v->message, 30);
            })
            ->addColumn('created_at', function (ContactMessage $v) {
                return Carbon::parse($v->created_at)->format('d M, Y (h:i a)');
            })
            ->addColumn('is_read', function (ContactMessage $v) {
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
            ->addColumn('is_replied', function (ContactMessage $data) {
                $data;
                if( $data->is_replied ){
                    $data = '<span class="badge badge-light-success">Replied</span>';
                }else {
                    $data = '<span class="badge badge-light-danger ">No replied</span>';
                }
                return $data;
            })
            ->addColumn('details', function (ContactMessage $data) {
                return '<a href=" ' . route('contact.message.details',$data->id) . ' " target="_blank">
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
            ->rawColumns(['details', 'is_read', 'is_replied']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ContactMessage $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
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
            Column::make('name')->addClass('d-flex align-items-center')->name('name')->title('Name'),
            Column::make('email')->addClass('text-center text-nowrap no-export')->name('email'),
            Column::make('subject')->addClass('text-center text-nowrap no-export')->name('subject'),
            Column::make('message')->addClass('text-center text-nowrap no-export')->name('message'),
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
        return 'Message_' . date('YmdHis');
    }
}
