<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Receivable;
use App\Models\ReceivableHead;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReceivableController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:balance-sheet-maintain', ['only' => ['create', 'store', 'receivableIndex','payDue']]);
    }

    public function create(Request $request)
    {
        $pageTitle = "Create Accounts Receivable";
        $heads = ReceivableHead::where('type', 2)->get();
        return view('accounts-receivable.create', compact('pageTitle', 'heads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'head' => 'required',
            'receivable_amount' => 'required',
            'description' => 'required'
        ]);

        $receivable                       = new Receivable();
        $receivable->receivable_head_id   = $request->head;
        $receivable->receivable_amount    = $request->receivable_amount;
        $receivable->description          = $request->description;
        $receivable->save();

        $notify[] = ['success', "New Account's Receivable has been created successfully."];
        return to_route('accounts.receivable.index')->withNotify($notify);
    }

    public function payCustomerDue(Request $request, $id)
    {

        $findReceivable = Receivable::find($id);
        if ($request->balance > $findReceivable->receivable_amount) {
            $notify[] = ['error', 'The due amount entered is incorrect. Please verify and try again.'];
            return back()->withNotify($notify);
        }
        // dd($request->all(), $id ,$findReceivable);

        $findReceivable->receivable_amount -= $request->balance;
        $findReceivable->save();

        // ==================== get cutomer due amount =================start
            $customerDue = Customer::whereId($findReceivable->customer_id)->first();
            if ($customerDue) {
                $customerDue->due -= $request->balance;
                $customerDue->save();
            }   
        // ==================== get cutomer due amount =================end

        $accArr = [
            'customer_id'       => $findReceivable->customer_id,
            'type'              => 7,
            'credit'            => $request->balance,
            'description'       => $request->comment ?? "Due Payment received of " . $request->balance . " Tk as receivable amount.",
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', $findReceivable->customer_id, 7);

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'depositor_name'   => $request->depositor_name,
                'credit'           => $request->balance,
                'description' => 'Company received amount of ' . $request->balance . ' due payment as Receivable.',
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }


        $notify[] = ['success', 'Due payment has been received successfully.'];
        return back()->withNotify($notify);

    }

    public function payDue(Request $request, $id)
    {
        $findReceivable = Receivable::find($id);
        // dd($request->all(), $findReceivable);   
        if ($request->balance > $findReceivable->receivable_amount) {
            $notify[] = ['error', 'The due amount entered is incorrect. Please verify and try again.'];
            return back()->withNotify($notify);
        }


        // dd($request->all(), $id);

        // $findReceivable = Receivable::find($id);
        $findReceivable->receivable_amount -= $request->balance;
        $findReceivable->save();

        // ==================== get cutomer due amount =================start
        // if($findReceivable->receivable_head_id == 4){
        //     $customerDue = Customer::whereId($findReceivable->customer_id)->first();
        //     if ($customerDue) {
        //         $customerDue->due -= $request->balance;
        //         $customerDue->save();
        //     }   
        // }   
  
        // ==================== get cutomer due amount =================end

        // dd($findReceivable->id);

        $accArr = [
            'receivable_id'     => $findReceivable->id,
            'type'              => 17,
            'credit'            => $request->balance,
            'description'       => $request->comment ?? "Due Payment received of " . $request->balance . " Tk as receivable amount.",
            'payment_method'    => $request->payment_method,
        ];

        $account = updateAcc($accArr, 'NTR', $findReceivable->id, 7);

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'depositor_name'   => $request->depositor_name,
                'credit'           => $request->balance,
                'description' => 'Company received amount of ' . $request->balance . ' due payment as Receivable.',
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        }


        $notify[] = ['success', 'Due payment has been received successfully.'];
        return back()->withNotify($notify);
    }


    public function receivableIndex(Request $request)
    {
        // return 1;
        $pageTitle = "Accounts Receivable";
        $todayTime = Carbon::now()->format('d-m-Y');

        $type = $request->type;
        $date = $request->date;
        $range = $request->range;
        $head = $request->head;
        $debit_or_credit = $request->debit_or_credit;
        $entry_type = $request->entry_type;

        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();

        $receivableQuery = Receivable::query();
        // return $receivableQuery;
        $receivableQuery->where(function ($query) {
            $query
                // ->where('due_amount', '>', 0)
                // ->orWhere('supplier_advance_amount', '>', 0)
                // ->orWhere('employee_advance_amount', '>', 0)
                ->orWhere('receivable_amount', '>', 0)->orWhere('effective_amount', '>', 0);
        });

        if ($head) {
            $receivableQuery->where('receivable_head_id', $head);
        }
        if ($debit_or_credit) {
            $receivableQuery = $receivableQuery->where('debit_or_credit', $debit_or_credit);
        }
        if (!is_null($entry_type)) {
            $receivableQuery = $receivableQuery->where('entry_type', $entry_type);
        }


        if ($type) {
            // dd($type);
            if ($type == 1 && $date) {
                $givenDate = $date;
                $receivableQuery->whereDate('created_at', $givenDate);
            } elseif ($type == 2 && $range) {
                $dates = explode(' to ', $range);
                if (count($dates) == 2) {
                    $startDate = $dates[0] . ' 00:00:00';
                    $endDate = $dates[1] . ' 23:59:59';
                    $receivableQuery->whereBetween('created_at', [$startDate, $endDate]);
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
            $data = isset($givenDate) ? compact('receivables', 'givenDate', 'banks') : compact('receivables', 'banks');
            $html = view('accounts-receivable.acc-receivable-pdf', $data)->render();

            $mpdf->WriteHTML($html);
            return response($mpdf->Output('acc-receivable.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }

        // $receivables = $receivableQuery->paginate(gs()->pagination);
        $receivables = $receivableQuery->latest()->paginate(gs()->pagination);

        $receivableHeads = ReceivableHead::all();

        // dd($receivables->toArray());

        return view('accounts-receivable.accounts-receivable', compact('pageTitle', 'receivables', 'banks', 'receivableHeads'));
    }
}
