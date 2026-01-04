<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sell;
use App\Models\Account;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerLedgerController extends Controller
{
    //
    public function accountIndex(Request $request, $id)
    {

        $todayTime = Carbon::now()->format('d-m-Y');

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
        $pageTitle = "Account Ledger";
        $customerAccData = Account::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $customerAccData = $customerAccData->$clause('created_at', $givenDate);
        }

        // $customerAccData = $customerAccData->where('customer_id', $id)
        //     ->with('sell')
        //     ->get();

            $customerAccData = $customerAccData->where('customer_id', $id)
            ->with('sell')
            // ->latest()
            ->get();

        // dd($customerAccData->toArray());

        $customerData = Customer::find($id);


        if ($request->action == 'print') {
            $mpdf = setPdf();

            $data = isset($givenDate) ? compact('customerAccData', 'customerData', 'givenDate') : compact('customerAccData', 'customerData');
            $html = view('customer-ledger.account-pdf', $data)->render();

            $mpdf->WriteHTML($html);
            return response($mpdf->Output('customer-ledger-' . $id . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }

        return view('customer-ledger.account-ledger-index', compact('pageTitle', 'customerAccData', 'id', 'customerData'));
    }
    public function sellIndex(Request $request, $id)

    {
        // dd($request->all());    

        $todayTime = Carbon::now()->format('d-m-Y');

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

        $sells = Sell::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $sells = $sells->$clause('created_at', $givenDate);
        }
        $sells = $sells->with(['customer'])
            ->where('customer_id', $id)
            ->latest()
            ->notDeleted()
            ->get();

        $pageTitle = "Sell History";
        $customerData = Customer::find($id);

        if ($request->action == 'print') {
            $mpdf = setPdf();

            $data = isset($givenDate) ? compact('sells', 'customerData', 'givenDate') : compact('sells', 'customerData');
            $html = view('customer-ledger.ledger-pdf', $data)->render();

            $mpdf->WriteHTML($html);
            return response($mpdf->Output('customer-ledger-' . $id . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }

        // dd($pageTitle);

        return view('customer-ledger.sell-history-index', compact('pageTitle', 'sells', 'id', 'customerData'));
    }
}
