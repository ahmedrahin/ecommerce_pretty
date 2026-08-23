<?php

namespace App\Http\Controllers\Apps\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\District;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItems;
use App\DataTables\Order\OrderDataTable;
use App\DataTables\Order\TrashOrderDataTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Order\TodayOrderDataTable;
use App\DataTables\Order\MonthlyOrderDataTable;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $cacheKey;

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.order');
        // set middlware for permession
        $this->middleware('can:all order')->only(['index', 'show']);
    }

    // order list show in order table
    public function index(OrderDataTable $dataTable){
        $order = Order::get();
        $deliveryStats = DB::table('orders')
            ->selectRaw('
                orders.delivery_status as status,
                COUNT(orders.id) as total_orders,
                COALESCE(SUM(orders.grand_total), 0) as total_amount
            ')
            ->whereNull('orders.deleted_at')
            ->groupBy('orders.delivery_status')
            ->get()
            ->keyBy('status');

        // totals
        $totalOrders = $deliveryStats->sum('total_orders');
        $totalAmount = $deliveryStats->sum('total_amount');

        return $dataTable->render('pages.apps.order.list', compact('order' ,'totalOrders', 'totalAmount', 'deliveryStats'));
    }

     public function create(){
        $ShippingMethods = ShippingMethod::orderBy('base_id', 'desc')->where('status', 1)->get();
        $districts = District::orderBy('name', 'asc')->where('status',1)->get();
        return view('pages.apps.order.create', compact('ShippingMethods', 'districts'));
    }

    // store new order
    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|numeric|digits:11',
            'payment_type' => 'required',
            'shipping_method' => 'nullable',
            'order_date' => 'required',
            'district_id' => 'required',
            'shipping_address' => 'required',
            'shipping_cost' => 'required',
        ],[
            'shipping_cost.required' => 'Shipping cost field is required',
            'shipping_method.required' => 'Please select a shipping method',
            'order_date.required' => 'Please select a date',
            'payment_type.required' => 'Please select a payment method type',
            'district_id.required' => 'Please select a city',
        ]);

        $letters = Str::upper(Str::random(4));
        $numbers = (string) rand(1000, 9999);
        $orderId = str_shuffle($letters . $numbers);
        // Check if there are selected products in session
        $selectedProducts = session()->get('selectedProducts', []);
        $quantities = session()->get('quantities', []);
        if (empty($selectedProducts)) {
            return response()->json([
                'error' => 'No products selected for the order.'
            ], 400);
        }

        $grandTotal = session()->get('totalCost', 0);

        // Create the order
        $order = Order::create([
            'order_id'  => $orderId,
            'user_id' => Auth::id() ?? null,
            'user_type' => 'author',
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'payment_type' => $request->payment_type,
            'shipping_method' => $request->shipping_method,
            'order_date' => $request->order_date,
            'district_id' => $request->district_id,
            'shipping_address' => $request->shipping_address,
            'shipping_cost' => $request->shipping_cost,
            'note' => $request->note,
            'transaction_id' => $request->transaction_id,
            'payment_from' => $request->payment_from,
            'discount_amount' => $request->discount_amount,

            'grand_total' => $grandTotal + $request->shipping_cost
        ]);

        // Loop through session data and save each product as order items
        foreach ($selectedProducts as $productId) {
            $quantity = $quantities[$productId] ?? 1;

            // Fetch the latest product information
            $product = Product::find($productId);
            if ($product) {
                // Check stock availability
                if ($product->quantity >= $quantity) {
                    // Determine price based on discount option
                    $price = ($product->discount_option != 1) ? $product->offer_price : $product->base_price;
                    $product->update(['quantity' => $product->quantity - $quantity]);

                    // Create the order item
                    $order->orderItems()->create([
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    // After update, check if stock is 0
                    if ($product->fresh()->quantity == 0) {
                        \App\Models\ProductStockManage::create([
                            'product_id' => $product->id,
                            'stock'      => 'out_of_stock',
                            'quantity'   => 0,
                        ]);
                    }
                }
            }
        }

        // Clear session data after order creation
        session()->forget(['selectedProducts', 'quantities', 'totalQuantity', 'totalCost']);
        $this->refreshCache();
        return response()->json([
            'message' => 'Order has been successfully created!',
            'order_id' => $order->id,
        ], 200);
    }

    // checkout page
    public function checkout()
    {
        // Fetch cart data from the session
        $cart = session()->get('cart', []);
        $direct_checkout = session()->get('direct_checkout', []);
        if (empty($cart) && empty($direct_checkout)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        if (config('website_settings.guest_checkout') == 0 && !Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Please login to proceed with checkout.');
        }

        // Pass the cart data to the checkout view
        return view('frontend.pages.order.checkout', ['cart' => $cart]);
    }


    // edit the order
    public function edit(int $id){

    }

    // show the order details
    public function show(int $id){
        $order = Order::withTrashed()->find($id);

        if(!$order){
            return redirect()->back()->with('error', 'The order is not found');
        }

        return view('pages.apps.order.order-details', compact('order'));
    }

    // order invoice genarate
    public function order_invoice(int $id){
        $order = Order::findOrFail($id);
        return view('pages.apps.order.order-invoice', compact('order'));
    }

    public function downloadInvoice(string $order_id)
    {
        $order = Order::where('order_id', $order_id)->first();

        // Generate PDF
        $pdf = PDF::loadView('frontend.pages.order.invoice', compact('order'));

        // Display PDF in the browser
        return $pdf->stream('order-invoice.pdf');

    }

    public function todayOrder(TodayOrderDataTable $dataTable)
    {
        $deliveryStats = DB::table('orders')->whereDate('order_date', today())
            ->selectRaw('
                orders.delivery_status as status,
                COUNT(orders.id) as total_orders,
                COALESCE(SUM(orders.grand_total), 0) as total_amount
            ')
            ->whereNull('orders.deleted_at')
            ->groupBy('orders.delivery_status')
            ->get()
            ->keyBy('status');

        // totals
        $totalOrders = $deliveryStats->sum('total_orders');
        $totalAmount = $deliveryStats->sum('total_amount');

        $order = Order::whereDate('order_date', Carbon::today())->get();
        return $dataTable->render('pages.apps.order.today-order', compact('order', 'totalOrders', 'totalAmount', 'deliveryStats'));
    }

    public function monthlyOrder(MonthlyOrderDataTable $dataTable, $year = null, $month = null)
    {
        // Use current year & month if not provided
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;
        $getMonth = ucfirst($month) . "-" . $year;

        $isMonthExists = DB::table('orders')
                        ->whereYear('order_date', $year)
                        ->whereMonth('order_date', $month)
                        ->whereNull('orders.deleted_at')
                        ->exists();

        $allYear = DB::table('orders')
                    ->whereNull('orders.deleted_at')
                    ->selectRaw('DISTINCT YEAR(order_date) as year')
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        $monthis = Carbon::parse("1 $month")->month;
        $order = Order::whereYear('order_date', $year)
                       ->whereMonth('order_date', $monthis)
                       ->get();

        $deliveryStats = DB::table('orders')->whereYear('order_date', $year)->whereMonth('order_date', $monthis)
            ->selectRaw('
                orders.delivery_status as status,
                COUNT(orders.id) as total_orders,
                COALESCE(SUM(orders.grand_total), 0) as total_amount
            ')
            ->whereNull('orders.deleted_at')
            ->groupBy('orders.delivery_status')
            ->get()
            ->keyBy('status');

        // totals
        $totalOrders = $deliveryStats->sum('total_orders');
        $totalAmount = $deliveryStats->sum('total_amount');

        return $dataTable->render('pages.apps.order.monthly', compact('order', 'month', 'getMonth', 'isMonthExists', 'allYear', 'year', 'totalOrders', 'totalAmount', 'deliveryStats'));
    }

     // Refresh the cache
    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Order::orderBy('id', 'desc')->get();
        });
    }

    public function trash(TrashOrderDataTable $dataTable){
        return $dataTable->render('pages.apps.order.trash');
    }

}
