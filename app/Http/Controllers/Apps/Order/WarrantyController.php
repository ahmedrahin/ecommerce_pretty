<?php

namespace App\Http\Controllers\Apps\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warenty;
use App\Models\ProductWarranty;
use App\DataTables\Order\WarrantyDataTable;
use App\DataTables\Order\AllWarrantyDataTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class WarrantyController extends Controller
{
    private $cacheKey;

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.warranty');
    }

    public function all(AllWarrantyDataTable $dataTable){
         return $dataTable->render('pages.apps.warranty.all');
    }

    public function index(WarrantyDataTable $dataTable, $year = null, $month = null)
    {
        // Use current year & month if not provided
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;
        $getMonth = ucfirst($month) . "-" . $year;

       $isMonthExists = DB::table('warenties')
                            ->whereYear('date_of', $year)
                            ->whereMonth('date_of', $month)
                            ->exists();

        $allYear = DB::table('warenties')
                            ->selectRaw('DISTINCT YEAR(date_of) as year')
                            ->orderBy('year', 'desc')
                            ->pluck('year');


        return $dataTable->render('pages.apps.warranty.list', compact('month', 'getMonth', 'isMonthExists', 'allYear', 'year'));
    }

    public function create(){
        return view('pages.apps.warranty.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'mobile' => 'required',
            'sale_date' => 'required',
            'product_name.*' => 'required',
        ], [
            'product_name.*.required' => 'Please enter at least one product.',
        ]);

        $products = json_encode($request->product_name);

        $letters = Str::upper(Str::random(4));
        $numbers = (string) rand(1000, 9999);
        $orderId = str_shuffle($letters . $numbers);

        $data = new Warenty();
        // $data->user_id = $request->user_id ?? null;
        $data->client_name = $request->client_name;
        $data->mobile = $request->mobile;
        $data->email = $request->email;
        $data->sale_date = $request->sale_date;
        $data->provider = $request->provider;
        $data->order_id = $orderId;
        $data->date_of = $request->date_of ?? now();
        $data->recive_by = $request->recive_by;
        $data->cost = $request->cost;

        $data->save();

        foreach ($request->product_name as $index => $productName) {
            ProductWarranty::create([
                'warenty_id' => $data->id,
                'product_name' => $productName,
                'serial_no' => $request->serial_no[$index],
                'remarks' => $request->remarks[$index] ?? null,
                'model' => $request->model[$index] ?? null,
                'problem' => $request->problem[$index] ?? null,
                'change' => $request->change[$index] ?? null,
            ]);
        }

        $this->refreshCache();
        return response()->json([
            'status' => 'success',
            'message' => 'Warranty Added Successfully',
            'redirect' => route('warranty.show', $data->id),
        ]);
    }

    public function show($id){
        $data = Warenty::with('productInfo')->find($id);
        return view('pages.apps.warranty.show', compact('data'));
    }

    public function edit($id)
    {
        $data = Warenty::with('productInfo')->findOrFail($id);
        $productWarranties = $data->productInfo;

        return view('pages.apps.warranty.edit', compact('data', 'productWarranties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_name' => 'required',
            'mobile' => 'required',
            'sale_date' => 'required',

            'product_name.*' => 'required',
        ], [
            'product_name.*.required' => 'Please enter at least one product.',
        ]);

        // Update Main Warranty Data
        $warranty = Warenty::findOrFail($id);
        $warranty->client_name = $request->client_name;
        $warranty->mobile = $request->mobile;
        $warranty->email = $request->email;
        $warranty->sale_date = $request->sale_date;
        $warranty->provider = $request->provider;
        $warranty->date_of = $request->date_of ?? $warranty->date_of;
        $warranty->recive_by = $request->recive_by;
        $warranty->cost = $request->cost;
        $warranty->status = $request->status;

        $request->status == 'delivered' ? $warranty->delivery_date = now() : $warranty->delivery_date = null;

        $warranty->save();

        // Sync Product Information
        $existingProducts = $warranty->productInfo->pluck('id')->toArray();

        // Collect IDs from the form if they exist
        $formIds = $request->input('product_id', []);

        // Find IDs to Delete
        $toDelete = array_diff($existingProducts, $formIds);

        if (!empty($toDelete)) {
            ProductWarranty::whereIn('id', $toDelete)->delete();
        }

        // Loop through each product and update or create if not exists
       foreach ($request->product_name as $index => $productName) {
            ProductWarranty::updateOrCreate(
                ['id' => $formIds[$index] ?? null],  // check by id
                [
                    'warenty_id'   => $warranty->id,
                    'product_name' => $productName,
                    'serial_no'    => $request->serial_no[$index],
                    'remarks'      => $request->remarks[$index] ?? null,
                    'model'        => $request->model[$index] ?? null,
                    'problem' => $request->problem[$index] ?? null,
                    'change' => $request->change[$index] ?? null,
                ]
            );
        }

        $this->refreshCache();
        return response()->json([
            'status' => 'success',
            'message' => 'Warranty Updated Successfully',
            'redirect' => route('warranty.show', $warranty->id),
        ]);
    }

    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Warenty::orderBy('id', 'desc')->get();
        });
    }

}
