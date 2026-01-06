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

class AdminController extends Controller
{

    public function dashboard()
    {
        $totalQuotation = Quotation::count();
        $totalPayments = PaymentRecord::sum('amount');
        $totalDueAmount = Invoice::sum('due_amount');
        $totalUnpaidInvoices = Invoice::where('status', 'unpaid')->count();
        $totalPartialPaidInvoices = Invoice::where('status', 'partially_paid')->count();


        $monthlyStatistics = $this->getMonthlyStatistics();
        $months = $monthlyStatistics['months'];
        $amounts = $monthlyStatistics['amounts'];


        // total paid & total unpaid amount
        $totalPaid = Invoice::sum('paid_amount');
        $totalUnpaid = Invoice::sum('due_amount');


        $totalActivebatch = PoultryBatch::active()->count();
        $totalInActivebatch = PoultryBatch::inactive()->count();
        $totalCustomers = Customer::count();
        // dd($totalActivebatch);


        return view('dashboard', compact('totalCustomers', 'totalActivebatch', 'totalInActivebatch', 'totalQuotation', 'totalPayments', 'totalDueAmount', 'totalUnpaidInvoices', 'totalPartialPaidInvoices', 'months', 'amounts', 'totalPaid', 'totalUnpaid'));
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
