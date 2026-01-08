<?php

namespace App\Http\Controllers;

use App\Models\PoultryBatch;
use App\Models\PoultryExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoultryExpenseController extends Controller
{
    public function index(Request $request, $batch_id)
    {
        $bathInfo = PoultryBatch::findOrFail($batch_id);
        $expenses = PoultryExpense::where('batch_id', $batch_id)->latest()->get();

        $invNumber = $this->getNextInvoiceNumber();


        // Summary Calculations
        $totalExpenses = $expenses->sum('total_amount');

        $cashExpenses = $expenses->where('transaction_type', 'cash')->sum('total_amount');
        $dueExpenses = $expenses->where('transaction_type', 'due')->sum('total_amount');

        $totalPaidFromDue = $expenses->where('transaction_type', 'due')->sum('paid_amount');
        $totalDueRemaining = $dueExpenses - $totalPaidFromDue;

        $fullyPaidCount = $expenses->where('transaction_type', 'due')->where('payment_status', 'paid')->count();
        $partiallyPaidCount = $expenses->where('transaction_type', 'due')->where('payment_status', 'partial')->count();

        $totalTransactions = $expenses->count();
        $latestExpenseDate = $expenses->max('expense_date');

        return view('poultry-expenses.index', compact('bathInfo', 'expenses', 'invNumber', 'totalExpenses', 'totalTransactions', 'latestExpenseDate', 'cashExpenses', 'dueExpenses', 'totalPaidFromDue', 'totalDueRemaining', 'fullyPaidCount', 'partiallyPaidCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date'     => 'required|date',
            'expense_title'    => 'required|string|max:255',
            'category'         => 'required|in:feed,medicine,transportation,bedding,labor,utilities,death_loss,miscellaneous,bad_debt,bio_security,chickens,other',
            'transaction_type' => 'required|in:cash,due',
            'quantity'         => 'required|numeric|min:0',
            'unit'             => 'required|in:bag,piece,kg,litre,gram,bottle,pack,other',
            'price'            => 'required|numeric|min:0',
            'total_amount'     => 'required|numeric|min:0',
        ]);

        PoultryExpense::create($request->all());

        return redirect()->back()->with('success', 'Expense created successfully.');
    }

    public function update(Request $request, $id)
    {
        $expense = PoultryExpense::findOrFail($id);

        $request->validate([
            'expense_date'     => 'required|date',
            'expense_title'    => 'required|string|max:255',
            'category'         => 'required|in:feed,medicine,transportation,bedding,labor,utilities,death_loss,miscellaneous,bad_debt,bio_security,chickens,other',
            'transaction_type' => 'required|in:cash,due',
            'quantity'         => 'required|numeric|min:0',
            'unit'             => 'required|in:bag,piece,kg,litre,gram,bottle,pack,other',
            'price'            => 'required|numeric|min:0',
            'total_amount'     => 'required|numeric|min:0',
        ]);

        $expense->update($request->all());

        return redirect()->back()->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = PoultryExpense::findOrFail($id);
        $expense->delete();

        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }


    // InvoiceController.php বা যেকোনো কন্ট্রোলারে এই মেথড যোগ করো

    public function getNextInvoiceNumber()
    {
        // তোমার ইনভয়েস টেবিলের নাম যদি invoices হয়
        $lastInvoice = DB::table('invoices')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastInvoice || empty($lastInvoice->invoice_number)) {
            $nextNumber = 1;
        } else {
            // শেষ ইনভয়েস নম্বর থেকে শুধু নম্বর অংশ বের করো (যেমন INV-001 → 1)
            preg_match('/\d+$/', $lastInvoice->invoice_number, $matches);
            $lastNumber = isset($matches[0]) ? (int)$matches[0] : 0;
            $nextNumber = $lastNumber + 1;
        }

        // ফরম্যাট করো (যেমন: INV-0001, INV-0010)
        $formatted = 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // অথবা যদি শুধু নম্বর চাও: 0001, 0010 ইত্যাদি
        // $formatted = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $formatted;
    }
}
