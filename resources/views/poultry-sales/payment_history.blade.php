@extends('layouts.vertical', ['title' => 'Payment History'])

@section('content')
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Sales Payment History', 'subtitle' => 'All Transactions'])

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fe-dollar-sign fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ number_format($totalPayments, 2) }} ৳</h4>
                    <small>Total Paid Amount</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fe-check-circle fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ $paymentCount }}</h4>
                    <small>Total Transactions</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fe-bar-chart-2 fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ number_format($avgPayment, 2) }} ৳</h4>
                    <small>Average Payment</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fe-calendar fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ $payments->count() > 0 ? $payments->first()->payment_date->format('d M Y') : '-' }}</h4>
                    <small>Latest Payment Date</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fe-filter me-2"></i> Advanced Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('poultry.sales.payments.history') }}">
                <div class="row g-3">
                    <div class="col">
                        <label>Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $id => $name)
                                <option value="{{ $id }}" {{ request('batch_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Customer</label>
                        <select name="customer_id" class="form-select">
                            <option value="">All Customers</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}" {{ request('customer_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Sale ID</label>
                        <select name="sale_id" class="form-select">
                            <option value="">All Sales</option>
                            @foreach($sales as $id => $saleId)
                                <option value="{{ $id }}" {{ request('sale_id') == $id ? 'selected' : '' }}>
                                    Sale #{{ $saleId }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 col-lg-2 align-self-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fe-search"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-3 col-lg-1 align-self-end">
                        <a href="{{ route('poultry.sales.payments.history') }}" class="btn btn-secondary w-100">
                            <i class="fe-refresh-cw"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-body">
            <table id="paymentHistoryTable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Sale ID</th>
                        <th>Batch</th>
                        <th>Customer</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ isset($payment->payment_date) ? $payment->payment_date->format('d M Y') : '-' }}</td>
                            <td>{{ number_format($payment->amount, 2) }} ৳</td>
                            <td>Sale #{{ $payment->sale_id }}</td>
                            <td>{{ $payment->sale->batch->batch_name ?? '-' }}</td>
                            <td>{{ $payment->sale->batch->customer->name ?? '-' }}</td>
                            <td>{{ $payment->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js'])
@endsection