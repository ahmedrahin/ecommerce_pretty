<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Brand;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BrandsDataTable extends DataTable
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
            ->addColumn('image', function (Brand $brand) {
                $imagePath = $brand->image ? $brand->image : 'uploads/blank-image.svg';
                $imageUrl  = asset($imagePath);
                return '<img src="' . $imageUrl . '" alt="Brand Image" width="50" height="50" class="table-image">';
            })
            ->editColumn('name', function (Brand $brand) {
                return '<span class="text-gray-800 fs-5 fw-bold">' . $brand->name . '</span>';
            })
            ->editColumn('product_summaries', function (Brand $brand) {
                $productCount = $brand->product->count(); 
                if ($productCount == 0) {
                    $count = '<span class="badge badge-light-danger">0</span>';
                } elseif ($productCount > 0 && $productCount <= 5) {
                    $count = '<span class="badge badge-light-warning">' . $productCount . '</span>';
                } else {
                    $count = '<span class="badge badge-light-primary">' . $productCount . '</span>';
                }

                return $count;
            })
            ->editColumn('category_id', function (Brand $brand) {
                return $brand->category ? '<span class="badge badge-light-primary">' . $brand->category->name . '</span>' : '<span class="text-gray-800 fs-5 fw-bold">-</span>';
            })
            ->addColumn('active', function (Brand $brand) {
                return view('pages.apps.brand.columns._active_status', compact('brand'));
            })
            ->addColumn('actions', function (Brand $brand) {
                return view('pages.apps.brand.columns._actions', compact('brand'));
            })
            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['image', 'name', 'actions', 'product_summaries', 'category_id', 'active']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Brand $model): QueryBuilder
    {
        $cacheKey = config('dbcachekey.brand');
        $brands = Cache::rememberForever($cacheKey, function () use ($model) {
            return $model->newQuery()
                        ->orderBy('id', 'desc') 
                        ->get();
        });

        $ids = $brands->pluck('id')->toArray(); 
        return $model->newQuery()->whereIn('id', $ids)->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('brand-table')
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
            
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/brand/columns/_draw-scripts.js')) . "}");
    }
    
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('Sl.')->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('image')->title('Image')->orderable(false)->searchable(false),
            Column::make('name')->title('Name'),
            Column::computed('category_id')->title('Category')->addClass('text-center'),
            Column::computed('product_summaries')->title('Product_summaries')->addClass('text-center'),
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
        return 'Brands_' . date('YmdHis');
    }
}