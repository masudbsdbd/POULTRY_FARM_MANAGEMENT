<?php

namespace App\Http\Controllers;

use App\Models\PoultryBatch;
use App\Models\PoultryOthersIncome;
use Illuminate\Http\Request;

class PoultryOthersIncomeController extends Controller
{
    public function index($batch_id)
    {
        $bathInfo = PoultryBatch::findOrFail($batch_id);
        $incomes = PoultryOthersIncome::where('batch_id', $batch_id)->latest()->paginate(gs()->pagination);
        $totalIncome = $incomes->sum('amount');

        return view('Poultry-Others-Income.index', compact('bathInfo', 'incomes', 'totalIncome'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'income_date'  => 'required|date',
            'note'         => 'nullable|string',
        ]);

        PoultryOthersIncome::create($request->all() + ['batch_id' => $request->batch_id]);

        return redirect()->back()->with('success', 'Other income added successfully.');
    }

    public function update(Request $request, $id)
    {
        $income = PoultryOthersIncome::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'income_date'  => 'required|date',
            'note'         => 'nullable|string',
        ]);

        $income->update($request->all());

        return redirect()->back()->with('success', 'Other income updated successfully.');
    }

    public function destroy($id)
    {
        $income = PoultryOthersIncome::findOrFail($id);
        $income->delete();

        return redirect()->back()->with('success', 'Other income deleted successfully.');
    }
}
