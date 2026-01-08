<?php

namespace App\Http\Controllers;

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
}
