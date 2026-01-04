<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Employee;
use App\Models\Bank;
use App\Models\ExpenseHead;
use App\Models\BankTransaction;
use App\Models\BsType;
use App\Models\BsAccount;
use App\Models\JournalEntry;
use App\Models\Payable;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:expense-list', ['only' => ['index']]);
        $this->middleware('permission:expense-create|expense-edit', ['only' => ['store']]);
        $this->middleware('permission:expense-create', ['only' => ['create']]);
        $this->middleware('permission:expense-edit', ['only' => ['edit']]);
        $this->middleware('permission:expense-delete', ['only' => ['delete']]);

        $this->middleware('permission:expense-head-list', ['only' => ['headIndex']]);
        $this->middleware('permission:expense-head-create|expense-head-edit', ['only' => ['headStore']]);
        $this->middleware('permission:expense-head-create', ['only' => ['headCreate']]);
        $this->middleware('permission:expense-head-edit', ['only' => ['headEdit']]);
        $this->middleware('permission:expense-head-delete', ['only' => ['headDelete']]);
    }
    //
    public function index()
    {
        $pageTitle = 'All Expenses';
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        $expenses = Expense::with('employee','expenseHead')->latest()->notDeleted()->paginate(gs()->pagination);
        return view('expense.index', compact('pageTitle', 'expenses','banks'));
    }
    public function headIndex()
    {
        $pageTitle = 'All Expenses Heads';
        $expenseHeads = ExpenseHead::latest()->notDeleted()->paginate(gs()->pagination);
        return view('expense-head.index', compact('pageTitle', 'expenseHeads'));
    }
    public function create()
    {
        $pageTitle = 'Expense Create';
        $employees = Employee::latest()->notDeleted()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        $expenseHeads = ExpenseHead::latest()->notDeleted()->get();

        return view('expense.create', compact('pageTitle', 'employees', 'expenseHeads', 'banks'));
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

        $notify[] = ['success', "expense amount has been pay successfully."];
        return to_route('expense.index')->withNotify($notify);
    }


    public function headCreate()
    {
        $pageTitle = 'Expense Head Create';

        return view('expense-head.create', compact('pageTitle'));
    }
    public function edit($id)
    {
        $pageTitle = 'Edit Expense';
        $expense = Expense::find($id);
        $employees = Employee::latest()->notDeleted()->get();
        $expenseHeads  = ExpenseHead::latest()->notDeleted()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('expense.create', compact('expense', 'pageTitle', 'employees', 'expenseHeads', 'banks'));
    }
    public function headEdit($id)
    {
        $pageTitle = 'Edit Expense Head';
        $expenseHead = ExpenseHead::find($id);
        return view('expense-head.create', compact('expenseHead', 'pageTitle'));
    }
    public function headStore(Request $request, $id = 0)
    {
        // $input = $request->all();
        // dd($input);

        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required',
        ]);

        if ($id > 0) {
            $expenseHead = ExpenseHead::whereId($id)->first();
            $message = 'Expense Head updated successfully';
            $expenseHead->update_by = auth()->user()->id;
        } else {
            $expenseHead = new ExpenseHead();
            $message = 'Expense Head has been created successfully';
        }


        $expenseHead->name = $request->name;
        $expenseHead->details = $request->details;
        $expenseHead->entry_by = auth()->user()->id;
        $expenseHead->save();


        $notify[] = ['success', $message];
        return to_route('expense.head.index')->withNotify($notify);
    }
    public function store(Request $request, $id = 0)
    {
        // $input = $request->all();
        // dd($input);
        $request->validate([
            'expense_head_id' => 'required',
            'employee_id' => 'required',
            'title' => 'required|string|max:255',
            'entry_date' => 'required',
            // 'amount' => 'required',
            // 'payment_method' => 'required',
        ]);

        $bank = Bank::whereId($request->bank_id)->first();
        if (isset($bank) && $request->balance > $bank->balance && $id == 0) {
            $notify[] = ['error', 'Insufficient balance in ' . $bank->bank_name];
            return back()->withNotify($notify);
        }

        // if ($id > 0) {
        //     $expense = Expense::whereId($id)->first();
        //     $message = 'Expense updated successfully';
        //     $expense->update_by = auth()->user()->id;


        //     if (!isset($request->payment_method)) {

        //         $isExistAcc = Account::where('expense_id', $id)->first();

        //         $isExistBankTrx = BankTransaction::where('account_id', $isExistAcc->id)->first();
        //         if ($isExistBankTrx) {

        //             $getBank = Bank::find($isExistBankTrx->bank_id);
        //             if ($getBank) {
        //                 $getBank->balance += $isExistBankTrx->debit;
        //                 $getBank->save();
        //             }

        //             $isExistBankTrx->delete();
        //         }

        //         if ($isExistAcc) {
        //             $isExistAcc->delete();
        //         }
        //     }

        //     if (isset($request->payment_method)) {
        //         $isExistExpense = Expense::find($id);
        //         $isExistExpense->pending_status = 0;
        //         $isExistExpense->save();
        //     }

        //     $isExistPayableTrx = Payable::where('expense_id', $id)->first();
        //     if ($isExistPayableTrx) {
        //         $isExistPayableTrx->delete();
        //     }
        // } else {
        $expense = new Expense();
        $message = 'Expense has been created successfully';
        // }

        $expense->expense_head_id = $request->expense_head_id;
        $expense->entry_date = $request->entry_date;
        $expense->employee_id = $request->employee_id;
        $expense->title = $request->title;
        $expense->description = $request->description;
        $expense->entry_by = auth()->user()->id;
        if (!empty($request->pending_status)) {
            $expense->pending_status = 1;
            $expense->amount = $request->pending_amount;
        } else {
            $expense->amount = $request->amount;
            $expense->paid_amount = $request->amount;
        }

        $expense->save();

        if (!empty($request->pending_status)) {

            $isExistAccPayable = Payable::where('expense_id', $id)->where('payables_head_id', 4)->first();

            $payable = isset($isExistAccPayable) ? $isExistAccPayable : new Payable();
            $payable->expense_id = $expense->id;
            $payable->payables_head_id = 4;
            $payable->payable_amount = $request->pending_amount;
            $payable->description = $request->description;
            $payable->save();
        } else {

            $accArr = [
                'expense_id'       => $expense->id,
                'type'             => 9,
                'debit'            => $expense->amount,
                'description'      => "Expense(" . $expense->employee->name . ") payment of " . $expense->amount . " Tk has been successfully paid.",
                'employee_id'      => $expense->employee_id,
                'payment_method'   => $request->payment_method,
            ];


            // dd($expense->toArray());
            // if($expense->pending_status == 1){

            // $account = updateAcc($accArr, 'NTR', 0, 9);

            // }else{

            $account = updateAcc($accArr, 'expense_id', $id, 9);
            // }


            if ($request->payment_method == 2) {

                $bankTrArr = [
                    'account_id'       => $account->id,
                    'withdrawer_name'  => $request->withdrawer_name,
                    'debit'            => $request->amount,
                    'description'      => 'Company give Employee' . $expense->employee->name . ' for expence. Total amount ' . $expense->amount,
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
        }



        $notify[] = ['success', $message];
        return to_route('expense.index')->withNotify($notify);
    }
    public function delete($id)
    {
        $file = Expense::find($id);
        $file->is_deleted = 1;
        $file->save();

        $notify[] = ['success', 'Expense has been successfully deleted'];
        return back()->withNotify($notify);
    }
    public function headDelete($id)
    {
        $file = ExpenseHead::find($id);

        $file->is_deleted = 1;
        $file->save();

        $notify[] = ['success', 'Expense Head has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
