<?php

namespace App\Http\Controllers\Apps\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;
use App\Models\ServiceProducts;
use App\DataTables\Order\ServiceDataTable;
use App\DataTables\Order\AllSerivceDataTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceController extends Controller
{

    public function all(AllSerivceDataTable $dataTable){
         return $dataTable->render('pages.apps.service.all');
    }

    public function index(ServiceDataTable $dataTable, $year = null, $month = null)
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

         $monthis = Carbon::parse("1 $month")->month;
        $order = Services::whereYear('date_of', $year)
                       ->whereMonth('date_of', $monthis)
                       ->get();

        return $dataTable->render('pages.apps.service.list', compact('order', 'month', 'getMonth', 'isMonthExists', 'allYear', 'year'));
    }

    public function create(){
        return view('pages.apps.service.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'mobile' => 'required',
            'sale_date' => 'required',
            // 'order_date' => 'required',

            'product_name.*' => 'required',
            'service_cost' => 'required|min:1|numeric'
        ], [
            'product_name.*.required' => 'Please enter at least one product.',
        ]);

        $products = json_encode($request->product_name);

        $letters = Str::upper(Str::random(4));
        $numbers = (string) rand(1000, 9999);
        $orderId = str_shuffle($letters . $numbers);

        $data = new Services();
        $data->client_name = $request->client_name;
        $data->mobile = $request->mobile;
        $data->email = $request->email;
        $data->sale_date = $request->sale_date;
        // $data->order_date = $request->order_date;
        $data->order_id = $orderId;
        $data->date_of = $request->date_of ?? now();
        $data->recive_by = $request->recive_by;
        $data->service_cost = $request->service_cost;

        $data->save();

        foreach ($request->product_name as $index => $productName) {
            ServiceProducts::create([
                'services_id' => $data->id,
                'product_name' => $productName,
                'serial_no' => $request->serial_no[$index],
                'remarks' => $request->remarks[$index] ?? null,
                'model' => $request->model[$index] ?? null,
                'problem' => $request->problem[$index] ?? null,
                'change' => $request->change[$index] ?? null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Servicing Added Successfully',
            'redirect' => route('service.show', $data->id),
        ]);
    }

     public function edit($id)
    {
        $data = Services::with('productInfo')->findOrFail($id);
        $productWarranties = $data->productInfo;

        return view('pages.apps.service.edit', compact('data', 'productWarranties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_name' => 'required',
            'mobile' => 'required',
            'sale_date' => 'required',
            'product_name.*' => 'required',
            'service_cost' => 'required|min:1|numeric'
        ], [
            'product_name.*.required' => 'Please enter at least one product.',
        ]);

        // Update Main Warranty Data
        $warranty = Services::findOrFail($id);
        $warranty->client_name = $request->client_name;
        $warranty->mobile = $request->mobile;
        $warranty->email = $request->email;
        $warranty->sale_date = $request->sale_date;
        $warranty->date_of = $request->date_of ?? $warranty->date_of;
        $warranty->recive_by = $request->recive_by;
        $warranty->status = $request->status;
        $warranty->service_cost = $request->service_cost;
        $warranty->save();

        // Sync Product Information
        $existingProducts = $warranty->productInfo->pluck('id')->toArray();

        // Collect IDs from the form if they exist
        $formIds = $request->input('product_id', []);

        // Find IDs to Delete
        $toDelete = array_diff($existingProducts, $formIds);

        if (!empty($toDelete)) {
            ServiceProducts::whereIn('id', $toDelete)->delete();
        }

        // Loop through each product and update or create if not exists
       foreach ($request->product_name as $index => $productName) {
            ServiceProducts::updateOrCreate(
                ['id' => $formIds[$index] ?? null],  // check by id
                [
                    'services_id'   => $warranty->id,
                    'product_name' => $productName,
                    'serial_no'    => $request->serial_no[$index],
                    'remarks'      => $request->remarks[$index] ?? null,
                    'model'        => $request->model[$index] ?? null,
                    'problem' => $request->problem[$index] ?? null,
                    'change' => $request->change[$index] ?? null,
                ]
            );
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Servicing Updated Successfully',
            'redirect' => route('service.show', $warranty->id),
        ]);
    }

    public function show($id){
        $data = Services::with('productInfo')->find($id);
        return view('pages.apps.service.show', compact('data'));
    }

}
