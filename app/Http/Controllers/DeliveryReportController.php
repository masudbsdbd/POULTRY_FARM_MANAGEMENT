<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DeliveryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:delivery-report-list', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        $pageTitle = 'Today Delivery Report';
        $todayTime = Carbon::now()->format('d-m-Y');

        $products = Product::whereStatus(1)->notDeleted()->latest()->get();
        $customers = Customer::whereStatus(1)->notDeleted()->latest()->get();

        $type = $request->type;
        $date = $request->date;
        $range = $request->range;

        if ($range) {
            $dates = explode(' to ', $range);
            $givenDates = [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59',
            ];
        }

        $product_id = $request->product_id;
        $customer_id = $request->customer_id;

        $deliveries = Delivery::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }

            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $deliveries = $deliveries->$clause('created_at', $givenDate);
        }

        if ($product_id) {
            $deliveries = $deliveries->whereHas('sell.sellRecords', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            });
        }

        if ($customer_id) {
            $deliveries = $deliveries->where('customer_id', $customer_id);
        }

        $deliveries = $deliveries->with('sell', 'sell.sellRecords', 'sell.sellRecords.product.unit')->latest()->paginate(gs()->pagination);

        return view('delivery-report.index', compact('pageTitle', 'todayTime', 'deliveries', 'products', 'customers'));
    }
}
