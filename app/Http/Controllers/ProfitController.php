<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseBatch;
use App\Models\SellRecord;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:profit-list', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        // dd($request->all());

        $pageTitle = 'Total Profit Info';

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
        $purchase_batch_id = $request->purchase_batch_id;
        // dd($product_id);

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
        if ($product_id) {
            $sellRecords = $sellRecords->where('product_id', $product_id);
        }
        if ($purchase_batch_id) {
            $sellRecords = $sellRecords->where('purchase_batch_id', $purchase_batch_id);
        }

        $sellRecords = $sellRecords->with(['product', 'purchaseBatch'])->latest()
            ->paginate(gs()->pagination);

            // dd($sellRecords->toArray());

        $products = Product::latest()->notDeleted()->get();
        $purchaseBatches = PurchaseBatch::latest()->get();

        if ($request->action == 'print') {
            $mpdf = setPdf();

            $data = isset($givenDate) ? compact('sellRecords', 'givenDate') : compact('sellRecords');
            // $html = view('customer-ledger.ledger-pdf', $data)->render();
            $html = view('profit.profit-pdf', $data)->render();

            $mpdf->WriteHTML($html);
            return response($mpdf->Output('profit-' . 1 . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }

        // dd($pageTitle);

        // return view('customer-ledger.sell-history-index', compact('pageTitle', 'sells', 'id', 'customerData'));
        return view('profit.index', compact('pageTitle', 'sellRecords', 'products', 'purchaseBatches'));
        // return view('profit.index', compact('pageTitle', 'sellRecords', 'products', 'purchaseBatches'));
    }
}
