@extends('layouts.vertical', ['title' => 'Expense Payment History'])

@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Expense Payment History', 'subtitle' => $expense->expense_title])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('poultry.expense.index', $bathInfo->id) }}" class="btn btn-info mb-3">
                        <i class="mdi mdi-arrow-left"></i> Back to Expenses
                    </a>

                    <!-- Expense Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5>Total Amount</h5>
                                    <h3 class="text-primary">{{ number_format($expense->total_amount, 2) }} ৳</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5>Paid Amount</h5>
                                    <h3 class="text-success">{{ number_format($totalPaid, 2) }} ৳</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5>Due Amount</h5>
                                    <h3 class="text-danger">{{ number_format($dueAmount, 2) }} ৳</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5>Status</h5>
                                    <span class="badge bg-{{ $expense->payment_status == 'paid' ? 'success' : ($expense->payment_status == 'partial' ? 'warning' : 'danger') }} fs-6">
                                        {{ ucfirst($expense->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Payment Form -->
                    @if($dueAmount > 0)
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Record New Payment</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('poultry.expense.payment.store', $expense->id) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Payment Date</label>
                                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Amount <small class="text-muted">(Due: {{ number_format($dueAmount, 2) }} ৳)</small></label>
                                            <input type="number" step="0.01" name="amount" class="form-control" required min="0.01" max="{{ $dueAmount }}">
                                        </div>
                                        <div class="col-md-4 align-self-end">
                                            <button type="submit" class="btn btn-primary w-100">Record Payment</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success text-center">
                            <i class="fe-check-circle fs-1"></i><br>
                            This expense is fully paid.
                        </div>
                    @endif

                    <!-- Payment History Table -->
                    <h5>Payment History</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                    <td class="text-success fw-bold">{{ number_format($payment->amount, 2) }} ৳</td>
                                    <td>
                                        <form action="{{ route('poultry.expense.payment.destroy', $payment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm confirmationBtn"
                                                data-question="Delete this payment?">
                                                <i class="mdi mdi-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No payments recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection