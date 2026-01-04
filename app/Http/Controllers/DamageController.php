<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\Damage;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockItem;
use App\Models\Supplier;

class DamageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:damage-list', ['only' => ['index']]);
        $this->middleware('permission:damage-create|damage-edit', ['only' => ['store']]);
        $this->middleware('permission:damage-create', ['only' => ['create']]);
        $this->middleware('permission:damage-edit', ['only' => ['edit']]);
        $this->middleware('permission:damage-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'All Damages';
        $damages = Damage::where('is_deleted', 0)
            ->with('supplier', 'product')
            ->latest()
            ->get();
        return view('damage.index', compact('pageTitle', 'damages'));
    }

    public function create()
    {
        $pageTitle = "Damage";
        $products = Product::whereStatus(1)->with('stockItem')->notDeleted()->latest()->get();
        $suppliers = Supplier::whereStatus(1)->notDeleted()->latest()->get();
        return view('damage.store', compact('pageTitle', 'products', 'suppliers'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Damage';
        $damage = Damage::find($id);
        $stocks = Stock::where('product_id', $damage->product_id)
            ->with('batch', 'batch.purchase', 'product.unit')
            ->get()->map(function ($stock) {
                return [
                    'supplier_id' => $stock->batch->purchase->supplier_id ?? null,
                    'purchase_id' => $stock->batch->purchase->id ?? null,
                    'batch_id' => $stock->batch->id ?? null,
                    'batch_code' => $stock->batch->batch_code ?? null,
                    'avg_purchase_price' => $stock->avg_purchase_price,
                    'stock' => $stock->stock,
                    'unit_name' => $stock->product->unit->name ?? 'unit',
                    'purchase_batch_id' => $stock->purchase_batch_id ?? null,
                ];
            });

        $products = Product::whereStatus(1)->with('stockItem')->notDeleted()->latest()->get();

        return view('damage.store', compact('damage', 'pageTitle', 'products', 'stocks'));
    }


    public function batchAjax($id)
    {
        $stocks = Stock::whereProductId($id)->with('batch', 'batch.purchase', 'product.unit')->get();

        return $stocks->map(function ($stock) {
            return [
                'supplier_id' => $stock->batch->purchase->supplier_id ?? null,
                'purchase_id' => $stock->batch->purchase->id ?? null,
                'batch_id' => $stock->batch->id ?? null,
                'batch_code' => $stock->batch->batch_code ?? null,
                'avg_purchase_price' => $stock->avg_purchase_price,
                'stock' => $stock->stock,
                'unit_name' => $stock->product->unit->name ?? 'unit',
                'purchase_batch_id' => $stock->purchase_batch_id ?? null,
            ];
        });
    }

    public function store(Request $request, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'entry_date'          => 'required',
            'qty'                 => 'required',
            'avg_purchase_price'  => 'required',
            'total_qty'           => 'required',
            'total_damage_price'  => 'required',
            'description'         => 'required',
        ]);

        if ($id > 0) {
            $damage = Damage::whereId($id)->first();
            $message = 'Damage updated successfully';
            $damage->last_update       = now();
            $damage->update_by         = auth()->user()->id;
            $givenStatus      = isset($request->damage_status) ? 1 : 0;

            $accounts = Account::where('purchase_id', $damage->purchase_id)
                ->where('type', 1)
                ->first();

            $accountExist = Account::whereDamageId($id)->first();
            if ($accountExist) {
                $accountExist->delete();
            }

            $accounts->debit += $damage->total_damage_price;
            $accounts->save();

            $damageStatus = $damage->damage_status;
            if ($damageStatus == 1) {
                $damageProductId = $damage->product_id;
                $damagepurchaseBatchId = $damage->purchase_batch_id;
                $damageStockQty = $damage->qty;

                $stockData = Stock::where('product_id', $damageProductId)
                    ->where('purchase_batch_id', $damagepurchaseBatchId)
                    ->first();

                $stockData->stock += $damageStockQty;
                $stockData->save();

                $stockItem = StockItem::where('product_id', $damageProductId)->first();
                $stockItem->stock += $damageStockQty;
                $stockItem->total_damage_qty -= $damageStockQty;
                $stockItem->save();
            }
        } else {
            $damage           = new Damage();
            $message          = 'New Damage created successfully';
            $givenStatus      = isset($request->damage_status) ? 1 : 0;
        }

        if ($givenStatus == 1) {

            $productId = $request->product_id;
            $purchaseBatchId = $request->purchase_batch_id;

            $stockCheck = Stock::where('purchase_batch_id', $purchaseBatchId)
                ->where('product_id', $productId)
                ->first();

            $stockCheck->stock -= $request->qty;
            $stockCheck->save();

            $stockItem = StockItem::where('product_id', $productId)->first();
            $stockItem->stock -= $request->qty; 
            $stockItem->total_damage_qty += $request->qty; 
            $stockItem->save();


            // $purchaseId = $request->purchase_id;

            // $accounts = Account::where('purchase_id', $purchaseId)
            //     ->where('type', 1)
            //     ->first();

            // $accounts->debit -= $request->total_damage_price;
            // $accounts->save();

            // dd($accounts->debit);
        }

        $damage->purchase_id          = $request->purchase_id;
        $damage->supplier_id          = $request->supplier_id;
        $damage->purchase_batch_id    = $request->purchase_batch_id;
        $damage->product_id           = $request->product_id;
        $damage->price                = $request->avg_purchase_price;
        $damage->total_damage_price   = $request->total_damage_price;
        $damage->qty                  = $request->qty;
        $damage->total_qty            = $request->total_qty;
        $damage->description          = $request->description;
        $damage->entry_date           = $request->entry_date;
        $damage->entry_by             = auth()->user()->id;


        if ($givenStatus == 1) {
            $damage->damage_status = 1;
        } elseif ($givenStatus == 0) {
            $damage->damage_status = 0;
        }
        if (!empty($request->status)) {
            $damage->status = $request->status;
        }
        if (!empty($request->conversation)) {
            $damage->conversation = $request->conversation;
        }
        if (!empty($request->replacement_repair_date)) {
            $damage->replacement_repair_date = $request->replacement_repair_date;
        }
        $damage->save();

        // $damageId = $damage->id;

        // dd($damageId);

        // if ($givenStatus == 1) {
        //     $accArr = [
        //         'damage_id'        => $damageId,
        //         'type'             => 12,
        //         'debit'            => $request->total_damage_price,
        //         'description'      => "Damage product from Supplier (" . $damage->supplier->name . ") Damage amount " . $request->total_damage_price . " Purchase Id (" . $request->purchase_id . ")",
        //         'supplier_id'      => $damage->supplier_id,
        //         'purchase_id'      => $damage->purchase_id,
        //         'payment_method'   => 1,
        //     ];

        //     updateAcc($accArr, 'damage_id', $id, 12);
        // }


        $notify[] = ['success', $message];
        return to_route('damage.index')->withNotify($notify);
    }
    public function delete($id)
    {
        $damage = Damage::find($id);
        $damage->is_deleted = 1;
        $damage->save();

        $notify[] = ['success', 'Damage has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
