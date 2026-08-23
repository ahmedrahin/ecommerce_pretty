<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Product;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductsDataTable extends DataTable
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
            ->editColumn('name', function (Product $product) {
                return view('pages.apps.product.columns.name', compact('product'));
            })
            ->editColumn('base_price', function (Product $product) {
                if ($product->discount_option == 1) {
                    return '$' . ($product->base_price);
                } else {
                    return '$' . ($product->offer_price) . '<br><del style="color: #f1416cad">' . '$' . ($product->base_price) . '</del>';
                }
            })
            ->editColumn('offer_price', function (Product $product) {
                return $product->offer_price;
            })
            // ->addColumn('expire_date', function (Product $product) {
            //     return $product->expire_date;
            // })
            ->editColumn('sku_code', function (Product $product) {
                return $product->sku_code;
            })
            ->editColumn('quantity', function (Product $product) {
                if ($product->quantity == 0) {
                    return '<span class="badge badge-light-danger">Sold out</span>';
                } elseif ($product->quantity > 0 && $product->quantity <= 5) {
                    return '<span class="badge badge-light-warning">Low stock: ' . $product->quantity . '</span>';
                } else {
                    return '<span class="badge badge-light-primary">' . $product->quantity . '</span>';
                }
            })
            ->editColumn('category_id', function (Product $product) {
                return optional($product->category)->name ?? '<span class="badge badge-light-danger">Uncategorized</span>';
            })
            ->addColumn('rating', function (Product $product) {
                $averageRating = round($product->reviews()->avg('rating'), 1);
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= $i <= $averageRating
                        ? '<i class="ki-duotone ki-star fs-6" style="color:#ffad0f;"></i>'
                        : '<i class="ki-duotone ki-star fs-6" style="opacity:0.3;"></i>';
                }
                return '<div class="rating" style="justify-content: center;">' . $stars . '</div>';
            })

            ->addColumn('status', function (Product $product) {
                return view('pages.apps.product.columns._active_status', compact('product'));
            })
            ->addColumn('selling', function (Product $product) {
                // total order items quantity
                $totalOrders = $product->orderItems()->sum('quantity');

                // canceled order items quantity
                $canceledOrders = $product->cancelOrderItems()->sum('quantity');
                $fakeOrders = $product->fakeOrderItems()->sum('quantity');

                // final total
                $totalSales = $totalOrders;

                $badgeClass = 'badge-light-primary';
                $label = $totalSales;

                if ($totalSales == 0) {
                    $badgeClass = 'badge-light-danger';
                    $label = 'No Sale';
                } elseif ($totalSales > 10) {
                    $badgeClass = 'badge-light-success';
                    $label = $totalSales;
                }

                return '<span class="badge ' . $badgeClass . '">' . $label . '</span>';
            })

            ->filterColumn('category_id', function ($query, $keyword) {
                $keywords = explode('|', $keyword);
                $query->whereHas('category', function ($q) use ($keywords, $keyword) {
                    $q->whereIn('id', $keywords);
                });
            })

            ->filterColumn('rating', function ($query, $keyword) {
                $keywords = explode('|', $keyword);
                $query->where(function ($q) use ($keywords) {
                    $q->whereIn('id', function ($subquery) use ($keywords) {
                        $subquery->select('product_id')
                                 ->from('reviews')
                                 ->groupBy('product_id')
                                 ->havingRaw('ROUND(AVG(rating)) IN (' . implode(',', array_map('intval', $keywords)) . ')');
                    });

                    if (in_array('0', $keywords)) {
                        $q->orWhereDoesntHave('reviews'); // Products with no reviews
                    }
                });
            })


            ->filterColumn('status', function ($query, $keyword) {
                if (is_numeric($keyword)) {
                    $query->where('status', $keyword);
                }
            })

            ->filterColumn('selling', function ($query, $keyword) {
                switch ($keyword) {
                    case 'best':
                        $topProductIds = \DB::table('order_items')
                            ->select('product_id', DB::raw('SUM(quantity) as total_sales'))
                            ->groupBy('product_id')
                            ->orderBy('total_sales', 'desc')
                            ->limit(8)
                            ->pluck('product_id');

                        $query->whereIn('id', $topProductIds);
                        break;
                    case 'no':
                        $query->whereDoesntHave('orderItems');
                        break;
                    case '1-10':
                        $query->whereHas('orderItems', function ($q) {
                            $q->select(DB::raw('sum(quantity) as total_sales'))
                              ->groupBy('product_id')
                              ->havingRaw('total_sales BETWEEN 1 AND 10');
                        });
                        break;
                    case '10-20':
                        $query->whereHas('orderItems', function ($q) {
                            $q->select(DB::raw('sum(quantity) as total_sales'))
                              ->groupBy('product_id')
                              ->havingRaw('total_sales BETWEEN 10 AND 20');
                        });
                        break;
                    case '20-50':
                        $query->whereHas('orderItems', function ($q) {
                            $q->select(DB::raw('sum(quantity) as total_sales'))
                              ->groupBy('product_id')
                              ->havingRaw('total_sales BETWEEN 20 AND 50');
                        });
                        break;
                    case '50-100':
                        $query->whereHas('orderItems', function ($q) {
                            $q->select(DB::raw('sum(quantity) as total_sales'))
                              ->groupBy('product_id')
                              ->havingRaw('total_sales BETWEEN 50 AND 100');
                        });
                        break;
                    default:
                        break;
                }
            })

            ->filterColumn('base_price', function ($query, $keyword) {
                switch ($keyword) {
                    case 'no-offer':
                        $query->where('discount_option', 1);
                        break;
                    case 'offer':
                        $query->where('discount_option', '!=', 1);
                        break;
                    case '30-offer':
                        $query->where('discount_option', '!=', 1)
                              ->whereRaw('(base_price - offer_price) / base_price * 100 >= 30');
                        break;
                    default:
                        break;
                }
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereDate('created_at', '=', $keyword);
            })

            // ->filterColumn('expire_date', function ($query) {
            //     $query->whereDate('expire_date', '<', now());
            // })

            ->addColumn('actions', function (Product $product) {
                return view('pages.apps.product.columns._actions', compact('product'));
            })
            ->orderColumn('id', 'id $1')
            ->setRowId('id')
            ->rawColumns(['name', 'base_price', 'quantity', 'category_id', 'rating', 'selling', 'actions']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        $cacheKey = config('dbcachekey.product');
        $products = Cache::rememberForever($cacheKey, function () use ($model) {
            return $model->newQuery()
                         ->orderBy('id', 'desc')
                         ->get();
        });

        $ids = $products->pluck('id')->toArray();
        return $model->newQuery()->whereIn('id', $ids)->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
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
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/product/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('ID')->addClass('text-center'),
            Column::make('name')->title('Name'),
            Column::make('base_price')->title('Price')->addClass('text-center'),
            Column::make('offer_price')->visible(false)->exportable(false)->printable(false),
            Column::make('sku_code')->title('Sku_code')->addClass('text-center'),
            Column::make('quantity')->title('Qty')->addClass('text-center'),
            Column::make('category_id')->title('Category')->addClass('text-center'),
            Column::make('rating')->title('Rating')->addClass('text-center'),
            Column::make('selling')->title('Total Order')->addClass('text-center'),
            Column::make('status')->title('Status')->addClass('text-center text-nowrap no-export')->exportable(false)->printable(false),
            Column::make('created_at')->visible(false)->exportable(false)->printable(false),
            // Column::make('expire_date')->visible(false)->exportable(false)->printable(false),
            Column::computed('actions')->title('Actions')->addClass('text-end text-nowrap no-export')->exportable(false)->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
