<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentRecord;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\PoultryBatch;
use App\Models\PoultrySale;

class AdminController extends Controller
{

    public function dashboard()
    {
        $totalActivebatch = PoultryBatch::active()->count();
        $totalInActivebatch = PoultryBatch::inactive()->count();
        $totalCustomers = Customer::count();
        // dd($totalActivebatch);

        $totalSalesAmount = PoultrySale::sum('total_amount');
        $toalPaidAmount = PoultrySale::sum('paid_amount');
        $totalDueAmount = $totalSalesAmount - $toalPaidAmount;


        return view('dashboard', compact('totalCustomers', 'totalActivebatch', 'totalInActivebatch', 'totalDueAmount', 'toalPaidAmount'));
    }



    public function getMonthlyStatistics()
    {
        $year = Carbon::now()->year;

        $payments = PaymentRecord::select(
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->whereYear('payment_date', $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = [];
        $amounts = [];

        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create()->month($m)->format('M'); // Jan, Feb ...
            $amounts[] = $payments[$m] ?? 0;
        }

        return ['months' => $months, 'amounts' => $amounts];
    }
}
