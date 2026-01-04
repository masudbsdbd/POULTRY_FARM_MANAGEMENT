<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\PurchaseBatch;
use App\Models\ManageStock;
use App\Models\ManageStockItem;


class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:stock-list', ['only' => ['todayStock', 'monthStock', 'stockItems', 'detail', 'manageStock','manageStockItems']]);
    }
    public function todayStock()
    {
        $pageTitle = 'Today Stocks';
        $stocks = Stock::whereDate('created_at', Carbon::today())->latest()->paginate(gs()->pagination);
        return view('stock.index', compact('pageTitle', 'stocks'));
    }

    public function monthStock()
    {
        $pageTitle = 'Stocks of ' . Carbon::now()->format('F');
        $stocks = Stock::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->latest()
            ->paginate(gs()->pagination);
        return view('stock.index', compact('pageTitle', 'stocks'));
    }

    public function stockItems()
    {
        $pageTitle = 'All Stock Item';
        $stocks = StockItem::latest()->paginate(gs()->pagination);
        return view('stock.items', compact('pageTitle', 'stocks'));
    }

    public function detail($id)
    {
        $pageTitle = 'All Stock Item';
        $stocks = Stock::whereProductId($id)->paginate(gs()->pagination);
        return view('stock.index', compact('pageTitle', 'stocks'));
    }

    public function manageStock()
    {
        $pageTitle = 'Manage Stock';
        $manageStocks = ManageStock::with('items','items.product', 'items.batch', 'supplier')
        ->latest()->paginate(gs()->pagination);
        return view('stock.manage', compact('pageTitle', 'manageStocks'));
    }
    public function manageStockCreate()
    {
        $pageTitle = 'Manage Stock Create';
        $suppliers = Supplier::whereStatus(1)->latest()->get();
        return view('stock.manage-create', compact('pageTitle', 'suppliers'));
    }

    public function manageStockAjax($id)
    {
        $batches = PurchaseBatch::whereSupplierId($id)
        ->with(['purchase.items.product'])
        ->get();

            $data = $batches->map(function ($batch) {
            return [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'products' => $batch->purchase->items->map(function ($item) use ($batch) {
                    return [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'avg_purchase_price' => $item->avg_purchase_price,
                        'stock' => $batch->stock->stock, 
                    ];
                }),
            ];
            });

        
        return response()->json($data);
    }

    public function manageStockStore(Request $request){
        $request->validate([
            'products' => 'required|array|min:1',
            'batch_id' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
            'avg_purchase_price' => 'required|array|min:1',
            'priceTotal' => 'required|array|min:1',
            'stock_status' => 'required|integer',
            'description' => 'required|string',
            'total_qty' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ]);
        $input = $request->all();
        // dd($input);

        $supplier_id = $input['supplier_id'];
        $products = $input['products'];
        $batch_id = $input['batch_id'];
        $qty = $input['qty'];
        $avg_purchase_price = $input['avg_purchase_price'];
        $priceTotal = $input['priceTotal'];
        $stock_status = $input['stock_status'];
        $total_qty = $input['total_qty'];
        $grand_total = $input['grand_total'];
        $description = $input['description'];
        
        if($stock_status == 3 || $stock_status == 6){
            $manageStock = new ManageStock();
            $manageStock->supplier_id = $supplier_id;
            $manageStock->total_qty = $total_qty;
            $manageStock->grand_total = $grand_total;
            $manageStock->description = $description;
            $manageStock->stock_status = $stock_status;
            $manageStock->save();

            foreach ($products as $key => $product) {
                $manageStockItem = new ManageStockItem();
                $manageStockItem->manage_stock_id = $manageStock->id;
                $manageStockItem->supplier_id = $supplier_id;
                $manageStockItem->product_id = $product;
                $manageStockItem->batch_id = $batch_id[$key];                
                $manageStockItem->qty = $qty[$key];
                $manageStockItem->avg_purchase_price = $avg_purchase_price[$key];
                $manageStockItem->total_amount = $priceTotal[$key];
                $manageStockItem->save();
                
                $stock = Stock::where('product_id', $product)->where('purchase_batch_id', $batch_id[$key])->first();
                $stock->stock += $qty[$key];
                $stock->save();

                $stockItem = StockItem::where('product_id', $product)->first();
                $stockItem->stock += $qty[$key];
                $stockItem->save();
            }
                // dd('add');

                $message = 'Stock successfully added.';

                $notify[] = ['success', $message];
                return to_route('stock.manage')->withNotify($notify);

        }else{

                $manageStock = new ManageStock();
                $manageStock->supplier_id = $supplier_id;
                $manageStock->total_qty = $total_qty;
                $manageStock->grand_total = $grand_total;
                $manageStock->description = $description;
                $manageStock->stock_status = $stock_status;
                $manageStock->save();

            foreach ($products as $key => $product) {

                $manageStockItem = new ManageStockItem();
                $manageStockItem->manage_stock_id = $manageStock->id;
                $manageStockItem->supplier_id = $supplier_id;
                $manageStockItem->product_id = $product;
                $manageStockItem->batch_id = $batch_id[$key];                
                $manageStockItem->qty = $qty[$key];
                $manageStockItem->avg_purchase_price = $avg_purchase_price[$key];
                $manageStockItem->total_amount = $priceTotal[$key];
                $manageStockItem->save();

            $stock = Stock::where('product_id', $product)->where('purchase_batch_id', $batch_id[$key])->first();
            $stock->stock -= $qty[$key];
            $stock->save();

            $stockItem = StockItem::where('product_id', $product)->first();
            $stockItem->stock -= $qty[$key];
            $stockItem->save();

                if($stock_status == 1){
                    $stockItem->total_damage_qty += $total_qty;
                    $stockItem->save();
                }


            }

            $message = 'Stock successfully removed.';

            $notify[] = ['success', $message];
            return to_route('stock.manage')->withNotify($notify);

            // dd('minus'); 
        }
    }



}


