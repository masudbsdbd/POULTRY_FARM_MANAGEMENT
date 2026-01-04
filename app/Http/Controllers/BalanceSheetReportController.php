<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Sell;
use App\Models\Purchase;
use App\Models\Supplier; 
use App\Models\Customer; 

class BalanceSheetReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:balance-sheet-report-list', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'All Balance Sheet Report Info';
        $searchCondition = [];

        $totalSupplierAdvance = Supplier::sum('advance');
        $totalCustomerAdvance = Customer::sum('advance');

        $range = $request->range;

        if ($range) {
            $dates = explode(' to ', $range);
            $givenDates = [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59',
            ];

            $accounts = Account::whereBetween('created_at', [$givenDates[0], $givenDates[1]])->latest()->get();
        } elseif (!empty($request->date)) {
            $date = \Carbon\Carbon::parse($request->date)->startOfDay();
            $accounts = Account::whereDate('created_at', '=', $date->toDateString())->latest()->get();
        } else {
            $accounts = Account::latest()->get();
        }

        $groupedByDate = [];

        foreach ($accounts as $account) {
            $date = \Carbon\Carbon::parse($account->created_at)->toDateString();
            $sell_id = $account->sell_id;
            $purchase_id = $account->purchase_id;

            $type = $account->type;

            $customer_advance = 0;
            $supplier_advance = 0;

            if ($type == 5) {
                $customer_advance += $account->credit;
            }

            if ($type == 6) {
                $supplier_advance += $account->debit;
            }

            $sell_qty = Sell::where('id', $sell_id)->pluck('total_qty')->first();
            $sell_main_price = Sell::where('id', $sell_id)->pluck('total_price')->first();
            $sell_payment_received = Sell::where('id', $sell_id)->pluck('payment_received')->first();

            $purchase_qty = Purchase::where('id', $purchase_id)->pluck('total_qty')->first();
            $purchase_main_price = Purchase::where('id', $purchase_id)->pluck('total_price')->first();
            $purchase_payment_received = Purchase::where('id', $purchase_id)->pluck('payment_received')->first();

            if (!isset($groupedByDate[$date])) {
                $groupedByDate[$date] = [
                    'balance' => 0,
                    'credit' => 0,
                    'debit' => 0,
                    'sell_qty' => 0,
                    'purchase_qty' => 0,
                    'customer_advance' => 0,
                    'supplier_advance' => 0,
                    'processed_sell_ids' => [],
                    'total_sell_main_price' => 0,
                    'total_sell_payment_received' => 0,
                    'processed_purchase_ids' => [],
                    'total_purchase_main_price' => 0,
                    'total_purchase_payment_received' => 0,
                ];
            }

            if ($type == 5) {
                $groupedByDate[$date]['customer_advance'] += $customer_advance;
            }
            if ($type == 6) {
                $groupedByDate[$date]['supplier_advance'] += $supplier_advance;
            }

            if (!in_array($purchase_id, $groupedByDate[$date]['processed_purchase_ids'])) {
                $groupedByDate[$date]['purchase_qty'] += $purchase_qty;
                $groupedByDate[$date]['total_purchase_main_price'] +=  $purchase_main_price;
                $groupedByDate[$date]['total_purchase_payment_received'] +=  $purchase_payment_received;
                $groupedByDate[$date]['processed_purchase_ids'][] = $purchase_id;
            }

            if (!in_array($sell_id, $groupedByDate[$date]['processed_sell_ids'])) {
                $groupedByDate[$date]['sell_qty'] += $sell_qty;
                $groupedByDate[$date]['total_sell_main_price'] +=  $sell_main_price;
                $groupedByDate[$date]['total_sell_payment_received'] +=  $sell_payment_received;
                $groupedByDate[$date]['processed_sell_ids'][] = $sell_id;
            }

            $groupedByDate[$date]['balance'] += $account->balance;
            $groupedByDate[$date]['credit'] += $account->credit;
            $groupedByDate[$date]['debit'] += $account->debit;
        }

        foreach ($groupedByDate as $date => $data) {
            unset($groupedByDate[$date]['processed_sell_ids']);
            unset($groupedByDate[$date]['processed_purchase_ids']);
        }

        return view('balance-sheet-report.index', compact('pageTitle', 'groupedByDate', 'totalSupplierAdvance','totalCustomerAdvance'));
    }

}
