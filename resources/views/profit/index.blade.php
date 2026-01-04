@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Manage Bank Info'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ $pageTitle }}</h4>
                    <h5>
                        @if(request('date'))
                        Search Date: ( {{ request('date') }} )
                        @elseif(request('range'))
                        Search Date: ( {{ request('range') }} )
                        @else

                        @endif

                    </h5>
                    <form action="{{ route('profit.index') }}" method="get"  id="myForm">
                        <input type="hidden" name="action" id="form-action" value="search">
                        <div class="row">
                            <!-- Start Date Field -->
                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Search Type</p>
                                <select class="form-select" id="type-select" name="type">
                                    <option value="">Select Type</option>
                                    <option value="1" @selected(request('type')=='1' )>Single Date</option>
                                    <option value="2" @selected(request('type')=='2' )>Date Range</option>
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

                            <div class="col-md-3">
                                <p class="fw-bold text-muted">Select Product</p>
                                <select id="select-product" class="form-control" data-toggle="select2" data-width="100%"
                                    name="product_id">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $item)
                                    <option value="{{ $item->id }}" @selected(request('product_id') ? $item->id == request('product_id') ?? true : false)>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->

                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Select Batch</p>
                                <select id="select-batch" class="form-control" data-toggle="select2"
                                    data-width="100%" name="purchase_batch_id">
                                    <option value="">Select Batch</option>
                                    @foreach ($purchaseBatches as $item)
                                    <option value="{{ $item->id }}" @selected(request('purchase_batch_id') ? $item->id == request('purchase_batch_id') ?? true : false)>{{ $item->batch_code }}</option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->

                            <!-- Search Button -->
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100"
                                    style="margin-top: 36px;">Search</button>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-success w-100 printBtn"
                                    style="margin-top: 36px;">
                                    <i class="mdi mdi-printer"></i> Print
                                </button>
                            </div>

                        </div>
                    </form>

                    <div class="mt-3">
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center"> Date</th>
                                    <th class="text-center"> Batch</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Purchase Price</th>
                                    <th class="text-center">Sell Price</th>
                                    <th class="text-center">Discount</th>
                                    <th class="text-center">Sell Qty</th>
                                    <!-- <th class="text-center">Return Qty</th> -->
                                    <th class="text-center">Total Purchase Price (after sell)</th>
                                    <th class="text-center">Total Sell Price (after sell)</th>
                                    <th class="text-center">Profit/Loss</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                $totalSellQty = 0;
                                $totalPurchasePrice = 0;
                                $totalSellPrice = 0;
                                @endphp
                                @foreach ($sellRecords as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ showDateTime($item->created_at) }}</td>

                                    <td class="text-center">{{ @$item->purchaseBatch->batch_code }}</td>
                                    <td class="text-center">{{ $item->product->name }}</td>
                                    <td class="text-center">{{ showAmount($item->avg_purchase_price) }} Tk</td>
                                    <td class="text-center">{{ showAmount($item->avg_sell_price) }} Tk</td>
                                    <td class="text-center">{{ showAmount($item->discount) }} %</td>
                                    <td class="text-center">{{ $item->sell_qty }} {{ $item->product->unit->name }}</td>
                                    <!-- <td class="text-center">{{ $item->sell_qty }} {{ $item->product->unit->name }}</td> -->
                                    <td class="text-center">{{ number_format($item->avg_purchase_price * $item->sell_qty, 2) }} Tk</td>
                                    <td class="text-center">{{ number_format($item->avg_sell_price * $item->sell_qty, 2) }} Tk</td>

                                    <td class="text-center">{{ number_format(($item->avg_sell_price * $item->sell_qty) - ($item->avg_purchase_price * $item->sell_qty), 2) }} Tk</td>
                                </tr>
                                @php
                                $totalSellQty += $item->sell_qty;
                                $totalPurchasePrice += $item->avg_purchase_price * $item->sell_qty;
                                $totalSellPrice += $item->avg_sell_price * $item->sell_qty;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="font-size: 16px;">
                                    <td class="fw-bold">Total: </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><strong>{{$totalSellQty}}</strong></td>
                                    <!-- <td class="text-center"></td> -->
                                    <td class="text-center"><strong>{{showAmount($totalPurchasePrice)}} Tk</strong></td>
                                    <td class="text-center"><strong>{{showAmount($totalSellPrice)}} Tk</strong></td>
                                    <td class="text-end"><strong>{{showAmount($totalSellPrice - $totalPurchasePrice)}} Tk</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="d-flex justify-content-end mt-3">{{ $sellRecords->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="sellDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Sell Items to <span id="cusotmerName"></span></h4>
                        <h5>
                            @if(request('date'))
                            Expense Details: ( {{ request('date') }} )
                            @elseif(request('range'))
                            Search Date: ( {{ request('range') }} )
                            @else
                            Expense Details: ( - )
                            @endif
                        </h5>
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
                </div> <!-- end card -->
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
@endsection

@section('script')
@vite(['resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js', 'resources/js/pages/datatables.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        (function($) {
            "use strict";


                $(document).on('click', '.printBtn', function() {
                console.log('print');
                    document.getElementById('form-action').value = 'print';
                    $('#myForm').attr('target', '_blank');
                    $('#myForm').submit();
                });



            $(document).on('click', '.clear-btn', function() {
                $('.basicDatepicker input').val('');
                $('.rangeDatepicker input').val('');
                $('#type-select').val('');
            });

            $('#type-select').on('change', function() {
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

            $(document).on('click', '.sellDetailBtn', function() {
                var modal = $('#sellDetail');
                let data = $(this).data();
                console.log(data);
                modal.find('#cusotmerName').text(data.cusotmername);

                let tableBody = modal.find('table tbody');
                tableBody.empty();

                let totalQty = 0;
                let totalPrice = 0;

                data.records.forEach((item, index) => {
                    totalQty += parseFloat(item.sell_qty) || 0;
                    totalPrice += parseFloat(item.total_amount) || 0;

                    let num = parseFloat(item.total_amount);

                    let row = `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td>${item.product.name || '-'}</td>
                            <td>${item.product.unit.name || '-'}</td>
                            <td>${item.sell_qty || '-'}</td>
                            <td>${num.toFixed(2) || '-'}</td>
                        </tr>
                    `;
                    tableBody.append(row);
                });

                let totalRow = `
                        <tr class="table-secondary">
                            <th colspan="3" class="text-end"><strong>Total</strong></th>
                            <td><strong>${totalQty}</strong></td>
                            <td><strong>${totalPrice.toFixed(2)}</strong></td>
                        </tr>
                    `;

                tableBody.append(totalRow);

                modal.modal('show');
            });

        })(jQuery);
    });
</script>
@endsection