<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PoultryBatch;
use App\Models\PoultrySale;
use App\Models\PoultrySalesPayment;
use Illuminate\Http\Request;

class PoultrySaleController extends Controller
{
    // public function index(Request $request, $batch_id)
    // {
    //     $bathInfo = PoultryBatch::findOrFail($batch_id);
    //     $sales = PoultrySale::where('batch_id', $batch_id)->latest()->get();

    //     return view('poultry-sales.index', compact('bathInfo', 'sales'));
    // }

    public function index(Request $request, $batch_id)
    {
        $bathInfo = PoultryBatch::findOrFail($batch_id);

        $sales = PoultrySale::where('batch_id', $batch_id)->latest()->get();

        // Summary Calculations
        $totalSalesCount = $sales->count();

        $totalSaleAmount = $sales->sum('total_amount');
        $totalPaidAmount = $sales->sum('paid_amount');
        $totalDueAmount = $totalSaleAmount - $totalPaidAmount;

        $totalPiecesSold = $sales->where('sale_type', 'by_piece')->sum('quantity');
        $totalKgSold = $sales->where('sale_type', 'by_weight')->sum('weight_kg');

        // Customer যদি relation থাকে (যদি না থাকে তাহলে skip করো)
        // উদাহরণ: $customer = $bathInfo->customer ?? null;

        return view('poultry-sales.index', compact(
            'bathInfo',
            'sales',
            'totalSalesCount',
            'totalSaleAmount',
            'totalPaidAmount',
            'totalDueAmount',
            'totalPiecesSold',
            'totalKgSold'
            // 'customer' // যদি থাকে
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date'       => 'required|date',
            'sale_type'       => 'required|in:by_weight,by_piece',
            'quantity'        => 'required|numeric|min:0',
            'weight_kg'       => 'nullable|required_if:sale_type,by_weight|numeric|min:0',
            'rate'            => 'required|numeric|min:0',
            'paid_amount'     => 'required|numeric|min:0',
            'payment_date'    => 'required|date',
            'sales_channel'   => 'required|in:wholesale,retail',
        ]);

        $total_amount = $request->sale_type === 'by_weight'
            ? $request->weight_kg * $request->rate
            : $request->quantity * $request->rate;

        $payment_status = 'due';
        if ($request->paid_amount >= $total_amount) {
            $payment_status = 'paid';
        } elseif ($request->paid_amount > 0) {
            $payment_status = 'partial';
        }

        $sale = PoultrySale::create($request->except('total_amount') + [
            'batch_id'      => $request->batch_id,
            'sale_date'     => $request->sale_date,
            'total_amount'  => $total_amount,
            'payment_status' => $payment_status,
        ]);

        if ($request->paid_amount > 0) {
            PoultrySalesPayment::create([
                'sale_id'       => $sale->id,
                'payment_date'  => $request->payment_date,
                'amount'        => $request->paid_amount,
            ]);
        }

        return redirect()->back()->with('success', 'Sale recorded successfully.');
    }

    public function update(Request $request, $id)
    {
        $sale = PoultrySale::findOrFail($id);

        $request->validate([
            'sale_date'       => 'required|date',
            'sale_type'       => 'required|in:by_weight,by_piece',
            'quantity'        => 'required|numeric|min:0',
            'weight_kg'       => 'nullable|required_if:sale_type,by_weight|numeric|min:0',
            'rate'            => 'required|numeric|min:0',
            'paid_amount'     => 'required|numeric|min:0',
            'payment_date'    => 'required|date',
            'sales_channel'   => 'required|in:wholesale,retail',
        ]);

        $total_amount = $request->sale_type === 'by_weight'
            ? $request->weight_kg * $request->rate
            : $request->quantity * $request->rate;

        $payment_status = 'due';
        if ($request->paid_amount >= $total_amount) {
            $payment_status = 'paid';
        } elseif ($request->paid_amount > 0) {
            $payment_status = 'partial';
        }

        $sale->update($request->except('total_amount') + [
            'total_amount'   => $total_amount,
            'payment_status' => $payment_status,
        ]);

        return redirect()->back()->with('success', 'Sale updated successfully.');
    }

    public function destroy($id)
    {
        $sale = PoultrySale::findOrFail($id);
        $sale->delete();

        return redirect()->back()->with('success', 'Sale deleted successfully.');
    }


    public function getPayments($sale_id)
    {
        $sale = PoultrySale::with('payments')->findOrFail($sale_id);
        $bathInfo = $sale->batch; // assuming you have batch relation

        return view('poultry-sales.payments', compact('sale', 'bathInfo'));
    }

    public function createPayments(Request $request, $sale_id)
    {
        $sale = PoultrySale::findOrFail($sale_id);

        $due = $sale->total_amount - $sale->paid_amount;

        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                "max:$due"
            ],
            'payment_date' => 'required|date',
            'note' => 'nullable|string'
        ], [
            'amount.max' => "You cannot receive more than the due amount (৳" . number_format($due, 2) . ")."
        ]);

        PoultrySalesPayment::create([
            'sale_id' => $sale->id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'note' => $request->note
        ]);

        // Update paid_amount & status
        $totalPaid = $sale->payments()->sum('amount');
        $sale->paid_amount = $totalPaid;
        $sale->payment_status = $totalPaid >= $sale->total_amount ? 'paid' : ($totalPaid > 0 ? 'partial' : 'due');
        $sale->save();

        return redirect()->back()->with('success', 'Payment added successfully.');
    }

    public function paymentDelete($payment_id)
    {
        $payment = PoultrySalesPayment::findOrFail($payment_id);
        $sale = $payment->sale;

        $payment->delete();

        // Recalculate paid amount and status
        $totalPaid = $sale->payments()->sum('amount');
        $sale->paid_amount = $totalPaid;

        if ($totalPaid >= $sale->total_amount) {
            $sale->payment_status = 'paid';
        } elseif ($totalPaid > 0) {
            $sale->payment_status = 'partial';
        } else {
            $sale->payment_status = 'due';
        }

        $sale->save();

        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }



    public function paymentHistory(Request $request)
    {
        $query = PoultrySalesPayment::with(['sale.batch.customer'])
            ->orderBy('payment_date', 'desc');

        // Filters
        if ($request->filled('sale_id')) {
            $query->where('sale_id', $request->sale_id);
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        if ($request->filled('customer_id')) {
            $query->whereHas('sale.batch', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $payments = $query->get();

        // Summary
        $totalPayments = $payments->sum('amount');
        $avgPayment = $payments->count() > 0 ? $payments->avg('amount') : 0;
        $paymentCount = $payments->count();

        // For Filter Dropdowns
        $batches = PoultryBatch::orderBy('batch_name')->pluck('batch_name', 'id');
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $sales = PoultrySale::orderBy('sale_date', 'desc')->pluck('id', 'id');

        return view('poultry-sales.payment_history', compact(
            'payments',
            'totalPayments',
            'avgPayment',
            'paymentCount',
            'batches',
            'customers',
            'sales'
        ));
    }
}
