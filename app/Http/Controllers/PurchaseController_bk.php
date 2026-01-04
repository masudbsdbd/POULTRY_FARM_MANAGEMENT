<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\PurchaseBatch;
use App\Models\Stock;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class PurchaseController extends Controller
{
    public function index()
    {
        $pageTitle = 'Purchase List';
        $purchases = Purchase::with('items', 'items.product', 'items.product.unit')->notDeleted()->latest()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('purchase.index', compact('pageTitle', 'purchases', 'banks'));
    }

    public function create()
    {
        $pageTitle = 'Create New Purchase';
        $suppliers = Supplier::whereStatus(1)->latest()->get();
        $products = Product::whereStatus(1)->latest()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('purchase.create', compact('pageTitle', 'suppliers', 'products', 'banks'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Purchase';
        $suppliers = Supplier::whereStatus(1)->latest()->get();
        $products = Product::whereStatus(1)->latest()->get();
        $purchase = Purchase::findOrFail($id);
        $purchase_items = $purchase->items;
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();

        $advanceAccountRow = Account::wherePurchaseId($purchase->id)->whereIsAdvance(1)->first();
        // return $advanceAccountRow;
        return view('purchase.create', compact('pageTitle', 'suppliers', 'products', 'purchase', 'purchase_items', 'banks', 'advanceAccountRow'));
    }

    public function payDue(Request $request, $id)
    {
        $purchase = Purchase::whereId($id)->first();

        if ($request->balance > $purchase->due_to_company) {
            $notify[] = ['error', 'The due amount entered is incorrect. Please verify and try again.'];
            return back()->withNotify($notify);
        }

        $bank = Bank::whereId($request->bank_id)->first();
        if (isset($bank) && $request->balance > $bank->balance) {
            $notify[] = ['error', 'Insufficient balance in ' . $bank->bank_name];
            return back()->withNotify($notify);
        }

        $purchase->due_to_company -= $request->balance;
        $purchase->payment_received += $request->balance;
        $purchase->save();

        $accArr = [
            'purchase_id'       => $purchase->id,
            'type'              => 8,
            'debit'             => $request->balance,
            'description'       => $request->comment ? $request->comment : "Paid the due amount of " . $request->balance . " Tk to the supplier " . $purchase->supplier->name . " for previous Purchase (" . $purchase->batch->batch_code .  ")",
            'supplier_id'       => $purchase->supplier_id,
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'purchase_id');

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'  => $request->withdrawer_name,
                'debit'            => $request->balance,
                'description'      => 'Company give due to Supplier ' . $purchase->supplier->name . ' And Purchase Batch is ' . $purchase->batch->batch_code,
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }

        $notify[] = ['success', 'Purchase Due has been paid successfully.'];
        return back()->withNotify($notify);
    }

    public function generatePdf($id)
    {
        $fontPath = public_path('fonts/kalpurush.ttf');
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $path = public_path('fonts');

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'fontDir' => array_merge($fontDirs, [$path]),
            'fontdata' => $fontData + [
                'kalpurush' => [
                    'R' => 'kalpurush.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'kalpurush',
        ]);

        $purchase = Purchase::whereId($id)->first();

        $html = view('purchase.purchase-invoice', compact('purchase'))->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('purchase-invoice-' . $purchase->id . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }

    public function store(Request $request, $id = 0)
    {
        // return $request;
        $request->validate([
            'supplier_id'       => 'required',
            'purchase_date'     => 'required',
            'products'          => 'required',
            'qty'               => 'required',
            'unitPrice'         => 'required',
            'priceTotal'        => 'required',
            'totalQuantity'     => 'required',
            'calculatedPrice'   => 'required',
            'balance'           => 'required',
            'payment_method'    => 'required',
        ]);

        $bank = Bank::whereId($request->bank_id)->first();
        if (isset($bank) && $request->balance > $bank->balance && $id == 0) {
            $notify[] = ['error', 'Insufficient balance in ' . $bank->bank_name];
            return back()->withNotify($notify);
        }

        $productIdArr = $request->products;
        $qtyArr = $request->qty;
        $prductUnitPrice = $request->unitPrice;
        $priceArr = $request->priceTotal;

        $inputtedAmount = $request->balance;
        $isAdvance = false;

        // Calculate the original amount. Eliminate discount and commission
        if (isset($request->commission) && isset($request->discount)) {
            $amountBeforeDiscount = $request->calculatedPrice + $request->discount;
            $originalAmount = $amountBeforeDiscount / (1 - $request->commission / 100);
        } elseif (isset($request->commission)) {
            $originalAmount = $request->calculatedPrice / (1 - $request->commission / 100);
        } elseif (isset($request->discount)) {
            $originalAmount = $request->calculatedPrice + $request->discount;
        } else {
            $originalAmount = $request->calculatedPrice;
        }

        if ($id > 0) {
            $purchase = Purchase::whereId($id)->first();
            $message = 'Purchase updated successfully';
            $purchase->update_by         = auth()->user()->id;
            $prviousParchasePaymentReceived = $purchase->payment_received;

            $purchaseItems = PurchaseItem::where('purchase_id', $id)->get();

            foreach ($purchaseItems as $single_item) {
                $stockItem = StockItem::where('product_id', $single_item->product_id)->first();
                $stockItem->total_purchase_qty -= $single_item->qty;
                $stockItem->stock -= $single_item->qty;
                $stockItem->save();
            }
        } else {
            $purchase           = new Purchase();
            $message            = 'New Purchase created successfully';
        }

        $purchase->supplier_id       = $request->supplier_id;
        $purchase->purchase_date     = $request->purchase_date;
        $purchase->total_qty         = $request->totalQuantity;
        $purchase->main_price        = $originalAmount;
        $purchase->total_price       = $request->calculatedPrice;
        $purchase->commission        = $request->commission;
        $purchase->discount          = $request->discount;
        $purchase->payment_received  = $request->balance;
        $purchase->entry_by          = auth()->user()->id;
        $purchase->entry_date        = now();
        $purchase->save();

        $purchase->invoice_no        = "purchase-invoice-" . $purchase->id;
        $purchase->save();

        if ($id == 0) {
            $batch = new PurchaseBatch();
            $batch->supplier_id = $request->supplier_id;
            $batch->purchase_id = $purchase->id;
            $batch->save();

            $batch->batch_code  = 'SQ' . $batch->id;
            $batch->save();
        }

        $findSupplier = Supplier::whereid($request->supplier_id)->first();

        if ($id > 0) {
            $advanceAccountRow = Account::wherePurchaseId($id)->whereIsAdvance(1)->first();

            if (isset($advanceAccountRow)) {
                if ($prviousParchasePaymentReceived > $request->calculatedPrice) {

                    if ($findSupplier->id == $advanceAccountRow->supplier_id) {
                        $findSupplier->advance -= $advanceAccountRow->debit;
                        $findSupplier->save();
                    } else {
                        $previousSupplier = Supplier::whereid($advanceAccountRow->supplier_id)->first();
                        $previousSupplier->advance -= $advanceAccountRow->debit;
                        $previousSupplier->save();
                    }
                } else {
                    if ($findSupplier->id == $advanceAccountRow->supplier_id) {
                        $findSupplier->advance += $advanceAccountRow->debit;
                        $findSupplier->save();
                    } else {
                        $previousSupplier = Supplier::whereid($advanceAccountRow->supplier_id)->first();
                        $previousSupplier->advance += $advanceAccountRow->debit;
                        $previousSupplier->save();
                    }
                }
            }
        }

        $getAdvance = $findSupplier->advance;

        if ($request->balance > $request->calculatedPrice) {
            $due = 0;
            $purchase->due_to_company = $due;
            $purchase->save();

            $remainingBalance = $getAdvance + $request->balance - $request->calculatedPrice;
            $findSupplier->advance = $remainingBalance;
            $findSupplier->save();

            // $accArrF = [
            //     'purchase_id'       => $purchase->id,
            //     'type'              => 1,
            //     'debit'             => $request->balance - $request->calculatedPrice,
            //     'description'       => "Purchase(" . $purchase->batch->batch_code  . ") payment of " . $request->calculatedPrice . " Tk has been successfully paid. New added advance amount of " . $request->balance - $request->calculatedPrice . " Tk to the supplier" . " (" . $findSupplier->name . "). Previous Balance: " . showAmount($getAdvance, 2, false) . " Tk, Current Balance: " . $findSupplier->advance . " Tk",
            //     'supplier_id'       => $purchase->supplier_id,
            //     'payment_method'    => $request->payment_method,
            //     'is_advance'        => 1,
            // ];

            // updateAcc($accArrF, 'purchase_id', $id, true);

            $calculatedDebit = $request->balance - $request->calculatedPrice;
            $inputtedAmount -= $calculatedDebit;
            // $accArr['is_advance'] = true;

            $isAdvance = true;

            // $accDesc = 

            $this->supplierAccountUpdate($request, $findSupplier, $calculatedDebit);
        } else {

            $due = $request->calculatedPrice == $request->balance ? 0 : ($request->calculatedPrice - $request->balance);
            $purchase->due_to_company = $due;
            $purchase->save();

            if ($getAdvance > 0) {

                $remainingAdvance = $getAdvance - $request->balance;

                if ($remainingAdvance > 0) {

                    $findSupplier->advance = $remainingAdvance;
                    $findSupplier->save();

                    // $accArrF = [
                    //     'purchase_id'       => $purchase->id,
                    //     'type'              => 1,
                    //     'debit'             => $request->balance,
                    //     'description'       => "Purchase(" . $purchase->batch->batch_code  . ") payment of " . $request->balance . " Tk has been successfully paid using the advance amount from supplier" . " (" . $findSupplier->name . "). Previous Balance: " . showAmount($getAdvance, 2, false) . " Tk, Current Balance: " . $remainingAdvance . " Tk",
                    //     'supplier_id'       => $purchase->supplier_id,
                    //     'payment_method'    => $request->payment_method,
                    //     'is_advance'        => 1,
                    // ];

                    // updateAcc($accArrF, 'purchase_id', $id, true);

                    // $inputtedAmount = $request->balance;
                    $isAdvance = true;
                    $accDesc = " The payment was made using the advance amount from supplier." . " Previous Balance: " . showAmount($getAdvance, 2, false) . " Tk, Current Balance: " . showAmount($remainingAdvance, 2, false) . " Tk";
                    
                } else {
                    
                    $findSupplier->advance = 0;
                    $findSupplier->save();

                    // $accArrS = [
                    //     'purchase_id'       => $purchase->id,
                    //     'type'              => 1,
                    //     'debit'             => $getAdvance,
                    //     'description'       => "Purchase(" . $purchase->batch->batch_code  . ") payment of " . $getAdvance . " Tk has been successfully paid using the advance amount from supplier" . " (" . $findSupplier->name . "). Previous Balance: " . showAmount($getAdvance, 2, false) . " Tk, Current Balance: " . 0 . " Tk",
                    //     'supplier_id'       => $purchase->supplier_id,
                    //     'payment_method'    => $request->payment_method,
                    //     'is_advance'        => 1,
                    // ];

                    // updateAcc($accArrS, 'purchase_id', $id, true);

                    // $inputtedAmount = $request->balance;
                    $isAdvance = true;
                    $accDesc = " The payment was made using the advance amount of " . $getAdvance . " from supplier." . " Previous Balance: " . showAmount($getAdvance, 2, false) . " Tk, Current Balance: " . 0 . " Tk";
                }
            }
        }

        if (!empty($productIdArr)) {
            if ($id > 0) {
                $exist_purchase_item = PurchaseItem::wherePurchaseId($id)->get();
                foreach ($exist_purchase_item as $singleItem) {
                    $singleItem->delete();
                }
            }

            if ($id > 0) {
                $existing_batch_id = PurchaseBatch::wherePurchaseId($id)->first();
                $existing_stocks = Stock::where('purchase_batch_id', $existing_batch_id->id)->get();
                foreach ($existing_stocks as $singleStock) {
                    $singleStock->delete();
                }
            }

            foreach ($productIdArr as $key => $product_id) {
                $product = Product::find($product_id);
                $purchase_item = new PurchaseItem();
                $purchase_item->purchase_id  = $purchase->id;
                $purchase_item->product_id = $product->id;
                $purchase_item->price = $prductUnitPrice[$key];
                $purchase_item->qty = $qtyArr[$key];
                $purchase_item->supplier_name = $purchase->supplier->name;
                $purchase_item->total_amount = $priceArr[$key];
                $purchase_item->update_by = auth()->user()->id;
                $purchase_item->save();

                if ($id > 0) {
                    $getBatch = PurchaseBatch::wherePurchaseId($id)->first();
                }

                $singleProduct = [
                    'originalPrice' => $purchase_item->price * $purchase_item->qty,
                    'quantity' => $purchase_item->qty
                ];

                $averagePrice = $this->calculateAveragePrice($request->calculatedPrice, $singleProduct, $originalAmount);

                $purchase_item->avg_purchase_price = $averagePrice;
                $purchase_item->save();

                $newStock = new Stock();
                $newStock->purchase_id = $purchase->id;
                $newStock->purchase_batch_id = isset($getBatch) ? $getBatch->id : $batch->id;
                $newStock->product_id = $product->id;
                $newStock->product_name = $product->name;
                $newStock->avg_purchase_price = $averagePrice;
                $newStock->purchase_qty = $purchase_item->qty;
                $newStock->total_purchase_price = $newStock->avg_purchase_price * $newStock->purchase_qty;
                $newStock->stock += $purchase_item->qty;
                $newStock->save();

                $existingItemStock = StockItem::whereProductId($product->id)->first();
                if (isset($existingItemStock)) {
                    $itemStock = StockItem::whereProductId($product->id)->first();
                    $itemStock->total_purchase_qty += $newStock->purchase_qty;
                    $itemStock->stock += $purchase_item->qty;
                } else {
                    $itemStock = new StockItem();
                    $itemStock->total_purchase_qty = $purchase_item->qty;
                    $itemStock->stock = $purchase_item->qty;
                }

                $itemStock->product_id = $product->id;
                $itemStock->product_name = $product->name;
                $itemStock->save();
            }
        }

        $accArr = [
            'purchase_id'       => $purchase->id,
            'type'              => 1,
            'debit'             => $inputtedAmount,
            'description'       => "Purchase(" . $purchase->batch->batch_code  . ") payment of " . $inputtedAmount . " Tk has been successfully paid to Supplier " . $purchase->supplier->name . "." . ($due ? ' There is a due of ' . $due . ' Tk.' : '') . (isset($accDesc) ? $accDesc : ''),
            'supplier_id'       => $purchase->supplier_id,
            'payment_method'    => $request->payment_method,
        ];

        if ($isAdvance == true) { 
            $accArr['is_advance'] = 1;
            $account = updateAcc($accArr, 'purchase_id', $id, true);
        }
        else{
            $account = updateAcc($accArr, 'purchase_id', $id);
        }


        // dd($accArr);

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'  => $request->withdrawer_name,
                'debit'            => $inputtedAmount,
                'description'      => 'Company give payment to Supplier ' . $purchase->supplier->name . ' And Purchase Batch is ' . $purchase->batch->batch_code,
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

        $notify[] = ['success', $message];
        return to_route('purchase.index')->withNotify($notify);
    }

    function calculateAveragePrice($totalAmount, $product, $totalOriginalPrice)
    {
        $productProportion = $product['originalPrice'] / $totalOriginalPrice;
        $adjustedPrice = $totalAmount * $productProportion;
        $averagePrice = $adjustedPrice / $product['quantity'];
        return $averagePrice;
    }

    public function supplierAccountUpdate($request, $supplier, $amount)
    {
        $accArr = [
            'supplier_id'       => $supplier->id,
            'type'              => 6,
            'debit'             => $amount,
            'description'       => "Paid advance payment of " . $amount . " Tk to the supplier " . $supplier->name . ".",
            'payment_method'    => $request->payment_method,
            'is_advance'        => 1
        ];

        $account = updateAcc($accArr, 'NTR');

        if ($request->payment_method == 2) {
            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'  => $request->withdrawer_name,
                'debit'            => $amount,
                'description'      => 'Company paid advance to the supplier ' . $supplier->name,
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }
    }

    public function delete($id)
    {
        $purchase = Purchase::find($id);
        $purchase->is_deleted = 1;
        $purchase->save();

        $notify[] = ['success', 'Purchase has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
