<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Subcategory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SubcategoriesDataTable extends DataTable
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
            ->editColumn('name', function (Subcategory $subcategory) {
                return view('pages.apps.subcategory.columns.sub-category-details', compact('subcategory'));
            })
            ->editColumn('category_name', function (Subcategory $subcategory) {
                return '<span class="badge badge-light-primary">' . $subcategory->category->name ?? '' . '</span>';
            })
            ->editColumn('product_summaries', function (Subcategory $subcategory) {
                $productCount = $subcategory->product->count(); 
                if ($productCount == 0) {
                    $count = '<span class="badge badge-light-danger">0</span>';
                } elseif ($productCount > 0 && $productCount <= 5) {
                    $count = '<span class="badge badge-light-warning">' . $productCount . '</span>';
                } else {
                    $count = '<span class="badge badge-light-primary">' . $productCount . '</span>';
                }

                return $count;
            })
            ->filterColumn('category_name', function ($query, $keyword) {
                $query->where('categories.name', 'LIKE', "%{$keyword}%"); // Enable search on category name
            })
            ->addColumn('is_featured', function (Subcategory $category) {
                return view('pages.apps.subcategory.columns.featured', compact('category'));
            })
            ->addColumn('active', function (Subcategory $subcategory) {
                return view('pages.apps.subcategory.columns._active_status', compact('subcategory'));
            })
            ->addColumn('actions', function (Subcategory $subcategory) {
                return view('pages.apps.subcategory.columns._actions', compact('subcategory'));
            })
            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['name', 'category_name', 'product_summaries', 'actions']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Subcategory $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('subcategories.*', 'categories.name as category_name') 
            ->join('categories', 'categories.id', '=', 'subcategories.category_id') 
            ->orderByDesc('subcategories.id');
    }
    

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subcategory-table')
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
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/subcategory/columns/_draw-scripts.js')) . "}");
    }
    
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('ID')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('name')->title('Subcategory'),
            Column::make('category_name')
                ->title('Category')
                ->addClass('text-center')
                ->searchable(true), // Enable searching
            Column::computed('product_summaries')->title('Product Summaries')->addClass('text-center'),
             Column::make(data: 'is_featured')->title('Featured')->addClass('text-center')->exportable(false)->printable(false),
            Column::computed('active')
                ->title('Status')
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
        return 'Subcategories_' . date('YmdHis');
    }
}
