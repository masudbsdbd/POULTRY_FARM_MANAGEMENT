<?php

namespace App\Http\Controllers;

use App\Models\Account;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:account-maintain', ['only' => ['accountStatements', 'cashStatement', 'BankStatement']]);
    }
    // public function accountStatements (Request $request)
    // {
    //     $pageTitle = 'Account Statements';
    //     $statements = Account::latest()->paginate(gs()->pagination);
    //     return view('account.index', compact('pageTitle', 'statements'));
    // }
    // public function cashStatement (Request $request)
    // {
    //     $pageTitle = 'Cash Account Statements';
    //     $statements = Account::wherePaymentMethod(1)->latest()->paginate(gs()->pagination);
    //     return view('account.index', compact('pageTitle', 'statements'));
    // }

    // public function BankStatement (Request $request)
    // {
    //     $pageTitle = 'Bank Account Statements';
    //     $statements = Account::wherePaymentMethod(2)->latest()->paginate(gs()->pagination);
    //     return view('account.index', compact('pageTitle', 'statements'));
    // }

    public function accountStatements (Request $request)
    {
        $pageTitle = 'Account Statements';
        $type = $request->type;
        $date = $request->date;
        $range = $request->range;
        // dd($type, $date, $range);

        if ($range) {
            $dates = explode(' to ', $range);
            $givenDates = [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59',
            ];
        }

        $statements = Account::latest();;

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $statements = $statements->$clause('created_at', $givenDate);
        }
        $statements = $statements->paginate(gs()->pagination);

        return view('account.index', compact('pageTitle', 'statements'));
    }

    public function cashStatement(Request $request)
    {
        $pageTitle = 'Cash Account Statements';
        $type = $request->type;
        $date = $request->date;
        $range = $request->range;

        if ($range) {
            $dates = explode(' to ', $range);
            $givenDates = [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59',
            ];
        }

        $statements = Account::wherePaymentMethod(1)->latest();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $statements = $statements->$clause('created_at', $givenDate);
        }

        $statements = $statements->paginate(gs()->pagination);

        return view('account.index', compact('pageTitle', 'statements'));
    }

    public function bankStatement(Request $request)
    {
        $pageTitle = 'Bank Account Statements';
        $type = $request->type;
        $date = $request->date;
        $range = $request->range;

        if ($range) {
            $dates = explode(' to ', $range);
            $givenDates = [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59',
            ];
        }

        $statements = Account::wherePaymentMethod(2)->latest();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $statements = $statements->$clause('created_at', $givenDate);
        }

        $statements = $statements->paginate(gs()->pagination);

        return view('account.index', compact('pageTitle', 'statements'));
    }


}
