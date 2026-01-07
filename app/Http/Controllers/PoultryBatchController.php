<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PoultryBatch;
use Illuminate\Http\Request;

class PoultryBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $pageTitle = 'Active Batches';
    //     $batches = PoultryBatch::with('customer')->active()->orderBy('batch_start_date', 'desc')->paginate(gs()->pagination);
    //     return view('poultry_batch.index', compact('pageTitle', 'batches'));
    // }

    public function index(Request $request)
    {
        $pageTitle = 'Active Batches';

        $query = PoultryBatch::with('customer')->orderBy('batch_start_date', 'desc');

        // Filters
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('chicken_type')) {
            $query->where('chicken_type', $request->chicken_type);
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate('batch_start_date', '>=', $request->start_date_from);
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate('batch_start_date', '<=', $request->start_date_to);
        }

        if ($request->filled('close_date_from')) {
            $query->whereDate('batch_close_date', '>=', $request->close_date_from);
        }

        if ($request->filled('close_date_to')) {
            $query->whereDate('batch_close_date', '<=', $request->close_date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->active(); // Default: only active
        }

        $batches = $query->get();

        // Summary Data
        $totalBatches = $batches->count();
        $totalChickens = $batches->sum('total_chickens');
        $totalPurchaseCost = $batches->sum(function ($batch) {
            return $batch->total_chickens * $batch->price_per_chicken;
        });
        $broilerCount = $batches->where('chicken_type', 'broiler')->count();
        $layerCount = $batches->where('chicken_type', 'layer')->count();

        // For Filter Dropdown
        $customers = Customer::orderBy('name')->pluck('name', 'id');

        return view('poultry_batch.index', compact(
            'pageTitle',
            'batches',
            'customers',
            'totalBatches',
            'totalChickens',
            'totalPurchaseCost',
            'broilerCount',
            'layerCount'
        ));
    }

    public function inactiveBatches(Request $request)
    {
        $pageTitle = 'Inactive Batches';

        $query = PoultryBatch::with('customer')->orderBy('batch_start_date', 'desc');

        // Filters
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('chicken_type')) {
            $query->where('chicken_type', $request->chicken_type);
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate('batch_start_date', '>=', $request->start_date_from);
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate('batch_start_date', '<=', $request->start_date_to);
        }

        if ($request->filled('close_date_from')) {
            $query->whereDate('batch_close_date', '>=', $request->close_date_from);
        }

        if ($request->filled('close_date_to')) {
            $query->whereDate('batch_close_date', '<=', $request->close_date_to);
        }

        if ($request->filled('close_date_from')) {
            $query->whereDate('batch_close_date', '>=', $request->close_date_from);
        }

        if ($request->filled('close_date_to')) {
            $query->whereDate('batch_close_date', '<=', $request->close_date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->inactive();
        }

        $batches = $query->get();

        // Summary Data
        $totalBatches = $batches->count();
        $totalChickens = $batches->sum('total_chickens');
        $totalPurchaseCost = $batches->sum(function ($batch) {
            return $batch->total_chickens * $batch->price_per_chicken;
        });
        $broilerCount = $batches->where('chicken_type', 'broiler')->count();
        $layerCount = $batches->where('chicken_type', 'layer')->count();

        // For Filter Dropdown
        $customers = Customer::orderBy('name')->pluck('name', 'id');

        return view('poultry_batch.inactive_batches', compact(
            'pageTitle',
            'batches',
            'customers',
            'totalBatches',
            'totalChickens',
            'totalPurchaseCost',
            'broilerCount',
            'layerCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add New Batch';
        $customers = Customer::get();
        return view('poultry_batch.create', compact('pageTitle', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'batch_name' => 'required|string|max:255',
            'batch_number' => 'nullable|string|max:255',
            'chicken_type' => 'required|string|max:255',
            'total_chickens' => 'required|integer|min:1',
            'price_per_chicken' => 'required|numeric|min:0',
            'chicken_grade' => 'nullable|in:A,B,C,D',
            'hatchery_name' => 'required|string|max:255',
            'shed_number' => 'nullable|string|max:255',
            'target_feed_qty' => 'nullable|numeric|min:0',
            'terget_feed_unit' => 'nullable|in:bag,kg',
            'batch_start_date' => 'required|date',
            'batch_description' => 'nullable|string',
        ]);

        // Create new batch
        $batch = new PoultryBatch();
        $batch->customer_id = $validated['customer_id'];
        $batch->batch_name = $validated['batch_name'];
        $batch->batch_number = $validated['batch_number'] ?? null;
        $batch->chicken_type = $validated['chicken_type'];
        $batch->total_chickens = $validated['total_chickens'];
        $batch->price_per_chicken = $validated['price_per_chicken'];
        $batch->chicken_grade = $validated['chicken_grade'] ?? null;
        $batch->hatchery_name = $validated['hatchery_name'];
        $batch->shed_number = $validated['shed_number'] ?? null;
        $batch->target_feed_qty = $validated['target_feed_qty'] ?? null;
        $batch->terget_feed_unit = $validated['terget_feed_unit'] ?? null;
        $batch->batch_start_date = $validated['batch_start_date'];
        $batch->batch_description = $validated['batch_description'] ?? null;

        $batch->save();

        return redirect()->route('poultrybatch.index')
            ->with('success', 'Poultry batch created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Batch';
        $customers = Customer::get();
        $batch = PoultryBatch::find($id);
        return view('poultry_batch.create', compact('pageTitle', 'customers', 'batch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'batch_name' => 'required|string|max:255',
            'batch_number' => 'nullable|string|max:255',
            'chicken_type' => 'required|string|max:255',
            'total_chickens' => 'required|integer|min:1',
            'price_per_chicken' => 'required|numeric|min:0',
            'chicken_grade' => 'nullable|in:A,B,C,D',
            'hatchery_name' => 'required|string|max:255',
            'shed_number' => 'nullable|string|max:255',
            'target_feed_qty' => 'nullable|numeric|min:0',
            'terget_feed_unit' => 'nullable|in:bag,kg',
            'batch_start_date' => 'required|date',
            'batch_description' => 'nullable|string',
        ]);

        // update batch
        $batch = PoultryBatch::find($id);
        $batch->customer_id = $validated['customer_id'];
        $batch->batch_name = $validated['batch_name'];
        $batch->batch_number = $validated['batch_number'] ?? null;
        $batch->chicken_type = $validated['chicken_type'];
        $batch->total_chickens = $validated['total_chickens'];
        $batch->price_per_chicken = $validated['price_per_chicken'];
        $batch->chicken_grade = $validated['chicken_grade'] ?? null;
        $batch->hatchery_name = $validated['hatchery_name'];
        $batch->shed_number = $validated['shed_number'] ?? null;
        $batch->target_feed_qty = $validated['target_feed_qty'] ?? null;
        $batch->terget_feed_unit = $validated['terget_feed_unit'] ?? null;
        $batch->batch_start_date = $validated['batch_start_date'];
        $batch->batch_description = $validated['batch_description'] ?? null;
        $batch->save();

        return redirect()->route('poultrybatch.index')->with('success', 'Batch has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
