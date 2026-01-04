<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerReturn;
use App\Models\CustomerReturnItems;
use App\Models\Customer;
use App\Models\Sell;
use App\Models\SellRecord;
use App\Models\PurchaseBatch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Bank;
use App\Models\StockItem;
use App\Models\BankTransaction;
use App\Models\BsType;
use App\Models\BsAccount;
use App\Models\JournalEntry;
use App\Models\Payable;

class CustomerReturnController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:sell-return-list', ['only' => ['index']]);
        $this->middleware('permission:sell-return-create|sell-return-edit', ['only' => ['store']]);
        $this->middleware('permission:sell-return-create', ['only' => ['create']]);
        $this->middleware('permission:sell-return-edit', ['only' => ['edit']]);
        $this->middleware('permission:sell-return-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'All Return from Customer';
        $customerReturns = CustomerReturn::latest()
            ->with(['customer'])
            ->notDeleted()->paginate(gs()->pagination);
        return view('customer-return.index', compact('pageTitle', 'customerReturns'));
    }
    public function create()
    {
        $pageTitle = 'Create Return from Customer';
        $customers = Customer::latest()->notDeleted()->paginate(20);
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('customer-return.create', compact('pageTitle', 'customers', 'banks'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Customer Return';
        $customers = Customer::latest()->notDeleted()->paginate(20);
        $customerRetun = CustomerReturn::find($id);
        $customerReturnItems = CustomerReturnItems::where('customer_return_id', $id)->get();

        // dd($CustomerReturnItems);
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();

        $sells = Sell::where('customer_id', $customerRetun->customer_id)->get();

        $sellProductIds = SellRecord::whereIn('sell_id', $sells->pluck('id'))
            ->distinct()
            ->pluck('product_id');

        $productsFromDB = Product::whereIn('id', $sellProductIds)->get();

        $products = [];
        foreach ($productsFromDB as $item) {
            $batches = SellRecord::where('product_id', $item->id)
                ->whereIn('sell_id', $sells->pluck('id'))
                ->get();

            $batchDetails = [];
            foreach ($batches as $batch) {
                $purchaseBatch = PurchaseBatch::find($batch->purchase_batch_id);
                if ($purchaseBatch) {
                    $batchDetails[] = [
                        'batch_id' => $purchaseBatch->id,
                        'supplier_id' => $purchaseBatch->supplier_id,
                        'purchase_id' => $purchaseBatch->purchase_id,
                        'batch_code' => $purchaseBatch->batch_code,
                        'created_at' => $batch->created_at->format('Y-m-d H:i:s') ?? null,
                        'sell_qty' => $batch->sell_qty,
                        'avg_sell_price' => $batch->avg_sell_price,
                        'sell_id' => $batch->sell_id,
                    ];
                }
            }

            $products[] = [
                'product_id' => $item->id ?? null,
                'product_name' => $item->name ?? null,
                'batch_details' => $batchDetails ?? [],
            ];
        }
        // dd($products);

        $baseProductBatches = SellRecord::whereIn('sell_id', $sells->pluck('id'))
            ->where('product_id', $customerRetun->base_product_id)
            ->get();

        $batchData = [];
        foreach ($baseProductBatches as $batchItem) {
            $purchaseBatch = PurchaseBatch::find($batchItem->purchase_batch_id);

            if ($purchaseBatch) {
                $batchDetails = [];
                $batches = SellRecord::where('product_id', $customerRetun->base_product_id)
                    ->where('purchase_batch_id', $purchaseBatch->id)
                    ->whereIn('sell_id', $sells->pluck('id'))
                    ->get();

                foreach ($batches as $batch) {
                    $batchDetails[] = [
                        'batch_id' => $purchaseBatch->id,
                        'supplier_id' => $purchaseBatch->supplier_id,
                        'purchase_id' => $purchaseBatch->purchase_id,
                        'batch_code' => $purchaseBatch->batch_code,
                        'created_at' => $batch->created_at->format('Y-m-d H:i:s') ?? null,
                        'sell_qty' => $batch->sell_qty,
                        'avg_sell_price' => $batch->avg_sell_price,
                        'sell_id' => $batch->sell_id,
                    ];
                }

                $batchData[] = [
                    'batch_id' => $purchaseBatch->id ?? null,
                    'batch_name' => $purchaseBatch->batch_code ?? null,
                    'created_at' => $batchItem->created_at->format('Y-m-d H:i:s') ?? null,
                    'sell_qty' => $batchItem->sell_qty ?? null,
                    'batchDetails' => $batchDetails ?? [],
                ];
            }
        }
        // dd($batchData->toArray());
        $batches = CustomerReturnItems::where('customer_return_id', $id)->pluck('purchase_batch_id')->toArray();
        $newArray = array_values($batches);

        // dd($newArray);
        return view('customer-return.create', compact('pageTitle', 'customers', 'customerRetun', 'customerReturnItems', 'products', 'batchData', 'batches', 'banks'));
    }


    public function returnAjax($id)
    {
        $sells = Sell::where('customer_id', $id)->get();

        $sellProductIds = SellRecord::whereIn('sell_id', $sells->pluck('id'))
            ->distinct()
            ->pluck('product_id');

        $products = Product::whereIn('id', $sellProductIds)->get();

        $result = [];
        foreach ($products as $item) {
            $batches = SellRecord::where('product_id', $item->id)
                ->whereIn('sell_id', $sells->pluck('id'))
                ->get();

            $batchDetails = [];
            foreach ($batches as $batch) {
                $purchaseBatch = PurchaseBatch::find($batch->purchase_batch_id);
                if ($purchaseBatch) {
                    $batchDetails[] = [
                        'batch_id' => $purchaseBatch->id,
                        'supplier_id' => $purchaseBatch->supplier_id,
                        'purchase_id' => $purchaseBatch->purchase_id,
                        'batch_code' => $purchaseBatch->batch_code,
                        'created_at' => $batch->created_at->format('Y-m-d H:i:s') ?? null,
                        'sell_qty' => $batch->sell_qty,
                        'avg_purchase_price' => $batch->avg_purchase_price,
                        'avg_sell_price' => $batch->avg_sell_price,
                        'sell_id' => $batch->sell_id,
                    ];
                }
            }

            $result[] = [
                'product_id' => $item->id ?? null,
                'product_name' => $item->name ?? null,
                'batch_details' => $batchDetails ?? [],
            ];
        }

        // dd($result);

        return $result;
    }

    public function store(Request $request, $id = 0)
    {
        // $input = $request->all();
        // dd($input);
        $input = $request->all();

        if ($input['avg_sell_prices'] < $input['return_prices']) {
            $notify[] = ['error', 'The return price cannot be less than the average sell price.'];
            return back()->withNotify($notify);
        }


        $request->validate([
            'batch_id' => 'required',
            'payment_method' => 'required',

        ]);

        $bank = Bank::whereId($request->bank_id)->first();
        if (isset($bank) && $request->balance > $bank->balance && $id == 0) {
            $notify[] = ['error', 'Insufficient balance in ' . $bank->bank_name];
            return back()->withNotify($notify);
        }


        $batchesDecode = json_decode($input['batches_id'][0], true);

        $customerId = $input['customer_id'];
        $totalQuantity = $input['totalQuantity'];
        $calculatedPrice = $input['calculatedPrice'];
        $baseProductId = $input['base_product_id'];
        $productId = $input['product_id'];
        $batchId = $batchesDecode;
        $purchaseId = $input['purchase_id'];
        $quantities = $input['quantities'];
        $returnPrices = $input['return_prices'];
        $avgSellPrices = $input['avg_sell_prices'];
        $sellId = $input['sell_id'];
        // dd($productId);

        if ($id > 0) {

            $data = CustomerReturn::whereId($id)->first();
            $message = 'Customer Return updated successfully';
            $data->last_update       = now();
            $data->update_by         = auth()->user()->id;

            // ===== correction for sell items =====
            $customerReturnItems = CustomerReturnItems::where('customer_return_id', $id)->get();

            foreach ($customerReturnItems as $key => $cusRetItems) {

                $sells = Sell::where('id', $cusRetItems->sell_id)->first();
                $sells->total_qty += $cusRetItems->return_qty;
                $sells->save();


                $sellRecords = SellRecord::where('sell_id', $sells->id)->first();
                $sellRecords->sell_qty += $cusRetItems->return_qty;
                $sellRecords->save();


                $stockItems = StockItem::where('product_id', $cusRetItems->product_id)->first();
                $stockItems->stock -= $cusRetItems->return_qty;
                $stockItems->sell_return_qty -= $itemsQty;
                $stockItems->save();

                $stocks = Stock::where('product_id', $cusRetItems->product_id)
                    ->where('purchase_id', $cusRetItems->purchase_id)
                    ->where('purchase_batch_id', $cusRetItems->purchase_batch_id)
                    ->first();
                $stocks->stock -= $cusRetItems->return_qty;
                $stocks->save();

                // $data[] = $cusRetItems->toArray();
            }
            // dd($data);

            foreach ($customerReturnItems as $singleItem) {
                $singleItem->delete();
            }
            // ===== correction for sell itemss =====

        } else {
            $data = new CustomerReturn();
            $message = 'New Customer Return created successfully';
        }
        // dd($stocksData->toArray());

        $data->customer_id = $customerId;
        $data->base_product_id = $baseProductId;
        $data->total_qty = $totalQuantity;
        $data->total_return_price = $calculatedPrice;
        $data->entry_date = now();
        $data->entry_by = auth()->user()->id;
        $data->save();

        // =========================================
        $customer = Customer::whereId($customerId)->first();

        $sells = Sell::whereCustomerId($customerId)->where('due_to_company', '>', 0)->get();
        $totalDues = Sell::whereCustomerId($customerId)->sum('due_to_company');

        $inputtedAmount = $request->calculatedPrice;

        $bulkAmount = 0;

        foreach ($sells as $item) {
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
        if ($request->calculatedPrice > $totalDues) {

            $extra = $request->calculatedPrice - $totalDues;
            $customer->advance += $extra;
            $customer->save();

            // == ===== == Payable Account Start ==>
            $payableData = Payable::where('customer_id', $customer->id)->where('payables_head_id', 2)->first();
            if ($payableData) {
                $payableData->payable_amount += $extra;
                $payableData->save();
            }
            // == ===== == Payable Account End ==>

        }
        // =========================================

        $customerReturnId = $data->id;

        $accArr = [
            'sell_return_id'   => $customerReturnId,
            'type'             => 10,
            'debit'            => $request->amount,
            'description'      => "Sell return (" . $data->customer->name . ") payment of " . $request->amount . " Tk has been successfully paid.",
            'customer_id'      => $data->customer_id,
            'payment_method'   => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'sell_return_id', $id, 10);


        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'  => $request->withdrawer_name,
                'debit'            => $request->amount,
                'description'      => 'Company return product to customer' . $data->customer->name . ' Total amount ' . $data->total_return_price,
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        } else {
            $transactionExist = BankTransaction::whereAccountId($account->id)->first();
            if (isset($transactionExist)) {
                $bank = Bank::whereId($transactionExist->bank_id)->first();
                $bank->balance += $transactionExist->debit;
                $bank->save();

                $transactionExist->delete();
            }
        }

        foreach ($productId as $index => $product) {
            $CustomerReturnItem = new CustomerReturnItems();
            $CustomerReturnItem->customer_return_id = $customerReturnId;
            $CustomerReturnItem->purchase_batch_id = $batchId[$index];
            $CustomerReturnItem->purchase_id = $purchaseId[$index];
            $CustomerReturnItem->sell_id = $sellId[$index];
            $CustomerReturnItem->product_id = $product;
            $CustomerReturnItem->return_qty = $quantities[$index];
            $CustomerReturnItem->avg_sell_price = $avgSellPrices[$index];
            $CustomerReturnItem->retun_sell_price = $returnPrices[$index];
            $CustomerReturnItem->retun_total_sell_price = $quantities[$index] * $returnPrices[$index];
            $CustomerReturnItem->save();

            $stocks = Stock::where('purchase_batch_id', $batchId[$index])
                ->where('product_id', $product)
                ->first();

            if ($stocks) {
                $stocks->stock += $quantities[$index];
                $stocks->save();
            }


            $stockItems = StockItem::where('product_id', $product)->first();
            if ($stockItems) {
                $stockItems->stock += $quantities[$index];
                $stockItems->sell_return_qty += $quantities[$index];
                $stockItems->save();
            }
        }

        $sellRecord = SellRecord::whereIn('sell_id', $sellId)
            ->whereIn('purchase_batch_id', $batchId)
            ->whereIn('product_id', $productId)
            ->get();

        // $data = [];
        foreach ($sellRecord as $key => $slRcd) {
            $slRcd->sell_qty -= $quantities[$key];
            $slRcd->save();

            $sllId = $slRcd->sell_id;
            $sell = Sell::find($sllId);
            if ($sell) {
                $sell->total_qty -= $quantities[$key];
                $sell->save();
            }

            // ====================== profit correction start ======================
            $sellReturnQty = $quantities[$key];
            $exceptProfitPrice = $sellReturnQty * $slRcd->avg_purchase_price;
            $allReturnPrice = $quantities[$key] * $returnPrices[$key];

            $profitOrLoss = $allReturnPrice - $exceptProfitPrice;
            // $data[] = $profitOrLoss;

            if($profitOrLoss > 0){

                $slRcd->profit -= $profitOrLoss;
                $slRcd->save();
            }elseif($profitOrLoss < 0){
                $slRcd->profit += $profitOrLoss;
                $slRcd->save();
            }
            // ====================== profit correction end ======================
        }
        // dd($data);


        $message = 'New Customer return created successfully';
        $notify[] = ['success', $message];
        return to_route('customer-return.index')->withNotify($notify);
    }
    public function delete($id)
    {
        // dd($id);
        $customer_return = CustomerReturn::find($id);
        $customer_return->is_deleted = 1;
        $customer_return->save();

        $notify[] = ['success', 'Customer Return has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
