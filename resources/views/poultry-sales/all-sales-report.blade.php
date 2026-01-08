@extends('layouts.vertical', ['title' => 'All Sales Report'])

@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'All Sales Report', 'subtitle' => 'Complete Sales History'])

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <i class="fe-shopping-cart text-primary fs-1 mb-3"></i>
                    <h3 class="fw-bold text-primary">{{ $totalSales }}</h3>
                    <p class="text-muted mb-0">Total Sales</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <i class="fe-dollar-sign text-success fs-1 mb-3"></i>
                    <h3 class="fw-bold text-success">{{ number_format($totalRevenue, 2) }} ৳</h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <i class="fe-check-circle text-info fs-1 mb-3"></i>
                    <h3 class="fw-bold text-info">{{ number_format($totalPaid, 2) }} ৳</h3>
                    <p class="text-muted mb-0">Total Paid</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <i class="fe-alert-circle text-warning fs-1 mb-3"></i>
                    <h3 class="fw-bold text-warning">{{ number_format($totalDue, 2) }} ৳</h3>
                    <p class="text-muted mb-0">Total Due</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <i class="fe-package text-secondary fs-1 mb-3"></i>
                    <h3 class="fw-bold text-secondary">{{ number_format($byPiece) }} pcs</h3>
                    <p class="text-muted mb-0">Sold by Piece</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <i class="fe-weight text-purple fs-1 mb-3"></i>
                    <h3 class="fw-bold text-purple">{{ number_format($byWeight, 2) }} kg</h3>
                    <p class="text-muted mb-0">Sold by Weight</p>
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
                    <div class="col-md-3">
                        <label>Batch</label>
                        <select name="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $id => $name)
                                <option value="{{ $id }}" {{ request('batch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Customer</label>
                        <select name="customer_id" class="form-select">
                            <option value="">All Customers</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}" {{ request('customer_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Sale Type</label>
                        <select name="sale_type" class="form-select">
                            <option value="">All</option>
                            <option value="by_piece" {{ request('sale_type') == 'by_piece' ? 'selected' : '' }}>By Piece</option>
                            <option value="by_weight" {{ request('sale_type') == 'by_weight' ? 'selected' : '' }}>By Weight</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="">All</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="due" {{ request('payment_status') == 'due' ? 'selected' : '' }}>Due</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Channel</label>
                        <select name="sales_channel" class="form-select">
                            <option value="">All</option>
                            <option value="wholesale" {{ request('sales_channel') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                            <option value="retail" {{ request('sales_channel') == 'retail' ? 'selected' : '' }}>Retail</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="fe-search"></i> Apply</button>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <a href="{{ route('poultry.sale.reports.all-sales') }}" class="btn btn-secondary w-100"><i class="fe-refresh-cw"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card">
        <div class="card-body">
            <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Sale Date</th>
                        <th>Batch</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Qty / Weight</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Channel</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sale->sale_date->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('customer.manageBatch', $sale->batch_id) }}" class="text-primary fw-bold">
                                    {{ $sale->batch->batch_name ?? 'Deleted Batch' }}
                                </a>
                            </td>
                            <td>{{ $sale->batch->customer->name ?? 'Walk-in' }}</td>
                            <td>
                                <span class="badge bg-{{ $sale->sale_type == 'by_piece' ? 'info' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $sale->sale_type)) }}
                                </span>
                            </td>
                            <td>
                                @if($sale->sale_type == 'by_piece')
                                    {{ number_format($sale->quantity) }} pcs
                                @else
                                    {{ number_format($sale->weight_kg, 2) }} kg
                                @endif
                            </td>
                            <td>{{ number_format($sale->rate, 2) }} ৳</td>
                            <td class="fw-bold">{{ number_format($sale->total_amount, 2) }} ৳</td>
                            <td class="text-success">{{ number_format($sale->paid_amount, 2) }} ৳</td>
                            <td class="text-danger">{{ number_format($sale->total_amount - $sale->paid_amount, 2) }} ৳</td>
                            <td>
                                <span class="badge bg-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $sale->sales_channel == 'wholesale' ? 'secondary' : 'primary' }}">
                                    {{ ucfirst($sale->sales_channel) }}
                                </span>
                            </td>
                            <td>{{ $sale->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                            <td class="d-none text-center py-5 text-muted">No sales recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $sales->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js'])

<script>
    $('#basic-datatables').DataTable({
        responsive: true,
         pageLength: 10,
         lengthMenu: [10, 25, 50, 100],
         order: [[1, 'desc']],
         language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries"
            },
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries"
            },
            paging: false
        // অন্য অপশন
    });
</script>
@endsection