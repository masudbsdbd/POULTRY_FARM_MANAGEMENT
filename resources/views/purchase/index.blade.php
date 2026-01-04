@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Purchase'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @can('purchase-create')
                        <a href="{{ route('purchase.create') }}" class="mb-2 btn btn-primary waves-effect waves-light">Add
                            New</a>
                        @endcan

                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Batch Code</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">Payment</th>
                                <th class="text-center">Dues</th>
                                <th class="text-center">Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $totalPrice = 0;
                            $totalPayment = 0;
                            $totalDues = 0;
                            @endphp
                            @foreach ($purchases as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->batch->batch_code }}</td>
                                <td class="text-center">{{ $item->supplier->name }}</td>
                                <td class="text-center">
                                    {{ $item->total_qty }} units
                                </td>
                                <td class="text-center">{{ showAmount($item->total_price) }}</td>
                                <td class="text-center">{{ showAmount($item->payment_received) }}</td>
                                <td class="text-center">{{ showAmount($item->due_to_company) }}</td>
                                <td class="text-center">{{ showDateTime($item->purchase_date, true) }}</td>
                                <td class="text-end">
                                    @can('purchase-payment')
                                    <button title="Due Payment" type="button"
                                        class="btn btn-success waves-effect waves-light paymentBtn {{ $item->due_to_company > 0 ? '' : 'disabled' }}"
                                        data-suppliername="{{ $item->supplier->name }}"
                                        data-due="{{ $item->due_to_company }}"
                                        data-action="{{ route('purchase.due', $item->id) }}"><i
                                            class="mdi mdi-cash-multiple"></i></button>
                                    @endcan

                                    <button title="View" type="button"
                                        class="btn btn-primary waves-effect waves-light purchaseDetailBtn"
                                        data-items="{{ $item->items }}"
                                        data-suppliername="{{ $item->supplier->name }}"><i
                                            class="mdi mdi-eye"></i></button>
                                    @can('purchase-edit')
                                    <a title="Edit" href="{{ route('purchase.edit', $item->id) }}"
                                        class="btn btn-blue waves-effect waves-light">
                                        <i class="mdi mdi-grease-pencil"></i></a>
                                    @endcan

                                    <a title="Supplier Ledger"
                                        href="{{ route('supplier.ledger.purchase.index', $item->supplier_id) }}"
                                        class="btn btn-pink waves-effect waves-light">
                                        <i class="mdi mdi-information"></i></a>

                                    <a title="Purchase Invoice" href="{{ route('purchase.pdf', $item->id) }}"
                                        target="_blank" class="btn btn-dark waves-effect waves-light">
                                        <i class="mdi mdi-printer"></i></a>
                                    @can('purchase-delete')
                                    <button title="Delete" type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this purchase?')"
                                        data-action="{{ route('purchase.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                    @endcan
                                </td>
                            </tr>
                            @php
                            $totalPrice += $item->total_price;
                            $totalPayment += $item->payment_received;
                            $totalDues += $item->due_to_company;
                            @endphp
                            @endforeach
                        </tbody>

                        @if (!$purchases->isEmpty())
                        <tfoot>
                            <tr style="font-size: 16px;">
                                <td class="fw-bold">Total</td>
                                <td></td>
                                <td></td>
                                <td class="text-center"></td>
                                <td class="text-center"><strong>{{ showAmount($totalPrice) }} Tk</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalPayment) }} Tk</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalDues) }} Tk</strong></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $purchases->links() }}</div>
                    <!-- end row-->
                </div> <!-- container -->
            </div> <!-- container -->
        </div> <!-- container -->
    </div> <!-- container -->
</div> <!-- container -->

<div id="purchasePaymentModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Due payment for this Purchase from <span class="supplierName"></span></h4>
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
                                    <input id="totalBalance" type="number" step="0.01" min="0"
                                        class="form-control rounded-0" placeholder="Enter Amount" name="balance"
                                        required>
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
                                    <select class="form-select rounded-0 selectBank" name="bank_id">
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $item)
                                        <option value="{{ $item->id }}" data-balance="{{ $item->balance }}">
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
                                    <label for="example-withdrawer" class="form-label">Withdrawer Name</label>
                                    <input id="withdrawer_name" type="text" id="example-withdrawer"
                                        class="form-control" name="withdrawer_name" placeholder="Withdrawer Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="catename" class="form-label">Comment</label>
                        <textarea class="form-control" name="comment" placeholder="Comment" rows="5"></textarea>
                    </div>
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="submit">Add Payment</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="purchaseDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Purchase from <span id="viewSupplierName"></span></h4>
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Product Name</th>
                                    <th>Avg Purchase Price</th>
                                    <th>Warehouse</th>
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
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        (function($) {
            "use strict";

            $(document).on('click', '.paymentBtn', function() {
                var modal = $('#purchasePaymentModal');
                let data = $(this).data();
                modal.find('.supplierName').text(data.suppliername);

                $('#paymentForm')[0].reset();
                $('#bankInfo').addClass('d-none');
                $('#bank_id').prop('required', false);
                $('#withdrawer_name').prop('required', false);
                $('#paymentForm').attr('action', data.action);
                $('.selectBank').attr('disabled', true);

                modal.modal('show');
            });

            $('#paymentMethod').on('change', function() {
                if ($(this).val() == 2) {
                    $('#bankInfo').removeClass('d-none');
                    $('#bank_id').prop('required', true);
                    $('#withdrawer_name').prop('required', true);
                } else {
                    $('#bankInfo').addClass('d-none');
                    $('#bank_id').prop('required', false);
                    $('#withdrawer_name').prop('required', false);
                }
            });

            $('.selectBank').on('change', function() {

                let balance = parseFloat($('#totalBalance').val());
                let bank_balance = parseFloat($(this).find(':selected').data('balance'));

                if (balance !== undefined && balance !== null && !isNaN(balance)) {
                    if (balance > bank_balance) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Insufficient balance in this bank.'
                        })
                        $('.selectBank').val('');
                    }
                }
            });

            $('#totalBalance').on('input', function() {
                $('.selectBank').val('');
                if ($(this).val() == '') {
                    $('.selectBank').attr('disabled', true);
                } else {
                    $('.selectBank').attr('disabled', false);
                }
            });

            $(document).on('click', '.purchaseDetailBtn', function() {
                var modal = $('#purchaseDetail');
                let data = $(this).data();

                modal.find('#viewSupplierName').text(data.suppliername);

                let tableBody = modal.find('table tbody');
                tableBody.empty();

                let totalQty = 0;
                let totalPrice = 0;
                data.items.forEach((item, index) => {
                    totalQty += parseFloat(item.qty) || 0;
                    totalPrice += parseFloat(item.avg_purchase_price) || 0;

                    let num = (parseFloat(item.avg_purchase_price) || 0) * (parseFloat(item.qty) || 0);

                    let row = `
                                <tr>
                                    <th scope="row">${index + 1}</th>
                                    <td>${item.product?.name ?? '-'}</td>
                                    <td>${!isNaN(parseFloat(item.avg_purchase_price)) ? parseFloat(item.avg_purchase_price).toFixed(2) + ' Tk' : '-'}</td>
                                    <td>${item?.warehouse?.warehouse_name ? item?.warehouse.warehouse_name : "N/A"}</td>
                                    <td>${item.qty ? item.qty + ' ' + (item.product?.unit?.name ?? '-') : '-'}</td>
                                    <td>${num.toFixed(2)} Tk</td>
                                </tr>
                            `;

                    tableBody.append(row);
                });


                let totalRow = `
                        <tr class="table-secondary">
                            <th colspan="4" class="text-end"><strong>Total</strong></th>
                            <td><strong>${totalQty + ' units'}</strong></td>
                            <td><strong>${totalPrice.toFixed(2) + ' Tk'}</strong></td>
                        </tr>
                    `;

                tableBody.append(totalRow);

                modal.modal('show');
            });
        })(jQuery);
    });
</script>
@endsection