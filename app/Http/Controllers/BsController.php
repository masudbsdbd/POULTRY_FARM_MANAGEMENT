<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\BsType;
use App\Models\BsAccount;
use App\Models\JournalEntry;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\Sell;
use App\Models\Expense;
use App\Models\Stock;
use App\Models\SellRecord;
use App\Models\Damage;
use App\Models\Bank;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Investment;
use App\Models\Owner;
use App\Models\ManageStock;
use App\Models\Income;
use App\Models\EmployeeTransaction;

class BsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:balance-sheet-maintain', ['only' => ['journalIndex','receivableIndex', 'chartAccount', 'journalCreate', 'journalStore']]);
    }
    public function journalIndex()
    {

        $pageTitle = "All Journals";

        $allJournalEntry = JournalEntry::latest()->paginate(gs()->pagination);

        return view('bs-account.journal-index', compact('pageTitle', 'allJournalEntry'));
    }

    public function receivableIndex(Request $request)
    
    {
        // return $request;
        $pageTitle = "Accounts Receivable";
        $todayTime = Carbon::now()->format('d-m-Y');

        $type = $request->type;
        $date = $request->date;
        $range = $request->range;

        // return 1;

        $receivableQuery = BsAccount::select(
            'bs_accounts.sell_id',
            'sells.invoice_no',
            'sells.sell_date'
        )
            ->where('bs_accounts.account_type', 1)
            ->where('bs_accounts.account_sub_type', 4)
            ->join('sells', 'bs_accounts.sell_id', '=', 'sells.id')
            ->groupBy('bs_accounts.sell_id', 'sells.invoice_no', 'sells.sell_date')
            ->selectRaw('SUM(bs_accounts.credit) as total_credit, SUM(bs_accounts.debit) as total_debit, (SUM(bs_accounts.debit) - SUM(bs_accounts.credit)) as balance');

        // return $receivableQuery;

        if ($type) {
            if ($type == 1 && $date) {
                $givenDate = $date;
                $receivableQuery = $receivableQuery->whereDate('sells.sell_date', $givenDate);
            } elseif ($type == 2 && $range) {
                $dates = explode(' to ', $range);
                if (count($dates) == 2) {
                    $startDate = $dates[0] . ' 00:00:00';
                    $endDate = $dates[1] . ' 23:59:59';
                    $receivableQuery = $receivableQuery->whereBetween('sells.sell_date', [$startDate, $endDate]);
                }
                $givenDate = [$startDate, $endDate];
            } else {
                $notify[] = ['error', 'Kindly select a valid date or date range.'];
                return back()->withNotify($notify);
            }
        }

        if ($request->action == 'print') {
            $mpdf = setPdf();

            $receivables = $receivableQuery->get();
            $data = isset($givenDate) ? compact('receivables', 'givenDate') : compact('receivables');
            $html = view('bs-account.acc-receivable-pdf', $data)->render();

            $mpdf->WriteHTML($html);
            return response($mpdf->Output('acc-receivable.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }

        $receivables = $receivableQuery->paginate(gs()->pagination);

        return view('bs-account.accounts-receivable', compact('pageTitle', 'receivables'));
    }

    // public function chartAccount()
    // {
    //     $allAssets = BsAccount::with('bsType')
    //         ->where('account_type', 1)
    //         ->get()
    //         ->groupBy('bsType.id')
    //         ->map(function ($group) {
    //             $totalDebit = $group->sum('debit');
    //             $totalCredit = $group->sum('credit');
    //             return [
    //                 'name' => $group->first()->bsType->name,
    //                 'net_amount' => $totalDebit - $totalCredit,
    //             ];
    //         });

    //     // dd($allAssets->toArray());
    //     $totalAssets = $allAssets->sum('net_amount');


    //     // Libilities->
    //     $allLibilities = BsAccount::with('bsType')
    //         ->where('account_type', 2)
    //         ->get()
    //         ->groupBy('bsType.id')
    //         ->map(function ($group) {
    //             $totalDebit = $group->sum('debit');
    //             $totalCredit = $group->sum('credit');
    //             return [
    //                 'name' => $group->first()->bsType->name,
    //                 'net_amount' => $totalCredit - $totalDebit,
    //             ];
    //         });
    //     $totalLibilities = $allLibilities->sum('net_amount');

    //     // dd($totalLibilities);

    //     // Equity->
    //     $allEquity = BsAccount::with('bsType')
    //         ->where('account_type', 3)
    //         ->get()
    //         ->groupBy('bsType.id')
    //         ->map(function ($group) {
    //             $totalDebit = $group->sum('debit');
    //             $totalCredit = $group->sum('credit');
    //             return [
    //                 'name' => $group->first()->bsType->name,
    //                 'net_amount' => $totalCredit - $totalDebit,
    //             ];
    //         });
    //     $totalEquity = $allEquity->sum('net_amount');


    //     return view('bs-account.chart-account-index', compact('allAssets', 'totalAssets', 'allLibilities', 'totalLibilities', 'allEquity', 'totalEquity'));
    // }


    // public function chartAccount()
    // {
    //     $totalAssets = Asset::pluck('purchase_price')->sum();


    //     $totalCashCredit = Account::where('payment_method', 1)->pluck('credit')->sum();
    //     $totalCashDebit = Account::where('payment_method', 1)->pluck('debit')->sum();
    //     $currentCashAmount = $totalCashCredit - $totalCashDebit;


    //     $totalBankCredit = Account::where('payment_method', 2)->pluck('credit')->sum();
    //     $totalBankDebit = Account::where('payment_method', 2)->pluck('debit')->sum();
    //     $currentBankAmount = $totalBankCredit - $totalBankDebit;


    //     // dd($currentBank);

    //     $totalSupplierAdvanceReceivableAmountSum = Receivable::pluck('supplier_advance_amount')->sum();
    //     $totalDueReceivableAmountSum = Receivable::pluck('due_amount')->sum();
    //     $totalReceivableAmountSum = Receivable::pluck('receivable_amount')->sum();
    //     $totalReceivableAmount = $totalSupplierAdvanceReceivableAmountSum + $totalDueReceivableAmountSum + $totalReceivableAmountSum;

    //     // dd($totalSupplierAdvanceReceivableAmountSum, $totalDueReceivableAmountSum, $totalReceivableAmountSum);


    //     $totalPurchaseStock = Stock::get()->sum(function ($stock) {
    //         return $stock->avg_purchase_price * $stock->stock;
    //     });

    //     // dd($totalPurchaseStock);


    //     $totalCustomerAdvancePayableAmountSum = Payable::pluck('customer_advance_amount')->sum();
    //     $totalDuePayableAmountSum = Payable::pluck('due_amount')->sum();
    //     $totalPayableAmountSum = Payable::pluck('payable_amount')->sum();
    //     //find employee salary payable->
    //     $employeePayableSalary = Payable::where('payables_head_id', 3)->pluck('due_amount')->sum();
    //     $totalPayableAmount = $totalCustomerAdvancePayableAmountSum + $totalDuePayableAmountSum + $totalPayableAmountSum;
    //     // dd($employeePayableSalary);
    //     // dd($totalPayableAmount , $totalCustomerAdvancePayableAmountSum, $totalDuePayableAmountSum, $totalPayableAmountSum);


    //     //find Income ->
    //     $incomes = Account::where('type', 15)->pluck('credit')->sum();
    //     // dd($incomes);

    //     return view('bs-account.chart-account-index', compact('totalAssets', 'currentCashAmount', 'currentBankAmount', 'totalReceivableAmount', 'totalPurchaseStock', 'totalPayableAmount', 'incomes', 'employeePayableSalary'));
    // }


    public function chartAccount(Request $request)
    {
        $date = $request->date;
    
        if ($date) {
            $date = Carbon::parse($date)->startOfDay(); 
        } else {
            $date = now(); 
        }


        $totalPurchaseAmount = Asset::whereDate('purchase_date', '<=', $date)->sum('purchase_price');
        $totalEffectiveDebit = Asset::whereDate('purchase_date', '<=', $date)
            ->where('debit_or_credit', 'debit')
            ->sum('effective_amount');
        $totalEffectiveCredit = Asset::whereDate('purchase_date', '<=', $date)
            ->where('debit_or_credit', 'credit')
            ->sum('effective_amount');
        $totalEffectiveAmount = $totalEffectiveDebit - $totalEffectiveCredit;
        $totalAssets = $totalPurchaseAmount + $totalEffectiveAmount;

        // dd($totalAssets);
        // $totalAsstAmountSeparator = Asset::whereDate('created_at', '<=', $date)
        //     ->with('assetHead')
        //     ->where('purchase_price', '>', 0)
        //     ->get()
        //     ->groupBy('asset_head_id')
        //     ->map(function ($items, $headId) {
        //         return (object) [
        //             'asset_head_id' => $headId,
        //             'asset_head_name' => optional($items->first()->assetHead)->name,
        //             'total_amount' => $items->sum('purchase_price'),
        //         ];
        //     });

        $totalAsstAmountSeparator = Asset::whereDate('created_at', '<=', $date)
            ->with('assetHead')
            ->get()
            ->groupBy('asset_head_id')
            ->map(function ($items, $headId) {
                $debitTotal = $items->where('debit_or_credit', 'debit')->sum('effective_amount');
                $creditTotal = $items->where('debit_or_credit', 'credit')->sum('effective_amount');

                return (object) [
                    'asset_head_id'    => $headId,
                    'asset_head_name'  => optional($items->first()->assetHead)->name,
                    'total_amount'     => $items->sum('purchase_price'),
                    'effective_amount' => $debitTotal - $creditTotal,
                ];
            });


        // dd($totalAsstAmountSeparator->toArray());
    
        $totalCashCredit = Account::where('payment_method', 1)->whereDate('created_at', '<=', $date)->pluck('credit')->sum();
        $totalCashDebit = Account::where('payment_method', 1)->whereDate('created_at', '<=', $date)->pluck('debit')->sum();
        $currentCashAmount = $totalCashCredit - $totalCashDebit;

        // dd($currentCashAmount);
    
        $totalBankCredit = Account::where('payment_method', 2)->whereDate('created_at', '<=', $date)->pluck('credit')->sum();
        $totalBankDebit = Account::where('payment_method', 2)->whereDate('created_at', '<=', $date)->pluck('debit')->sum();
        $currentBankAmount = $totalBankCredit - $totalBankDebit;
    
        // $totalSupplierAdvanceReceivableAmountSum = Receivable::whereDate('created_at', '<=', $date)->pluck('supplier_advance_amount')->sum();
        // $totalDueReceivableAmountSum = Receivable::whereDate('created_at', '<=', $date)->pluck('due_amount')->sum();
        // $totalReceivableAmountSum = Receivable::whereDate('created_at', '<=', $date)->pluck('receivable_amount')->sum();
        // $totalReceivableAmount = $totalSupplierAdvanceReceivableAmountSum + $totalDueReceivableAmountSum + $totalReceivableAmountSum;
    
        // ==================================
        $totalReceivableAmountSum = Receivable::whereDate('created_at', '<=', $date)->pluck('receivable_amount')->sum();

        $totalReceivableEffectiveDebit = Receivable::whereDate('created_at', '<=', $date)
            ->where('debit_or_credit', 'debit')
            ->sum('effective_amount');
        $totalReceivableEffectiveCredit = Receivable::whereDate('created_at', '<=', $date)
            ->where('debit_or_credit', 'credit')
            ->sum('effective_amount');
        $totalReceivableEffectiveAmount = $totalReceivableEffectiveDebit - $totalReceivableEffectiveCredit;
        $totalReceivableAmount =  $totalReceivableAmountSum + $totalReceivableEffectiveAmount;

        $totalReceivableAmountSeparator = Receivable::whereDate('created_at', '<=', $date)
            ->with('receivableHead')
            // ->where('receivable_amount', '>', 0)
            ->get()
            ->groupBy('receivable_head_id')
            ->map(function ($items, $headId) {
                $debitTotal = $items->where('debit_or_credit', 'debit')->sum('effective_amount');
                $creditTotal = $items->where('debit_or_credit', 'credit')->sum('effective_amount');
                return (object) [
                    'receivable_head_id' => $headId,
                    'receivable_head_name' => optional($items->first()->receivableHead)->name,
                    'total_amount' => $items->sum('receivable_amount'),
                    'effective_amount' => $debitTotal - $creditTotal,
                ];
            });
        // dd($totalReceivableAmountSeparator->toArray());
         // ==================================

        // $totalDamageAmount = Damage::whereDate('created_at', '<=', $date)->pluck('total_damage_price')->sum();
        $totalDamageAmount = ManageStock::whereDate('created_at', '<=', $date)
            ->whereNotIn('stock_status', [3, 6])
            ->sum('grand_total');


        $totalPurchaseIncreaseAmount = ManageStock::whereDate('created_at', '<=', $date)
        ->whereIn('stock_status', [3, 6])
        ->sum('grand_total');
        // dd($totalPurchaseIncreaseAmount);


        $totalPurchaseStock = Stock::whereDate('created_at', '<=', $date)->get()->sum(function ($stock) {
            return $stock->avg_purchase_price * $stock->stock;
        });

        // ================================
        // $totalPayableAmountSum = Payable::whereDate('created_at', '<=', $date)->pluck('payable_amount')->sum();
        // $employeePayableSalary = Payable::where('payables_head_id', 3)->whereDate('created_at', '<=', $date)->pluck('payable_amount')->sum();
        
        $totalPayableAmountSum = Payable::whereDate('created_at', '<=', $date)->pluck('payable_amount')->sum();

        $totalPayableEffectiveDebit = Payable::whereDate('created_at', '<=', $date)
            ->where('debit_or_credit', 'debit')
            ->sum('effective_amount');
        $totalPayableEffectiveCredit = Payable::whereDate('created_at', '<=', $date)
            ->where('debit_or_credit', 'credit')
            ->sum('effective_amount');
        $totalPayableEffectiveAmount = $totalPayableEffectiveCredit - $totalPayableEffectiveDebit;

        $totalPayableAmount = $totalPayableAmountSum + $totalPayableEffectiveAmount;
        // dd($totalPayableAmount);

        $totalOwnerDebits = Owner::whereDate('created_at', '<=', $date)->where('debit_or_credit', 'debit')->sum('amount');
        $totalOwnerCredits = Owner::whereDate('created_at', '<=', $date)->where('debit_or_credit', 'credit')->sum('amount');
        $totalOwnerAmount = $totalOwnerCredits - $totalOwnerDebits;
        // dd($totalOwnerAmount);

        // $totalPayableAmountSeparator = Payable::selectRaw('payables_head_id, SUM(payable_amount) as total_amount')
        //     ->whereDate('created_at', '<=', $date)
        //     ->where('payable_amount', '>', 0)
        //     ->groupBy('payables_head_id')
        //     ->with('payablesHead')
        //     ->get()
        //     ->map(function ($item) {
        //         return (object) [
        //             'payables_head_id' => $item->payables_head_id,
        //             'payables_head_name' => optional($item->payablesHead)->name,
        //             'total_amount' => $item->total_amount,
        //         ];
        //     });

        $totalPayableAmountSeparator = Payable::whereDate('created_at', '<=', $date)
            // ->where('payable_amount', '>', 0)
            ->with('payablesHead')
            ->get()
            ->groupBy('payables_head_id')
            ->map(function ($items, $headId) {
                $debitTotal = $items->where('debit_or_credit', 'debit')->sum('effective_amount');
                $creditTotal = $items->where('debit_or_credit', 'credit')->sum('effective_amount');

                return (object) [
                    'payables_head_id'   => $headId,
                    'payables_head_name' => optional($items->first()->payablesHead)->name,
                    'total_amount'       => $items->sum('payable_amount'),
                    'effective_amount'   => $creditTotal - $debitTotal,
                ];
            });

        // dd($totalPayableAmountSeparator->toArray());
        // ================================
    
        $incomes = Account::where('type', 15)->whereDate('created_at', '<=', $date)->pluck('credit')->sum();

        $totalEffectiveIncomeDebit = Income::whereDate('created_at', '<=', $date)
            ->where('debit_or_credit', 'debit')
            ->sum('effective_amount');
        $totalEffectiveIncomeCredit = Income::whereDate('created_at', '<=', $date)  
            ->where('debit_or_credit', 'credit')    
            ->sum('effective_amount');
        $totalEffectiveIncomeAmount = $totalEffectiveIncomeCredit - $totalEffectiveIncomeDebit;
        // dd($totalEffectiveIncomeAmount);

        $totalSellwithoutProfitAmount = Sell::whereDate('created_at', '<=', $date)
            ->where('profit', '>', 0)
            ->get()
            ->sum(function ($sell) {
                return $sell->total_price - $sell->profit;
            });

        // dd($totalSellwithoutProfitAmount);

        $totalSellProfit = SellRecord::whereDate('created_at', '<=', $date)->sum('profit');

        $totalManualExpenseAmount = Expense::whereDate('entry_date', '<=', $date)->sum('paid_amount');
        $totalJournalExpenseDebit = Expense::whereDate('entry_date', '<=', $date)->where('debit_or_credit', 'debit')->sum('effective_amount');
        $totalJournalExpenseCredit = Expense::whereDate('entry_date', '<=', $date)->where('debit_or_credit', 'credit')->sum('effective_amount');
        $totalExpenseAmount = $totalManualExpenseAmount + $totalJournalExpenseDebit - $totalJournalExpenseCredit;

        // dd($totalExpenseAmount);
        $employeeBalances = EmployeeTransaction::whereDate('created_at', '<=', $date)
            ->select('employee_id')
            ->selectRaw('SUM(salary_amount) as total_salary')
            ->selectRaw('SUM(received_amount) as total_received')
            ->groupBy('employee_id')
            ->get();

        $empAdvanceSalary = $employeeBalances->sum(function($item) {
            $balance = $item->total_received - $item->total_salary;
            return $balance > 0 ? $balance : 0;
        });

        // dd($empAdvanceSalary);


        if ($request->date) {
            $data = compact('totalAssets', 'currentCashAmount', 'currentBankAmount', 'totalReceivableAmount', 'totalPurchaseStock', 'totalPayableAmount', 'incomes', 'totalSellProfit', 'totalDamageAmount', 'totalReceivableAmountSeparator', 'totalPayableAmountSeparator','totalAsstAmountSeparator','totalOwnerAmount', 'totalExpenseAmount','totalPurchaseIncreaseAmount','totalSellwithoutProfitAmount','totalEffectiveIncomeAmount','empAdvanceSalary');
    
            $mpdf = setPdf();
            $html = view('bs-account.chart-account-index-pdf', $data)->render();
            $mpdf->WriteHTML($html);
    
            return response($mpdf->Output('chart-account.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }
    
        return view('bs-account.chart-account-index', compact('totalAssets', 'currentCashAmount', 'currentBankAmount', 'totalReceivableAmount', 'totalPurchaseStock', 'totalPayableAmount', 'incomes', 'totalSellProfit', 'totalDamageAmount', 'totalReceivableAmountSeparator', 'totalPayableAmountSeparator','totalAsstAmountSeparator','totalOwnerAmount', 'totalExpenseAmount','totalPurchaseIncreaseAmount','totalSellwithoutProfitAmount','totalEffectiveIncomeAmount','empAdvanceSalary'));
    }
    
    public function journalHeadIndex()
    {
        $pageTitle = "Journal Head List";
        $bsType = BsType::latest()->paginate(gs()->pagination);
        return view('bs-account.journal-head-index', compact('bsType', 'pageTitle'));
    }

    public function journalHeadStore(Request $request, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('bs_types', 'name')->ignore($id),
            ],
            'main_type' => 'required',
        ]);

        if ($id > 0) {
            $journalHeadData = BsType::find($id); 
            $message = 'Journal Head has been updated successfully';
            $givenStatus = isset($request->editsubcatstatus) ? 1 : 0;
        } else {
            $journalHeadData = new BsType();
            $message = 'Journal Head added successfully.';
            $givenStatus = isset($request->status) ? 1 : 0;
        }
            $journalHeadData->name = $request->name;
            $journalHeadData->main_type = $request->main_type;
            $journalHeadData->status = $givenStatus;
            $journalHeadData->save();
            return back()->with('success', $message);
    }


    public function journalCreate()
    {
        $bsType = BsType::all();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('bs-account.journal-create', compact('bsType', 'banks'));
    }

    // public function journalStore(Request $request)
    // {
    //     dd($request->all());
    //     $date = $request->entry_date;
    //     $description = $request->description;
    //     $type1 = $request->type_1;
    //     $type2 = $request->type_2;
    //     $amount = $request->amount;

    //     // $findType1 = BsType::where('main_type', $type1)->first();
    //     // $findType2 = BsType::where('main_type', $type2)->first();
    //     // dd($findType1->type, $findType2->type);

    //         $payment_method = ($type1 == 1 || $type2 == 1) ? 1 : 2;

    //         if( (($type1 == 1 || $type2 == 1) || ($type1 == 2 || $type2 == 2)) && ($type1 == 3 || $type2 == 3) ){
    //             $accArr = [
    //                 'type'             => 0,
    //                 'payment_method'   => $payment_method,
    //                 'debit'            => $amount,
    //                 'description'      => $description,
    //             ];
    //             $account = updateAcc($accArr, 'NTR', 0, 0);

    //             if($payment_method == 2){
    //                 $bankTrArr = [
    //                     'account_id'       => $account->id,
    //                     'withdrawer_name'   => $request->withdrawer_name,
    //                     'debit'            => $amount,
    //                     'description'      => $description,
    //                     'bank_id'          => $request->bank_id,
    //                     'check_no'         => $request->check_no,
    //                 ];

    //                 bankTr($account, $bankTrArr);   
    //             }

    //             $assets = new Asset();
    //             $assets->asset_head_id = 1;
    //             $assets->purchase_date = $date;
    //             $assets->description = $description;
    //             $assets->purchase_price = $amount;
    //             $assets->entry_by = auth()->user()->id;
    //             $assets->save();
                
    //             $notify[] = ['success', 'Journal entry saved successfully.'];   
    //             return back()->withNotify($notify);

    //         }else if ( (($type1 == 1 || $type2 == 1) || ($type1 == 2 || $type2 == 2)) && ($type1 == 4 || $type2 == 4) ) {

    //             $accArr = [
    //                 'type'             => 0,
    //                 'payment_method'   => $payment_method,
    //                 'debit'            => $amount,
    //                 'description'      => $description,
    //             ];
    //             $account = updateAcc($accArr, 'NTR', 0, 0);

    //             if($payment_method == 2){
    //                 $bankTrArr = [
    //                     'account_id'       => $account->id,
    //                     'withdrawer_name'  => $request->withdrawer_name,
    //                     'debit'            => $amount,
    //                     'description'      => $description,
    //                     'bank_id'          => $request->bank_id,
    //                     'check_no'         => $request->check_no,
    //                 ];

    //                 bankTr($account, $bankTrArr);   
    //             }   

    //             $receivables = new Receivable();
    //             $receivables->receivable_head_id = 3;
    //             $receivables->receivable_amount = $amount;
    //             $receivables->save();

    //             $notify[] = ['success', 'Journal entry saved successfully.'];   
    //             return back()->withNotify($notify);

    //         }else if( (($type1 == 1 || $type2 == 1) || ($type1 == 2 || $type2 == 2)) && ($type1 == 5 || $type2 == 5) ){
    //             $accArr = [
    //                 'type'             => 0,
    //                 'payment_method'   => $payment_method,
    //                 'credit'           => $amount,
    //                 'description'      => $description,
    //             ];  
    //             $account = updateAcc($accArr, 'NTR', 0, 0);

    //             if($payment_method == 2){
    //                 $bankTrArr = [
    //                     'account_id'        => $account->id,
    //                     'depositor_name'    => $request->withdrawer_name,
    //                     'credit'            => $amount,
    //                     'description'       => $description,
    //                     'bank_id'           => $request->bank_id,
    //                     'check_no'          => $request->check_no,
    //                 ]; 

    //                 bankTr($account, $bankTrArr);   
    //             }

    //             $payables = new Payable();
    //             $payables->payables_head_id = 2;
    //             $payables->payable_amount = $amount;
    //             $payables->save();

    //             $notify[] = ['success', 'Journal entry saved successfully.'];   
    //             return back()->withNotify($notify);
                
    //         }else if( (($type1 == 1 || $type2 == 1) || ($type1 == 2 || $type2 == 2)) && ($type1 == 6 || $type2 == 6) ){
    //             $accArr = [
    //                 'type'             => 0,
    //                 'payment_method'   => $payment_method,
    //                 'credit'            => $amount,
    //                 'description'      => $description,
    //             ];  
    //             $account = updateAcc($accArr, 'NTR', 0, 0);

    //             if($payment_method == 2){
    //                 $bankTrArr = [
    //                     'account_id'        => $account->id,
    //                     'depositor_name'    => $request->withdrawer_name,
    //                     'credit'            => $amount,
    //                     'description'       => $description,
    //                     'bank_id'           => $request->bank_id,
    //                     'check_no'          => $request->check_no,
    //                 ]; 

    //                 bankTr($account, $bankTrArr);   
    //             }
                
    //             $notify[] = ['success', 'Journal entry saved successfully.'];   
    //             return back()->withNotify($notify);

    //         }else if( (($type1 == 1 || $type2 == 1) || ($type1 == 2 || $type2 == 2)) && ($type1 == 7 || $type2 == 7) ){
    //             $findType = BsType::where('main_type', 7)->first();
    //             $type = $findType->type;
    //             // dd($type);
    //             $accArr = [
    //                 'type'             => $type,
    //                 'payment_method'   => $payment_method,
    //                 'debit'            => $amount,
    //                 'description'      => $description,
    //             ];  
    //             $account = updateAcc($accArr, 'NTR', 0, $type);
    
    //             if($payment_method == 2){
    //                 $bankTrArr = [
    //                     'account_id'        => $account->id,
    //                     'withdrawer_name'   => $request->withdrawer_name,
    //                     'debit'             => $amount,
    //                     'description'       => $description,
    //                     'bank_id'           => $request->bank_id,
    //                     'check_no'          => $request->check_no,
    //                 ]; 
    
    //                 bankTr($account, $bankTrArr);   
    //             }                   
    
    //             $notify[] = ['success', 'Journal entry saved successfully.'];   
    //             return back()->withNotify($notify);
    //         }
            
    //         else if( (($type1 == 1 || $type2 == 1) || ($type1 == 2 || $type2 == 2)) ){      

    //             dd("working");
    //         }

    //         else{  
               
    //             $notify[] = ['error', 'Kindly select a valid type.'];   
    //             return back()->withNotify($notify);

    //         }
    // }


    // public function journalStore(Request $request)
    // {
    //     // dd($request->all());

    //     $fieldOne = $request->accounts[0]['type_1'];
    //     $fieldTwo = $request->accounts[1]['type_2'];

    //     // dd($fieldOne, $fieldTwo);

    //     if(($fieldOne == 1 || $fieldTwo == 1) && ($fieldOne == 7 || $fieldTwo == 7)){

    //         if(($fieldOne || $fieldTwo == 7) && $request->accounts[0]['debit'] != 0){
            
    //             dd("yes");

    //         }else if(($fieldOne || $fieldTwo == 1) && $request->accounts[0]['credit'] != 0){
    //             dd("no");
    //         }
            

    //     }else{
    //         dd("nai kicu");
    //     }
    
    // }


    // public function journalStore(Request $request)
    // {
    //     // dd($request->all());
    //     $amount = $request->amount;
    //     $description = $request->description;
    //     $type1 = $request->type_one;
    //     $type2 = $request->type_tow;

    //     // dd($type1, $type2);

    //     if ($type1 == 1 || $type2 == 1) {
    //         $payment_method = 1;
    //     } elseif ($type1 == 2 || $type2 == 2) {
    //         $payment_method = 2;
    //     } else {
    //         $payment_method = null;
    //     }

    //     $fieldOne = $request->accounts[0]['type_1'];
    //     $fieldTwo = $request->accounts[1]['type_2'];

    //     // dd($payment_method); 

    //     // dd($fieldOne, $fieldTwo);
    //     if ((($fieldOne == 1 || $fieldTwo == 1) || ($fieldOne == 2 || $fieldTwo == 2)) && ($fieldOne == 7 || $fieldTwo == 7)) {

    //         if ((($fieldOne == 7) && $request->accounts[0]['debit'] != 0) || (($fieldOne == 1 || $fieldOne == 2) && $request->accounts[0]['credit'] != 0)) {
    //         // ======================
    //             // Account 0 er data
    //             $debit_0 = $request->accounts[0]['debit'];
    //             $credit_0 = $request->accounts[0]['credit'];
    //             $amount_0 = $debit_0 != 0 ? $debit_0 : $credit_0;

    //             $debit_or_credit = $debit_0 != 0 ? 'debit' : 'credit';
    //             // dd($fieldOne, $debit_or_credit);

    //             $journalEntryData = new JournalEntry();
    //             $journalEntryData->type = $fieldOne;
    //             $journalEntryData->debit_or_credit = $debit_or_credit;
    //             $journalEntryData->description = $description;
    //             $journalEntryData->entry_date = $request->entry_date;
    //             $journalEntryData->amount = $amount_0;
    //             $journalEntryData->entry_by = auth()->user()->id;           
    //             $journalEntryData->save();

    //         // ======================
    //             $accArr = [
    //                 'type'             => 9,
    //                 'payment_method'   => $payment_method,
    //                 'debit'            => $amount,
    //                 'description'      => $description,
    //             ];
    //             $account = updateAcc($accArr, 'NTR', 0, 9);

    //             if ($payment_method == 2) {
    //                 $bankTrArr = [
    //                     'account_id'        => $account->id,
    //                     'withdrawer_name'   => $request->withdrawer_name,
    //                     'debit'             => $amount,
    //                     'description'       => $description,
    //                     'bank_id'           => $request->bank_id,
    //                     'check_no'          => $request->check_no,
    //                 ];

    //                 bankTr($account, $bankTrArr);
    //             }
    //             $notify[] = ['success', 'Journal entry saved successfully.'];
    //             return to_route('bs.account.journal.index')->withNotify($notify);

    //         } else {

    //             $debit_0 = $request->accounts[0]['debit'];
    //             $credit_0 = $request->accounts[0]['credit'];
    //             $amount_0 = $debit_0 != 0 ? $debit_0 : $credit_0;
    //             $debit_or_credit = $debit_0 != 0 ? 'debit' : 'credit';
    //             // dd($fieldOne, $debit_or_credit);

    //             $journalEntryData = new JournalEntry();
    //             $journalEntryData->type = $fieldOne;
    //             $journalEntryData->debit_or_credit = $debit_or_credit;
    //             $journalEntryData->description = $description;
    //             $journalEntryData->entry_date = $request->entry_date;
    //             $journalEntryData->amount = $amount_0;
    //             $journalEntryData->entry_by = auth()->user()->id;
    //             $journalEntryData->save();

    //             $accArr = [
    //                 'type'             => 9,
    //                 'payment_method'   => $payment_method,
    //                 'credit'            => $amount,
    //                 'description'      => $description,
    //             ];
    //             $account = updateAcc($accArr, 'NTR', 0, 9);

    //             if ($payment_method == 2) {
    //                 $bankTrArr = [
    //                     'account_id'        => $account->id,
    //                     'withdrawer_name'   => $request->withdrawer_name,
    //                     'debit'             => $amount,
    //                     'description'       => $description,
    //                     'bank_id'           => $request->bank_id,
    //                     'check_no'          => $request->check_no,
    //                 ];

    //                 bankTr($account, $bankTrArr);
    //             }

    //             $notify[] = ['success', 'Journal entry saved successfully.'];
    //             return to_route('bs.account.journal.index')->withNotify($notify);
    //         }
    //     // }else if ((($fieldOne == 1 || $fieldTwo == 1) || ($fieldOne == 2 || $fieldTwo == 2)) && ($fieldOne == 1 || $fieldTwo == 1)) {
    //      }else if ((($fieldOne == 1 || $fieldTwo == 1) && ($fieldOne == 2 || $fieldTwo == 2))) {
    //         // dd("working");

    //         $debit_0 = $request->accounts[0]['debit'];
    //         $credit_0 = $request->accounts[0]['credit'];
    //         $amount_0 = $debit_0 != 0 ? $debit_0 : $credit_0;
    //         $debit_or_credit = $debit_0 != 0 ? 'debit' : 'credit';

    //         $journalEntryData = new JournalEntry();
    //         $journalEntryData->type = $fieldOne;
    //         $journalEntryData->debit_or_credit = $debit_or_credit;
    //         $journalEntryData->description = $description;
    //         $journalEntryData->entry_date = $request->entry_date;
    //         $journalEntryData->amount = $amount_0;
    //         $journalEntryData->entry_by = auth()->user()->id;
    //         $journalEntryData->save();

    //         if ($debit_or_credit == 'credit') {
    //             $accArr = [
    //                 'type'           => 3,
    //                 'payment_method' => 1,
    //                 'debit'          => $amount_0,
    //                 'description'    => $description,
    //             ];
    //             $account = updateAcc($accArr, 'NTR', 0, 3);

    //             $accArr = [
    //                 'type'           => 3,
    //                 'payment_method' => 2,
    //                 'credit'         => $amount_0,
    //                 'description'    => $description,
    //             ];
    //             $account = updateAcc($accArr, 'NTR', 0, 3);
    //         } else {
    //             $accArr = [
    //                 'type'           => 4,
    //                 'payment_method' => 2,
    //                 'debit'          => $amount_0,
    //                 'description'    => $description,
    //             ];
    //             $account = updateAcc($accArr, 'NTR', 0, 4);

    //             $accArr = [
    //                 'type'           => 4,
    //                 'payment_method' => 1,
    //                 'credit'         => $amount_0,
    //                 'description'    => $description,
    //             ];
    //             $account = updateAcc($accArr, 'NTR', 0, 4);
    //         }

    //         $bankTrArr = [
    //             'account_id'      => $account->id,
    //             $debit_or_credit == 'debit' ? 'withdrawer_name' : 'depositor_name' => $request->withdrawer_name,
    //             $debit_or_credit  => $amount_0,
    //             'description'     => $description,
    //             'bank_id'         => $request->bank_id,
    //             'check_no'        => $request->check_no,
    //         ];

    //         bankTr($account, $bankTrArr);

    //         $notify[] = ['success', 'Journal entry saved successfully.'];
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }

        
    //     else {
    //         dd("nai kicu");
    //     }
    // }


    // public function journalStore(Request $request)
    // {
    //     // dd($request->all());
    //     // $amount = $request->amount;
    //     // $description = $request->description;
    //     // $type1 = $request->type_one;
    //     // $type2 = $request->type_tow;

    //     // // dd($type1, $type2);

    //     // if ($type1 == 1 || $type2 == 1) {
    //     //     $payment_method = 1;
    //     // } elseif ($type1 == 2 || $type2 == 2) {
    //     //     $payment_method = 2;
    //     // } else {
    //     //     $payment_method = null;
    //     // }

    //     // =================== insert journal ====
    //     // $input = $request->all();
    //     // dd($request->accounts);
    //     $randomCode = strtoupper(Str::random(6));
    //     foreach ($request->accounts as $acc) {
    //         $typeId = $acc['type_1'] ?? $acc['type_2'] ?? null;

    //         if (!$typeId) {
    //             continue; 
    //         }

    //         $bsType = BsType::find($typeId);
    //         if (!$bsType) {
    //             continue; 
    //         }

    //         $amount = $acc['debit'] != 0 ? $acc['debit'] : $acc['credit'];
    //         $debit_or_credit = $acc['debit'] != 0 ? 'debit' : 'credit';

    //         $journalEntry = new JournalEntry();
    //         $journalEntry->name = $bsType->name; 
    //         $journalEntry->type = $bsType->main_type; 
    //         $journalEntry->debit_or_credit = $debit_or_credit;
    //         $journalEntry->description = $request->description;
    //         $journalEntry->entry_date = $request->entry_date;
    //         $journalEntry->amount = $amount;
    //         $journalEntry->entry_by = auth()->user()->id;
    //         $journalEntry->Code = $randomCode;
    //         $journalEntry->save();
    //     }
        
    //     // =================== insert journal ====
    //     $fieldIdOne = $request->accounts[0]['type_1'];
    //     $fieldOne = BsType::find($fieldIdOne)->main_type;
    //     $fieldTypeOne = BsType::find($fieldIdOne)->type;

    //     $fieldIdTwo = $request->accounts[1]['type_2'];
    //     $fieldTwo = BsType::find($fieldIdTwo)->main_type;        
    //     $fieldTypeTwo = BsType::find($fieldIdTwo)->type;

    //     if($fieldOne == 7 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("expense debit cash credit");
    //         $payment_method = ($fieldTwo == 1) ? 1 : 2;

    //         $accArr = [
    //             'type'           => 9,
    //             'debit'          => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 9);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }
            
    //         $expense = new Expense();
    //         $expense->employee_id = auth()->user()->id;
    //         $expense->type = 1;
    //         $expense->debit_or_credit = 'debit';
    //         $expense->entry_date = $request->entry_date;
    //         $expense->description = $request->description;
    //         $expense->effective_amount = $request->accounts[0]['debit'];
    //         $expense->paid_amount = $request->accounts[0]['debit'];
    //         $expense->entry_by = auth()->user()->id;
    //         $expense->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];
    //         return to_route('bs.account.journal.index')->withNotify($notify);

    //     }
    //     else if($fieldOne == 7 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("expense credit cash debit");
    //         $payment_method = ($fieldTwo == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 9,
    //             'credit'         => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 9);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $expense = new Expense();
    //         $expense->employee_id = auth()->user()->id;
    //         $expense->type = 1;
    //         $expense->debit_or_credit = 'credit';
    //         $expense->entry_date = $request->entry_date;
    //         $expense->description = $request->description;
    //         $expense->effective_amount = $request->accounts[0]['credit'];
    //         $expense->paid_amount = $request->accounts[0]['credit'];
    //         $expense->entry_by = auth()->user()->id;
    //         $expense->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];
    //         return to_route('bs.account.journal.index')->withNotify($notify);

    //     }
    //     else if(($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 7 && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("cash debit expense credit");
    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 9,
    //             'credit'          => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 9);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $expense = new Expense();
    //         $expense->employee_id = auth()->user()->id;
    //         $expense->type = 1;
    //         $expense->debit_or_credit = 'credit';
    //         $expense->entry_date = $request->entry_date;
    //         $expense->description = $request->description;
    //         $expense->effective_amount = $request->accounts[0]['debit'];
    //         $expense->paid_amount = $request->accounts[0]['debit'];
    //         $expense->entry_by = auth()->user()->id;
    //         $expense->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);

    //     }
    //     else if(($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 7 && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("cash credit expense debit");
    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 9,
    //             'debit'          => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 9);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $expense = new Expense();
    //         $expense->employee_id = auth()->user()->id;
    //         $expense->type = 1;
    //         $expense->debit_or_credit = 'debit';
    //         $expense->entry_date = $request->entry_date;
    //         $expense->description = $request->description;
    //         $expense->effective_amount = $request->accounts[0]['credit'];
    //         $expense->paid_amount = $request->accounts[0]['credit'];
    //         $expense->entry_by = auth()->user()->id;
    //         $expense->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);

    //     }
    //     else if(($fieldOne == 1 && $fieldTwo == 2) && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("cash debit bank credit 1");+
    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 4,
    //             'credit'         => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];
    //         $account = updateAcc($accArr, 'NTR', 0, 4);

    //         $accArr = [
    //             'type'           => 4,
    //             'debit'          => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method == 1 ? 2 : 1,
    //         ];
    //         $account_2 = updateAcc($accArr, 'NTR', 0, 4);

    //         $bankTrArr = [
    //             'account_id'      => $account_2->id,
    //             'withdrawer_name' => $request->withdrawer_name,
    //             'debit'           => $request->accounts[0]['debit'],
    //             'description'     => $request->description,
    //             'bank_id'         => $request->bank_id,
    //             'check_no'        => $request->check_no,
    //         ];

    //         bankTr($account_2, $bankTrArr);

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);

    //     }
    //     else if(($fieldOne == 1 && $fieldTwo == 2) && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("cash credit bank debit 2" );
    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 3,
    //             'debit'          => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];
    //         $account = updateAcc($accArr, 'NTR', 0, 3);

    //         $accArr = [
    //             'type'           => 3,
    //             'credit'         => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method == 1 ? 2 : 1,
    //         ];
    //         $account_2 = updateAcc($accArr, 'NTR', 0, 3);

    //         $bankTrArr = [
    //             'account_id'      => $account_2->id,
    //             'depositor_name'  => $request->withdrawer_name,
    //             'credit'          => $request->accounts[0]['credit'],
    //             'description'     => $request->description,
    //             'bank_id'         => $request->bank_id,
    //             'check_no'        => $request->check_no,
    //         ];

    //         bankTr($account_2, $bankTrArr);

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);

    //     }
    //     else if(($fieldOne == 2 && $fieldTwo == 1) && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("bank debit cash credit 3");
    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArrOne = [
    //             'type'           => 3,
    //             'credit'         => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];
    //         $account = updateAcc($accArrOne, 'NTR', 0, 3);

    //         $accArrTwo = [
    //             'type'           => 3,
    //             'debit'         => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method == 2 ? 1 : 2,
    //         ];
    //         $account_2 = updateAcc($accArrTwo, 'NTR', 0, 3);

    //         $bankTrArr = [
    //             'account_id'      => $account->id,
    //             'depositor_name'  => $request->withdrawer_name,
    //             'credit'          => $request->accounts[0]['debit'],
    //             'description'     => $request->description,
    //             'bank_id'         => $request->bank_id,
    //             'check_no'        => $request->check_no,
    //         ];

    //         bankTr($account, $bankTrArr);

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);

    //     }
    //     else if(($fieldOne == 2 && $fieldTwo == 1) && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("bank credit cash debit 4");

    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArrOne = [
    //             'type'           => 4,
    //             'debit'         => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];
    //         $account = updateAcc($accArrOne, 'NTR', 0, 4);

    //         $accArrTwo = [
    //             'type'           => 4,
    //             'credit'         => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method == 2 ? 1 : 2,
    //         ];
    //         $account_2 = updateAcc($accArrTwo, 'NTR', 0, 4);

    //         $bankTrArr = [
    //             'account_id'      => $account->id,
    //             'withdrawer_name'  => $request->withdrawer_name,
    //             'debit'          => $request->accounts[0]['credit'],
    //             'description'     => $request->description,
    //             'bank_id'         => $request->bank_id,
    //             'check_no'        => $request->check_no,
    //         ];

    //         bankTr($account, $bankTrArr);

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if(($fieldOne == 3 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0))){
    //         // dd($fieldOne, $fieldTwo);
    //         // dd("asset debit cash credit");
    //         // dd($fieldTypeOne, $fieldTypeTwo);
    //         $assets = new Asset();
    //         $assets->asset_head_id = $fieldTypeOne;
    //         $assets->type = 1;
    //         $assets->purchase_date = $request->entry_date;
    //         $assets->description = $request->description;
    //         $assets->effective_amount = $request->accounts[0]['debit'];
    //         $assets->debit_or_credit = 'debit';
    //         $assets->entry_by = auth()->user()->id;
    //         $assets->save();

    //         $payment_method = ($fieldTwo == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 18,
    //             'debit'          => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 18);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }
            
    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if(($fieldOne == 3 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0))){
    //         // dd("asset credit cash debit");

    //         $assets = new Asset();
    //         $assets->asset_head_id = $fieldTypeOne;
    //         $assets->type = 1;
    //         $assets->purchase_date = $request->entry_date;
    //         $assets->description = $request->description;
    //         $assets->effective_amount = $request->accounts[0]['credit'];
    //         $assets->debit_or_credit = 'credit';
    //         $assets->entry_by = auth()->user()->id;
    //         $assets->save();

    //         $payment_method = ($fieldTwo == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 18,
    //             'credit'          => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 18);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'           => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }
            
    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);

    //     }
    //     else if((($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 3 && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0))){
    //         // dd("cash credit asset debit");

    //         $assets = new Asset();
    //         $assets->asset_head_id = $fieldTypeTwo;
    //         $assets->type = 1;
    //         $assets->purchase_date = $request->entry_date;
    //         $assets->description = $request->description;
    //         $assets->effective_amount = $request->accounts[0]['credit'];
    //         $assets->debit_or_credit = 'debit';
    //         $assets->entry_by = auth()->user()->id;
    //         $assets->save();

    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 18,
    //             'debit'          => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 18);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }
            
    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
        
    //     }
    //     else if((($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 3 && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0))){

    //         // dd("cash debit asset credit");
    //         $assets = new Asset();
    //         $assets->asset_head_id = $fieldTypeTwo;
    //         $assets->type = 1;
    //         $assets->purchase_date = $request->entry_date;
    //         $assets->description = $request->description;
    //         $assets->effective_amount = $request->accounts[0]['debit'];
    //         $assets->debit_or_credit = 'credit';
    //         $assets->entry_by = auth()->user()->id;
    //         $assets->save();

    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 18,
    //             'credit'         => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 18);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }
            
    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 6 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("invest credit cash debit 1");
    //         $investment = new Investment(); 
    //         $investment->effective_amount = $request->accounts[0]['credit'];
    //         $investment->entry_type = 1;
    //         $investment->debit_or_credit = 'credit';
    //         $investment->payment_method = ($fieldTwo == 1) ? 1 : 2;
    //         $investment->entry_date = $request->entry_date;
    //         $investment->entry_by = auth()->user()->id;
    //         $investment->save();

    //         $payment_method = ($fieldTwo == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 16,
    //             'credit'         => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 16);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 6 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("invest debit cash credit");

    //         $investment = new Investment(); 
    //         $investment->effective_amount = $request->accounts[0]['debit'];
    //         $investment->entry_type = 1;
    //         $investment->debit_or_credit = 'debit';
    //         $investment->payment_method = ($fieldTwo == 1) ? 1 : 2;
    //         $investment->entry_date = $request->entry_date;
    //         $investment->entry_by = auth()->user()->id;
    //         $investment->save();

    //         $payment_method = ($fieldTwo == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 16,
    //             'debit'          => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 16);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if(($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 6 && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){

    //         // dd('invest credit cash debit');
    //         $investment = new Investment(); 
    //         $investment->effective_amount = $request->accounts[0]['debit'];
    //         $investment->entry_type = 1;
    //         $investment->debit_or_credit = 'credit';
    //         $investment->payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $investment->entry_date = $request->entry_date;
    //         $investment->entry_by = auth()->user()->id;
    //         $investment->save();

    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArr = [ 
    //             'type'           => 16,
    //             'credit'         => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 16);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if(($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 6 && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd('invest debit cash credit');

    //         $investment = new Investment(); 
    //         $investment->effective_amount = $request->accounts[0]['credit'];
    //         $investment->entry_type = 1;
    //         $investment->debit_or_credit = 'debit';
    //         $investment->payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $investment->entry_date = $request->entry_date;
    //         $investment->entry_by = auth()->user()->id;
    //         $investment->save();


    //         $payment_method = ($fieldOne == 1) ? 1 : 2;
    //         $accArr = [
    //             'type'           => 16,
    //             'debit'          => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => $payment_method,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 16);

    //         if ($payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 5 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("advance credit cash debit 1");
    //         $payable = new Payable();
    //         $payable->payables_head_id = $fieldTypeOne;
    //         $payable->description = $request->description;
    //         $payable->effective_amount = $request->accounts[0]['credit'];
    //         $payable->debit_or_credit = 'credit';
    //         $payable->entry_type = 1;
    //         $payable->save();
            
    //         $accArr = [
    //             'type'           => 5,
    //             'credit'         => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => ($fieldTwo == 1) ? 1 : 2,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 5);

    //         if ($account->payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 5 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("advance debit cash credit 2");
    //         $payable = new Payable();
    //         $payable->payables_head_id = $fieldTypeOne;
    //         $payable->description = $request->description;
    //         $payable->effective_amount = $request->accounts[0]['debit'];
    //         $payable->debit_or_credit = 'debit';
    //         $payable->entry_type = 1;
    //         $payable->save();

    //         $accArr = [
    //             'type'           => 5,
    //             'debit'          => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => ($fieldTwo == 1) ? 1 : 2,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 5);

    //         if ($account->payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if(($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 5 && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("advance credit cash debit 3");
    //         $payable = new Payable();
    //         $payable->payables_head_id = $fieldTypeTwo;
    //         $payable->description = $request->description;
    //         $payable->effective_amount = $request->accounts[0]['debit'];
    //         $payable->debit_or_credit = 'credit';
    //         $payable->entry_type = 1;
    //         $payable->save();

    //         $accArr = [
    //             'type'           => 5,
    //             'credit'         => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => ($fieldOne == 1) ? 1 : 2,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 5);

    //         if ($account->payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if(($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 5 && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("advance debit cash credit 4");
    //         $payable = new Payable();
    //         $payable->payables_head_id = $fieldTypeTwo;
    //         $payable->description = $request->description;
    //         $payable->effective_amount = $request->accounts[0]['credit'];
    //         $payable->debit_or_credit = 'debit';
    //         $payable->entry_type = 1;
    //         $payable->save();

    //         $accArr = [
    //             'type'           => 5,
    //             'debit'          => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => ($fieldOne == 1) ? 1 : 2,
    //         ];

    //         $account = updateAcc($accArr, 'NTR', 0, 5);

    //         if ($account->payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 5 && $fieldTwo == 8  && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("Supplier Due credit Purchase A/C debit 1");
    //         $payable = new Payable();
    //         $payable->payables_head_id = $fieldTypeOne;
    //         $payable->description = $request->description;
    //         $payable->effective_amount = $request->accounts[0]['credit'];
    //         $payable->debit_or_credit = 'credit';
    //         $payable->entry_type = 1;
    //         $payable->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 5 && $fieldTwo == 8  && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("Supplier Due debit Purchase A/C credit 2");
    //         $payable = new Payable();
    //         $payable->payables_head_id = $fieldTypeOne;
    //         $payable->description = $request->description;
    //         $payable->effective_amount = $request->accounts[0]['debit'];
    //         $payable->debit_or_credit = 'debit';
    //         $payable->entry_type = 1;
    //         $payable->save();
            
    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 8 && $fieldTwo == 5  && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("Supplier Due credit Purchase A/C debit 3");
    //         $payable = new Payable();
    //         $payable->payables_head_id = $fieldTypeTwo;
    //         $payable->description = $request->description;
    //         $payable->effective_amount = $request->accounts[0]['debit'];
    //         $payable->debit_or_credit = 'credit';
    //         $payable->entry_type = 1;
    //         $payable->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 8 && $fieldTwo == 5  && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("Supplier Due debit Purchase A/C credit 4");
    //         $payable = new Payable();
    //         $payable->payables_head_id = $fieldTypeTwo;
    //         $payable->description = $request->description;
    //         $payable->effective_amount = $request->accounts[0]['credit'];
    //         $payable->debit_or_credit = 'debit';
    //         $payable->entry_type = 1;
    //         $payable->save();
            
    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 4 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("advance debit cash credit 1");
    //         $receivable = new Receivable();
    //         $receivable->receivable_head_id = $fieldTypeOne;
    //         $receivable->description = $request->description;
    //         $receivable->effective_amount = $request->accounts[0]['debit']; 
    //         $receivable->debit_or_credit = 'debit';
    //         $receivable->entry_type = 1;
    //         $receivable->save();

    //         $accArr = [
    //             'type'           => 6,
    //             'debit'          => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => ($fieldTwo == 1) ? 1 : 2,
    //         ];
    //         $account = updateAcc($accArr, 'NTR', 0, 6);

    //         if ($account->payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if ($fieldOne == 4 && ($fieldTwo == 1 || $fieldTwo == 2) && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)) {
    //         // dd("advance credit cash debit 2");

    //         $receivable = new Receivable();
    //         $receivable->receivable_head_id = $fieldTypeOne;
    //         $receivable->description = $request->description;
    //         $receivable->effective_amount = $request->accounts[0]['credit'];
    //         $receivable->debit_or_credit = 'credit';
    //         $receivable->entry_type = 1;
    //         $receivable->save();

    //         $accArr = [
    //             'type'           => 6,
    //             'credit'         => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => ($fieldTwo == 1) ? 1 : 2,
    //         ];
    //         $account = updateAcc($accArr, 'NTR', 0, 6);

    //         if ($account->payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if(($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 4 && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("advance debit cash credit 3");
    //         $receivable = new Receivable();
    //         $receivable->receivable_head_id = $fieldTypeTwo;
    //         $receivable->description = $request->description;
    //         $receivable->effective_amount = $request->accounts[0]['credit'];
    //         $receivable->debit_or_credit = 'debit';
    //         $receivable->entry_type = 1;
    //         $receivable->save();

    //         $accArr = [
    //             'type'           => 6,
    //             'debit'         => $request->accounts[0]['credit'],
    //             'description'    => $request->description,
    //             'payment_method' => ($fieldOne == 1) ? 1 : 2,
    //         ];
    //         $account = updateAcc($accArr, 'NTR', 0, 6);

    //         if ($account->payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'withdrawer_name' => $request->withdrawer_name,
    //                 'debit'           => $request->accounts[0]['credit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if(($fieldOne == 1 || $fieldOne == 2) && $fieldTwo == 4 && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("advance credit cash debit 4");
    //         $receivable = new Receivable();
    //         $receivable->receivable_head_id = $fieldTypeTwo;
    //         $receivable->description = $request->description;
    //         $receivable->effective_amount = $request->accounts[0]['debit'];
    //         $receivable->debit_or_credit = 'credit';
    //         $receivable->entry_type = 1;
    //         $receivable->save();

    //         $accArr = [
    //             'type'           => 6,
    //             'credit'         => $request->accounts[0]['debit'],
    //             'description'    => $request->description,
    //             'payment_method' => ($fieldOne == 1) ? 1 : 2,
    //         ];
    //         $account = updateAcc($accArr, 'NTR', 0, 6);

    //         if ($account->payment_method == 2) {
    //             $bankTrArr = [
    //                 'account_id'      => $account->id,
    //                 'depositor_name'  => $request->withdrawer_name,
    //                 'credit'          => $request->accounts[0]['debit'],
    //                 'description'     => $request->description,
    //                 'bank_id'         => $request->bank_id,
    //                 'check_no'        => $request->check_no,
    //             ];

    //             bankTr($account, $bankTrArr);
    //         }   

    //         $notify[] = ['success', 'Journal entry saved successfully.'];       
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 4 && $fieldTwo == 9 && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("sell due debit sell A/C credit 1");
    //         $receivable = new Receivable();
    //         $receivable->receivable_head_id = $fieldTypeOne;
    //         $receivable->description = $request->description;   
    //         $receivable->effective_amount = $request->accounts[0]['debit'];
    //         $receivable->debit_or_credit = 'debit';
    //         $receivable->entry_type = 1;
    //         $receivable->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 4 && $fieldTwo == 9 && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("sell due credit sell A/C debit 2");
    //         $receivable = new Receivable();
    //         $receivable->receivable_head_id = $fieldTypeOne;
    //         $receivable->description = $request->description;
    //         $receivable->effective_amount = $request->accounts[0]['credit'];
    //         $receivable->debit_or_credit = 'credit';
    //         $receivable->entry_type = 1;
    //         $receivable->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 9  && $fieldTwo == 4 && ($request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0)){
    //         // dd("sell due debit sell A/C credit 3");
    //         $receivable = new Receivable();
    //         $receivable->receivable_head_id = $fieldTypeTwo;
    //         $receivable->description = $request->description;   
    //         $receivable->effective_amount = $request->accounts[0]['credit'];
    //         $receivable->debit_or_credit = 'debit';
    //         $receivable->entry_type = 1;
    //         $receivable->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else if($fieldOne == 9 && $fieldTwo == 4 && ($request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0)){
    //         // dd("sell due credit sell A/C debit 4");
    //         $receivable = new Receivable();
    //         $receivable->receivable_head_id = $fieldTypeTwo;
    //         $receivable->description = $request->description;
    //         $receivable->effective_amount = $request->accounts[0]['debit'];
    //         $receivable->debit_or_credit = 'credit';
    //         $receivable->entry_type = 1;
    //         $receivable->save();

    //         $notify[] = ['success', 'Journal entry saved successfully.'];   
    //         return to_route('bs.account.journal.index')->withNotify($notify);
    //     }
    //     else{
            
    //         $notify[] = ['error', 'Kindly select a valid type.'];   
    //         return back()->withNotify($notify);
    //     }

    // }


    // public function journalStore(Request $request)
    // {
    //     $request->validate([
    //         'entry_date' => 'required|date',
    //         // 'code' => 'required|string|unique:journal_entries,code',
    //         'description' => 'required|string',
    //         'accounts' => 'required|array|min:1',
    //         'accounts.*.account_type' => 'required|exists:bs_types,id',
    //         'accounts.*.debit' => 'nullable|numeric|min:0',
    //         'accounts.*.credit' => 'nullable|numeric|min:0',
    //     ]);

    //     $journalEntryData = new JournalEntry();
    //     // $journalEntryData->code = $request->code;
    //     $journalEntryData->description = $request->description;
    //     $journalEntryData->entry_date = $request->entry_date;
    //     $journalEntryData->entry_by = auth()->user()->id;
    //     $journalEntryData->amount = 0;
    //     $journalEntryData->save();

    //     $totalCredit = 0;

    //     foreach ($request->accounts as $acc) {

    //         $bsType = BsType::find($acc['account_type']);

    //         // if (!$bsType) {
    //         //     return back()->with('error', 'Invalid account type selected.');
    //         // }

    //         $totalCredit += $acc['credit'] ?? 0;

    //         $data = new BsAccount();
    //         $data->journal_entry_id = $journalEntryData->id;
    //         $data->account_type = $bsType->type;
    //         $data->account_sub_type = $bsType->id;
    //         $data->debit = $acc['debit'] ?? 0;
    //         $data->credit = $acc['credit'] ?? 0;
    //         $data->save();
    //     }

    //     $journalEntryData->amount = $totalCredit;
    //     $journalEntryData->save();

    //     $notify[] = ['success', 'Journal entry saved successfully.'];
    //     return to_route('bs.account.journal.index')->withNotify($notify);
    // }

    // public function journalStore(Request $request)
    // {
    //     // dd($request->all());
    //     $request->validate([
    //         'entry_date' => 'required|date',
    //         // 'code' => 'required|string|unique:journal_entries,code',
    //         'description' => 'required|string',
    //         'accounts' => 'required|array|min:1',
    //         'accounts.*.account_type' => 'required|exists:bs_types,id',
    //         'accounts.*.debit' => 'nullable|numeric|min:0',
    //         'accounts.*.credit' => 'nullable|numeric|min:0',
    //     ]);

    //     // $journalEntryData = new JournalEntry();
    //     // // $journalEntryData->code = $request->code;
    //     // $journalEntryData->description = $request->description;
    //     // $journalEntryData->entry_date = $request->entry_date;
    //     // $journalEntryData->entry_by = auth()->user()->id;
    //     // $journalEntryData->amount = 0;
    //     // $journalEntryData->save();

    //     $totalCredit = 0;
    //     $typeData = [];

    //     foreach ($request->accounts as $acc) {

    //         $bsType = BsType::find($acc['account_type']);
    //         // dd($bsType->name);

    //         // if (!$bsType) {
    //         //     return back()->with('error', 'Invalid account type selected.');
    //         // }

    //         // $totalCredit += $acc['credit'] ?? 0;

    //         // $data = new BsAccount();
    //         // $data->journal_entry_id = $journalEntryData->id;
    //         // $data->account_type = $bsType->type;
    //         // $data->account_sub_type = $bsType->id;
    //         // $data->debit = $acc['debit'] ?? 0;
    //         // $data->credit = $acc['credit'] ?? 0;
    //         // $data->save();

    //         $data[] = $bsType->type;
    //     }
    //     // dd($typeData);

    //     if($typeData['type'] == 0 && $typeData['type'] == 16){
    //         // dd($typeData);
    //         dd("ss");
    //     }


    //     $journalEntryData->amount = $totalCredit;
    //     $journalEntryData->save();

    //     $notify[] = ['success', 'Journal entry saved successfully.'];
    //     return to_route('bs.account.journal.index')->withNotify($notify);
    // }


    // public function journalStore(Request $request)
    // {
    //     dd($request->all());
    //     $request->validate([
    //         'entry_date' => 'required|date',
    //         // 'code' => 'required|string|unique:journal_entries,code',
    //         'description' => 'required|string',
    //         'accounts' => 'required|array|min:1',
    //         'accounts.*.account_type' => 'required|exists:bs_types,id',
    //         'accounts.*.debit' => 'nullable|numeric|min:0',
    //         'accounts.*.credit' => 'nullable|numeric|min:0',
    //     ]);
    
    //     $typeData = [];
    
    //     foreach ($request->accounts as $acc) {
    //         $bsType = BsType::find($acc['account_type']);
    //         if ($bsType) {
    //             $typeData[] = $bsType->type;
    //         }
    //     }
    
    //     if (in_array(0, $typeData) && in_array(16, $typeData)) {
    //         $accArr = [
    //             'type'             => 16,
    //             'credit'           => $request->accounts[0]['credit'] ?? 0,
    //             'description' => 'Investment of amount ' . showAmount($request->accounts[0]['credit'] ?? 0),
    //             'payment_method'   => 0,
    //         ];
    //         updateAcc($accArr, 'NTR', 0, 16);
    //     }

    //     $notify[] = ['success', 'Journal entry saved successfully.'];
    //     return to_route('bs.account.journal.index')->withNotify($notify);

    // }


    public function journalStore(Request $request)
    {
        // dd($request->all());
        // $amount = $request->amount;
        // $description = $request->description;
        // $type1 = $request->type_one;
        // $type2 = $request->type_tow;

        // // dd($type1, $type2);

        // if ($type1 == 1 || $type2 == 1) {
        //     $payment_method = 1;
        // } elseif ($type1 == 2 || $type2 == 2) {
        //     $payment_method = 2;
        // } else {
        //     $payment_method = null;
        // }

        // =================== insert journal ====
        // $input = $request->all();
        // dd($request->accounts);
        $randomCode = strtoupper(Str::random(6));
        foreach ($request->accounts as $acc) {
            $typeId = $acc['type_1'] ?? $acc['type_2'] ?? null;

            if (!$typeId) {
                continue; 
            }

            $bsType = BsType::find($typeId);
            if (!$bsType) {
                continue; 
            }

            $amount = $acc['debit'] != 0 ? $acc['debit'] : $acc['credit'];
            $debit_or_credit = $acc['debit'] != 0 ? 'debit' : 'credit';

            $journalEntry = new JournalEntry();
            $journalEntry->name = $bsType->name; 
            $journalEntry->type = $bsType->main_type; 
            $journalEntry->debit_or_credit = $debit_or_credit;
            $journalEntry->description = $request->description;
            $journalEntry->entry_date = $request->entry_date;
            $journalEntry->amount = $amount;
            $journalEntry->entry_by = auth()->user()->id;
            $journalEntry->Code = $randomCode;
            $journalEntry->save();
        }
        
        // =================== insert journal ====
        $fieldIdOne = $request->accounts[0]['type_1'];
        $fieldOne = BsType::find($fieldIdOne);

        $fieldIdTwo = $request->accounts[1]['type_2'];
        $fieldTwo = BsType::find($fieldIdTwo);

        // ===== Field One: Cash/Bank Credit =====
        if ($fieldOne->main_type == 1 && $fieldOne->sub_type == 1 && $request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0) {
            $payment_method = ($fieldOne->type == 1) ? 1 : 2;

            $accArr = [
                'type'           => 0,
                'debit'          => $request->accounts[0]['credit'],
                'entry_type'     => 1,
                'description'    => $request->description,
                'payment_method' => $payment_method,
            ];

            $account = updateAcc($accArr, 'NTR', 0, 0);

            if ($payment_method == 2) {
                $bankTrArr = [
                    'account_id'       => $account->id,
                    'withdrawer_name'  => $request->withdrawer_name,
                    'debit'            => $request->accounts[0]['credit'],
                    'description'      => $request->description,
                    'bank_id'          => $request->bank_id,
                    'check_no'         => $request->check_no,
                ];
                bankTr($account, $bankTrArr);
            }
        }

        // ===== Field Two: Cash/Bank Debit =====
        if ($fieldTwo->main_type == 1 && $fieldTwo->sub_type == 1 && $request->accounts[1]['debit'] != 0 && $request->accounts[0]['debit'] == 0) {
            $payment_method = ($fieldTwo->type == 1) ? 1 : 2;

            $accArr = [
                'type'           => 0,
                'credit'         => $request->accounts[1]['debit'],
                'entry_type'     => 1,
                'description'    => $request->description,
                'payment_method' => $payment_method,
            ];

            $account = updateAcc($accArr, 'NTR', 0, 0);

            if ($payment_method == 2) {
                $bankTrArr = [
                    'account_id'      => $account->id,
                    'depositor_name'  => $request->withdrawer_name,
                    'credit'          => $request->accounts[1]['debit'],
                    'description'     => $request->description,
                    'bank_id'         => $request->bank_id,
                    'check_no'        => $request->check_no,
                ];
                bankTr($account, $bankTrArr);
            }
        }

        // ===== Field One: Cash/Bank Debit =====
        if ($fieldOne->main_type == 1 && $fieldOne->sub_type == 1 && $request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0) {
            $payment_method = ($fieldOne->type == 1) ? 1 : 2;

            $accArr = [
                'type'           => 0,
                'credit'         => $request->accounts[0]['debit'],
                'entry_type'     => 1,
                'description'    => $request->description,
                'payment_method' => $payment_method,
            ];

            $account = updateAcc($accArr, 'NTR', 0, 0);

            if ($payment_method == 2) {
                $bankTrArr = [
                    'account_id'      => $account->id,
                    'depositor_name'  => $request->withdrawer_name,
                    'credit'          => $request->accounts[0]['debit'],
                    'description'     => $request->description,
                    'bank_id'         => $request->bank_id,
                    'check_no'        => $request->check_no,
                ];
                bankTr($account, $bankTrArr);
            }
        }

        // ===== Field Two: Cash/Bank Credit =====
        if ($fieldTwo->main_type == 1 && $fieldTwo->sub_type == 1 && $request->accounts[1]['credit'] != 0 && $request->accounts[0]['credit'] == 0) {
            $payment_method = ($fieldTwo->type == 1) ? 1 : 2;

            $accArr = [
                'type'           => 0,
                'debit'          => $request->accounts[1]['credit'],
                'entry_type'     => 1,
                'description'    => $request->description,
                'payment_method' => $payment_method,
            ];

            $account = updateAcc($accArr, 'NTR', 0, 0);

            if ($payment_method == 2) {
                $bankTrArr = [
                    'account_id'      => $account->id,
                    'withdrawer_name' => $request->withdrawer_name,
                    'debit'           => $request->accounts[1]['credit'],
                    'description'     => $request->description,
                    'bank_id'         => $request->bank_id,
                    'check_no'        => $request->check_no,
                ];
                bankTr($account, $bankTrArr);
            }
        }

        // ===== Field One: Owner's Credit =====
        if ($fieldOne->main_type == 3 && $fieldOne->sub_type == 1 && $request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0){
            // dd("working");
            $owners = new Owner();
            $owners->entry_type = 1;
            $owners->debit_or_credit = 'credit';
            $owners->amount = $request->accounts[0]['credit'];
            $owners->description = $request->description;
            $owners->save();
        }

        // ===== Field One: Owner's Debit =====
        if($fieldOne->main_type == 3 && $fieldOne->sub_type == 1 && $request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0){
            // dd("working");
            $owners = new Owner();
            $owners->entry_type = 1;
            $owners->debit_or_credit = 'debit';
            $owners->amount = $request->accounts[0]['debit'];
            $owners->description = $request->description;
            $owners->save();
        }

        // ===== Field Two: Owner's credit =====
        if($fieldTwo->main_type == 3 && $fieldTwo->sub_type == 1 && $request->accounts[1]['credit'] != 0 && $request->accounts[0]['credit'] == 0){
            // dd("working");
            $owners = new Owner();
            $owners->entry_type = 1;
            $owners->debit_or_credit = 'credit';
            $owners->amount = $request->accounts[1]['credit'];
            $owners->description = $request->description;
            $owners->save();
        }

        // ===== Field Two: Owner's debit =====
        if($fieldTwo->main_type == 3 && $fieldTwo->sub_type == 1 && $request->accounts[1]['debit'] != 0 && $request->accounts[0]['debit'] == 0){
            // dd("working");
            $owners = new Owner();
            $owners->entry_type = 1;
            $owners->debit_or_credit = 'debit';
            $owners->amount = $request->accounts[1]['debit'];
            $owners->description = $request->description;
            $owners->save();
        }

        // ===== Field One: Expense Debit =====
        if($fieldOne->main_type == 4 && $request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0){
            // dd("working");
            $expense = new Expense();
            $expense->employee_id = auth()->user()->id;
            $expense->type = 1;
            $expense->debit_or_credit = 'debit';
            $expense->entry_date = $request->entry_date;
            $expense->description = $request->description;
            $expense->effective_amount = $request->accounts[0]['debit'];
            $expense->entry_by = auth()->user()->id;
            $expense->save();
            
        }

        // ===== Field One: Expense Credit =====
        if($fieldOne->main_type == 4 && $request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0){
            // dd("working");
            $expense = new Expense();
            $expense->employee_id = auth()->user()->id;
            $expense->type = 1;
            $expense->debit_or_credit = 'credit';
            $expense->entry_date = $request->entry_date;
            $expense->description = $request->description;
            $expense->effective_amount = $request->accounts[0]['credit'];
            $expense->entry_by = auth()->user()->id;
            $expense->save();
        }

        // ===== Field Two: Expense Credit =====
        if($fieldTwo->main_type == 4 && $request->accounts[1]['credit'] != 0 && $request->accounts[0]['credit'] == 0){
            // dd("working");
            $expense = new Expense();
            $expense->employee_id = auth()->user()->id;
            $expense->type = 1;
            $expense->debit_or_credit = 'credit';
            $expense->entry_date = $request->entry_date;
            $expense->description = $request->description;
            $expense->effective_amount = $request->accounts[1]['credit'];
            $expense->entry_by = auth()->user()->id;
            $expense->save();
        }

        // ===== Field Two: Expense Debit =====
        if($fieldTwo->main_type == 4 && $request->accounts[1]['debit'] != 0 && $request->accounts[0]['debit'] == 0){
            // dd("working");
            $expense = new Expense();
            $expense->employee_id = auth()->user()->id;
            $expense->type = 1;
            $expense->debit_or_credit = 'debit';
            $expense->entry_date = $request->entry_date;
            $expense->description = $request->description;
            $expense->effective_amount = $request->accounts[1]['debit'];
            $expense->entry_by = auth()->user()->id;
            $expense->save();
        }

        // ===== Field One: Asset Debit =====
        if($fieldOne->main_type == 1 && $fieldOne->sub_type == 2 && $request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0){
            // dd('working 1');
            $assets = new Asset();
            $assets->asset_head_id = $fieldOne->type;
            $assets->type = 1;
            $assets->purchase_date = $request->entry_date;
            $assets->description = $request->description;
            $assets->effective_amount = $request->accounts[0]['debit'];
            $assets->debit_or_credit = 'debit';
            $assets->entry_by = auth()->user()->id;
            $assets->save();
        }

        // ===== Field One: Asset Credit =====
        if($fieldOne->main_type == 1 && $fieldOne->sub_type == 2 && $request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0){
            // dd('working 2');
            $assets = new Asset();
            $assets->asset_head_id = $fieldOne->type;
            $assets->type = 1;
            $assets->purchase_date = $request->entry_date;
            $assets->description = $request->description;
            $assets->effective_amount = $request->accounts[0]['credit'];
            $assets->debit_or_credit = 'credit';
            $assets->entry_by = auth()->user()->id;
            $assets->save();

        }

        // ===== Field Two: Asset Debit =====
        if($fieldTwo->main_type == 1 && $fieldTwo->sub_type == 2 && $request->accounts[1]['debit'] != 0 && $request->accounts[0]['debit'] == 0){
            // dd('working 3');
            $assets = new Asset();
            $assets->asset_head_id = $fieldTwo->type;
            $assets->type = 1;
            $assets->purchase_date = $request->entry_date;
            $assets->description = $request->description;
            $assets->effective_amount = $request->accounts[1]['debit'];
            $assets->debit_or_credit = 'debit';
            $assets->entry_by = auth()->user()->id;
            $assets->save();
        }

        // ===== Field Two: Asset Credit =====
        if($fieldTwo->main_type == 1 && $fieldTwo->sub_type == 2 && $request->accounts[1]['credit'] != 0 && $request->accounts[0]['credit'] == 0){
            // dd('working 4');
            $assets = new Asset();
            $assets->asset_head_id = $fieldTwo->type;
            $assets->type = 1;
            $assets->purchase_date = $request->entry_date;
            $assets->description = $request->description;
            $assets->effective_amount = $request->accounts[1]['credit'];
            $assets->debit_or_credit = 'credit';
            $assets->entry_by = auth()->user()->id;
            $assets->save();
        }

        // ===== Field One: Receivable Debit =====
        if($fieldOne->main_type == 1 && $fieldOne->sub_type == 3 && $request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0){
            // dd("working 1");
            $receivables = new Receivable();            
            $receivables->receivable_head_id = $fieldOne->type;
            $receivables->employee_id = auth()->user()->id;
            $receivables->description = $request->description; 
            $receivables->effective_amount = $request->accounts[0]['debit'];
            $receivables->debit_or_credit = 'debit';
            $receivables->entry_type = 1;
            $receivables->save();
        }

        // ===== Field Two: Receivable Credit =====
        if($fieldOne->main_type == 1 && $fieldOne->sub_type == 3 && $request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0){
            // dd("working 2");
            $receivables = new Receivable();            
            $receivables->receivable_head_id = $fieldOne->type;
            $receivables->employee_id = auth()->user()->id;
            $receivables->description = $request->description; 
            $receivables->effective_amount = $request->accounts[0]['credit'];
            $receivables->debit_or_credit = 'credit';
            $receivables->entry_type = 1;
            $receivables->save();
        }

        // ===== Field Two: Receivable Debit =====
        if($fieldTwo->main_type == 1 && $fieldTwo->sub_type == 3 && $request->accounts[1]['debit'] != 0 && $request->accounts[0]['debit'] == 0){
            // dd("working 3");
            $receivables = new Receivable();            
            $receivables->receivable_head_id = $fieldTwo->type;
            $receivables->employee_id = auth()->user()->id;
            $receivables->description = $request->description; 
            $receivables->effective_amount = $request->accounts[1]['debit'];
            $receivables->debit_or_credit = 'debit';
            $receivables->entry_type = 1;
            $receivables->save();
        }

        // ===== Field Two: Receivable Credit =====
        if($fieldTwo->main_type == 1 && $fieldTwo->sub_type == 3 && $request->accounts[1]['credit'] != 0 && $request->accounts[0]['credit'] == 0){
            // dd("working 4");
            $receivables = new Receivable();            
            $receivables->receivable_head_id = $fieldTwo->type;
            $receivables->employee_id = auth()->user()->id;
            $receivables->description = $request->description; 
            $receivables->effective_amount = $request->accounts[1]['credit'];
            $receivables->debit_or_credit = 'credit';
            $receivables->entry_type = 1;
            $receivables->save();
        }

        // ===== Field One: Income Credit (Equity) =====
        if($fieldOne->main_type == 3 && $fieldOne->sub_type == 2 && $request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0){
            // dd("working 1");
            $incomes = new Income();
            $incomes->description = $request->description; 
            $incomes->effective_amount = $request->accounts[0]['credit'];
            $incomes->debit_or_credit = 'credit';
            $incomes->entry_type = 1;
            $incomes->save();
        }

        // ===== Field One: Income Debit (Equity) =====
        if($fieldOne->main_type == 3 && $fieldOne->sub_type == 2 && $request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0){
            // dd("working 2");
            $incomes = new Income();
            $incomes->description = $request->description; 
            $incomes->effective_amount = $request->accounts[0]['debit'];
            $incomes->debit_or_credit = 'debit';
            $incomes->entry_type = 1;
            $incomes->save();
        }

        // ===== Field Two: Income Credit (Equity) =====
        if($fieldTwo->main_type == 3 && $fieldTwo->sub_type == 2 && $request->accounts[1]['credit'] != 0 && $request->accounts[0]['credit'] == 0){
            // dd("working 3");
            $incomes = new Income();
            $incomes->description = $request->description; 
            $incomes->effective_amount = $request->accounts[1]['credit'];
            $incomes->debit_or_credit = 'credit';
            $incomes->entry_type = 1;
            $incomes->save();
        }

        // ===== Field Two: Income Debit (Equity) =====
        if($fieldTwo->main_type == 3 && $fieldTwo->sub_type == 2 && $request->accounts[1]['debit'] != 0 && $request->accounts[0]['debit'] == 0){
            // dd("working 4");
            $incomes = new Income();
            $incomes->description = $request->description; 
            $incomes->effective_amount = $request->accounts[1]['debit'];
            $incomes->debit_or_credit = 'debit';
            $incomes->entry_type = 1;
            $incomes->save();
        }

        // ===== Field One: payable Credit =====
        if($fieldOne->main_type == 2 && $fieldOne->sub_type == 1 && $request->accounts[0]['credit'] != 0 && $request->accounts[1]['credit'] == 0){
            // dd("working 1");
            $payable = new Payable();   
            $payable->payables_head_id = $fieldOne->type;
            $payable->description = $request->description; 
            $payable->effective_amount = $request->accounts[0]['credit'];
            $payable->debit_or_credit = 'credit';
            $payable->entry_type = 1;
            $payable->save();
        }

        // ===== Field Two: payable Debit =====
        if($fieldOne->main_type == 2 && $fieldOne->sub_type == 1 && $request->accounts[0]['debit'] != 0 && $request->accounts[1]['debit'] == 0){
            // dd("working 2");
            $payable = new Payable();   
            $payable->payables_head_id = $fieldOne->type;
            $payable->description = $request->description; 
            $payable->effective_amount = $request->accounts[0]['debit'];
            $payable->debit_or_credit = 'debit';
            $payable->entry_type = 1;
            $payable->save();
        }

        // ===== Field Two: payable Credit =====
        if($fieldTwo->main_type == 2 && $fieldTwo->sub_type == 1 && $request->accounts[1]['credit'] != 0 && $request->accounts[0]['credit'] == 0){
            // dd("working 3");
            $payable = new Payable();   
            $payable->payables_head_id = $fieldTwo->type;
            $payable->description = $request->description; 
            $payable->effective_amount = $request->accounts[1]['credit'];
            $payable->debit_or_credit = 'credit';
            $payable->entry_type = 1;
            $payable->save();
        }

        // ===== Field Two: payable Debit =====
        if($fieldTwo->main_type == 2 && $fieldTwo->sub_type == 1 && $request->accounts[1]['debit'] != 0 && $request->accounts[0]['debit'] == 0){
            // dd("working 4");
            $payable = new Payable();   
            $payable->payables_head_id = $fieldTwo->type;
            $payable->description = $request->description; 
            $payable->effective_amount = $request->accounts[1]['debit'];
            $payable->debit_or_credit = 'debit';
            $payable->entry_type = 1;
            $payable->save();
        }

    
        $notify[] = ['success', 'Journal entry saved successfully.'];
        return to_route('bs.account.journal.index')->withNotify($notify);




    }
}
