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
    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Manage Bank Info'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ $pageTitle }}</h4>
                    <form action="{{ route('balance.sheet.report.index') }}" method="get">
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
                    </form>

                    <div class="mt-3">
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Advance ( Supplier )</th>
                                    <th class="text-center">Advance ( Customer )</th>
                                    <th class="text-center">Purchase Qty</th>
                                    <th class="text-center">Purchase Price</th>
                                    <th class="text-center">Sell Qty</th>
                                    <th class="text-center">Sell Price</th>
                                    <th class="text-center">Pay (purchase)</th>
                                    <th class="text-center">Pay (sell)</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $totalAdvanceSupplier = 0;
                                    $totalAdvanceCustomer = 0;
                                    $totalPurchaseQty = 0;
                                    $totalSellQty = 0;
                                    $totalPurchaseAmount = 0;
                                    $totalSellAmount = 0;
                                @endphp
                                @foreach ($groupedByDate as $key => $item)
                                @php
                                    $totalAdvanceSupplier += $item['supplier_advance'];
                                    $totalAdvanceCustomer += $item['customer_advance'];
                                    $totalPurchaseQty += $item['purchase_qty'];
                                    $totalSellQty += $item['sell_qty'];
                                    $totalPurchaseAmount += $item['total_purchase_main_price'];
                                    $totalSellAmount += $item['total_sell_main_price'];
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ showDateTime($key) }}</td>
                                    <td class="text-center">{{ $item['supplier_advance'] }}</td>
                                    <td class="text-center">{{ $item['customer_advance'] }}</td>
                                    <td class="text-center">{{ $item['purchase_qty'] }}</td>
                                    <td class="text-center">{{ $item['total_purchase_main_price'] }}</td>
                                    <td class="text-center">{{ $item['sell_qty'] }}</td>
                                    <td class="text-center">{{ $item['total_sell_main_price'] }}</td>
                                    <td class="text-center">{{ $item['total_purchase_payment_received'] }}</td>
                                    <td class="text-center">{{ $item['total_sell_payment_received'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Purchase Qty:</h5>
                                <h5>{{ $totalPurchaseQty }}</h5>
                            </div>
                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Sell Qty:</h5>
                                <h5>{{ $totalSellQty }}</h5>
                            </div>
                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Advance (Supplier - from transactions):</h5>
                                <h5>{{ number_format($totalAdvanceSupplier, 2) }} Tk</h5>
                            </div>
                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Advance (Customer - from transactions):</h5>
                                <h5>{{ number_format($totalAdvanceCustomer, 2) }} Tk</h5>
                            </div>
                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Advance (Supplier):</h5>
                                <h5>{{ number_format($totalSupplierAdvance, 2) }} Tk</h5>
                            </div>
                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Advance (Customer):</h5>
                                <h5>{{ number_format($totalCustomerAdvance, 2) }} Tk</h5>
                            </div>
                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Purchase Amount:</h5>
                                <h5>{{ number_format($totalPurchaseAmount, 2) }} Tk</h5>
                            </div>
                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Sell Amount:</h5>
                                <h5>{{ number_format($totalSellAmount, 2) }} Tk</h5>
                            </div>
                            <hr class="my-2">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sell Detail Modal -->
<div id="sellDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
</div>
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
