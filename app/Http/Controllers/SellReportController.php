<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Sell;
use App\Models\CustomerReturnItems;


class SellReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:sell-report-list', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        // dd($request->all());
        $pageTitle = 'Sell Report';
        $todayTime = Carbon::now()->format('d-m-Y');

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
        $customer_id = $request->customer_id;
        // dd($customer_id);
        $sells = Sell::query();
        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $sells = $sells->$clause('created_at', $givenDate);
            // dd($givenDate);
        }
        if ($customer_id) {
            $sells = $sells->where('customer_id', $customer_id);
        }


        $sells = $sells->with(['sellRecords.product', 'customer', 'sellRecords' => function ($query) {
            $query->select('sell_records.*')
                ->addSelect([
                    'return_qty' => CustomerReturnItems::query()
                        ->selectRaw('SUM(return_qty)')
                        ->whereColumn('customer_return_items.purchase_batch_id', 'sell_records.purchase_batch_id')
                        ->whereColumn('customer_return_items.product_id', 'sell_records.product_id')
                ]);
        }])
            ->latest()
            ->paginate(gs()->pagination);

        // dd($sells->toArray());

        $customers = Customer::latest()->notDeleted()->get();

        return view('sell-report.index', compact('pageTitle', 'todayTime', 'customers', 'sells'));
    }
}
