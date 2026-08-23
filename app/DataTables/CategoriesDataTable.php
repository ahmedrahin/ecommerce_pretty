<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Category;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;

class CategoriesDataTable extends DataTable
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
            ->editColumn('name', function (Category $category) {
                return view('pages.apps.category.columns.category-detail', compact('category'));
            })
            ->editColumn('product_summaries', function (Category $category) {
                $productCount = $category->product->count(); 
                if ($productCount == 0) {
                    $count = '<span class="badge badge-light-danger">0</span>';
                } elseif ($productCount > 0 && $productCount <= 5) {
                    $count = '<span class="badge badge-light-warning">' . $productCount . '</span>';
                } else {
                    $count = '<span class="badge badge-light-primary">' . $productCount . '</span>';
                }

                return $count;
            })
             ->addColumn('is_featured', function (Category $category) {
                return view('pages.apps.category.columns.featured', compact('category'));
            })
            ->addColumn('active', function (Category $category) {
                return view('pages.apps.category.columns._active_status', compact('category'));
            })
            ->addColumn('actions', function (Category $category) {
                return view('pages.apps.category.columns._actions', compact('category'));
            })
            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['name','product_summaries']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Category $model): QueryBuilder
    {
        $cacheKey = config('dbcachekey.category');

        // Cache categories with ordering
        $categories = Cache::rememberForever($cacheKey, function () use ($model) {
            return $model->newQuery()
                ->with('subcategories.subsubcategories')
                ->orderByDesc('id') // Correct descending order
                ->get();
        });

        // Return the query builder with the correct order
        return $model->newQuery()
            ->whereIn('id', $categories->pluck('id'))
            ->orderByDesc('id'); // Ensure ordering here as well
    }

    
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('category-table')
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
            ->parameters([
                'colReorder' => true,
                'drawCallback' => "function() {" . file_get_contents(resource_path('views/pages/apps/category/columns/_draw-scripts.js')) . "}",
            ]);
           // ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/category/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('ID')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('name')->title('Category'),
            Column::computed('product_summaries')->title('Product_summaries')->addClass('text-center'),
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
        return 'Categories_' . date('YmdHis');
    }
}

