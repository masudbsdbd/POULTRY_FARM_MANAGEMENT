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
                    <h4>Today ({{ $todayTime }}) </h4>

                    <form action="{{ route('sell-report.index') }}" method="get">
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

                            <div class="col-md-5">
                                <p class="fw-bold text-muted">Select Customer</p>
                                <select id="select-customer" class="form-control" data-toggle="select2" data-width="100%"
                                    name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $item)
                                    <option value="{{ $item->id }}" @selected(request('id') ? $item->id == request('id') ?? true : false)>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->

                            {{-- <div class="col-md-2">
                                <p class="fw-bold text-muted">Select Customer</p>
                                <select id="select-customer" class="form-control" data-toggle="select2"
                                    data-width="100%" name="">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $item)
                                    <option value="{{ $item->id }}" @selected(request('customer_id') ? $item->id == request('customer_id') ?? true : false)>{{ $item->name }}</option>
                            @endforeach
                            </select>
                        </div> <!-- end col -->--}}

                        <!-- Search Button -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"
                                style="margin-top: 36px;">Search</button>
                        </div>

                </div>
                </form>

                <div class="mt-3">
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Sell Qty</th>
                                <th class="text-center">Paymet Received</th>
                                <th class="text-center">Paymet Due</th>
                                <th class="text-center">Discount</th>
                                <th class="text-center">Delivery</th>
                                <th class="text-center">Products</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $totalQty = 0;
                            $totalPaymentReceived = 0;
                            $totalPaymentDue = 0;
                            $totalDiscount = 0;
                            $totalNotDelivered = 0;
                            $totalDelivered = 0;
                            @endphp
                            @foreach ($sells as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->customer->name }}</td>
                                <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                <td class="text-center">{{ ($item->total_qty) }}</td>
                                <td class="text-center">{{ showAmount($item->payment_received) }}</td>
                                <td class="text-center">{{ showAmount($item->due_to_company) }}</td>
                                <td class="text-center">{{ showAmount($item->discount) }}</td>
                                <td class="text-center">
                                    @if($item->delivery_status == 1)
                                    <span class="badge badge-soft-success">Delivered</span>
                                    @php $totalDelivered++; @endphp
                                    @else
                                    <span class="badge badge-soft-danger">Not Delivered</span>
                                    @php $totalNotDelivered++; @endphp
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button title="View" type="button"
                                        class="btn btn-primary waves-effect waves-light sellDetailBtn"
                                        data-records="{{ $item->sellRecords }}"
                                        data-cusotmerName="{{ $item->customer->name }}"
                                        data-return_qty="{{ $item->sellRecords->sum('return_qty') ?? 0 }}"><i><i
                                                class="mdi mdi-eye"></i></button>
                                </td>
                            </tr>
                            @php
                            $totalQty += $item->total_qty;
                            $totalPaymentReceived += $item->payment_received;
                            $totalPaymentDue += $item->due_to_company;
                            $totalDiscount += $item->discount;

                            @endphp
                            @endforeach
                        </tbody>
                        @if (!$sells->isEmpty())
                        <tfoot>
                            <tr style="font-size: 16px;">
                                <td class="fw-bold">Total :</td>
                                <td class="text-center"> Delivered ( {{$totalDelivered}} )</td>
                                <td class="text-center">Not Delivered ( {{$totalNotDelivered}} )</td>
                                <td class="text-center"><strong>{{ $totalQty }}</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalPaymentReceived) }} Tk</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalPaymentDue) }} Tk</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalDiscount) }} Tk</strong></td>
                                <td class="text-center"><strong></strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $sells->links() }}</div>
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
                        <h4 class="mb-3">Sell Items to - <span id="cusotmerName"></span></h4>
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>Return Qty</th>
                                    <th>Purchase Price</th>
                                    <th>Sell Price</th>
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
                            <td>${item.product.name  || '-'}</td>
                            <td>${item.sell_qty  || '-'}</td>
                            <td>${item.return_qty  || '-'}</td>
                            <td>${parseFloat(item.avg_purchase_price).toFixed(2) + ' Tk'   || '-'}</td>
                            <td>${parseFloat(item.avg_sell_price).toFixed(2) + ' Tk'  || '-'}</td>
                            <td>${parseFloat(item.total_amount).toFixed(2) + ' Tk'  || '-'}</td>
                        </tr>

                    `;
                    tableBody.append(row);
                });

                let totalRow = `
                        <tr class="table-secondary">
                            <th colspan="5" class="text-end"><strong>Total</strong></th>
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