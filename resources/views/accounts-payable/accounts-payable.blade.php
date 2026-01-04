@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
    <!-- Start Content -->
    <div class="container-fluid">

        @include('layouts.shared.page-title', [
            'title' => $pageTitle,
            'subtitle' => $pageTitle,
        ])

        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ $pageTitle }}</h4>

                <form id="myForm" action="" method="get">

                    <input type="hidden" name="action" id="form-action" value="search">

                    <div class="row my-3">
                        <!-- Start Date Field -->
                        <div class="col-md-2">
                            <p class="fw-bold text-muted">Search Type</p>
                            <select class="form-select" id="type-select" name="type">
                                <option value="">Select Type</option>
                                <option value="1" @selected(request('type') == '1')>Single Date</option>
                                <option value="2" @selected(request('type') == '2')>Date Range</option>
                            </select>
                        </div>

                        <div class="col-md-2">
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
                            <p class="fw-bold text-muted">Select Head</p>
                            <select lass="form-control" data-toggle="select2" data-width="100%" name="head_id">
                                <option value="">Select</option>
                                @foreach ($heads as $item)
                                    <option value="{{ $item->id }}" @selected(request('head_id') == $item->id)>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-1">
                            <p class="fw-bold text-muted">Entry Type</p>
                            <select lass="form-control" data-toggle="select2" data-width="100%" name="entry_type">
                                <option value="">Select</option >
                                <option value="0">Manual</option >
                                <option value="1">Journal</option >
                            </select>
                        </div>
                        <div class="col-md-2">
                            <p class="fw-bold text-muted">Effective Debit/Credit</p>
                            <select lass="form-control" data-toggle="select2" data-width="100%" name="debit_or_credit">
                                <option value="">Select</option >
                                <option value="debit">Debit</option >
                                <option value="credit">Credit</option >
                            </select>
                        </div>
                        <!-- Submit Buttons -->
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-4 ps-0">
                                    <button type="submit" class="btn btn-primary w-100"
                                        onclick="document.getElementById('form-action').value='search'"
                                        style="margin-top: 36px;">
                                        <i class="mdi mdi-search-web"></i> Search
                                    </button>
                                </div>
                                <div class="col-md-4 ps-0">
                                    <button type="button" class="btn btn-success w-100 printBtn" style="margin-top: 36px;">
                                        <i class="mdi mdi-printer"></i> Print
                                    </button>
                                </div>
                                <div class="col-md-4 ps-0">
                                    <a href="{{ route('accounts.payable.create') }}" style="margin-top: 36px;"
                                        class="mb-2 btn btn-primary waves-effect waves-light"><i class="mdi mdi-plus"></i>
                                        Add</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

                <p>
                    @if (request('date'))
                        Search Date: ( {{ request('date') }} )
                    @elseif(request('range'))
                        Search Date: ( {{ request('range') }} )
                    @endif
                </p>

                <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th class="text-center">SL</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Account's Head</th>
                            <th class="text-center">Purchase Batch</th>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Employee</th>
                            <th class="text-center">Invoice Number</th>
                            <th class="text-center">Entry Type</th>
                            <th class="text-center">Effective Debit/Credit</th>
                            <th class="text-center">Payable Amount</th>
                            <th class="text-center">Effective Amount</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        @php
                            $totalPayableAmount = 0;
                            $totalEffectiveAmount = 0;
                        @endphp
                        @foreach ($payables as $item)
                            @php
                                $totalPayableAmount += $item->payable_amount;
                                $totalEffectiveAmount += $item->effective_amount;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                <td class="text-center">
                                    <span class="badge badge-soft-secondary">{{ $item->payablesHead->name }}</span>
                                </td>
                                <td class="text-center">
                                    @if (isset($item->supplier_id))
                                        {{ @$item->purchase->batch->batch_code }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (isset($item->supplier_id))
                                        {{ $item->supplier->name }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (isset($item->customer_id))
                                        {{ $item->customer->name }}
                                        ({{ $item->customer->code }})
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (isset($item->employee_id))
                                        {{ $item->employee->name }}
                                        ({{ $item->employee->code }})
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (isset($item->purchase))
                                        {{ $item->purchase->invoice_no }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->entry_type == 0)
                                        <span class="badge badge-soft-dark">manual</span>
                                    @else
                                        <span class="badge badge-soft-dark">journal</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                @if(isset($item->debit_or_credit))
                                @if($item->debit_or_credit == 'debit')
                                <span class="badge badge-soft-danger">Debit</span>
                                @else
                                <span class="badge badge-soft-success">Credit</span>
                                @endif
                                @else
                                ---
                                @endif
                                </td>

                                <td class="text-center">{{ showAmount($item->payable_amount) }} </td>
                                <td class="text-center">{{ showAmount($item->effective_amount) }} </td>
                                <td class="text-end">

                                    @if ($item->payables_head_id == 1 && $item->purchase_id != null)
                                        <button title="Due Payment" type="button"
                                            class="btn btn-success waves-effect waves-light paymentBtn"
                                            data-action="{{ route('purchase.due', $item->purchase_id) }}"><i
                                                class="mdi mdi-cash-multiple"></i></button>
                                    @elseif ($item->payables_head_id == 1 && $item->purchase_id == null)
                                    @elseif ($item->payables_head_id == 2)

                                    @elseif ($item->payables_head_id == 5)
                                    <button title="Due Payment" type="button"
                                        class="btn btn-success waves-effect waves-light paymentBtn  {{ $item->payable_amount > 0 ? '' : 'disabled' }}"
                                        data-suppliername="" data-due=""
                                        data-action="{{ route('accounts.payable.supplier.due.pay', $item->id) }}"><i
                                            class="mdi mdi-cash-multiple"></i></button>

                                    @elseif ($item->payables_head_id == 3 && $item->employee_id != null)
                                        <a href="{{ route('employee.transaction.create') }}"
                                            class="btn btn-success waves-effect waves-light">
                                            <i class="mdi mdi-cash-multiple"></i></a>
                                    @elseif ($item->payables_head_id == 3 && $item->employee_id == null)

                                    @elseif ($item->payables_head_id == 4 && $item->expense_id != null)
                                        <button title="Due Payment" type="button"
                                            class="btn btn-success waves-effect waves-light paymentBtn"
                                            data-action="{{ route('accounts.payable.expense.pay', $item->expense_id) }}"><i
                                                class="mdi mdi-cash-multiple"></i></button>
                                    @elseif ($item->payables_head_id == 4 && $item->expense_id == null)

                                    @else
                                        <button title="Due Payment" type="button"
                                            class="btn btn-success waves-effect waves-light paymentBtn"
                                            data-action="{{ route('accounts.payable.pay', $item->id) }}"><i
                                                class="mdi mdi-cash-multiple"></i></button>
                                    @endif
                                    <button title="View" type="button"
                                        class="btn btn-primary waves-effect waves-light purchaseDetailBtn"
                                        data-description="{{ $item->description }}"><i class="mdi mdi-eye"></i></button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                    <tfoot>
                        <tr style="font-size: 16px;">
                            <td class="fw-bold">Total Receivable Amount: </td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"><strong>{{ $totalPayableAmount }} Tk</strong></td>
                            <td class="text-center"><strong>{{ $totalEffectiveAmount }} Tk</strong></td>
                            <td class="text-center"></td>
                        </tr>

                    </tfoot>
                </table>
                <div class="d-flex justify-content-end mt-3">{{ $payables->links() }}</div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
        <!-- end row-->
    </div> <!-- container -->

    <div id="purchasePaymentModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h4>Accounts Payable Settlement</h4>
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2">Account's Payable Description</h4>
                            <p id="accPayableDesc"></p>
                        </div>
                    </div> <!-- end card -->
                    <div class="text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/form-pickers.init.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $(document).on('click', '.paymentBtn', function() {
                    var modal = $('#purchasePaymentModal');
                    let data = $(this).data();

                    $('#paymentForm')[0].reset();
                    $('#bankInfo').addClass('d-none');
                    $('#bank_id').prop('required', false);
                    $('#withdrawer_name').prop('required', false);
                    $('#paymentForm').attr('action', data.action);

                    modal.modal('show');
                });

                $(document).on('click', '.purchaseDetailBtn', function() {
                    var modal = $('#purchaseDetail');
                    let data = $(this).data();

                    modal.find('#accPayableDesc').text(data.description);
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

                $(document).on('click', '.printBtn', function() {
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


            })(jQuery);
        });
    </script>
@endsection
