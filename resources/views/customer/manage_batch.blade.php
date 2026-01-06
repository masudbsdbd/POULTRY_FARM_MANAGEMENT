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

    {{-- dashboard cards --}}
    <div class="row g-3">
         <div class="col-md-6 col-xl-4">
            <a href="{{ route('customer.index') }}" class="btn btn-info"><i class="mdi mdi-arrow-left"></i>back</a>
        </div>
        <div class="col-md-6 col-xl-4"></div><div class="col-md-6 col-xl-4"></div>

        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-heart font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">
                                    <span data-plugin="counterup">
                                        <span id="total-quot-amount">{{ $batchInfo->total_chickens }}
                                        </span>
                                    </span>
                                </h3>
                                <p class="text-muted mb-1 text-truncate">Total Chickens</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-shopping-cart font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">
                                    <span data-plugin="counterup">
                                        <span id="total-invoiced-amount">
                                            @php
                                               $daysOld =  floor($batchInfo->batch_start_date->diffInDays($batchInfo->batch_close_date ?? now()));
                                            @endphp
                                            {{ 
                                                $daysOld
                                            }}
                                        </span>
                                    </span>
                                </h3>
                                <p class="text-muted mb-1 text-truncate">Days Old</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-4">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-shopping-bag font-22 avatar-title text-info"></i>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">
                                    <span id="total-not-invoiced-amount">
                                        {{ round(($totalDeaths / $batchInfo->total_chickens) * 100) }}%
                                    </span>
                                </h3>
                                <p class="text-danger mb-1 text-truncate">Mortality Rate</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    </div> <!-- end col-->


    <div class="row">
        {{-- batch info start --}}
        <div class="col-md-6">
            <div class="card shadow-sm  rounded-4">
                <div class="card-header bg-success text-white d-flex align-items-center justify-content-between  rounded-4">
                    <h5 class="mb-0  font-20 text-white"><i class="fe-shopping-bag me-2 text-white"></i> Batch Information</h5>
                    <span class="badge bg-light text-info">{{ $batchInfo->batch_name }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Name:</span>
                            <span>{{ $batchInfo->batch_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Batch Number:</span>
                            <span>{{ $batchInfo->batch_number ?? 'n/a' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Chicken Type:</span>
                            <span>{{ $batchInfo->chicken_type }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Total Chickens:</span>
                            <span>{{ $batchInfo->total_chickens }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Price Per Chicken:</span>
                            <span>{{ number_format($batchInfo->price_per_chicken, 2) }} tk</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Grade:</span>
                            <span>
                                @if($batchInfo->chicken_grade == 'A')
                                    <span class="badge bg-success font-16">A</span>
                                @elseif($batchInfo->chicken_grade == 'B')
                                    <span class="badge bg-warning font-16">B</span>
                                @elseif($batchInfo->chicken_grade == 'C')
                                    <span class="badge bg-danger font-16">C</span>
                                @elseif($batchInfo->chicken_grade == 'D')
                                    <span class="badge bg-dark font-16">D</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Hatchery Name:</span>
                            <span>{{ $batchInfo->hatchery_name ?? 'n/a' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Shed Number:</span>
                            <span>{{ $batchInfo->shed_number ?? 'n/a' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Target Feed Qty:</span>
                            <span>{{ $batchInfo->target_feed_qty ?? 'n/a' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Target Feed Unit:</span>
                            <span>{{ $batchInfo->terget_feed_unit ?? 'n/a' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Batch Start Date:</span>
                            <span>{{ $batchInfo->batch_start_date ?? 'not-started' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Batch Close Date:</span>
                            <span>{{ $batchInfo->batch_close_date ?? 'not-closed' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Description:</span>
                            <span>{{ $batchInfo->batch_description ?? 'n/a' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
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
        </div>


        <div class="row">
            {{-- expense info start --}}
            <div class="col-md-7 mt-5">
                <div class="card shadow-sm rounded-4">
                    <div class="card-header bg-success text-white d-flex align-items-center justify-content-between rounded-top-4">
                        <h5 class="mb-0 font-20 text-white">
                            <i class="fe-shopping-bag me-2"></i> Expense Breakdown
                        </h5>
                        <a href="{{ route('poultry.expense.index', $batchInfo->id) }}" class="badge bg-light text-success font-16">
                            Add Expense
                        </a>
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach ($expenses as $expense)
                                @if($expense['category'] == 'chickens')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Chickens</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'feed')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Feed</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'medicine')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Medicine</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'transportation')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Transportation</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'bedding')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Bedding</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'labor')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Labor</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'utilities')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Utilities</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'death_loss')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold text-danger">Death Loss</span>
                                        <span class="text-danger">{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'bio_security')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Bio Security</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'miscellaneous')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Miscellaneous</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'bad_debt')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Bad Debt</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @elseif($expense['category'] == 'other')
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Others</span>
                                        <span>{{ $expense['total_expense'] ?? 0 }} tk</span>
                                    </li>
                                @endif
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between  mt-2">
                                <span class="fw-bold fs-5">Total Expense</span>
                                <span class="fw-bold fs-5 text-success">
                                    {{ $totalExpenses ?? 0 }} tk
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- expense info end --}}


            <div class="col-md-5 row">
                {{-- Sales Overview start --}}
                <div class="col-md-12 mt-5">
                    <div class="card shadow-sm rounded-4">
                        <div class="card-header bg-warning text-white d-flex align-items-center justify-content-between rounded-4">
                            <h5 class="mb-0  text-white  font-18"><i class="fe-shopping-bag me-2 text-white"></i> Sales Overview</h5>
                            <a href="{{ route('poultry.sale.index', $batchInfo->id) }}" class="badge bg-light text-info font-16">Make a Sale</a>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Total Sales:</span>
                                    <span>{{ showAmount(( 10 ), 2, false) ?? 'n/a' }} Bag</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Paid Amount:</span>
                                    <span>{{ showAmount(($totalFeedConsumedInKg / 50), 2, false) }} Bag</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Due:</span>
                                    <span>{{ $totalFeedConsumedInKg }} kg</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Sales Quantity:</span>
                                    <span>{{ showAmount(($totalFeedConsumedInKg / ($batchInfo->total_chickens - $totalDeaths)), 2, false) }}kg</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Total Weight:</span>
                                    <span class="text-danger">{{ $tergetFeedInKg < $totalFeedConsumedInKg ?  $totalFeedConsumedInKg - $tergetFeedInKg  . "kg " . "(" . (($totalFeedConsumedInKg / 50) - ($tergetFeedInKg / 50)) ." Bag)" : 0  }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Average Weight (kg):</span>
                                    <span class="text-danger">{{ $tergetFeedInKg < $totalFeedConsumedInKg ?  $totalFeedConsumedInKg - $tergetFeedInKg  . "kg " . "(" . (($totalFeedConsumedInKg / 50) - ($tergetFeedInKg / 50)) ." Bag)" : 0  }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Feed Consumption %</span>
                                    <span>{{ $batchInfo->total_chickens - $totalDeaths }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- Sales Overview end --}}

                {{-- Sales Overview start --}}
                <div class="col-md-12">
                    <div class="card shadow-sm rounded-4">
                        <div class="card-header bg-success text-white d-flex align-items-center justify-content-between rounded-4">
                            <h5 class="mb-0  text-white  font-18"><i class="fe-shopping-bag me-2 text-white"></i> Financial Summary</h5>
                            {{-- <a href="{{ route('death.list', $batchInfo->id) }}" class="badge bg-light text-info font-16">View Feed </a> --}}
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Total Sales Revenue:</span>
                                    <span>{{ showAmount(( 10 ), 2, false) ?? 'n/a' }} Bag</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">(+) Others Revenue:</span>
                                    <span>{{ showAmount(($totalFeedConsumedInKg / 50), 2, false) }} Bag</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Total Revenue:</span>
                                    <span>{{ $totalFeedConsumedInKg }} kg</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">(-) Total Cost:</span>
                                    <span>{{ showAmount(($totalFeedConsumedInKg / ($batchInfo->total_chickens - $totalDeaths)), 2, false) }}kg</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Net Profit/Loss:</span>
                                    <span class="text-danger">{{ $tergetFeedInKg < $totalFeedConsumedInKg ?  $totalFeedConsumedInKg - $tergetFeedInKg  . "kg " . "(" . (($totalFeedConsumedInKg / 50) - ($tergetFeedInKg / 50)) ." Bag)" : 0  }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-bold">Profit Margin</span>
                                    <span>{{ $batchInfo->total_chickens - $totalDeaths }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- Sales Overview end --}}
            </div>
        </div>
        
    </div>



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @can('invoice-create')
                        <div class="text-end mb-3">
                                <a href="{{ route('invoice.create',1) }}" class="btn btn-primary waves-effect waves-light">
                                    Add New Invoice
                                </a>
                                <a href="{{ route('poultry.expense.index', $batchInfo->id) }}" class="badge bg-light text-success font-16">
                                    Make a Sale
                                </a>
                        </div>
                    @endcan

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Invoice Number</th>
                                <th>Percentage</th>
                                <th>Invoice Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           <tr>
                                <td>sdaf</td>
                                <td>sdaf</td>
                                <td>sdaf</td>
                                <td>sdaf</td>
                                <td>sdaf</td>
                                <td class="text-success">22
                                    {{-- {{ number_format($invoice->paid_amount, 2) }} tk --}}
                                </td>
                                <td class="text-danger">11</td>
                                <td>
                                    <span style="font-size: 13px;" class="badge bg-success">Active</span>
                                </td>
                                <td class="text-end">
                                    <a
                                        href="#"
                                        class="btn btn-warning btn-sm"
                                        title="View Quotation"
                                    >
                                            <i class="mdi mdi-eye"></i>
                                    </a>
                                    @can('invoice-edit')
                                        <a href="{{ route('invoice.edit', 1) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                    @endcan
                                    {{-- <a href="{{ route('challan.all', $invoice->id) }}" class="btn btn-primary btn-sm" title="Create Challan">
                                        --}}
                                    <a target="_blank" href="#" class="btn btn-primary btn-sm" title="Print Invoice">
                                    <i styl class="mdi mdi-printer"></i>
                                    </a>
                                    @can('invoice-delete')
                                        <form action="{{ route('invoice.delete', 1) }}" method="POST" class="d-inline-block" id="deleteForm_{{ 1 }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                onclick="askPassword({{1}})">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="">
                                    {{-- <div class="d-flex justify-content-center" style="margin-left: 20px;">
                                        <span class=" fw-bold">{{ showAmount($totalPaidAmount, 2, false) }} tk</span>
                                    </div> --}}
                                </td>
                                <td colspan="1" class="">
                                    <div class="d-flex justify-content-start" style="">
                                        {{-- <span class=" fw-bold">{{ showAmount($totalPaidAmount, 2, false) }} tk</span> --}}
                                        <span class=" fw-bold">100 tk</span>
                                    </div>
                                </td>
                                <td colspan="1" class="">
                                    <div class="d-flex justify-content-start" style="">
                                        <span class=" fw-bold">200 tk</span>
                                    </div>
                                </td>
                                <td colspan="2" class="text-center"></td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Pagination --}}
                    {{-- <div class="d-flex justify-content-end mt-3">
                        {{  isset($invoices) ? $invoices->links() : '' }}
                    </div> --}}
                </div>
            </div>
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
