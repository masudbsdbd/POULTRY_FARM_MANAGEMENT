<?php

namespace App\Http\Controllers;

use App\Models\PurchaseBatch;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierReturn;
use App\Models\SupplierReturnItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockItem;
use App\Models\Purchase;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\BsType;
use App\Models\BsAccount;
use App\Models\JournalEntry;
use App\Models\Receivable;

class SupplierReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:purchase-return-list', ['only' => ['index']]);
        $this->middleware('permission:purchase-return-create|purchase-return-edit', ['only' => ['store']]);
        $this->middleware('permission:purchase-return-create', ['only' => ['create']]);
        $this->middleware('permission:purchase-return-edit', ['only' => ['edit']]);
        $this->middleware('permission:purchase-return-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'All Return to Supplier';
        $supplierReturns = SupplierReturn::latest()->with(['supplier', 'purchaseBatch'])->notDeleted()->paginate(gs()->pagination);
        return view('supplier-return.index', compact('pageTitle', 'supplierReturns'));
    }
    public function create()
    {
        $pageTitle = 'Create Return to Supplier';
        $suppliers = Supplier::all();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('supplier-return.create', compact('pageTitle', 'suppliers', 'banks'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Purchase Return';
        $supplierReturnData = SupplierReturn::find($id);
        $suppliers = Supplier::whereStatus(1)->notDeleted()->latest()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();

        $supplierId = $supplierReturnData->supplier_id;
        $purchaseBatches = PurchaseBatch::where('supplier_id', $supplierId)->get();
        foreach ($purchaseBatches as $purchaseBatch) {
            $purchaseItems = [];

            foreach ($purchaseBatch->purchase->items as $item) {
                $stock = $purchaseBatch->stock->where('purchase_batch_id', $purchaseBatch->id)
                    ->where('product_id', $item->product_id)
                    ->first();

                $quantity = $stock ? $stock->stock : 0;

                $purchaseItems[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'N/A',
                    'quantity' => $quantity,
                    'price' => $item->avg_purchase_price,
                    'purchase_id' => $item->purchase_id,
                ];
            }

            $purchaseBatch->purchase_items = $purchaseItems;
        }

        $supplierReturnItemsData = SupplierReturnItem::where('supplier_return_id', $id)->get();
        $supplierReturnItemsProducts = $supplierReturnItemsData->pluck('product_id');
        $purchaseBatchId = $supplierReturnData->purchase_batch_id;


        $supReturnItemsDt = SupplierReturnItem::where('supplier_return_id', $id);

        $purchaseId = $supReturnItemsDt->select('purchase_id')
            ->distinct()
            ->pluck('purchase_id');


        $productIds = Purchase::where('supplier_id', $supplierReturnData->supplier_id)
            ->with(['items' => function ($query) use ($purchaseId) {
                $query->whereIn('purchase_id', $purchaseId);
            }])
            ->get()
            ->pluck('items.*.product_id')
            ->flatten()
            ->unique();

        // dd($productIds->toArray());


        $productRecords = Product::whereIn('id', $productIds)
            ->with(['stocks' => function ($query) use ($purchaseBatchId) {
                $query->where('purchase_batch_id', $purchaseBatchId);
            }])->get();


        $products = [];
        foreach ($productRecords as $product) {
            $supplierReturnItem = $supplierReturnItemsData->firstWhere('product_id', $product->id);

            $stock = $product->stocks->first();


            $avg_price = Stock::where('purchase_id', $supplierReturnItem->purchase_id ?? null)
                ->where('product_id', $product->id)
                ->value('avg_purchase_price');

            $products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $avg_price,
                'stock_qty' => $stock ? $stock->stock : 0,
                'purchase_id' => $supplierReturnItem->purchase_id ?? null,
            ];
        }

        return view('supplier-return.create', compact(
            'supplierReturnData',
            'pageTitle',
            'purchaseBatches',
            'supplierReturnItemsData',
            'suppliers',
            'banks',
            'products'
        ));
    }

    public function returnAjax($id)
    {
        $purchaseBatchs = PurchaseBatch::where('supplier_id', $id)
            ->with(['purchase', 'purchase.items.product', 'stock']) // Include stock relation
            ->latest()
            ->get();

        $result = [];

        foreach ($purchaseBatchs as $purchaseBatch) {
            $purchaseItems = [];

            foreach ($purchaseBatch->purchase->items as $item) {
                $stock = $purchaseBatch->stock->where('purchase_batch_id', $purchaseBatch->id)
                    ->where('product_id', $item->product_id)
                    ->first();

                $quantity = $stock ? $stock->stock : 0;

                $purchaseItems[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'N/A',
                    'quantity' => $quantity,
                    'price' => $item->avg_purchase_price,
                    'purchase_id' => $item->purchase_id,
                ];
            }

            $result[] = [
                'batch_id' => $purchaseBatch->id ?? null,
                'batch_code' => $purchaseBatch->batch_code ?? null,
                'purchase_items' => $purchaseItems,
                'date' => $purchaseBatch->created_at->format('d-m-Y h:i A'), 
            ];
        }
        return $result;
    }

    public function store(Request $request, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'products' => 'required',
        ]);

        $input = $request->all();
        $supplierId = $input['supplier_id'];
        $batchId = $input['batch_id'];
        $totalQuantity = $input['totalQuantity'];
        $calculatedPrice = $input['calculatedPrice'];
        $products = $input['products'];
        $purchaseIds = $input['purchase_ids'];
        $quantities = $input['quantities'];
        $prices = $input['prices'];


        if ($id > 0) {
            $data = SupplierReturn::whereId($id)->first();
            $message = 'Supplier Return updated successfully';
            $data->last_update       = now();
            $data->update_by         = auth()->user()->id;

            $supplierReturnItems = SupplierReturnItem::where('supplier_return_id', $id)->get()->toArray();
            $purchaseBatchId = $data->purchase_batch_id;

            foreach ($supplierReturnItems as $key =>  $items) {
                $itemsQty = $items['return_qty'];
                $itemsProductId = $items['product_id'];

                $stock = Stock::where('purchase_batch_id', $purchaseBatchId)
                    ->where('product_id', $itemsProductId)
                    ->first();
                $stock->stock += $itemsQty;
                $stock->save();

                $stockItems = StockItem::where('product_id', $itemsProductId)
                    ->first();
                $stockItems->stock += $itemsQty;
                $stockItems->purchase_return_qty -= $itemsQty;
                $stockItems->save();
            }

            $exist_supplier_return_items_records = SupplierReturnItem::where('supplier_return_id', $id)->get();
            foreach ($exist_supplier_return_items_records as $singleItem) {
                $singleItem->delete();
            }
        } else {
            $data = new SupplierReturn();
            $message            = 'New Supplier Return created successfully';
        }

        $data->supplier_id = $supplierId;
        $data->purchase_batch_id = $batchId;
        $data->total_qty = $totalQuantity;
        $data->total_return_price = $calculatedPrice;
        $data->entry_date = now();
        $data->entry_by = auth()->user()->id;
        $data->save();

        // =========================================

        $supplier = Supplier::whereId($supplierId)->first();

        $purchases = Purchase::whereSupplierId($supplierId)->where('due_to_company', '>', 0)->get();
        $totalDues = Purchase::whereSupplierId($supplierId)->sum('due_to_company');

        $inputtedAmount = $request->calculatedPrice;
        $bulkAmount = 0;

        foreach ($purchases as $item) {
            // dd($inputtedAmount, $item->due_to_company);

            if ($inputtedAmount < $item->due_to_company) {

                $item->payment_received += $inputtedAmount;
                $item->due_to_company   -= $inputtedAmount;
                $item->save();
           
                break;
            } else {

                $inputtedAmount -= $item->due_to_company;
                $bulkAmount += $item->due_to_company;

                $item->payment_received = $item->total_price;
                $item->due_to_company = 0;
                $item->save();

                if ($inputtedAmount == 0) {
                    break;
                }
            }
        }


        // if ($request->calculatedPrice > $totalDues) {

        //     $extra = $request->calculatedPrice - $totalDues;

        //     $supplier->advance += $extra;
        //     $supplier->save();

        //     // == ===== == Receivable Account Start ==>
        //     $receivableData = Receivable::where('supplier_id', $supplier->id)->where('receivable_head_id', 3)->first();
        //     if ($receivableData) {
        //         $receivableData->receivable_amount += $extra;
        //         $receivableData->save();
        //     }
        //     // == ===== == Receivable Account End ==>

        // }
        // =========================================

        $supplierReturnId = $data->id;


        $accArr = [
            'purchase_return_id'   => $supplierReturnId,
            'type'                 => 11,
            'credit'                => $request->amount,
            'description'          => "Purchase return (" . $data->supplier->name . ") payment of " . $request->amount . " Tk has been successfully paid.",
            'supplier_id'          => $data->supplier_id,
            'payment_method'       => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'purchase_return_id', $id, 11);


        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'        => $account->id,
                'depositor_name'    => $request->depositor_name,
                'credit'            => $request->amount,
                'description'       => 'Company return product to Supplier' . $data->supplier->name . ' Total amount ' . $data->total_return_price,
                'bank_id'           => $request->bank_id,
                'check_no'          => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        } else {
            $transactionExist = BankTransaction::whereAccountId($account->id)->first();
            if (isset($transactionExist)) {
                $bank = Bank::whereId($transactionExist->bank_id)->first();
                $bank->balance -= $transactionExist->credit;
                $bank->save();

                $transactionExist->delete();
            }
        }

        foreach ($products as $index => $productId) {
            $productReturnItem = new SupplierReturnItem();
            $productReturnItem->supplier_return_id = $supplierReturnId;
            $productReturnItem->purchase_id = $purchaseIds[$index];
            $productReturnItem->product_id = $productId;
            $productReturnItem->return_qty = $quantities[$index];
            $productReturnItem->avg_purchase_price = $prices[$index];
            $productReturnItem->retun_product_price = $quantities[$index] * $prices[$index];
            $productReturnItem->save();

            $stock = Stock::where('purchase_batch_id', $batchId)
                ->where('product_id', $productId)
                ->first();

            $stock->stock -= $quantities[$index];
            $stock->save();


            $stockItems = StockItem::where('product_id', $productId)
                ->first();

            $stockItems->stock -= $quantities[$index];
            $stockItems->purchase_return_qty += $quantities[$index];
            $stockItems->save();
            
        }



        $message = 'New Supplier return created successfully';
        $notify[] = ['success', $message];
        return to_route('supplier-return.index')->withNotify($notify);
    }


    public function delete($id)
    {
        $Supplier_return = SupplierReturn::find($id);
        $Supplier_return->is_deleted = 1;
        $Supplier_return->save();

        $notify[] = ['success', 'Supplier return has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
