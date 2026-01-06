@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
      'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
      'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
      'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'All Batches', 'subtitle' => 'Batch Management'])

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fe-package fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ $totalBatches }}</h4>
                    <small>Total Active Batches</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fe-feather fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ number_format($totalChickens) }}</h4>
                    <small>Total Chickens</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fe-dollar-sign fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ number_format($totalPurchaseCost, 2) }} ৳</h4>
                    <small>Total Purchase Cost</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fe-activity fs-1 mb-2"></i>
                    <h4 class="mb-0">{{ $broilerCount }} / {{ $layerCount }}</h4>
                    <small>Broiler / Layer Batches</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fe-filter me-2"></i> Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('poultrybatch.inactive') }}">
                <div class="row g-3">
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
                        <label>Chicken Type</label>
                        <select name="chicken_type" class="form-select">
                            <option value="">All</option>
                            <option value="broiler" {{ request('chicken_type') == 'broiler' ? 'selected' : '' }}>Broiler</option>
                            <option value="layer" {{ request('chicken_type') == 'layer' ? 'selected' : '' }}>Layer</option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Start Date From</label>
                        <input type="date" name="start_date_from" class="form-control" value="{{ request('start_date_from') }}">
                    </div>
                    <div class="col">
                        <label>Start Date To</label>
                        <input type="date" name="start_date_to" class="form-control" value="{{ request('start_date_to') }}">
                    </div>
                    <div class="col">
                        <label>Close Date From</label>
                        <input type="date" name="close_date_from" class="form-control" value="{{ request('close_date_from') }}">
                    </div>
                    <div class="col">
                        <label>Close Date To</label>
                        <input type="date" name="close_date_to" class="form-control" value="{{ request('close_date_to') }}">
                    </div>
                    <div class="col-md-3 col-lg-2 align-self-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fe-search me-1"></i> Apply Filter
                        </button>
                    </div>
                    <div class="col-md-3 col-lg-1 align-self-end">
                        <a href="{{ route('poultrybatch.inactive') }}" class="btn btn-secondary w-100"> 
                            <i class="fe-refresh-cw"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="header-title">{{ $pageTitle }}</h4>
                <a href="{{ route('poultrybatch.create') }}" class="btn btn-primary">
                    <i class="fe-plus me-1"></i> Add New Batch
                </a>
            </div>

            <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Batch Name</th>
                        <th>Batch No</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Chickens</th>
                        <th>Grade</th>
                        <th>Start Date</th>
                        <th>Close Date</th>
                        <th>Status</th>
                        <th>Others Income</th>
                        <th>Manage Batch</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $batch->batch_name }}</td>
                            <td>{{ $batch->batch_number ?? '-' }}</td>
                            <td>{{ $batch->customer?->name ?? 'Walk-in' }}</td>
                            <td>
                                <span class="badge bg-{{ $batch->chicken_type == 'broiler' ? 'warning' : 'info' }}">
                                    {{ ucfirst($batch->chicken_type) }}
                                </span>
                            </td>
                            <td>{{ number_format($batch->total_chickens) }}</td>
                            <td>{{ $batch->chicken_grade ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($batch->batch_start_date)->format('d M Y') }}</td>
                            <td>{{ $batch->batch_close_date ? \Carbon\Carbon::parse($batch->batch_close_date)->format('d M Y') : '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $batch->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($batch->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('poultry.others-income.index', $batch->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="fe-dollar-sign"></i> Others Income
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('customer.manageBatch', $batch->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fe-settings"></i> Manage Batch
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
                            <td colspan="" class="d-none text-center py-4">No batches found.</td>
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