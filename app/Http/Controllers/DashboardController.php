<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');

        $todayUsers = User::where('isAdmin', 2)->whereDate('created_at', today())->latest()->take(20)->get();
        $todayOrderData = Order::whereDate('order_date', today())->latest()->take(20)->get();

        $data = array_merge(
            $this->todayReport(),
            $this->overAll($startDate, $endDate),
            [
                'startDate' => $startDate,
                'endDate'   => $endDate,
            ],
            compact('todayUsers', 'todayOrderData')
        );

        return view('pages.dashboards.index', $data);
    }


    public function overAll($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate   = Carbon::parse($endDate)->endOfDay();
        }

        // delivery stats
        $deliveryStats = DB::table('orders')
            ->whereNull('orders.deleted_at') 
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('order_date', [$startDate, $endDate]);
            })
            ->selectRaw('
                orders.delivery_status as status,
                COUNT(orders.id) as total_orders,
                COALESCE(SUM(orders.grand_total), 0) as total_amount
            ')
            ->groupBy('orders.delivery_status')
            ->get()
            ->keyBy('status');

        // totals
        $totalOrders = $deliveryStats->sum('total_orders');
        $totalAmount = $deliveryStats->sum('total_amount');

        // users
        $queryUsers = DB::table('users')->where('isAdmin', 2)
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });

        // orders
        $queryOrders = DB::table('orders')->whereNull('orders.deleted_at') 
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('order_date', [$startDate, $endDate]);
            });

        // visitors
        $queryVisitors = DB::table('visitors')
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });


        // best selling products
        $bestSellingProducts = DB::table('order_items')->whereNull('orders.deleted_at') 
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('orders.order_date', [$startDate, $endDate]);
            })
            ->whereIn('orders.delivery_status', ['delivered', 'completed'])
            ->select(
                'products.id',
                'products.name',
                'products.created_at',
                'products.quantity',
                'products.base_price as price',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_amount')
            )
            ->groupBy('products.id', 'products.name', 'products.created_at', 'products.quantity', 'products.base_price')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();


        // top 5 orders by grand_total
        $topOrders = Order::with('orderItems')->whereNull('orders.deleted_at') 
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('order_date', [$startDate, $endDate]);
            })
            ->orderByDesc('grand_total')
            ->limit(5)
            ->get();

        return [
            'user'                => $queryUsers->count(),
            'order'               => $queryOrders->count(),
            'visitor'             => $queryVisitors->count(),
            'topOrders'           => $topOrders,
            'totalSales'          => $queryOrders->sum('grand_total'),
            'bestSellingProducts' => $bestSellingProducts,
            'deliveryStats'       => $deliveryStats,
            'deliveryTotalOrders' => $totalOrders,
            'deliveryTotalAmount' => $totalAmount,
        ];
    }

    public function todayReport()
    {
        return [
            'todaySales' => DB::table('orders')
                ->whereNull('orders.deleted_at') 
                ->whereDate('order_date', today())
                ->sum('grand_total'),

            'todayOrders' => DB::table('orders')
                ->whereNull('orders.deleted_at') 
                ->whereDate('order_date', today())
                ->count(),

            'todayPendingOrders' => DB::table('orders')
                ->whereNull('orders.deleted_at') 
                ->whereDate('order_date', today())
                ->where('delivery_status', 'pending')
                ->count(),

            'orderedProductQuantity' => DB::table('order_items')
                ->whereNull('orders.deleted_at') 
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereDate('orders.order_date', today())
                ->sum('order_items.quantity'),

            'totalVisitors' => DB::table('visitors')
                ->whereDate('created_at', today())
                ->count(),

            'todayStocks' => DB::table('product_stock_manages as psm')
                ->join('products as p', 'psm.product_id', '=', 'p.id')
                ->whereDate('psm.created_at', today())
                ->select('psm.*', 'p.name as product_name', 'p.id as product_id')
                ->limit(20)
                ->get(),

        ];
    }

    public function allNotification()
    {
        return view('pages.dashboards.all-notification');
    }
}
