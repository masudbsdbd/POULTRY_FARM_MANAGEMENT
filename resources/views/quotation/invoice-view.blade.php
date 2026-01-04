@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite([
    'node_modules/flatpickr/dist/flatpickr.min.css',
    'node_modules/select2/dist/css/select2.min.css',
    'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
    'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
    'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'
])
@endsection

@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Quotation Management'])

    {{-- dashboard cards --}}
    {{-- <div class="row g-3">
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('quotation.index') }}" class="btn btn-info"><i class="mdi mdi-arrow-left"></i>back</a>
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
                                <h3 class="text-dark mt-1">&#2547; <span data-plugin="counterup"><span id="total-quot-amount">{{ showAmount($quotation->total_amount, 2, false) }}</span></span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Quatation Price</p>
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
                                <h3 class="text-dark mt-1">&#2547; <span data-plugin="counterup"><span id="total-invoiced-amount">{{ showAmount($quotation->invoiced_amount, 2, false) }}</span></span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Invoiced Amount</p>
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
                                <h3 class="text-dark mt-1">&#2547; 
                                    <span data-plugin="counterup"><span id="total-not-invoiced-amount">{{ showAmount($quotation->total_amount - $quotation->invoiced_amount, 2, false) }}</span></span></h3>
                                <p class="text-danger mb-1 text-truncate">Not Invoiced Amount</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    </div> --}}
    
    <div class="row">
        <div class="col-md-6 col-xl-4 mb-3">
            <a href="{{ route('invoice.all', $quotationsInfo->id) }}" class="btn btn-info"><i class="mdi mdi-arrow-left"></i>back</a>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Quotation Number: <span id="quotationNumber">{{ $quotationsInfo->quotation_number }}</span></h4>
                    <h4 class="header-title">Inoice Number: <span id="quotationName">{{ $invoiceInfo->invoice_number }}</span></h4>
                    {{-- <form action="{{ route('balance.sheet.report.index') }}" method="get">
                        <div class="row">
                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Search Type</p>
                                <select class="form-select" id="type-select" name="type">
                                    <option value="">Select Type</option>
                                    <option value="1" @selected(request('type')=='1')>Single Date</option>
                                    <option value="2" @selected(request('type')=='2')>Date Range</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="basicDatepicker {{ request('range') ? 'd-none' : '' }}">
                                    <p class="fw-bold text-muted">Select Date</p>
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="date" id="basic-datepicker" class="form-control"
                                            placeholder="Select Date" value="{{ old('date', request('date')) }}">
                                        <div class="input-group-text clear-btn" style="cursor: pointer;">X</div>
                                    </div>
                                </div>

                                <div class="rangeDatepicker {{ request('range') ? '' : 'd-none' }}">
                                    <p class="fw-bold text-muted">Choose Date Range</p>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="range-datepicker" class="form-control" name="range"
                                            placeholder="Ex. 2018-10-03 to 2018-10-10"
                                            value="{{ old('date', request('range')) }}">
                                        <div class="input-group-text clear-btn" style="cursor: pointer;">X</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100" style="margin-top: 36px;">Search</button>
                            </div>
                        </div>
                    </form> --}}

                    <div class="mt-3">
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Unit Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Total Price</th>
                                    <th class="text-center">Paid Qty</th>
                                    <th class="text-center">Remaining Qty</th>
                                    <th class="text-center">Paid Amount</th>
                                    <th class="text-center">Due Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($invoiceItems as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ showAmount($item->unit_price, 2, false) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->total }}</td>
                                        {{-- <td>{{ $item->paid_qty }}</td> --}}
                                        <td>
                                            {{-- @php
                                                $paidQtyPercent = round(($item->paid_qty / $item->quantity) * 100);
                                            @endphp --}}
                                                {{-- <a href="{{ route('challan.challanItems', [$item->quotation_id, $item->product_id]) }}" class="btn btn-sm btn-info mb-2">{{ $item->paid_qty }}</a> --}}
                                                {{ $item->paid_qty }}
                                            {{-- <div class="progress" style="height:20px; width:120px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paidQtyPercent }}%;">
                                                {{ $paidQtyPercent }}%
                                                </div>
                                            </div> --}}
                                        </td>
                                        <td>{{ $item->quantity - $item->paid_qty }}</td>
                                        <td>
                                            <div class="d-flex flex-column align-items-center">
                                                @php
                                                    $paidAmountPercent = round(($item->paid / $item->total) * 100);
                                                @endphp
                                                    {{-- <a href="{{ route('challan.challanItems', [$item->quotation_id, $item->product_id]) }}" class="btn btn-sm btn-info mb-2">{{ $item->paid_qty }}</a> --}}
                                                    {{ $item->paid }}
                                                <div class="progress" style="height:20px; width:120px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paidAmountPercent }}%;">
                                                    {{ $paidAmountPercent }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column align-items-center">
                                                @php
                                                    $dueAmountPercent = round(($item->due / $item->total) * 100);
                                                @endphp
                                                    {{-- <a href="{{ route('challan.challanItems', [$item->quotation_id, $item->product_id]) }}" class="btn btn-sm btn-info mb-2">{{ $item->paid_qty }}</a> --}}
                                                    {{ $item->due }}
                                                <div class="progress" style="height:20px; width:120px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $dueAmountPercent }}%;">
                                                    {{ $dueAmountPercent }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a
                                            href="{{ route('invoice.items_info', [$item->invoice_id, $item->product_id]) }}"
                                            class="btn btn-warning btn-sm"
                                            title="View Quotation"
                                            >
                                             <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Purchase Qty:</h5>
                                <h5>111111</h5>
                            </div>
                            <hr class="my-2">
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sell Detail Modal -->
{{-- <div id="sellDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Sell Items to <span id="cusotmerName"></span></h4>
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Product Name</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('script')
@vite([
    'resources/js/pages/form-advanced.init.js',
    'resources/js/pages/form-pickers.init.js',
    'resources/js/pages/datatables.init.js'
])

<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function ($) {
            "use strict";

            $(document).on('click', '.clear-btn', function () {
                $('.basicDatepicker input').val('');
                $('.rangeDatepicker input').val('');
                $('#type-select').val('');
            });

            $('#type-select').on('change', function () {
                const selectedValue = $(this).val();

                if (selectedValue == '') {
                    $('.basicDatepicker input').val('');
                    $('.rangeDatepicker input').val('');
                    $('.basicDatepicker input').prop('disabled', true);
                    $('.rangeDatepicker input').prop('disabled', true);
                } else if (selectedValue == '1') {
                    $('.basicDatepicker').removeClass('d-none');
                    $('.basicDatepicker input').prop('disabled', false);
                    $('.rangeDatepicker').addClass('d-none');
                    $('.rangeDatepicker input').val('');
                } else if (selectedValue == '2') {
                    $('.rangeDatepicker').removeClass('d-none');
                    $('.rangeDatepicker input').prop('disabled', false);
                    $('.basicDatepicker').addClass('d-none');
                    $('.basicDatepicker input').val('');
                }
            });
        })(jQuery);
    });
</script>
@endsection
