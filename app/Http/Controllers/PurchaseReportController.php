<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Damage;
use App\Models\SupplierReturnItem;
use App\Models\CustomerReturnItems;
use App\Models\Stock;
use App\Models\Supplier;


class PurchaseReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:purchase-report-list', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        $pageTitle = 'Purchase Report';
        $todayTime = Carbon::now()->format('d-m-Y');
        $type = $request->type;
        $date = $request->date;
        $range = $request->range;
        $supplier_id = $request->supplier_id;

        if ($range) {
            $dates = explode(' to ', $range);
            if (count($dates) == 2) {
                $givenDates = [
                    $dates[0] . ' 00:00:00',
                    $dates[1] . ' 23:59:59',
                ];
            } else {
                $notify[] = ['error', 'Invalid date range format. Please use "start date to end date".'];
                return back()->withNotify($notify);
            }
        }

        $purchases = Purchase::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;

            if (!$givenDate) {
                $notify[] = ['error', 'Kindly select a valid date.'];
                return back()->withNotify($notify);
            }

            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $purchases = $purchases->$clause('created_at', $givenDate);
        }

        if ($supplier_id) {
            $purchases = $purchases->where('supplier_id', $supplier_id);
        }

        $purchases = $purchases->with([
            'stock',
            'batch',
            'supplier',
            'items.product',
            'items' => function ($query) {
                $query->select('purchase_items.*')

                    ->addSelect([
                        'damage_qty' => Damage::query()
                            ->selectRaw('SUM(qty)')
                            ->whereColumn('damages.product_id', 'purchase_items.product_id')
                            ->whereColumn('damages.purchase_id', 'purchase_items.purchase_id')
                    ])

                    ->addSelect([
                        'return_qty' => SupplierReturnItem::query()
                            ->selectRaw('SUM(return_qty)')
                            ->whereColumn('supplier_return_items.purchase_id', 'purchase_items.purchase_id')
                            ->whereColumn('supplier_return_items.product_id', 'purchase_items.product_id')
                    ])

                    ->addSelect([
                        'customer_return_qty' => CustomerReturnItems::query()
                            ->selectRaw('SUM(return_qty)')
                            ->whereColumn('customer_return_items.purchase_id', 'purchase_items.purchase_id')
                            ->whereColumn('customer_return_items.product_id', 'purchase_items.product_id')
                    ])

                    ->addSelect([
                        'stock' => Stock::query()
                            ->selectRaw('SUM(stock)')
                            ->whereColumn('stocks.purchase_id', 'purchase_items.purchase_id')
                            ->whereColumn('stocks.product_id', 'purchase_items.product_id')
                    ])

                    ->addSelect([
                        'avg_purchase_price' => Stock::query()
                            ->selectRaw('SUM(avg_purchase_price)')
                            ->whereColumn('stocks.purchase_id', 'purchase_items.purchase_id')
                            ->whereColumn('stocks.product_id', 'purchase_items.product_id')
                    ]);
            }
        ])->latest()->paginate(gs()->pagination);

        // dd($purchases->toArray());

        $suppliers = Supplier::latest()->notDeleted()->get();

        return view('purchase-report.index', compact('pageTitle', 'todayTime', 'purchases', 'suppliers'));
    }
}
