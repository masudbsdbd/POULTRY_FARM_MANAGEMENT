<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use App\Models\BsType;
use App\Models\BsAccount;
use App\Models\JournalEntry;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:bank-list', ['only' => ['index']]);
        $this->middleware('permission:bank-create|bank-edit', ['only' => ['store']]);
        $this->middleware('permission:bank-create', ['only' => ['create']]);
        $this->middleware('permission:bank-edit', ['only' => ['edit']]);
        $this->middleware('permission:bank-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'All Banks Info';
        $banks = Bank::latest()->notDeleted()->paginate(gs()->pagination);
        return view('bank.index', compact('pageTitle', 'banks'));
    }
    public function individualIndex($id){
        
        $pageTitle = 'Bank transaction Info';

        $bankTransactions = BankTransaction::where('bank_id', $id)
        ->latest()
        ->paginate(20);

        $bankInfo = Bank::find($id);

        // dd($bankInfo->toArray());

        return view('bank-transactions.individual-index',compact('pageTitle','bankTransactions','bankInfo'));
    }

    public function create()
    {
        $pageTitle = 'Add New Bank Info';
        return view('bank.store', compact('pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Bank Info';
        $bank = Bank::findOrFail($id);
        return view('bank.store', compact('pageTitle', 'bank'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'account_name'     => 'required|string|max:255',
            'account_no'       => 'required|string|max:255',
            'bank_name'        => 'required|string|max:255',
            'branch_name'      => 'required',
            'balance'          =>  $id == 0 ? 'required' : 'nullable',
        ]);

        if ($id > 0) {
            $bank = Bank::whereId($id)->first();
            $message = 'Bank updated successfully';
            $bank->update_by = auth()->user()->id;
            $bank->last_update = now();
            $givenStatus = isset($request->editcatstatus) ? 1 : 0;
        } else {
            $bank             = new Bank();
            $message          = 'Bank created successfully';
            $givenStatus      = isset($request->status) ? 1 : 0;
            $bank->entry_by   = auth()->user()->id;
            $bank->entry_date = now();
        }
        $bank->account_name      = $request->account_name;
        $bank->account_no        = $request->account_no;
        $bank->bank_name         = $request->bank_name;
        $bank->branch_name       = $request->branch_name;
        $bank->status            = $givenStatus;
        $bank->save();

        // // == ===== == BS Account Start ==>

        // $bsSubTypeOne = BsType::find(1);
        // $bsSubTypeTwo = BsType::find(2);

        // $journalEntryData = new JournalEntry();
        // $journalEntryData->description = "Opening Balance For Bank (Amount is " . $request->balance . ")";
        // $journalEntryData->entry_date = now()->format('Y-m-d H:i:s');
        // $journalEntryData->entry_by = auth()->user()->id;
        // $journalEntryData->amount = $request->balance;
        // $journalEntryData->save();


        // $creditEntry = new BsAccount();
        // $creditEntry->journal_entry_id = $journalEntryData->id;
        // $creditEntry->account_type = $bsSubTypeOne->type;
        // $creditEntry->account_sub_type = $bsSubTypeOne->id;
        // $creditEntry->credit = $request->balance;
        // $creditEntry->debit = 0;
        // $creditEntry->save();

        // $debitEntry = new BsAccount();
        // $debitEntry->journal_entry_id = $journalEntryData->id;
        // $debitEntry->account_type = $bsSubTypeTwo->type;
        // $debitEntry->account_sub_type = $bsSubTypeTwo->id;
        // $debitEntry->debit = $request->balance;
        // $debitEntry->credit = 0;
        // $debitEntry->save();

        // // == ===== == BS Account End ==>


        if ($id == 0) {
            // Add an extra row in account table for deposit
            $accArr = [
                'type' => 3,
                'debit'  => $request->balance,
                'description'       => "Cash debited by Bank opening deposit",
                'payment_method'    => 1,
            ];
            updateAcc($accArr, 'NTR', $id, 3);

            $accArr = [
                'type'              => 3,
                'credit'            => $request->balance,
                'description'       => "Bank Opening Deposit",
                'payment_method'    => 2,
            ];

            $account = updateAcc($accArr, 'NTR', $id, 3);

            $bankTrArr = [
                'account_id'       => $account->id,
                'depositor_name'   => $bank->account_name,
                'credit'           => $request->balance,
                'description'      => "Bank Opening Deposit",
                'bank_id'          => $bank->id,
            ];

            bankTr($account, $bankTrArr);
        }

        $notify[] = ['success', $message];
        return to_route('bank.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $file = Bank::find($id);
        $file->is_deleted = 1;
        $file->save();

        $notify[] = ['success', 'Bank Info has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
