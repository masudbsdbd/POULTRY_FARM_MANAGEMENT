<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankTransaction;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:bank-transaction', ['only' => ['index']]);
        $this->middleware('permission:bank-diposit|bank-withdraw', ['only' => ['store']]);
        $this->middleware('permission:bank-diposit|bank-withdraw', ['only' => ['diposit', 'withdraw']]);
    }
    public function index()
    {
        $pageTitle = 'Banks transaction Info';
        $bankTransactions = BankTransaction::with('bank')->latest()->paginate(gs()->pagination);
        return view('bank-transactions.index', compact('pageTitle', 'bankTransactions'));
    }

    public function diposit()
    {
        $pageTitle = 'Bank Deposit';
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('bank-transactions.deposit', compact('pageTitle', 'banks'));
    }

    public function withdraw()
    {
        $pageTitle = 'Bank Withdraw';
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        return view('bank-transactions.withdraw', compact('pageTitle', 'banks'));
    }

    public function store(Request $request)
    {
        if ($request->withdraw_amount) {
            $request->validate([
                'withdraw_amount'      => 'required',
                'bank_id'          => 'required',
                'withdrawer_name'        => 'required',
            ]);

            $message = 'Balance Withdraw successfully';
        } else {
            $request->validate([
                'deposit_amount'      => 'required',
                'bank_id'          => 'required',
                'depositor_name'        => 'required',
            ]);

            $message = 'Balance deposit successfully';
        }

        // Add an extra row in account table for deposit and withdraw
        $accArr = [
            'type' => $request->withdraw_amount ? 4 : 3,
            $request->withdraw_amount ? 'credit' : 'debit'  => $request->withdraw_amount ? $request->withdraw_amount : $request->deposit_amount,
            'description'       => $request->withdraw_amount ? "Cash credited by Bank Withdraw" : "Cash debited by Bank deposit",
            'payment_method'    => 1,
        ];
        updateAcc($accArr, 'NTR', 0, $accArr['type']);

        // Usual accounts and bank transaction table update
        $accArr = [
            'type' => $request->withdraw_amount ? 4 : 3,
            $request->withdraw_amount ? 'debit' : 'credit'  => $request->withdraw_amount ? $request->withdraw_amount : $request->deposit_amount,
            'description'       => $request->withdraw_amount ? "Bank Withdraw" : "Bank Deposit",
            'payment_method'    => 2,
        ];

        $account = updateAcc($accArr, 'NTR', 0, $accArr['type']);

        $bankTrArr = [
            'account_id'       => $account->id,
            $request->withdraw_amount ? 'withdrawer_name' : 'depositor_name' => $request->withdraw_amount ? $request->withdrawer_name : $request->depositor_name,
            $request->withdraw_amount ? 'debit' : 'credit' => $request->withdraw_amount ? $request->withdraw_amount : $request->deposit_amount,
            'description'      => isset($request->description) ? $request->description : 'Balance ' . ($request->withdraw_amount ? 'Withdraw' : 'Deposit'),
            'bank_id'          => $request->bank_id,
        ];

        bankTr($account, $bankTrArr);

        $notify[] = ['success', $message];
        return to_route('bank.transaction.index')->withNotify($notify);
    }
}
