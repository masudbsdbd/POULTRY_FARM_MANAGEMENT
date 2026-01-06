<?php

namespace App\Http\Controllers;

use App\Models\PoultryBatch;
use App\Models\PoultrySale;
use App\Models\PoultrySalesPayment;
use Illuminate\Http\Request;

class PoultrySaleController extends Controller
{
    public function index(Request $request, $batch_id)
    {
        $bathInfo = PoultryBatch::findOrFail($batch_id);
        $sales = PoultrySale::where('batch_id', $batch_id)->latest()->get();

        return view('poultry-sales.index', compact('bathInfo', 'sales'));
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
            'total_amount'  => $total_amount,
            'payment_status' => $payment_status,
        ]);

        PoultrySalesPayment::create([
            'sale_id'       => $sale->id,
            'amount'        => $request->paid_amount,
        ]);

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
}
