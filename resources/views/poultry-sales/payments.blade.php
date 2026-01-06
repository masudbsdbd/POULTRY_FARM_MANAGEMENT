@extends('layouts.vertical', ['title' => 'Sale Payments'])

@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Sale Payments', 'subtitle' => 'Payment History'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('poultry.sale.index', $sale->batch_id) }}" class="btn btn-info mb-3">
                        <i class="mdi mdi-arrow-left"></i> Back to Sales
                    </a>

                    <div class="card border-left-primary shadow-sm h-100">
                        <div class="card-header bg-gradient-primary text-white py-3">
                            <h5 class="mb-0">
                                <i class="mdi mdi-receipt-text-outline me-2"></i>
                                Sale Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-muted">
                                <!-- Sale Date -->
                                <div class="col-6 col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-calendar-clock text-primary me-3 fs-4"></i>
                                        <div>
                                            <small class="text-uppercase fw-bold">Sale Date</small>
                                            <p class="mb-0 fw-semibold">{{ $sale->sale_date->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sale Type -->
                                <div class="col-6 col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-scale-balance text-info me-3 fs-4"></i>
                                        <div>
                                            <small class="text-uppercase fw-bold">Sale Type</small>
                                            <p class="mb-0 fw-semibold">{{ ucfirst(str_replace('_', ' ', $sale->sale_type)) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Amount -->
                                <div class="col-6 col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-cash-multiple text-success me-3 fs-4"></i>
                                        <div>
                                            <small class="text-uppercase fw-bold">Total Amount</small>
                                            <p class="mb-0 fw-semibold text-success">{{ number_format($sale->total_amount, 2) }} ৳</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paid Amount -->
                                <div class="col-6 col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-check-circle-outline text-primary me-3 fs-4"></i>
                                        <div>
                                            <small class="text-uppercase fw-bold">Paid Amount</small>
                                            <p class="mb-0 fw-semibold text-primary">{{ number_format($sale->paid_amount, 2) }} ৳</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Due Amount -->
                                <div class="col-6 col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-alert-circle-outline text-warning me-3 fs-4"></i>
                                        <div>
                                            <small class="text-uppercase fw-bold">Due Amount</small>
                                            <p class="mb-0 fw-semibold text-warning">
                                                {{ number_format($sale->total_amount - $sale->paid_amount, 2) }} ৳
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Status -->
                                <div class="col-6 col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-credit-card-check-outline text-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }} me-3 fs-4"></i>
                                        <div>
                                            <small class="text-uppercase fw-bold">Payment Status</small>
                                            <div class="mt-1">
                                                <span class="badge bg-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }} fs-6 px-3 py-2">
                                                    {{ ucfirst($sale->payment_status) }}
                                                    @if($sale->payment_status == 'paid')
                                                        <i class="mdi mdi-check ms-1"></i>
                                                    @elseif($sale->payment_status == 'partial')
                                                        <i class="mdi mdi-clock-outline ms-1"></i>
                                                    @else
                                                        <i class="mdi mdi-alert ms-1"></i>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5>Add New Payment</h5>

                    @php
                        $due = $sale->total_amount - $sale->paid_amount;
                        $isFullyPaid = $due <= 0;
                    @endphp

                    <form action="{{ route('poultry.sale.payment.store', $sale->id) }}" method="POST" class="mb-4" id="paymentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="amount" 
                                    class="form-control" 
                                    placeholder="e.g. 5000.00"
                                    value="{{ old('amount') }}"
                                    {{ $isFullyPaid ? 'readonly' : 'required' }}
                                >
                                @if($isFullyPaid)
                                    <small class="text-success">This sale is fully paid.</small>
                                @else
                                    <small class="text-muted">Due: {{ number_format($due, 2) }} ৳</small>
                                @endif
                                @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Payment Date <span class="text-danger">*</span></label>
                                <input 
                                    type="date" 
                                    name="payment_date" 
                                    class="form-control" 
                                    value="{{ old('payment_date', date('Y-m-d')) }}"
                                    {{ $isFullyPaid ? 'readonly' : 'required' }}
                                >
                            </div>

                            <div class="col-md-4">
                                <label>Note (Optional)</label>
                                <input 
                                    type="text" 
                                    name="note" 
                                    class="form-control" 
                                    placeholder="e.g. Cash payment"
                                    value="{{ old('note') }}"
                                    {{ $isFullyPaid ? 'readonly' : '' }}
                                >
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            class="btn btn-primary mt-3"
                            {{ $isFullyPaid ? 'disabled' : '' }}
                        >
                            @if($isFullyPaid)
                                Fully Paid
                            @else
                                Add Payment
                            @endif
                        </button>
                    </form>

                    <h5>Payment History</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sale->payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- <td>{{ $payment->payment_date }}</td> --}}
                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                    <td>{{ number_format($payment->amount, 2) }} ৳</td>
                                    <td>{{ $payment->note ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('poultry.sale.payment.delete', $payment->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm confirmationBtn"
                                                data-question="Are you sure to delete this payment?">
                                                <i class="mdi mdi-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No payments recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection