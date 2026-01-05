<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Sell;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\BsType;
use App\Models\BsAccount;
use App\Models\JournalEntry;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\Income;
use App\Models\PoultryBatch;
use App\Models\PoultryChickDeath;
use App\Models\PoultryExpense;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customer-list', ['only' => ['index']]);
        $this->middleware('permission:customer-create|customer-edit', ['only' => ['store']]);
        $this->middleware('permission:customer-create', ['only' => ['create']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit']]);
        $this->middleware('permission:customer-delete', ['only' => ['delete']]);
        $this->middleware('permission:customer-payment', ['only' => ['payment']]);
    }
    public function index($type = null)
    {
        $pageTitle = 'Customer List';

        $buttonColors = [
            'btn-success',
            'btn-info',
            'btn-warning',
            'btn-danger',
            'btn-dark',
            'btn-blue',
            'btn-pink',
            'btn-secondary',
            'btn-light',
            'btn-white',
            'btn-link',
        ];

        $customers = Customer::query();

        if ($type) {
            $customers = $customers->whereType($type);
        }

        $customers = $customers->notDeleted()->latest()->paginate(gs()->pagination);
        $count = Customer::notDeleted()->count();

        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        $customerTypes = CustomerType::whereStatus(1)->notDeleted()->latest()->get();
        return view('customer.index', compact('pageTitle', 'customers', 'banks', 'customerTypes', 'buttonColors', 'count'));
    }


    public function batches($customer_id)
    {
        $customerInfo = Customer::find($customer_id);
        $pageTitle = $customerInfo->name . "'s batches";
        $batches = PoultryBatch::where('customer_id', $customer_id)->orderBy('batch_start_date', 'desc')->get();
        return view('customer.poltry_batches', compact('pageTitle', 'batches', 'customerInfo'));
    }

    public function createBatch($customer_id)
    {
        $customerInfo = Customer::find($customer_id);
        $pageTitle = 'Create Batch for ' . $customerInfo->name;
        $customers = Customer::get();

        return view('poultry_batch.create', compact('pageTitle', 'customer_id', 'customers'));
    }


    public function manageBatch($batch_id)
    {
        $batchInfo = PoultryBatch::find($batch_id);
        $customerInfo = Customer::find($batchInfo->customer_id);
        $pageTitle = 'Manage Batch for ' . $customerInfo->name;
        $totalDeaths = PoultryChickDeath::where('batch_id', $batch_id)->sum('total_deaths');

        $expenseQuery = PoultryExpense::where('batch_id', $batch_id);

        $expenses = (clone $expenseQuery)->select('category', DB::raw('SUM(total_amount) as total_expense'))
            ->groupBy('category')
            ->orderBy('total_expense', 'desc')
            ->get();
        $totalExpenses = (clone $expenseQuery)->sum('total_amount');

        $totalFeedConsumedInKg = PoultryExpense::where('batch_id', $batch_id)
            ->where('category', 'feed')
            ->get()
            ->sum(function ($expense) {
                return $expense->unit === 'bag' ? $expense->quantity * 50 : ($expense->unit === 'kg' ? $expense->quantity : $expense->quantity * 50);
            });


        return view('customer.manage_batch', compact('pageTitle', 'batchInfo', 'customerInfo', 'totalDeaths', 'expenses', 'totalExpenses', 'totalFeedConsumedInKg'));
    }


    public function advanceIndex()
    {
        $pageTitle = 'All Customers with Advance';
        $customers = Customer::where('advance', '>', 0)->latest()->notDeleted()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('customer.index', compact('pageTitle', 'customers', 'banks'));
    }

    public function create()
    {
        $pageTitle = 'Create Customer';
        $types = CustomerType::latest()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('customer.store', compact('pageTitle', 'types', 'banks'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Customer';
        $customer = Customer::find($id);
        $types = CustomerType::latest()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('customer.store', compact('pageTitle', 'customer', 'types', 'banks'));
    }

    public function payment(Request $request, $id)
    {
        $findCustomer = Customer::whereId($id)->first();
        $bank = Bank::whereId($request->bank_id)->first();

        $customer = Customer::whereId($id)->first();

        if ($request->payment_mode == 1) {
            $sells = Sell::whereCustomerId($id)->where('due_to_company', '>', 0)->get();
            $totalDues = Sell::whereCustomerId($id)->sum('due_to_company');

            $inputtedAmount = $request->balance;
            $targetedId = [];
            $newArr = [];
            $bulkAmount = 0;
            $count = 0;

            if ($customer->due > 0) {
                if ($inputtedAmount >= $customer->due) {
                    $inputtedAmount -= $customer->due;

                    $this->customerAccountUpdate($request, $customer, $customer->due, 2, $type = 1);
                    $customer->due = 0;
                    $customer->save();


                    $receivableData = Receivable::where('receivable_head_id', 4)->where('customer_id', $id)->first();
                    if ($receivableData) {
                        $receivableData->receivable_amount = 0;
                        $receivableData->save();
                    }
                } else {
                    $customer->due -= $inputtedAmount;
                    $this->customerAccountUpdate($request, $customer, $inputtedAmount, 2, $type = 1);
                    $customer->save();

                    $receivableData = Receivable::where('receivable_head_id', 4)->where('customer_id', $id)->first();
                    if ($receivableData) {
                        $receivableData->receivable_amount -= $inputtedAmount;
                        $receivableData->save();
                    }

                    $notify[] = ['success', 'Customer due partially cleared.'];
                    return back()->withNotify($notify);
                }
            }

            foreach ($sells as $item) {
                if ($inputtedAmount < $item->due_to_company) {
                    $receivableData = Receivable::where('sell_id', $item->id)->where('customer_id', $item->customer_id)->first();
                    if ($receivableData) {
                        $receivableData->receivable_amount -= $inputtedAmount;
                        $receivableData->save();
                    }

                    $item->payment_received += $inputtedAmount;
                    $item->due_to_company   -= $inputtedAmount;
                    $item->save();

                    $targetedId = $item->id;
                    $this->sellAccountUpdate($request, $targetedId, $inputtedAmount);

                    break;
                } else {
                    $receivableData = Receivable::where('sell_id', $item->id)->where('customer_id', $item->customer_id)->first();
                    if ($receivableData) {
                        $receivableData->receivable_amount = 0;
                        $receivableData->save();
                    }

                    $inputtedAmount -= $item->due_to_company;
                    $bulkAmount += $item->due_to_company;

                    $item->payment_received = $item->total_price;
                    $item->due_to_company = 0;
                    $item->save();

                    array_push($targetedId, $item->id);
                    $newArr = $targetedId;

                    $count++;

                    if ($inputtedAmount == 0) {
                        break;
                    }
                }
            }

            if (is_array($newArr) && !empty($newArr)) {
                $this->sellAccountUpdate($request, $newArr, $bulkAmount);
            }

            if ($request->balance > ($totalDues + $findCustomer->due)) {
                $extra = $request->balance - ($totalDues + $findCustomer->due);

                $customer->advance += $extra;
                $customer->save();
                $this->customerAccountUpdate($request, $customer, $extra, 2, $type = 2);

                $payableData = Payable::where('customer_id', $id)->where('payables_head_id', 2)->first();
                if ($payableData) {
                    $payableData->payable_amount += $extra;
                    $payableData->description = "Customer advance amount of " . showAmount($extra);
                    $payableData->save();
                }
            }
        } else {
            if ($request->balance > $customer->advance) {
                $notify[] = ['error', 'Insufficient advance available with the customer.'];
                return back()->withNotify($notify);
            }

            $customer->advance -= $request->balance;
            $customer->save();

            $this->customerAccountUpdate($request, $customer, $request->balance, 1, $type = 2);

            $payableData = Payable::where('customer_id', $id)->where('payables_head_id', 2)->first();
            if ($payableData) {
                $payableData->payable_amount -= $request->balance;
                $payableData->save();
            }
        }

        $notify[] = ['success', 'Payment successfully completed.'];
        return back()->withNotify($notify);
    }

    public function customerAccountUpdate($request, $customer, $extraAmount = 0, $paymentMode, $type)
    {
        if ($type == 1) {

            $bankTrRecDesc = "Company received due from customer " . $customer->name . ". Amount of .$request->balance";

            $accArr = [
                'customer_id'       => $customer->id,
                'type'              => 7,
                $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 2 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
                'description' => "Customer {$customer->name} paid amount of {$request->balance} for sell due. ",
                'payment_method'    => $request->payment_method,
            ];

            $account = updateAcc($accArr, 'NTR', 0, 7);

            if ($request->payment_method == 2) {
                $bankTrArr = [
                    'account_id'       => $account->id,
                    'depositor_name' => $request->depositor_name,
                    $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 2 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
                    'description'      => $paymentMode == 1 ? $bankTrPayDesc : $bankTrRecDesc,
                    'bank_id'          => $request->bank_id,
                    'check_no'         => $request->check_no,
                ];

                bankTr($account, $bankTrArr);
            }
        } else {

            // $accPayDesc = "Paid advance payment of " . ($extraAmount > 0 ? $extraAmount : $request->balance) . " Tk to the customer " . $customer->name;
            // $bankTrPayDesc = 'Company receive advance from the customer ' . $customer->name;

            // $accRecDesc = "Receive advance amount of " . $request->balance . " Tk from the customer " . $customer->name;
            // $bankTrRecDesc = 'Company paid advance to the customer ' . $customer->name;

            $accRecDesc = "Received an advance payment of " . ($extraAmount > 0 ? $extraAmount : $request->balance) . " Tk from customer " . $customer->name . ".";
            $bankTrRecDesc = "Company received an advance from customer " . $customer->name . ".";

            $accPayDesc = "Returned an advance amount of " . $request->balance . " Tk to customer " . $customer->name . ".";
            $bankTrPayDesc = "Company paid back the advance to customer " . $customer->name . ".";


            $accArr = [
                'customer_id'       => $customer->id,
                'type'              => 5,
                // 'credit'            => $extraAmount > 0 ? $extraAmount : $request->balance,
                // 'description'       => $request->comment ? $request->comment : "Paid advance payment of " . ($extraAmount > 0 ? $extraAmount : $request->balance) . " Tk to the Customer " . $customer->name,
                $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 2 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
                'description'       => $request->comment ? $request->comment : ($paymentMode == 1 ? $accPayDesc : $accRecDesc),
                'payment_method'    => $request->payment_method,
            ];

            $account = updateAcc($accArr, 'NTR', 0, 5);

            if ($request->payment_method == 2) {
                $bankTrArr = [
                    'account_id'       => $account->id,
                    // 'depositor_name'   => $request->depositor_name,
                    // 'credit'           => $extraAmount > 0 ? $extraAmount : $request->balance,
                    // 'description'      => 'Company paid advance to the Customer ' . $customer->name,
                    $paymentMode == 1 ? 'withdrawer_name' : 'depositor_name' => $request->depositor_name,
                    $paymentMode == 1 ? 'debit' : 'credit'  => $paymentMode == 2 ? ($extraAmount > 0 ? $extraAmount : $request->balance) : $request->balance,
                    'description'      => $paymentMode == 1 ? $bankTrPayDesc : $bankTrRecDesc,
                    'bank_id'          => $request->bank_id,
                    'check_no'         => $request->check_no,
                ];

                bankTr($account, $bankTrArr);
            }
        }
    }

    public function sellAccountUpdate($request, $id, $amount = 0)
    {
        $getCustomerId = null;
        $finalAmount = 0;

        if (is_array($id)) {

            $finalAmount = $amount;

            if (count($id) > 1) {

                $sells = Sell::whereIn('id', $id)->get();;
                $invoiceName = '';

                foreach ($sells as $item) {
                    $invoiceName .=  $item->invoice_no . ', ';

                    if (!$getCustomerId) {
                        $getCustomerId = $item->customer_id;
                    }
                }

                $getCustomer = Customer::whereId($getCustomerId)->first();
                $invoiceNames = rtrim($invoiceName, ", ");

                $accDesc = "Received Bulk payment of " . $finalAmount . " Tk from customer " . $getCustomer->name . " for previous Sells (" . $invoiceNames .  ")";
                $bankDesc = 'Company received bulk payment from Customer ' . $getCustomer->name . ' And Sell invoices are (' . $invoiceNames . ')';
            } else {

                $sell = Sell::whereId($id[0])->first();
                $accDesc = "Sell Payment amount of " . $finalAmount . " Tk received from the customer " . $sell->customer->name . " for previous Sell (" . $sell->invoice_no .  ")";
                $bankDesc = 'Company received Sell payment from Customer ' . $sell->customer->name . ' And Sell invoice is "' . $sell->invoice_no . '"';
            }
        } else {
            $finalAmount = $amount;

            $sell = Sell::whereId($id)->first();
            $accDesc = "Partial payment amount of " . $finalAmount . " Tk received from the customer " . $sell->customer->name . " for previous Sell (" . $sell->invoice_no .  ")";
            $bankDesc = 'Company received partial payment from Customer ' . $sell->customer->name . ' And Sell invoice is "' . $sell->invoice_no . '"';
        }

        $accArr = [
            'sell_id'           => is_array($id) ? (count($id) > 1 ? 0 : $sell->id) : $sell->id,
            'type'              => 7,
            'credit'            => $finalAmount,
            'description'       => $request->comment ? $request->comment : $accDesc,
            'customer_id'       => isset($getCustomerId) ? $getCustomerId : $sell->customer_id,
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', 0, 7);

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'depositor_name'   => $request->depositor_name,
                'credit'           => $finalAmount,
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
            'type'        => 'nullable|string|max:255',
            'trn_number'  => 'nullable|string|max:255',
            'trn_date'    => 'nullable|string|max:255',
            'company'     => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:500',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($id),
            ],

            'mobile' => [
                'required',
                Rule::unique('customers', 'mobile')->ignore($id),
            ],
        ]);

        if ($id > 0) {
            $customer = Customer::whereId($id)->first();
            $message = 'Customer has been updated successfully';
            $givenStatus = isset($request->status) ? 1 : 0;
        } else {
            $customer = new Customer();
            $message = 'Customer has been created successfully';
            $givenStatus = isset($request->status) ? 1 : 0;
            $customer->code = randomCode('CUS');
            $customer->entry_by = auth()->user()->id;
            $customer->entry_date = now();
        }

        $customer->name = $request->name;
        $customer->type = $request->type;
        $customer->trn_number = $request->trn_number;
        $customer->trn_date = $request->trn_date;
        $customer->company = $request->company;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->status = $givenStatus;
        $customer->save();
        $notify[] = ['success', $message];
        return to_route('customer.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        $customer->is_deleted = 1;
        $customer->save();

        $notify[] = ['success', 'Customer has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
