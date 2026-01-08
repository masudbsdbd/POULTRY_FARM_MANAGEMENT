@extends('layouts.vertical', ['title' => 'Expense Payment History'])

@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Expense Payment History', 'subtitle' => 'All Paid Expense Transactions'])

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="fe-dollar-sign fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ number_format($totalPayments, 2) }} ৳</h4>
                    <small>Total Paid Amount</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="fe-check-circle fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ $paymentCount }}</h4>
                    <small>Total Transactions</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="fe-bar-chart-2 fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ number_format($avgPayment, 2) }} ৳</h4>
                    <small>Average Payment</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white shadow-sm rounded-4">
                <div class="card-body text-center">
                    <i class="fe-calendar fs-1 mb-2"></i>
                    <h4 class="mb-0">
                        {{ $latestPaymentDate ? $latestPaymentDate->format('d M Y') : '-' }}
                    </h4>
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
            <form method="GET">
                <div class="row g-3">
                    <div class="col">
                        <label>Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $id => $name)
                                <option value="{{ $id }}" {{ request('batch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Customer</label>
                        <select name="customer_id" class="form-select">
                            <option value="">All Customers</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}" {{ request('customer_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Expense</label>
                        <select name="expense_id" class="form-select">
                            <option value="">All Expenses</option>
                            @foreach($expenses as $id => $title)
                                <option value="{{ $id }}" {{ request('expense_id') == $id ? 'selected' : '' }}>
                                    {{ $title }}
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
                    {{-- <div class="col-md-2">
                        <label>Min Amount</label>
                        <input type="number" name="min_amount" class="form-control" value="{{ request('min_amount') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Max Amount</label>
                        <input type="number" name="max_amount" class="form-control" value="{{ request('max_amount') }}">
                    </div> --}}
                    <div class="col">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Note, Title, Batch, Customer">
                    </div>
                    <div class="col align-self-end">
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Table -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>SL</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Expense Title</th>
                        <th>Batch</th>
                        <th>Customer</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = ($payments->currentPage() - 1) * $payments->perPage() + 1 @endphp
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="text-success fw-bold">{{ number_format($payment->amount, 2) }} ৳</td>
                            <td>
                                <a href="{{ route('poultry.expense.payment.index', $payment->expense_id) }}" class="text-primary">
                                    {{ $payment->expense->expense_title ?? 'N/A' }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('customer.manageBatch', $payment->expense->batch_id) }}" class="text-primary">
                                    {{ $payment->expense->batch->batch_name ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $payment->expense->batch->customer->name ?? 'Walk-in' }}</td>
                            <td>{{ $payment->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No expense payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@vite(['resources/js/app.js'])
@endsection