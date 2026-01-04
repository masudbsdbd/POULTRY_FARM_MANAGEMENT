<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Investment;
use App\Models\BsType;
use App\Models\BsAccount;
use App\Models\JournalEntry;
use App\Models\Owner;

class InvestmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:investment-list', ['only' => ['index']]);
        $this->middleware('permission:investment-create|investment-edit', ['only' => ['store']]);
        $this->middleware('permission:investment-create', ['only' => ['create']]);
        $this->middleware('permission:investment-edit', ['only' => ['edit']]);
        $this->middleware('permission:investment-delete', ['only' => ['delete']]);
    }
    //
    public function index()
    {
        $pageTitle = "Investment List";
        $investments  = Investment::latest()->paginate(gs()->pagination);

        return view('investment.index', compact('pageTitle', 'investments'));
    }
    public function create()
    {
        $pageTitle = "Create Investment";
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();

        return view('investment.create', compact('pageTitle', 'banks'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name'              => 'required|string|max:255',
            'amount'            => 'required',
            'entry_date'        => 'required',
            'payment_method'    => 'required',
        ]);

        $investment = new Investment();
        $message = 'Investment has been created successfully';

        $investment->name              = $request->name;
        $investment->amount            = $request->amount;
        $investment->payment_method    = $request->payment_method;
        $investment->entry_date        = $request->entry_date;
        $investment->description       = $request->description;
        $investment->entry_by          = auth()->user()->id;
        $investment->save();

        $owner = new Owner();
        $owner->debit_or_credit = 'credit';
        $owner->amount = $request->amount;
        $owner->description = $request->description;
        $owner->save();

        // // == ===== == BS Account Start ==>
        // $bsSubTypeOne = BsType::find(6);
        // $bsSubTypeTwo = BsType::find(1);
        // $bsSubTypeThree = BsType::find(2);

        // $journalEntryData = new JournalEntry();
        // $journalEntryData->description = $request->description ?? "Invest amount of " . $request->amount . " Tk.";
        // $journalEntryData->entry_date = $request->entry_date;
        // $journalEntryData->entry_by = auth()->user()->id;
        // $journalEntryData->amount = $request->amount;
        // $journalEntryData->save();

        // $creditEntry = new BsAccount();
        // $creditEntry->journal_entry_id = $journalEntryData->id;
        // $creditEntry->investment_id = $investment->id;
        // $creditEntry->account_type = $bsSubTypeOne->type;
        // $creditEntry->account_sub_type = $bsSubTypeOne->id;
        // $creditEntry->description = $request->description ?? "Invest amount of " . $request->amount . " Tk.";
        // $creditEntry->credit = $request->amount;
        // $creditEntry->debit = 0;
        // $creditEntry->save();

        // $debitEntry = new BsAccount();
        // $debitEntry->journal_entry_id = $journalEntryData->id;
        // $debitEntry->investment_id = $investment->id;

        // if ($request->payment_method == 2) {
        //     $debitEntry->account_type = $bsSubTypeThree->type;
        //     $debitEntry->account_sub_type = $bsSubTypeThree->id;
        //     $debitEntry->description = $request->description ?? "Invest amount of " . $request->amount . " Tk, added to Bank.";
        // } else {
        //     $debitEntry->account_type = $bsSubTypeTwo->type;
        //     $debitEntry->account_sub_type = $bsSubTypeTwo->id;
        //     $debitEntry->description = $request->description ?? "Invest amount of " . $request->amount . " Tk,  added to Cash.";
        // }
        // $debitEntry->debit = $request->amount;
        // $debitEntry->credit = 0;
        // $debitEntry->save();

        // // == ===== == BS Account End ==>

        $accArr = [
            'investment_id'     => $investment->id,
            'type'              => 16,
            'credit'            => $request->amount,
            'description'       => $request->description ? $request->description : "Invested the amount of " . $request->amount . " " . $investment->name . " Tk in the company",
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', $investment->id, 16);


        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'depositor_name'   => $request->depositor_name,
                'credit'           => $request->amount,
                'description' => $request->description ? $request->description : "Invested the amount of " . $request->amount . " " . $investment->name . " Tk in the company",
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }



        $notify[] = ['success', $message];
        return to_route('investment.index')->withNotify($notify);
    }
}
