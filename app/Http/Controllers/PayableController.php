<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Payable;
use App\Models\BankTransaction;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\PayableHead;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayableController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:balance-sheet-maintain', ['only' => ['create', 'payAccPayable', 'store', 'payableIndex','expensePay']]);
    }
    public function create(Request $request)
    {
        $pageTitle = "Create Accounts Payable";
        $heads = PayableHead::where('type', 2)->get();
        return view('accounts-payable.create', compact('pageTitle', 'heads'));
    }

    public function paySupplierrDue(Request $request, $id){

        $findPayable = Payable::find($id);
        if ($request->balance > $findPayable->payable_amount) {
            $notify[] = ['error', 'The due amount entered is incorrect. Please verify and try again.'];
            return back()->withNotify($notify);
        }
        // dd($request->all(), $id ,$findPayable);

        $findPayable->payable_amount -= $request->balance;
        $findPayable->save();

        // ==================== get cutomer due amount =================start
            $supplierDue = Supplier::whereId($findPayable->supplier_id)->first();
            if ($supplierDue) {
                $supplierDue->due -= $request->balance;
                $supplierDue->save();
            }   
        // ==================== get cutomer due amount =================end

        $accArr = [
            'supplier_id'       => $findPayable->supplier_id,
            'type'              => 8,
            'debit'            => $request->balance,
            'description'       => $request->comment ?? "Due Payment received of " . $request->balance . " Tk as Payable amount.",
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', $findPayable->supplier_id, 8);

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'   => $request->withdrawer_name,
                'debit'           => $request->balance,
                'description' => 'Company received amount of ' . $request->balance . ' due payment as Payable.',
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }


        $notify[] = ['success', 'Due payment has been received successfully.'];
        return back()->withNotify($notify);

    }
    public function payAccPayable(Request $request, $id)
    {
        $payable = Payable::find($id);

        if ($request->balance > $payable->payable_amount) {
            $notify[] = ['error', 'The due amount entered is incorrect. Please verify and try again.'];
            return back()->withNotify($notify);
        }

        $bank = Bank::whereId($request->bank_id)->first();
        if (isset($bank) && $request->balance > $bank->balance) {
            $notify[] = ['error', 'Insufficient balance in ' . $bank->bank_name];
            return back()->withNotify($notify);
        }

        $payable->payable_amount -= $request->balance;
        $payable->save();

        $accArr = [
            'payable_id'        => $payable->id,
            'type'              => 18,
            'debit'             => $request->balance,
            'description'       => $request->comment ? $request->comment : "Settlement of Account's Payable amount of " . $request->balance . " Tk.",
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', $payable->id, 18);

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'  => $request->withdrawer_name,
                'debit'            => $request->balance,
                'description'      => "Company Settled Account's Payable amount of " . $request->balance,
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }

        $notify[] = ['success', "Account's payable successfully settled."];
        return back()->withNotify($notify);
    }

    public function store(Request $request)
    {
        $request->validate([
            'head'            => 'required',
            'payable_amount'  => 'required',
            'description'     => 'required'
        ]);

        $payable                       = new Payable();
        $payable->payables_head_id     = $request->head;
        $payable->payable_amount       = $request->payable_amount;
        $payable->description          = $request->description;
        $payable->save();

        $notify[] = ['success', "New Account's Payable has been created successfully."];
        return to_route('accounts.payable.index')->withNotify($notify);
    }

    public function payableIndex(Request $request)
    {
        // return 1111;
        $pageTitle = "Accounts Payable";
        $todayTime = Carbon::now()->format('d-m-Y');

        $type = $request->type;
        $date = $request->date;
        $range = $request->range;
        $head = $request->head_id;
        $debit_or_credit = $request->debit_or_credit;
        $entry_type = $request->entry_type;

        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        $heads = PayableHead::all();
        $payablesQuery = Payable::query();

        $payablesQuery->where(function ($query) {
            $query->where('payable_amount', '>', 0)->orWhere('effective_amount', '>', 0);
        });

        if ($type) {
            if ($type == 1 && $date) {
                $givenDate = $date;
                $payablesQuery = $payablesQuery->whereDate('created_at', $givenDate);
            } elseif ($type == 2 && $range) {
                $dates = explode(' to ', $range);
                if (count($dates) == 2) {
                    $startDate = $dates[0] . ' 00:00:00';
                    $endDate = $dates[1] . ' 23:59:59';
                    $payablesQuery = $payablesQuery->whereBetween('created_at', [$startDate, $endDate]);
                }
                $givenDate = [$startDate, $endDate];
            } else {
                $notify[] = ['error', 'Kindly select a valid date or date range.'];
                return back()->withNotify($notify);
            }
        }

        if ($head) {
            $payablesQuery = $payablesQuery->where('payables_head_id', $head);
        }
        if ($debit_or_credit) {
            $payablesQuery = $payablesQuery->where('debit_or_credit', $debit_or_credit);
        }
        if (!is_null($entry_type)) {
            $payablesQuery = $payablesQuery->where('entry_type', $entry_type);
        }


        if ($request->action == 'print') {
            $mpdf = setPdf('L');

            $payables = $payablesQuery->get();
            $data = isset($givenDate) ? compact('payables', 'givenDate', 'banks') : compact('payables', 'banks');
            $html = view('accounts-payable.acc-payable-pdf', $data)->render();

            $mpdf->WriteHTML($html);
            return response($mpdf->Output('acc-payable.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }

        $payables = $payablesQuery->latest()->paginate(gs()->pagination);

        return view('accounts-payable.accounts-payable', compact('pageTitle', 'payables', 'banks', 'heads'));
    }

    public function expensePay(Request $request, $id)
    {
        // dd($request->all(),$id);
        
        $findPayable = Payable::where('expense_id', $id)->first();

        $findExpense = Expense::find($id);


        if($findPayable->payable_amount < $request->balance){
            $notify[] = ['error', 'The inputted amount is greater than the due amount'];
            return back()->withNotify($notify);
        }

        // $findExpense->amount -= $request->balance;
        // $findExpense->paid_amount += $request->balance;
        $findExpense->paid_amount += $request->balance;
        $findExpense->save();


        $findPayable->payable_amount -= $request->balance;
        $findPayable->save();



        $accArr = [
            'expense_id'       => $id,
            'type'             => 9,
            'debit'            => $request->balance,
            'description'      => "Expense payment of " . $request->balance . " Tk has been successfully paid.",
            'payment_method'   => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', 0, 9);


        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'  => $request->withdrawer_name,
                'debit'            => $request->balance,
                'description'      => "Expense payment of " . $request->balance . " Tk has been successfully paid.",
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

        $notify[] = ['success', "expense amount has been debited successfully."];
        return to_route('accounts.payable.index')->withNotify($notify);
    }

    



}
