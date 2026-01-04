<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\SellRecord;
use App\Models\Product;

class DiscountReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:discount-report-list', ['only' => ['purchaseindex', 'sellindex']]);
    }
    public function purchaseindex(Request $request)
    {
        $pageTitle = 'Purchase Discount Report';
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
        $supplier_id = $request->supplier_id;
        // dd($supplier_id);

        $purchases = Purchase::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $purchases = $purchases->$clause('created_at', $givenDate);
            // dd($givenDate);
        }
        if ($supplier_id) {
            $purchases = $purchases->where('supplier_id', $supplier_id);
        }

        $purchases = $purchases->where(function ($q) {
            $q->where('discount', '>', 0)
              ->orWhere('commission', '>', 0);
        })

        ->with([
                'supplier',
                'items.product',
                'items' => function ($query) {
                    $query->select('purchase_items.*')
                        ->addSelect([
                            'avg_purchase_price' => Stock::query()
                                ->selectRaw('SUM(avg_purchase_price)')
                                ->whereColumn('stocks.purchase_id', 'purchase_items.purchase_id')
                                ->whereColumn('stocks.product_id', 'purchase_items.product_id')
                        ]);
                }
            ])
            ->latest()
            ->paginate(gs()->pagination);

        // dd($purchases->toArray());

        $suppliers = Supplier::latest()->notDeleted()->get();

        return view('discount-report.purchase-index', compact('pageTitle', 'todayTime', 'purchases', 'suppliers'));
    }

    public function sellindex(Request $request)
    {
        $pageTitle = 'Sell Discount Report';
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
        $product_id = $request->product_id;
        // dd($customer_id);

        $sellRecords = SellRecord::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $sellRecords = $sellRecords->$clause('created_at', $givenDate);
            // dd($givenDate);
        }

        if ($customer_id) {
            $sellRecords = $sellRecords->whereHas('sell', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            });
        }
        if ($product_id) {
            $sellRecords = $sellRecords->whereHas('product', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            });
        }
        $sellRecords = $sellRecords->where('discount', '>', 0)->with(['product', 'purchaseBatch', 'sell.customer'])->latest()
            ->paginate(gs()->pagination);

        // dd($sellRecords->toArray());



        $customers = Customer::latest()->notDeleted()->get();
        $products = Product::latest()->notDeleted()->get();

        return view('discount-report.sell-index', compact('pageTitle', 'todayTime', 'customers', 'products', 'sellRecords'));
    }
}
