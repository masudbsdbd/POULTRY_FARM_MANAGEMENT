@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite([
    'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
    'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
    'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
    'node_modules/mohithg-switchery/dist/switchery.min.css'
])
@endsection

@section('content')
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle ?? "All Invoices", 'subtitle' => 'Quotations'])

     @php
        $totalSalesRevenue = $sales->sum('total_amount');
        $otherIncome = 0;
        $totalRevenue = $totalSalesRevenue + $otherIncome;
        $totalExpense = $expenses->sum('total_expense');
        $netProfit = $totalRevenue - $totalExpense;
        $isProfit = $netProfit >= 0;
        $profitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 2) : 0;
    @endphp
    
    {{-- dashboard cards --}}
    <div class="row g-3">
         <div class="col-md-6 col-xl-4">
            <a href="{{ route('customer.index') }}" class="btn btn-info"><i class="mdi mdi-arrow-left"></i>back</a>
        </div>
        <div class="col-md-6 col-xl-4"></div><div class="col-md-6 col-xl-4"></div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4  widget-rounded-circle">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-5 text-center">
                            <div class="avatar-lg rounded-circle bg-soft-primary border border-primary d-flex align-items-center justify-content-center mx-auto">
                                <i class="fe-heart fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="col-7 text-end">
                            <h3 class="fw-bold mb-1">
                                <span data-plugin="counterup" id="total-quot-amount">
                                    {{ $batchInfo->total_chickens }}
                                </span>
                            </h3>
                            <p class="text-muted mb-0 text-uppercase small">Total Chickens</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4  widget-rounded-circle">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-5 text-center">
                            <div class="avatar-lg rounded-circle bg-soft-success border border-success d-flex align-items-center justify-content-center mx-auto">
                                <i class="fe-calendar fs-3 text-success"></i>
                            </div>
                        </div>
                        <div class="col-7 text-end">
                            <h3 class="fw-bold mb-1">
                                <span data-plugin="counterup" id="total-invoiced-amount">
                                    @php
                                        $daysOld = floor(
                                            $batchInfo->batch_start_date->diffInDays(
                                                $batchInfo->batch_close_date ?? now()
                                            )
                                        );
                                    @endphp
                                    {{ $daysOld }}
                                </span>
                            </h3>
                            <p class="text-muted mb-0 text-uppercase small">Days Old</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4  widget-rounded-circle">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-5 text-center">
                            <div class="avatar-lg rounded-circle bg-soft-danger border border-danger d-flex align-items-center justify-content-center mx-auto">
                                <i class="fe-trending-down fs-3 text-danger"></i>
                            </div>
                        </div>
                        <div class="col-7 text-end">
                            <h3 class="fw-bold mb-1">
                                <span id="total-not-invoiced-amount">
                                    {{ round(($totalDeaths / $batchInfo->total_chickens) * 100) }}%
                                </span>
                            </h3>
                            <p class="text-danger mb-0 text-uppercase small">Mortality Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4  widget-rounded-circle">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-5 text-center">
                            <div class="avatar-lg rounded-circle bg-soft-info border border-info d-flex align-items-center justify-content-center mx-auto">
                                <i class="fe-trending-up fs-3 text-info"></i>
                            </div>
                        </div>
                        <div class="col-7 text-end">
                            <h3 class="fw-bold mb-1">
                                <span id="total-not-invoiced-amount">
                                    {{ $profitMargin }}
                                </span>
                            </h3>
                            <p class="text-info mb-0 text-uppercase small">Profit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- end col-->


    <div class="row mt-3">
        {{-- batch info start --}}
        <div class="col-md-6">
            <div class="">
                <div class="card shadow border-0 rounded-4 h-100">
                    <div class="card-header bg-success bg-gradient text-white d-flex align-items-center justify-content-between rounded-4 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fe-shopping-bag me-2"></i> Batch Information
                        </h5>
                        <span class="badge bg-white text-success px-3 py-2 rounded-pill">
                            {{ $batchInfo->batch_name }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">

                            @php
                                $na = '<span class="text-muted">n/a</span>';
                            @endphp

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Name</span>
                                <span class="fw-medium">{{ $batchInfo->batch_name }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Batch Number</span>
                                <span>{!! $batchInfo->batch_number ?? $na !!}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Chicken Type</span>
                                <span>{{ $batchInfo->chicken_type }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Total Chickens</span>
                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                    {{ $batchInfo->total_chickens }}
                                </span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Price Per Chicken</span>
                                <span class="fw-semibold text-success">
                                    {{ number_format($batchInfo->price_per_chicken, 2) }} tk
                                </span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Grade</span>
                                <span>
                                    @if($batchInfo->chicken_grade == 'A')
                                        <span class="badge bg-success px-3 py-2">A</span>
                                    @elseif($batchInfo->chicken_grade == 'B')
                                        <span class="badge bg-warning text-dark px-3 py-2">B</span>
                                    @elseif($batchInfo->chicken_grade == 'C')
                                        <span class="badge bg-danger px-3 py-2">C</span>
                                    @elseif($batchInfo->chicken_grade == 'D')
                                        <span class="badge bg-dark px-3 py-2">D</span>
                                    @endif
                                </span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Hatchery Name</span>
                                <span>{!! $batchInfo->hatchery_name ?? $na !!}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Shed Number</span>
                                <span>{!! $batchInfo->shed_number ?? $na !!}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Target Feed Qty</span>
                                <span>{!! $batchInfo->target_feed_qty ?? $na !!}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Target Feed Unit</span>
                                <span>{!! $batchInfo->terget_feed_unit ?? $na !!}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Batch Start Date</span>
                                <span class="badge bg-light text-dark">
                                    {{ $batchInfo->batch_start_date ?? 'not-started' }}
                                </span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="fw-semibold text-secondary">Batch Close Date</span>
                                <span class="badge bg-light text-dark">
                                    {{ $batchInfo->batch_close_date ?? 'not-closed' }}
                                </span>
                            </li>

                            <li class="list-group-item px-4 py-3">
                                <span class="fw-semibold text-secondary d-block mb-1">Description</span>
                                <span class="text-muted">
                                    {{ $batchInfo->batch_description ?? 'n/a' }}
                                </span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

            {{-- Expense Breakdown Start --}}
            <div class="col-md-12 mt-4">
                <div class="card shadow-lg rounded-4 border-0 overflow-hidden">
                    <div class="card-header bg-gradient-danger text-white d-flex align-items-center justify-content-between py-3">
                        <h5 class="mb-0 font-20">
                            <i class="fe-dollar-sign me-2"></i> Expense Breakdown
                        </h5>
                        <a href="{{ route('poultry.expense.index', $batchInfo->id) }}" class="btn btn-light btn-sm rounded-pill px-4">
                            <i class="fe-plus me-1"></i> Add Expense
                        </a>
                    </div>

                    <div class="card-body p-4 bg-light">
                        @php
                            $totalExpenses = $expenses->sum('total_expense');

                            // ক্যাটাগরি অনুযায়ী সুন্দর নাম + কালার
                            $categoryLabels = [
                                'chickens'        => ['name' => 'Chickens Purchase',     'icon' => 'fe-package',      'color' => 'primary'],
                                'feed'            => ['name' => 'Feed Cost',             'icon' => 'fe-truck',        'color' => 'warning'],
                                'medicine'        => ['name' => 'Medicine & Vaccine',    'icon' => 'fe-activity',     'color' => 'info'],
                                'transportation'  => ['name' => 'Transportation',        'icon' => 'fe-truck',        'color' => 'secondary'],
                                'bedding'         => ['name' => 'Bedding',               'icon' => 'fe-home',         'color' => 'dark'],
                                'labor'           => ['name' => 'Labor Cost',            'icon' => 'fe-users',        'color' => 'success'],
                                'utilities'       => ['name' => 'Utilities (Electricity, Water)', 'icon' => 'fe-zap', 'color' => 'warning'],
                                'death_loss'      => ['name' => 'Death Loss',            'icon' => 'fe-heart',        'color' => 'danger'],
                                'bio_security'    => ['name' => 'Bio Security',          'icon' => 'fe-shield',       'color' => 'info'],
                                'miscellaneous'   => ['name' => 'Miscellaneous',         'icon' => 'fe-grid',         'color' => 'secondary'],
                                'bad_debt'        => ['name' => 'Bad Debt',              'icon' => 'fe-alert-circle', 'color' => 'danger'],
                                'other'           => ['name' => 'Other Expenses',        'icon' => 'fe-more-horizontal', 'color' => 'dark'],
                            ];
                        @endphp

                        <div class="row g-3">
                            @forelse($expenses as $expense)
                                @php
                                    $cat = $expense->category;
                                    $label = $categoryLabels[$cat] ?? ['name' => ucfirst(str_replace('_', ' ', $cat)), 'icon' => 'fe-tag', 'color' => 'secondary'];
                                    $amount = $expense->total_expense;
                                @endphp

                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded-3 shadow-sm">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fe {{ $label['icon'] }} text-{{ $label['color'] }} fs-3"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $label['name'] }}</h6>
                                                <small class="text-muted">
                                                    @if($amount > 0)
                                                        {{ number_format(($amount / $totalExpenses) * 100, 1) }}% of total
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-{{ $label['color'] }}">
                                                {{ number_format($amount, 2) }} ৳
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="fe-info fs-1 text-muted mb-3"></i>
                                    <p class="text-muted">No expenses recorded yet for this batch.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Total Expense -->
                        <div class="mt-4 p-4 bg-danger text-white rounded-3 text-center shadow">
                            <h5 class="mb-2">Total Expense</h5>
                            <h3 class="mb-0 fw-bold">{{ number_format($totalExpenses, 2) }} ৳</h3>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Expense Breakdown End --}}
        </div>
        {{-- batch info end --}}



        <div class="col-md-6">
            {{-- mortality info start --}}
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-info text-white d-flex align-items-center justify-content-between rounded-4">
                    <h5 class="mb-0  font-20 text-white"><i class="fe-shopping-bag me-2 text-white"></i> Mortality Information</h5>
                    <a href="{{ route('death.list', $batchInfo->id) }}" class="badge bg-light text-info font-16">View Death List</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Total Deaths:</span>
                            <span>{{ $totalDeaths }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Mortality Rate:</span>
                            <span>{{ round(($totalDeaths / $batchInfo->total_chickens) * 100) }}%</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Surviving Birds:</span>
                            <span>{{ $batchInfo->total_chickens - $totalDeaths }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- mortality info end --}}

            {{-- Feed Management start --}}
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-warning text-white d-flex align-items-center justify-content-between rounded-4">
                    <h5 class="mb-0  text-white  font-18"><i class="fe-shopping-bag me-2 text-white"></i> Feed Management</h5>
                    {{-- <a href="{{ route('death.list', $batchInfo->id) }}" class="badge bg-light text-info font-16">View Feed </a> --}}
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Target Feed Quantity:</span>
                            @php
                                $tergetFeedUnit = $batchInfo->terget_feed_unit;
                                $tergetFeedInKg = $batchInfo->target_feed_qty;
                                if($tergetFeedUnit == 'bag'){
                                    $tergetFeedInKg = $tergetFeedInKg * 50;
                                }
                                
                            @endphp
                            <span>{{ showAmount(( $tergetFeedInKg / 50), 2, false) ?? 'n/a' }} Bag</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Feed Consumed (Bag):</span>
                            <span>{{ showAmount(($totalFeedConsumedInKg / 50), 2, false) }} Bag</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Feed Consumed (Kg):</span>
                            <span>{{ $totalFeedConsumedInKg }} kg</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Feed Consum Per Chicken (Kg):</span>
                            <span>{{ showAmount(($totalFeedConsumedInKg / ($batchInfo->total_chickens - $totalDeaths)), 2, false) }}kg</span>
                        </li>
                         <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Over Feed:</span>
                            <span class="text-danger">{{ $tergetFeedInKg < $totalFeedConsumedInKg ?  $totalFeedConsumedInKg - $tergetFeedInKg  . "kg " . "(" . (($totalFeedConsumedInKg / 50) - ($tergetFeedInKg / 50)) ." Bag)" : 0  }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Feed Consumption %</span>
                            <span>{{ $batchInfo->total_chickens - $totalDeaths }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- Feed Management end --}}

            {{-- Sales Overview Start --}}
            <div class="col-md-12 mt-2">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-header bg-gradient-warning text-white d-flex align-items-center justify-content-between rounded-top-4 py-3">
                        <h5 class="mb-0 font-18">
                            <i class="fe-shopping-bag me-2"></i> Sales Overview
                        </h5>
                        <a href="{{ route('poultry.sale.index', $batchInfo->id) }}" class="btn btn-light btn-sm rounded-pill px-4">
                                
                        </a>
                        <a href="{{ route('poultry.sale.index', $batchInfo->id) }}" class="btn btn-sm btn-success text-white font-16">
                            <i class="fe-plus me-1"></i>
                            Make a Sale
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @php
                            // Sales Summary
                            $totalSalesCount = $sales->count();
                            $totalSaleAmount = $sales->sum('total_amount');
                            $totalPaid = $sales->sum('paid_amount');
                            $totalDue = $totalSaleAmount - $totalPaid;

                            // Quantity Summary
                            $totalPiecesSold = $sales->where('sale_type', 'by_piece')->sum('quantity');
                            $totalKgSold = $sales->where('sale_type', 'by_weight')->sum('weight_kg') ?? 0;

                            // Average Weight per bird (if needed)
                            $liveBirds = $batchInfo->total_chickens - ($totalDeaths ?? 0);
                            $avgWeightPerBird = $liveBirds > 0 ? round($totalKgSold / $liveBirds, 3) : 0;
                        @endphp

                        <div class="row text-center text-md-start">
                            <!-- Total Sales Count -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="text-center">
                                    <i class="fe-shopping-cart text-warning fs-1 mb-2"></i>
                                    <h6 class="fw-bold text-muted">Total Sales</h6>
                                    <h4 class="fw-bold text-dark">{{ $totalSalesCount }} times</h4>
                                </div>
                            </div>

                            <!-- Total Sale Amount -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="text-center">
                                    <i class="fe-dollar-sign text-success fs-1 mb-2"></i>
                                    <h6 class="fw-bold text-muted">Total Sale Amount</h6>
                                    <h4 class="fw-bold text-success">{{ number_format($totalSaleAmount, 2) }} ৳</h4>
                                </div>
                            </div>

                            <!-- Paid & Due -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="text-center">
                                    <i class="fe-check-circle text-primary fs-1 mb-2"></i>
                                    <h6 class="fw-bold text-muted">Paid Amount</h6>
                                    <h4 class="fw-bold text-primary">{{ number_format($totalPaid, 2) }} ৳</h4>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="text-center">
                                    <i class="fe-alert-circle text-danger fs-1 mb-2"></i>
                                    <h6 class="fw-bold text-muted">Due Amount</h6>
                                    <h4 class="fw-bold text-danger">{{ number_format($totalDue, 2) }} ৳</h4>
                                </div>
                            </div>

                            <!-- Sold Pieces -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="text-center">
                                    <i class="fe-package text-info fs-1 mb-2"></i>
                                    <h6 class="fw-bold text-muted">Sold (Pieces)</h6>
                                    <h4 class="fw-bold text-info">{{ number_format($totalPiecesSold) }} pcs</h4>
                                </div>
                            </div>

                            <!-- Sold Weight -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="text-center">
                                    <i class="fe-weight text-purple fs-1 mb-2"></i>
                                    <h6 class="fw-bold text-muted">Sold (Weight)</h6>
                                    <h4 class="fw-bold text-purple">{{ number_format($totalKgSold, 2) }} kg</h4>
                                </div>
                            </div>

                            <!-- Average Weight per Bird -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="text-center">
                                    <i class="fe-trending-up text-success fs-1 mb-2"></i>
                                    <h6 class="fw-bold text-muted">Avg Weight / Bird</h6>
                                    <h4 class="fw-bold text-success">{{ $avgWeightPerBird }} kg</h4>
                                    <small class="text-muted">(Live birds: {{ number_format($liveBirds) }})</small>
                                </div>
                            </div>

                            <!-- Payment Status Summary -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="text-center">
                                    <i class="fe-pie-chart text-warning fs-1 mb-2"></i>
                                    <h6 class="fw-bold text-muted">Payment Status</h6>
                                    <div class="mt-2">
                                        <span class="badge bg-success fs-6 me-1">{{ $sales->where('payment_status', 'paid')->count() }} Paid</span>
                                        <span class="badge bg-warning fs-6 me-1">{{ $sales->where('payment_status', 'partial')->count() }} Partial</span>
                                        <span class="badge bg-danger fs-6">{{ $sales->where('payment_status', 'due')->count() }} Due</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Sales Overview End --}}


            {{-- Financial Summary Start - Premium Light Neumorphism Design --}}
            <div class="col-md-12 mt-3">
                <div class="card border-1 rounded-5 overflow-hidden" style="background: #f8fafc; box-shadow: 12px 12px 24px #d4d9e0, -12px -12px 24px #ffffff; border-color: #e9ecef;">
                    
                    <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between py-5 px-5">
                        <h4 class="mb-0 text-dark fw-bold d-flex align-items-center">
                            <i class="fe-bar-chart-2 me-3 fs-3"></i>
                            Financial Summary
                        </h4>
                        <div class="px-4 py-2 rounded-pill fw-semibold text-primary" style="background: #f1f5f9; box-shadow: 4px 4px 8px #d4d9e0, -4px -4px 8px #ffffff;">
                            Batch Performance
                        </div>
                    </div>

                    <div class="card-body p-5">
                        <div class="row g-5">
                            <!-- Sales Revenue -->
                            <div class="col-lg-6 col-md-6">
                                <div class="p-3 rounded-4 text-center" style="background: #f8fafc; box-shadow: inset 6px 6px 12px #d4d9e0, inset -6px -6px 12px #ffffff;">
                                    <div class="icon mb-3 mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #f8fafc; box-shadow: 8px 8px 16px #d4d9e0, -8px -8px 16px #ffffff;">
                                        <i class="fe-shopping-bag fs-3 text-success font-16"></i>
                                    </div>
                                    <p class="mb-2 text-muted small text-uppercase fw-bold font-12">Total Sales Revenue</p>
                                    <h3 class="fw-bold mb-0 text-success font-16">{{ number_format($totalSalesRevenue, 2) }} ৳</h3>
                                </div>
                            </div>

                            <!-- Other Income -->
                            <div class="col-lg-6 col-md-6">
                                <div class="p-3 rounded-4 text-center" style="background: #f8fafc; box-shadow: inset 6px 6px 12px #d4d9e0, inset -6px -6px 12px #ffffff;">
                                    <div class="icon mb-3 mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #f8fafc; box-shadow: 8px 8px 16px #d4d9e0, -8px -8px 16px #ffffff;">
                                        <i class="fe-plus-circle fs-3 text-info font-16"></i>
                                    </div>
                                    <p class="mb-2 text-muted small text-uppercase fw-bold font-12">(+) Other Income</p>
                                    <h3 class="fw-bold mb-0 text-info font-16">{{ number_format($otherIncome, 2) }} ৳</h3>
                                </div>
                            </div>

                            <!-- Total Revenue -->
                            <div class="col-lg-6 col-md-6">
                                <div class="p-3 rounded-4 text-center text-white" style="background: linear-gradient(135deg, #ebc312, #ebc312); box-shadow: 10px 10px 20px #d4d9e0, -10px -10px 20px #ffffff;">
                                    <i class="fe-arrow-up-right fs-2 mb-3"></i>
                                    <p class="mb-2 small text-uppercase fw-bold opacity-95 font-12">Total Revenue</p>
                                    <h2 class="fw-bold mb-0 font-16">{{ number_format($totalRevenue, 2) }} ৳</h2>
                                </div>  
                            </div>

                            <!-- Total Expense -->
                            <div class="col-lg-6 col-md-6">
                                <div class="p-3 rounded-4 text-center" style="background: #f8fafc; box-shadow: inset 6px 6px 12px #d4d9e0, inset -6px -6px 12px #ffffff;">
                                    <div class="icon mb-3 mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #f8fafc; box-shadow: 8px 8px 16px #d4d9e0, -8px -8px 16px #ffffff;">
                                        <i class="fe-minus-circle fs-3 text-danger"></i>
                                    </div>
                                    <p class="mb-2 text-muted small text-uppercase fw-bold font-12">(-) Total Expense</p>
                                    <h3 class="fw-bold mb-0 text-danger font-16">{{ number_format($totalExpense, 2) }} ৳</h3>
                                </div>
                            </div>

                            <!-- Net Profit / Loss & Profit Margin - Full Width Row -->
                            <div class="col-12 mt-4">
                                <div class="row g-4">
                                    <!-- Net Profit / Loss -->
                                    <div class="col-md-6">
                                        <div class="p-5 rounded-5 text-center text-white" style="background: linear-gradient(135deg, {{ $isProfit ? '#22c55e, #16a34a' : '#ef4444, #dc2626' }}); box-shadow: 12px 12px 24px #d4d9e0, -12px -12px 24px #ffffff;">
                                            <i class="fe-{{ $isProfit ? 'trending-up' : 'trending-down' }} fs-1 mb-4"></i>
                                            <h4 class="fw-bold mb-3">Net {{ $isProfit ? 'Profit' : 'Loss' }}</h4>
                                            <h1 class="display-5 fw-bold font-22 text-white">
                                                {{ $isProfit ? '+' : '-' }}{{ number_format(abs($netProfit), 2) }} ৳
                                            </h1>
                                        </div>
                                    </div>

                                    <!-- Profit Margin -->
                                    <div class="col-md-6">
                                        <div class="p-4 rounded-5 text-center" style="background: #f8fafc; box-shadow: inset 10px 10px 20px #d4d9e0, inset -10px -10px 20px #ffffff;">
                                            <i class="fe-percent fs-1 mb-4 {{ $isProfit ? 'text-success' : 'text-danger' }}"></i>
                                            <h4 class="fw-bold mb-3 text-muted">Profit Margin</h4>
                                            <h1 class="display-5 fw-bold font-22 {{ $isProfit ? 'text-success' : 'text-danger' }}">
                                                {{ $profitMargin }}%
                                            </h1>
                                            <small class="text-muted font-12">(Net Profit ÷ Total Revenue)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Final Message -->
                        <div class="mt-5 text-center">
                            @if($isProfit)
                                <div class="p-3 rounded-5 text-white" style="background: linear-gradient(135deg, #22c55e, #16a34a); box-shadow: 12px 12px 24px #d4d9e0, -12px -12px 24px #ffffff;">
                                    <i class="fe-thumbs-up fs-1 mb-3"></i>
                                    <h3 class="fw-bold">Excellent Performance!</h3>
                                    <p class="fs-5 mb-0">This batch achieved a net profit of {{ number_format(abs($netProfit), 2) }} ৳</p>
                                </div>
                            @else
                                <div class="p-3 rounded-5 text-white" style="background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 12px 12px 24px #d4d9e0, -12px -12px 24px #ffffff;">
                                    <i class="fe-alert-triangle fs-1 mb-3"></i>
                                    <h3 class="fw-bold">Attention Required!</h3>
                                    <p class="fs-5 mb-0">This batch has a loss of {{ number_format(abs($netProfit), 2) }} ৳. Please review expenses.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- Financial Summary End --}}
        </div>
        
    </div>
</div>


<x-confirmation-modal></x-confirmation-modal>

{{-- Quotation View Modal --}}
<div class="modal fade" id="quotationViewModal" tabindex="-1" aria-labelledby="quotationViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quotationViewModalLabel">Quotation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6"><strong>Quotation Number:</strong> <span id="modalQuotationNumber"></span></div>
                    <div class="col-md-6"><strong>Customer:</strong> <span id="modalCustomer"></span></div>
                    <div class="col-md-6"><strong>Contract Amount:</strong> <span id="modalTotalAmount"></span></div>
                    <div class="col-md-6"><strong>Status:</strong> <span id="modalStatus"></span></div>
                    <div class="col-md-12"><strong>Note:</strong> <span id="modalNote"></span></div>
                </div>

                <h5>Products</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="modalProductsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Note</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- JS populate --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js'])

<script>
function askPassword(invoiceId){
        const password = prompt("Please enter your password to confirm deletion:");
        if (!password) return;
        const form = document.getElementById(`deleteForm_${invoiceId}`);
        const input = document.createElement("input");
        input.type="hidden";
        input.name="password";
        input.value=password;

        form.appendChild(input);
        form.submit();
    }
$(document).ready(function() {
    $('.viewQuotation').on('click', function() {
        var button = $(this);

        $('#quotationViewModalLabel').text(button.data('title'));
        $('#modalQuotationNumber').text(button.data('quotation-number'));
        $('#modalCustomer').text(button.data('customer'));
        $('#modalTotalAmount').text(button.data('total-amount'));
        $('#modalNote').text(button.data('note'));

        var status = button.data('status');
        if(status == 1){
            $('#modalStatus').html('<span class="badge bg-primary">Activated</span>');
        } else {
            $('#modalStatus').html('<span class="badge bg-secondary">Inactive</span>');
        }

        // Populate products
        var products = button.data('products');
        var tbody = $('#modalProductsTable tbody');
        tbody.empty();

        if(products && products.length > 0){
            products.forEach(function(item, index){
                tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.product.name}</td>
                        <td>${item.description || '-'}</td>
                        <td>${item.qty}</td>
                        <td>${parseFloat(item.unit_price).toFixed(2)}</td>
                        <td>${parseFloat(item.total).toFixed(2)}</td>
                    </tr>
                `);
            });
        } else {
            tbody.append('<tr><td colspan="6" class="text-center">No products found</td></tr>');
        }

        $('#quotationViewModal').modal('show');
    });
});
</script>
@endsection
