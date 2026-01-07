@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'All Batches', 'subtitle' => 'Batches'])

    

    <!-- Summary Cards - Only 3 -->
    <div class="row mb-2">
        <!-- Total Batches -->
        <div class="col-xl-4 col-md-4 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 rounded-4 widget-rounded-circle">
                <div class="card-body text-center ">
                    <div class="avatar-lg rounded-circle bg-soft-info border border-info mx-auto mb-4 d-flex align-items-center justify-content-center">
                        <i class="fe-grid fs-1 text-info"></i>
                    </div>
                    <h2 class="fw-bold mb-2 text-info">{{ $totalBatches }}</h2>
                    <p class="text-muted mb-0 text-uppercase fw-bold">Total Batches</p>
                </div>
            </div>
        </div>

        <!-- Active Batches -->
        <div class="col-xl-4 col-md-4 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 rounded-4 widget-rounded-circle">
                <div class="card-body text-center ">
                    <div class="avatar-lg rounded-circle bg-soft-success border border-success mx-auto mb-4 d-flex align-items-center justify-content-center">
                        <i class="fe-play-circle fs-1 text-success"></i>
                    </div>
                    <h2 class="fw-bold mb-2 text-success">{{ $activeBatches }}</h2>
                    <p class="text-muted mb-0 text-uppercase fw-bold">Active Batches</p>
                </div>
            </div>
        </div>

        <!-- Inactive Batches -->
        <div class="col-xl-4 col-md-4 col-sm-12 mb-3">
            <div class="card shadow-sm border-0 rounded-4 widget-rounded-circle">
                <div class="card-body text-center ">
                    <div class="avatar-lg rounded-circle bg-soft-danger border border-danger mx-auto mb-4 d-flex align-items-center justify-content-center">
                        <i class="fe-pause-circle fs-1 text-danger"></i>
                    </div>
                    <h2 class="fw-bold mb-2 text-danger">{{ $inactiveBatches }}</h2>
                    <p class="text-muted mb-0 text-uppercase fw-bold">Closed Batches</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Summary Cards -->
    
    
    
    

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between {{ Route::is('customer.index') ? '' : 'mb-3' }}">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @if (Route::is('customer.batches'))
                        <a href="{{ route('customer.createBatch', $customerInfo->id) }}"
                            class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add New Batch</a>
                        @endif
                    </div>


                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Batch Name</th>
                                {{-- <th class="text-center">Batch Number</th> --}}
                                <th class="text-center">Chicken Type</th>
                                <th class="text-center">Total Chickens</th>
                                <th class="text-center">Total Income</th>
                                <th class="text-center">Total Expenses</th>
                                <th class="text-center">Profit/Loss</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">Close Date</th>
                                <th class="text-center">Others Income</th>
                                <th class="text-center">Manage Batch</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($batches as $batch)
                            @php
                            $totalIncome = $batch->sales->sum('total_amount') + $batch->othersIncome->sum('amount');
                            $totalExpenses = $batch->expenses->sum('total_amount');
                            $profitLoss = $totalIncome - $totalExpenses;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $batch->batch_name }}</td>
                                {{-- <td class="text-center">{{ $batch->batch_number ?? '-' }}</td> --}}
                                <td class="text-center">{{ ucfirst($batch->chicken_type) }}</td>
                                <td class="text-center">{{ $batch->total_chickens }}</td>
                                <td class="text-center">{{ $totalIncome }}</td>
                                <td class="text-center text-danger">{{ $totalExpenses ?? '-' }}</td>
                                <td class="text-center fw-bold {{ $profitLoss > 0 ? 'text-success' : 'text-danger' }}">{{ $profitLoss ?? '-' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($batch->batch_start_date)->format('d M, Y') }}</td>
                                <td class="text-center">{{ $batch->batch_close_date ? \Carbon\Carbon::parse($batch->batch_close_date)->format('d M, Y') : '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('poultry.others-income.index', $batch->id) }}"
                                        class="mb-2 btn btn-success waves-effect waves-light createCatBtn">
                                        Others Income
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('customer.manageBatch', $batch->id) }}"
                                        class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">
                                        Manage Batch
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if($batch->status == 'active')
                                        <span class="badge badge-soft-primary font-15">Active</span>
                                    @else
                                        <span class="badge badge-soft-dark font-15">Closed</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('poultrybatch.edit', $batch->id) }}" class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-grease-pencil"></i>
                                    </a>
                                    {{-- <button type="button" class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="Are you sure to delete this batch?"
                                        data-action="{{ route('poultrybatch.destroy', $batch->id) }}">
                                        <i class="mdi mdi-trash-can-outline"></i>
                                    </button> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- <div class="d-flex justify-content-end mt-3">{{ $customers->links() }}</div> --}}

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
@endsection