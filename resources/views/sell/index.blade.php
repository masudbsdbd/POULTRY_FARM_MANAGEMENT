@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css',])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => $pageTitle])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>

                        @can('sell-create')
                        <a href="{{ route('sell.create') }}" class="mb-2 btn btn-primary waves-effect waves-light">Add
                            New</a>
                        @endcan
                    </div>

                    
                    <h5>
                        @if(request('date'))
                        Search Date: ( {{ request('date') }} )
                        @elseif(request('range'))
                        Search Date: ( {{ request('range') }} )
                        @else

                        @endif

                    </h5>
                    <form action="{{ route('sell.index') }}" method="get">
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

                          {{--  <div class="col-md-3">
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
                            </div> <!-- end col -->--}}

                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Select Customer</p>
                                <select id="select-customer" class="form-control" data-toggle="select2"
                                    data-width="100%" name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $item)
                                    <option value="{{ $item->id }}" @selected(request('customer_id') ? $item->id == request('customer_id') ?? true : false)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->

                            <!-- Search Button -->
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100"
                                    style="margin-top: 36px;">Search</button>
                            </div>

                        </div>
                    </form><br>


                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">Payment</th>
                                <th class="text-center">Dues</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Delivery Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $totalPrice = 0;
                            $totalQty = 0;
                            $totalPayment = 0;
                            $totalDues = 0;
                            @endphp
                            @foreach ($sells as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ @$item->customer->name }}</td>
                                <td class="text-center">
                                    {{ $item->total_qty }} units
                                </td>
                                <td class="text-center">{{ showAmount($item->total_price) }} Tk</td>
                                <td class="text-center">{{ showAmount($item->payment_received) }} Tk</td>
                                <td class="text-center">{{ showAmount($item->due_to_company) }} Tk</td>
                                <td class="text-center">{{ showDateTime($item->sell_date, true) }}</td>
                                <td class="text-center">
                                    @if ($item->due_to_company > 0)
                                    <span class="badge badge-soft-danger">Unpaid</span>
                                    @else
                                    <span class="badge badge-soft-success">Paid</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->delivery_status == 0)
                                    <span class="badge badge-soft-danger">Not Delivered</span>
                                    @else
                                    <span class="badge badge-soft-success">Delivered</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('sell-payment')
                                    <button title="Due Payment" type="button"
                                        class="btn btn-success waves-effect waves-light paymentBtn  {{ $item->due_to_company > 0 ? '' : 'disabled' }}"
                                        data-customername="{{ $item->customer->name }}"
                                        data-action="{{ route('sell.due', $item->id) }}"><i
                                            class="mdi mdi-cash-multiple"></i></button>
                                    @endcan

                                    <a title="Supplier Ledger" href="{{ route('customer.ledger.sell.index', $item->customer_id) }}"
                                        class="btn btn-danger waves-effect waves-light">
                                        <i class="mdi mdi-information"></i></a>

                                    <button title="View" type="button"
                                        class="btn btn-primary waves-effect waves-light sellDetailBtn"
                                        data-records="{{ $item->sellRecords }}"
                                        data-cusotmerName="{{ $item->customer->name }}"><i
                                            class="mdi mdi-eye"></i></button>
                                    @can('sell-delivery')
                                    <a href="{{ route('sell.delivery', $item->id) }}" title="Delivery"
                                        class="btn btn-pink waves-effect waves-light"><i
                                            class="mdi mdi-truck"></i></a>
                                    @endcan

                                    @can('sell-edit')
                                    <a href="{{ route('sell.edit', $item->id) }}"
                                        class="btn btn-blue waves-effect waves-light">
                                        <i class="mdi mdi-grease-pencil"></i></a>
                                    @endcan

                                    <a title="Purchase Invoice" target="_blank"
                                        href="{{ route('sell.pdf', $item->id) }}"
                                        class="btn btn-dark waves-effect waves-light">
                                        <i class="mdi mdi-printer"></i></a>
                                    @can('sell-delete')
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this sell?')"
                                        data-action="{{ route('sell.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                    @endcan

                                </td>
                            </tr>
                            @php
                            $totalPrice += $item->total_price;
                            $totalPayment += $item->payment_received;
                            $totalDues += $item->due_to_company;
                            $totalQty += $item->total_qty;
                            @endphp
                            @endforeach
                        </tbody>
                        @if (!$sells->isEmpty())
                        <tfoot>
                            <tr style="font-size: 16px;">
                                <td class="fw-bold">Total</td>
                                <td></td>
                                <td class="text-center"><strong>{{ $totalQty }}</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalPrice) }} Tk</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalPayment) }} Tk</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalDues) }} Tk</strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $sells->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->

<div id="sellPaymentModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Due payment receive for this sell from <span class="customerName"></span></h4>
                </div>
                <form id="paymentForm" class="px-3" action="" method="POST">
                    @csrf
                    <h4>Payment Method and Type</h4>
                    <div class="my-3"><label class="form-label">Payment Amount</label>
                        <div class="input-group">
                            <div class="col-lg-3 pe-0">
                                <select id="paymentMethod" class="form-select rounded-0" id="example-select"
                                    name="payment_method" required>
                                    <option value="1">Cash</option>
                                    <option value="2">Bank</option>
                                </select>
                            </div>

                            <div class="col-lg-9">
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" class="form-control rounded-0"
                                        placeholder="Enter Amount" name="balance" required>
                                    <div class="input-group-text">Tk</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="bankInfo" class="d-none">
                        <h4>Bank Information</h4>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="example-Account" class="form-label">Select Bank</label>
                                    <select id="bank_id" class="form-select rounded-0" name="bank_id">
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $item)
                                        <option value="{{ $item->id }}" @selected(isset($purchase) && $purchase->account->bankTransaction ? $item->id == $purchase->account->bankTransaction->bank_id : false)>
                                            {{ $item->account_no . ' - ' . $item->account_name . ' - ' . $item->bank_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="example-check" class="form-label">Check No</label>
                                    <input type="text" id="example-check" class="form-control" name="check_no"
                                        placeholder="Check No">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="example-depositor" class="form-label">Depositor Name</label>
                                    <input id="depositor_name" type="text" id="example-depositor"
                                        class="form-control" name="depositor_name" placeholder="Depositor Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="catename" class="form-label">Comment</label>
                        <textarea class="form-control" name="comment" placeholder="Comment" rows="5"></textarea>
                    </div>
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="submit">Receive Payment</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
                </div> <!-- end card -->
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js','resources/js/pages/form-pickers.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        (function($) {
            "use strict";

            $(document).on('click', '.paymentBtn', function() {
                var modal = $('#sellPaymentModal');
                let data = $(this).data();
                modal.find('.customerName').text(data.customername);

                $('#paymentForm')[0].reset();
                $('#bankInfo').addClass('d-none');
                $('#bank_id').prop('required', false);
                $('#depositor_name').prop('required', false);

                $('#paymentForm').attr('action', data.action);

                modal.modal('show');
            });

            $('#paymentMethod').on('change', function() {
                if ($(this).val() == 2) {
                    $('#bankInfo').removeClass('d-none');
                    $('#bank_id').prop('required', true);
                    $('#depositor_name').prop('required', true);
                } else {
                    $('#bankInfo').addClass('d-none');
                    $('#bank_id').prop('required', false);
                    $('#depositor_name').prop('required', false);
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

        })(jQuery);
    });
</script>
@endsection