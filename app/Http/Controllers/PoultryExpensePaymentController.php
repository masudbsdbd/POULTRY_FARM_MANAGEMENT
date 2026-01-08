<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PoultryBatch;
use App\Models\PoultryExpense;
use App\Models\PoultryExpensePayment;
use Illuminate\Http\Request;

class PoultryExpensePaymentController extends Controller
{
    // Payment History of a specific Expense
    public function index($expense_id)
    {
        $expense = PoultryExpense::with('payments')->findOrFail($expense_id);
        $bathInfo = $expense->batch;

        $payments = $expense->payments()->latest()->get();

        $totalPaid = $payments->sum('amount');
        $dueAmount = $expense->total_amount - $totalPaid;

        return view('poultry-expenses.payments', compact(
            'expense',
            'bathInfo',
            'payments',
            'totalPaid',
            'dueAmount'
        ));
    }

    // Store New Payment
    public function store(Request $request, $expense_id)
    {
        $expense = PoultryExpense::findOrFail($expense_id);

        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $due = $expense->total_amount - $expense->payments()->sum('amount');

        if ($request->amount > $due) {
            return back()->withErrors(['amount' => "Cannot pay more than due amount (৳{$due})"]);
        }

        PoultryExpensePayment::create([
            'expense_id' => $expense->id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
        ]);

        // Update Expense paid_amount & status
        $totalPaid = $expense->payments()->sum('amount');
        $expense->paid_amount = $totalPaid;
        $expense->payment_status = $totalPaid >= $expense->total_amount ? 'paid' : ($totalPaid > 0 ? 'partial' : 'due');
        $expense->save();

        return back()->with('success', 'Payment recorded successfully.');
    }

    // Delete Payment
    public function destroy($payment_id)
    {
        $payment = PoultryExpensePayment::findOrFail($payment_id);
        $expense = $payment->expense;

        $payment->delete();

        // Recalculate
        $totalPaid = $expense->payments()->sum('amount');
        $expense->paid_amount = $totalPaid;
        $expense->payment_status = $totalPaid >= $expense->total_amount ? 'paid' : ($totalPaid > 0 ? 'partial' : 'due');
        $expense->save();

        return back()->with('success', 'Payment deleted.');
    }


    public function expensePaymentsHistory(Request $request)
    {
        $pageTitle = 'Expense Payment History';

        $query = PoultryExpensePayment::with(['expense.batch.customer']);

        // Filters
        if ($request->filled('expense_id')) {
            $query->where('expense_id', $request->expense_id);
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('expense', fn($q) => $q->where('batch_id', $request->batch_id));
        }

        if ($request->filled('customer_id')) {
            $query->whereHas('expense.batch', fn($q) => $q->where('customer_id', $request->customer_id));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        // if ($request->filled('min_amount')) {
        //     $query->where('amount', '>=', $request->min_amount);
        // }

        // if ($request->filled('max_amount')) {
        //     $query->where('amount', '<=', $request->max_amount);
        // }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('expense', fn($e) => $e->where('expense_title', 'like', "%{$search}%"))
                    ->orWhereHas('expense.batch', fn($b) => $b->where('batch_name', 'like', "%{$search}%"))
                    ->orWhereHas('expense.batch.customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        // All Filtered for Summary
        $allPayments = (clone $query)->get();

        $totalPayments = $allPayments->sum('amount');
        $paymentCount = $allPayments->count();
        $avgPayment = $paymentCount > 0 ? $allPayments->avg('amount') : 0;
        $latestPaymentDate = $allPayments->max('payment_date');

        // Paginated for Table
        $payments = $query->orderBy('payment_date', 'desc')->paginate(10);

        // Filters Dropdown
        $batches = PoultryBatch::orderBy('batch_name')->pluck('batch_name', 'id');
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $expenses = PoultryExpense::orderBy('expense_date', 'desc')->get()->pluck('expense_title', 'id');

        return view('poultry-expenses.expense-history', compact(
            'pageTitle',
            'payments',
            'totalPayments',
            'paymentCount',
            'avgPayment',
            'latestPaymentDate',
            'batches',
            'customers',
            'expenses'
        ));
    }
}
