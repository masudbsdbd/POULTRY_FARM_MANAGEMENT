<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\IncomeList;
use App\Models\Bank;
use App\Models\BankTransaction;

class IncomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:income-list', ['only' => ['index']]);
        $this->middleware('permission:income-create|income-edit', ['only' => ['store']]);
        $this->middleware('permission:income-create', ['only' => ['create']]);
        $this->middleware('permission:income-edit', ['only' => ['edit']]);
        $this->middleware('permission:income-delete', ['only' => ['delete']]);

        $this->middleware('permission:income-list-list', ['only' => ['listIndex']]);
        $this->middleware('permission:income-list-create|income-list-edit', ['only' => ['listStore']]);
        $this->middleware('permission:income-list-create', ['only' => ['listCreate']]);
        $this->middleware('permission:income-list-edit', ['only' => ['listEdit']]);
        $this->middleware('permission:income-list-delete', ['only' => ['listDelete']]);
    }

    public function listCreate()
    {
        $pageTitle = "Create Income List";

        return view('income-list.create', compact('pageTitle'));
    }
    public function create()
    {
        $pageTitle = 'Income Create';
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        $incomeLists = IncomeList::latest()->notDeleted()->get();

        return view('income.create', compact('pageTitle', 'incomeLists', 'banks'));
    }
    public function listIndex()
    {
        $pageTitle = "All Income List";
        $incomeLists = IncomeList::latest()->notDeleted()->paginate(gs()->pagination);
        return view('income-list.index', compact('pageTitle', 'incomeLists'));
    }
    public function index()
    {
        $pageTitle = 'All Incomes';
        $incomes = Income::with('incomeList')->latest()->notDeleted()->paginate(gs()->pagination);
        return view('income.index', compact('pageTitle', 'incomes'));
    }
    public function edit($id)
    {
        $pageTitle = 'Edit Income';
        $incomes = Income::find($id);
        $incomeLists  = IncomeList::latest()->notDeleted()->get();
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('income.create', compact('incomes', 'pageTitle', 'incomeLists', 'banks'));
    }
    public function listEdit($id)
    {
        $pageTitle = 'Edit Income List';
        $incomeList = IncomeList::find($id);
        return view('income-list.create', compact('incomeList', 'pageTitle'));
    }
    public function listStore(Request $request, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required',
        ]);

        if ($id > 0) {
            $incomeList = IncomeList::whereId($id)->first();
            $message = 'Income List updated successfully';
            $incomeList->update_by = auth()->user()->id;
        } else {
            $incomeList = new IncomeList();
            $message = 'Income List has been created successfully';
        }

        $incomeList->name = $request->name;
        $incomeList->details = $request->details;
        $incomeList->entry_by = auth()->user()->id;
        $incomeList->save();

        $notify[] = ['success', $message];
        return to_route('income.list.index')->withNotify($notify);
    }
    public function store(Request $request, $id = 0)
    {
        // $input = $request->all();
        // dd($input);
        $request->validate([
            'income_list_id' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
        ]);

        $bank = Bank::whereId($request->bank_id)->first();
        if (isset($bank) && $request->balance > $bank->balance && $id == 0) {
            $notify[] = ['error', 'Insufficient balance in ' . $bank->bank_name];
            return back()->withNotify($notify);
        }

        if ($id > 0) {
            $income = Income::whereId($id)->first();
            $message = 'Income updated successfully';
            $income->update_by = auth()->user()->id;
        } else {
            $income = new Income();
            $message = 'Income has been created successfully';
        }

        $income->income_list_id = $request->income_list_id;
        $income->amount = $request->amount;
        $income->description = $request->description;
        $income->entry_by = auth()->user()->id;
        $income->save();

        $accArr = [
            'income_id'       => $income->id,
            'type'             => 15,
            'credit'            => $income->amount,
            'description'      => "Income (" . $income->incomeList->name . ") of " . $income->amount . " Tk has been successfully received as payment.",
            'payment_method'   => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'income_id', $id, 15);


        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'depositor_name'  => $request->depositor_name,
                'credit'            => $request->amount,
                'description'      => "The company has received an income from (" . $income->incomeList->name . "). The total amount received is " . $income->amount . " Tk.",
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
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


        $notify[] = ['success', $message];
        return to_route('income.index')->withNotify($notify);
    }
    public function listDelete($id)
    {
        $file = IncomeList::find($id);

        $file->is_deleted = 1;
        $file->save();

        $notify[] = ['success', 'Income list has been successfully deleted'];
        return back()->withNotify($notify);
    }
    public function delete($id)
    {
        $file = Income::find($id);
        $file->is_deleted = 1;
        $file->save();

        $notify[] = ['success', 'Income has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
