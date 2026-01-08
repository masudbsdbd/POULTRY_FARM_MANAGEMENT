<?php

namespace App\Http\Controllers;

use App\Models\PoultryBatch;
use App\Models\PoultryExpense;
use App\Models\PoultryExpensePayment;
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
        $totalDueExpenses = $expenses->whereIn('payment_status', ['due', 'partial'])->sum('total_amount');
        $totalPaidExpenses = $expenses->where('payment_status', 'paid')->sum('total_amount');

        $totalTransactions = $expenses->count();
        $latestExpenseDate = $expenses->max('expense_date');

        return view('poultry-expenses.index', compact('bathInfo', 'expenses', 'invNumber', 'totalExpenses', 'totalTransactions', 'latestExpenseDate', 'totalDueExpenses', 'totalPaidExpenses'));
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
            'description'      => 'nullable|string',
            'batch_id'         => 'required|exists:poultry_batches,id',
        ]);

        // Auto calculate total_amount (safety)
        $total_amount = $request->quantity * $request->price;

        // Default values
        $paid_amount = 0;
        $payment_status = 'due';

        if ($request->transaction_type === 'cash') {
            $paid_amount = $total_amount;
            $payment_status = 'paid';
        }
        // যদি due হয় → paid_amount = 0, payment_status = 'due'

        // dd($payment_status);

        $expense = PoultryExpense::create([
            'batch_id'          => $request->batch_id,
            'expense_date'      => $request->expense_date,
            'expense_title'     => $request->expense_title,
            'invoice_number'    => $request->invoice_number,
            'category'          => $request->category,
            'feed_name'         => $request->feed_name,
            'feed_type'         => $request->feed_type,
            'medicine_name'     => $request->medicine_name,
            'transaction_type'  => $request->transaction_type,
            'quantity'          => $request->quantity,
            'unit'              => $request->unit,
            'price'             => $request->price,
            'total_amount'      => $total_amount,
            'paid_amount'       => $paid_amount,
            'payment_status'    => $payment_status,
            'description'       => $request->description,
        ]);

        // শুধু যদি cash হয় এবং তুমি চাও cash payment-ও history-তে দেখাতে
        // তাহলে এটা যোগ করতে পারো (অপশনাল)
        if ($request->transaction_type === 'cash') {
            PoultryExpensePayment::create([
                'expense_id'   => $expense->id,
                'payment_date' => $request->expense_date, // একই তারিখে ধরে
                'amount'       => $total_amount,
                'note'         => 'Cash payment at expense creation',
            ]);
        }
        // যদি না চাও — কিছু করো না (যা বর্তমানে ঠিক আছে)

        return redirect()->back()->with('success', 'Expense created successfully.');
    }

    public function update(Request $request, $id)
    {
        $expense = PoultryExpense::findOrFail($id);

        $request->validate([
            'expense_date'     => 'required|date',
            'expense_title'    => 'required|string|max:255',
            'invoice_number'   => 'nullable|string|max:255',
            'category'         => 'required|in:feed,medicine,transportation,bedding,labor,utilities,death_loss,miscellaneous,bad_debt,bio_security,chickens,other',
            'transaction_type' => 'required|in:cash,due',
            'quantity'         => 'required|numeric|min:0',
            'unit'             => 'required|in:bag,piece,kg,litre,gram,bottle,pack,other',
            'price'            => 'required|numeric|min:0',
            'total_amount'     => 'required|numeric|min:0',
            'description'      => 'nullable|string',
        ]);

        // Auto recalculate total_amount from quantity * price (prevent mismatch)
        $new_total_amount = $request->quantity * $request->price;

        // Existing paid amount from payments table
        $current_paid = $expense->payments()->sum('amount');

        // New total amount after edit
        $new_total = $new_total_amount;

        // Validation: Paid cannot exceed new total
        if ($current_paid > $new_total) {
            return back()->withErrors([
                'total_amount' => "Total amount cannot be less than already paid amount (৳" . number_format($current_paid, 2) . "). Current paid: ৳" . number_format($current_paid, 2)
            ])->withInput();
        }

        // Determine new payment status
        $new_payment_status = 'due';
        $new_paid_amount = $current_paid;

        if ($request->transaction_type === 'cash') {
            // If changing to cash, assume full paid
            $new_paid_amount = $new_total;
            $new_payment_status = 'paid';
        } elseif ($current_paid >= $new_total) {
            $new_payment_status = 'paid';
        } elseif ($current_paid > 0) {
            $new_payment_status = 'partial';
        }
        // else remains 'due'

        // Update the expense
        $expense->update([
            'expense_date'      => $request->expense_date,
            'expense_title'     => $request->expense_title,
            'invoice_number'    => $request->invoice_number,
            'category'          => $request->category,
            'feed_name'         => $request->feed_name ?? null,
            'feed_type'         => $request->feed_type ?? null,
            'medicine_name'     => $request->medicine_name ?? null,
            'transaction_type'  => $request->transaction_type,
            'quantity'          => $request->quantity,
            'unit'              => $request->unit,
            'price'             => $request->price,
            'total_amount'      => $new_total,
            'paid_amount'       => $new_paid_amount,
            'payment_status'    => $new_payment_status,
            'description'       => $request->description,
        ]);

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
