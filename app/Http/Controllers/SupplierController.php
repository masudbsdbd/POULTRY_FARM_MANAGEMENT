<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Receivable;
use App\Models\Payable;
use App\Models\Income;

class SupplierController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:supplier-list', ['only' => ['index']]);
        $this->middleware('permission:supplier-create|supplier-edit', ['only' => ['store']]);
        $this->middleware('permission:supplier-create', ['only' => ['create']]);
        $this->middleware('permission:supplier-edit', ['only' => ['edit']]);
        $this->middleware('permission:supplier-delete', ['only' => ['delete']]);
        $this->middleware('permission:supplier-payment', ['only' => ['payment']]);
    }
    public function index()
    {
        $pageTitle = 'All Suppliers';
        $suppliers = Supplier::latest()->notDeleted()->paginate(gs()->pagination);
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('supplier.index', compact('pageTitle', 'suppliers', 'banks'));
    }

    public function advanceIndex()
    {
        $pageTitle = 'All Suppliers with Advance';
        $customers = Supplier::where('advance', '>', 0)->latest()->notDeleted()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('customer.index', compact('pageTitle', 'customers', 'banks'));
    }

    public function create()
    {
        $pageTitle = 'Create Supplier';
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('supplier.store', compact('pageTitle','banks'));
    }

    public function edit($id)
    {
        $pageTitle = 'Create Supplier';
        $supplier = Supplier::find($id);
        return view('supplier.store', compact('pageTitle', 'supplier'));
    }

    // public function payment(Request $request, $id)
    // {
    //     // return $request;
    //     $bank = Bank::whereId($request->bank_id)->first();
    //     // if (isset($bank) && $request->balance > $bank->balance && $request->payment_method == 2) {
    //     //     $notify[] = ['error', 'Insufficient balance in ' . $bank->bank_name];
    //     //     return back()->withNotify($notify);
    //     // }

    //     $supplier = Supplier::whereId($id)->first();

    //     if ($request->payment_mode == 1) {
    //         $purchases = Purchase::whereSupplierId($id)->where('due_to_company', '>', 0)->get();
    //         $totalDues = Purchase::whereSupplierId($id)->sum('due_to_company');

    //         $inputtedAmount = $request->balance;
    //         $targetedId = [];
    //         $newArr = [];
    //         $bulkAmount = 0;
    //         $count = 0;

    //         foreach ($purchases as $item) {
    //             if ($inputtedAmount < $item->due_to_company) {
    //                 $item->payment_received += $inputtedAmount;
    //                 $item->due_to_company   -= $inputtedAmount;
    //                 $item->save();

    //                 $targetedId = $item->id;

    //                 $this->purchaseAccountUpdate($request, $targetedId, $inputtedAmount);

    //                 break;
    //             } else {

    //                 $inputtedAmount -= $item->due_to_company;
    //                 $bulkAmount += $item->due_to_company;

    //                 $item->payment_received = $item->total_price;
    //                 $item->due_to_company = 0;
    //                 $item->save();

    //                 array_push($targetedId, $item->id);
    //                 $newArr = $targetedId;

    //                 $count++;

    //                 if ($inputtedAmount == 0) {
    //                     break;
    //                 }
    //             }
    //         }

    //         if (is_array($newArr) && !empty($newArr)) {
    //             $this->purchaseAccountUpdate($request, $newArr, $bulkAmount);
    //         }

    //         if ($request->balance > $totalDues) {

    //             $extra = $request->balance - $totalDues;

    //             $supplier->advance += $extra;
    //             $supplier->save();
    //             $this->supplierAccountUpdate($request, $supplier, $extra, 1);


    //             // == ===== == Receivable Account Start ==>
    //             $receivableData = Receivable::where('supplier_id', $id)->where('receivable_head_id', 3)->first();
    //             if ($receivableData) {
    //                 $receivableData->receivable_amount += $extra;
    //                 $receivableData->save();
    //             }
    //             // == ===== == Receivable Account End ==>


    //         }
    //     } else {
    //         if ($request->balance > $supplier->advance) {
    //             $notify[] = ['error', 'Insufficient advance available with the supplier.'];
    //             return back()->withNotify($notify);
    //         }

    //         $supplier->advance -= $request->balance;
    //         $supplier->save();

    //         $this->supplierAccountUpdate($request, $supplier, $request->balance, 2);
            
    //         // == ===== == Receivable Account Start ==>
    //         $receivableData = Receivable::where('supplier_id', $id)->where('receivable_head_id', 3)->first();
    //         if ($receivableData) {
    //             $receivableData->receivable_amount -= $request->balance;
    //             $receivableData->save();
    //         }
    //         // == ===== == Receivable Account End ==>

    //         // dd($request->balance);
    //         // dd($supplier->advance);
    //     }



    //     $notify[] = ['success', 'Payment successfully completed.'];
    //     return back()->withNotify($notify);
    // }

    public function payment(Request $request, $id)
    {
        // dd($request->all());
        $bank = Bank::whereId($request->bank_id)->first();
        
        $supplier = Supplier::whereId($id)->first();
        $inputtedAmount = $request->balance;



        if ($request->payment_mode == 1 && $inputtedAmount > 0) {

            if ($supplier->due > 0) {
                if ($inputtedAmount >= $supplier->due) {
                    $inputtedAmount -= $supplier->due;
                    $this->supplierAccountUpdate($request, $supplier, $supplier->due, 1, $type = 1); 
                    $supplier->due = 0;


                    $payableData = Payable::where('payables_head_id', 5)->where('supplier_id', $id)->first();
                    if ($payableData) {
                        $payableData->payable_amount = 0;
                        $payableData->save();
                    }
                } else {
                    $supplier->due -= $inputtedAmount;

                    $payableData = Payable::where('payables_head_id', 5)->where('supplier_id', $id)->first();
                    if ($payableData) {
                        $payableData->payable_amount -= $inputtedAmount;
                        $payableData->save();
                    }

                    $this->supplierAccountUpdate($request, $supplier, $inputtedAmount, 1, $type = 1);
                    $inputtedAmount = 0;

                }
                $supplier->save();
            }



            $purchases = Purchase::whereSupplierId($id)->where('due_to_company', '>', 0)->get();
            $totalDues = Purchase::whereSupplierId($id)->sum('due_to_company');

            $targetedId = [];
            $newArr = [];
            $bulkAmount = 0;

            foreach ($purchases as $item) {
                if ($inputtedAmount < $item->due_to_company) {
                    $payableData = Payable::where('purchase_id', $item->id)->where('supplier_id', $item->supplier_id)->first();
                    if ($payableData) {
                        $payableData->payable_amount -= $inputtedAmount;
                        $payableData->save();
                    }

                    $item->payment_received += $inputtedAmount;
                    $item->due_to_company   -= $inputtedAmount;
                    $item->save();

                    $targetedId = $item->id;
                    $this->purchaseAccountUpdate($request, $targetedId, $inputtedAmount);
                    $inputtedAmount = 0;
                    break;
                } else {
                    $payableData = Payable::where('purchase_id', $item->id)->where('supplier_id', $item->supplier_id)->first();
                    if ($payableData) {
                        $payableData->payable_amount = 0;
                        $payableData->save();
                    }

                    $inputtedAmount -= $item->due_to_company;
                    $bulkAmount += $item->due_to_company;

                    $item->payment_received = $item->total_price;
                    $item->due_to_company = 0;
                    $item->save();

                    array_push($targetedId, $item->id);
                    $newArr = $targetedId;

                    if ($inputtedAmount == 0) {
                        break;
                    }
                }
            }

            if (is_array($newArr) && !empty($newArr)) {
                $this->purchaseAccountUpdate($request, $newArr, $bulkAmount);
            }

            if ($inputtedAmount > 0) {
                $supplier->advance += $inputtedAmount;
                $supplier->save();
                $this->supplierAccountUpdate($request, $supplier, $inputtedAmount, 1, $type = 2);

                $receivableData = Receivable::where('supplier_id', $id)->where('receivable_head_id', 3)->first();
                if ($receivableData) {
                    $receivableData->receivable_amount += $inputtedAmount;
                    $receivableData->save();
                }
            }
        }

        elseif ($request->payment_mode != 1) {
            if ($request->balance > $supplier->advance) {
                $notify[] = ['error', 'Insufficient advance available with the supplier.'];
                return back()->withNotify($notify);
            }

            $supplier->advance -= $request->balance;
            $supplier->save();

            $this->supplierAccountUpdate($request, $supplier, $request->balance, 2, $type = 2);

            $receivableData = Receivable::where('supplier_id', $id)->where('receivable_head_id', 3)->first();
            if ($receivableData) {
                $receivableData->receivable_amount -= $request->balance;
                $receivableData->save();
            }
        }

        $notify[] = ['success', 'Payment successfully completed.'];
        return back()->withNotify($notify);
    }


    public function supplierAccountUpdate($request, $supplier, $extraAmount = 0, $paymentMode , $type)
    {
        if($type == 1){

            $bankTrRecDesc = "Company received due from supplier " . $supplier->name . ". Amount of .$request->balance";
    
            // $accArr = [
            //     'supplier_id'       => $supplier->id,
            //     'type'              => 8,
            //     $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 2 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
            //     'description' => "paid amount of {$request->balance} tk to supplier {$supplier->name}  for purchase due. ",
            //     'payment_method'    => $request->payment_method,
            // ];
            $accArr = [
                'supplier_id'       => $supplier->id,
                'type'              => 8,
                $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 1 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
                // 'description'       => $request->comment ? $request->comment : ($paymentMode == 1 ? $accPayDesc : $accRecDesc),
                'description' => "paid amount of {$request->balance} tk to supplier {$supplier->name}  for purchase due. ",
                'payment_method'    => $request->payment_method,
            ];
    
    
            $account = updateAcc($accArr, 'NTR', 0, 8);
    
            if ($request->payment_method == 2) {
                $bankTrArr = [
                    'account_id'       => $account->id,
                    // $paymentMode == 1 ? 'withdrawer_name' : 'depositor_name' => $request->depositor_name,
                      'withdrawer_name' => $request->withdrawer_name,
                    $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 2 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
                    // 'description'      => $paymentMode == 1 ? $bankTrPayDesc : $bankTrRecDesc,
                    'description' => "pay supplier {$supplier->name} due. amount of {$request->balance} for purchase due. ",
                    'bank_id'          => $request->bank_id,
                    'check_no'         => $request->check_no,
                ];
    
                bankTr($account, $bankTrArr);
            }
    
        }else{
        $accPayDesc = "Paid advance payment of " . ($extraAmount > 0 ? $extraAmount : $request->balance) . " Tk to the supplier " . $supplier->name;
        $bankTrPayDesc = 'Company paid advance to the supplier ' . $supplier->name;

        $accRecDesc = "Receive advance amount of " . $request->balance . " Tk from the supplier " . $supplier->name;
        $bankTrRecDesc = 'Company receive advance from the supplier ' . $supplier->name;

        $accArr = [
            'supplier_id'       => $supplier->id,
            'type'              => 6,
            $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 1 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
            'description'       => $request->comment ? $request->comment : ($paymentMode == 1 ? $accPayDesc : $accRecDesc),
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', 0, 6);

        if ($request->payment_method == 2) {
            $bankTrArr = [
                'account_id'       => $account->id,
                $paymentMode == 1 ? 'withdrawer_name' : 'depositor_name' => $request->withdrawer_name,
                $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 1 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
                'description'      => $paymentMode == 1 ? $bankTrPayDesc : $bankTrRecDesc,
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }
    }
    }

    public function purchaseAccountUpdate($request, $id, $amount = 0)
    {
        $getSupplierId = null;
        $finalAmount = 0;

        if (is_array($id)) {

            $finalAmount = $amount;

            if (count($id) > 1) {
                $purchases = Purchase::whereIn('id', $id)->get();
                $invoiceName = '';

                foreach ($purchases as $item) {
                    $invoiceName .=  $item->invoice_no . ', ';

                    if (!$getSupplierId) {
                        $getSupplierId = $item->supplier_id;
                    }
                }

                $getSupplier = Supplier::whereId($getSupplierId)->first();
                $invoiceNames = rtrim($invoiceName, ", ");

                $accDesc = "Bulk due payment of " . $finalAmount . " Tk paid to the supplier " . $getSupplier->name . " for previous Purchases (" . $invoiceNames .  ")";
                $bankDesc = 'Company give bulk due payment to Supplier ' . $getSupplier->name . ' And Purchase invoices are (' . $invoiceNames . ')';
            } else {

                $purchase = Purchase::whereId($id[0])->first();
                $accDesc = "Parchase due amount of " . $finalAmount . " Tk paid to the supplier " . $purchase->supplier->name . " for previous Purchase (" . $purchase->invoice_no .  ")";
                $bankDesc = 'Company give Parchase due to Supplier ' . $purchase->supplier->name . ' And Purchase invoice is ' . $purchase->invoice_no;
            }
        } else {
            $finalAmount = $amount;

            $purchase = Purchase::whereId($id)->first();
            $accDesc = "Partial due amount of " . $finalAmount . " Tk paid to the supplier " . $purchase->supplier->name . " for previous Purchase (" . $purchase->invoice_no .  ")";
            $bankDesc = 'Company give partial due to Supplier ' . $purchase->supplier->name . ' And Purchase invoice is ' . $purchase->invoice_no;
        }

        $accArr = [
            'purchase_id'       => is_array($id) ? (count($id) > 1 ? 0 : $purchase->id) : $purchase->id,
            'type'              => 8,
            'debit'             => $finalAmount,
            'description'       => $request->comment ? $request->comment : $accDesc,
            'supplier_id'       => isset($getSupplierId) ? $getSupplierId : $purchase->supplier_id,
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', 0, 8);

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'  => $request->withdrawer_name,
                'debit'            => $finalAmount,
                'description'      => $bankDesc,
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }
    }

    public function store(Request $request, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'name'        => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'address'     => 'required|string|max:500',

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('suppliers', 'email')->ignore($id),
            ],

            'mobile' => [
                'required',
                Rule::unique('suppliers', 'mobile')->ignore($id),
            ],
        ]);

        if ($id > 0) {
            $supplier               = Supplier::whereId($id)->first();
            $message                = 'Supplier has been updated successfully';
            $givenStatus            = isset($request->status) ? 1 : 0;
            $supplier->update_by    = auth()->user()->id;
        } else {
            $supplier               = new Supplier();
            $message                = 'Supplier has been created successfully';
            $givenStatus            = isset($request->status) ? 1 : 0;
            $supplier->code         = randomCode('SUP');
            $supplier->entry_by     = auth()->user()->id;
            $supplier->entry_date   = now();
        }

        $supplier->name             = $request->name;
        $supplier->company          = $request->company;
        $supplier->mobile           = $request->mobile;
        $supplier->email            = $request->email;
        $supplier->address          = $request->address;
        $supplier->status           = $givenStatus;
        $supplier->save();

        // // == ===== == Receivable Account Start ==>
        if ($id == 0) {
            $receivableData = new Receivable();
            $receivableData->supplier_id = $supplier->id;
            $receivableData->receivable_head_id = 3;
            $receivableData->save();
        }
        // // == ===== == Receivable Account End ==>

        // ======================= advance or due =======================start
        if($id == 0){
            if(!empty($request->due_amount)){
                $supplier->due = $request->due_amount;
                $supplier->save();
    
            // // == ===== == Payable Account Start ==>
                $payableData = new Payable();
                $payableData->supplier_id = $supplier->id;
                $payableData->payables_head_id = 5;
                $payableData->payable_amount = $request->due_amount;
                $payableData->save();
            // // == ===== == Payable Account End ==>
            // // == ===== == Income Account Start ==>
                $incomes = new Income();
                $incomes->entry_type = 1;
                $incomes->description = "Purchase due to supplier {$supplier->name}, amount: {$request->due_amount} Tk";
                $incomes->effective_amount = $request->due_amount;
                $incomes->debit_or_credit = 'debit';
                $incomes->entry_by = auth()->user()->id;
                $incomes->save();
            // // == ===== == Income Account End ==>
            $accArr = [
                'supplier_id'       => $supplier->id,
                'type'              => 1,
                'amount'            => $request->due_amount,
                'description'        =>"Sale due to supplier {$supplier->name}, amount: {$request->due_amount} Tk",
            ];
    
            updateAcc($accArr, 'supplier_id', $supplier->id, 1);
    
            }else{
    
                $supplier->advance = $request->balance;
                $supplier->save();
    
                $accArr = [
                    'supplier_id'       => $supplier->id,
                    'type'              => 6,
                    'debit'            => $request->balance,
                    'description'       =>  "Payment received of " . $request->balance . " Tk as Advance from supplier .",
                    'payment_method'    => $request->payment_method,
                ];
        
                $account = updateAcc($accArr, 'NTR', $supplier->id, 6);

                // == ===== == Receivable Account Start ==>
                $receivableData = Receivable::where('supplier_id', $supplier->id)->where('receivable_head_id', 3)->first();
                if ($receivableData) {
                    $receivableData->receivable_amount += $request->balance;
                    $receivableData->save();
                }
                // == ===== == Receivable Account End ==>
        
                if ($request->payment_method == 2) {
        
                    $bankTrArr = [
                        'account_id'       => $account->id,
                        'withdrawer_name'   => $request->withdrawer_name,
                        'debit'           => $request->balance,
                        'description'      => 'Company received amount of ' . $request->balance . ' Tk as Advance from supplier .',
                        'bank_id'          => $request->bank_id,
                        'check_no'         => $request->check_no,
                    ];
        
                    bankTr($account, $bankTrArr);
                }
        
            }
        }
            // ======================= advance or due =======================end


        $notify[] = ['success', $message];
        return to_route('supplier.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $supplier = Supplier::find($id);
        $supplier->is_deleted = 1;
        $supplier->save();

        $notify[] = ['success', 'Supplier has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
