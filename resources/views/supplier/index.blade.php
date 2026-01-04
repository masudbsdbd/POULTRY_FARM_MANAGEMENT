@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Manage Supplier'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title">{{ $pageTitle }}</h4>
                            @can('supplier-create')
                                <a href="{{ route('supplier.create') }}"
                                    class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add New</a>
                            @endcan
                        </div>

                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Compnay</th>
                                    <th class="text-center">Mobile</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Advance</th>
                                    <th class="text-center">Purchase Due</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach ($suppliers as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->company }}</td>
                                        <td class="text-center">{{ $item->mobile }}</td>
                                        <td class="text-center">{{ $item->email }}</td>
                                        <td class="text-center">
                                            @if ($item->status == 1)
                                                <span class="badge badge-soft-primary">Activated</span>
                                            @else
                                                <span class="badge badge-soft-dark">Deactivated</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ showAmount($item->advance) }} Tk</td>
                                        <td class="text-center">
                                            @php
                                                $sum = 0;
                                                $totalDues = App\Models\Purchase::whereSupplierId($item->id)->sum(
                                                    'due_to_company',
                                                );
                                            @endphp
                                            {{ showAmount($totalDues + $item->due) }} Tk

                                        </td>
                                        <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                        <td class="text-end">

                                            <a title="Supplier Ledger"
                                                href="{{ route('supplier.ledger.purchase.index', $item->id) }}"
                                                class="btn btn-pink waves-effect waves-light">
                                                <i class="mdi mdi-information"></i></a>

                                            @can('supplier-payment')
                                                <button title=" Payment" type="button"
                                                    class="btn btn-success waves-effect waves-light paymentBtn"
                                                    data-suppliername="{{ $item->name }}"
                                                    data-supplieradvance="{{ showAmount($item->advance) }}"
                                                    data-supplierdue="{{ showAmount($totalDues + $item->due) }}"
                                                    data-action="{{ route('supplier.payment', $item->id) }}"><i
                                                        class="mdi mdi-cash-multiple"></i></button>
                                            @endcan
                                            @can('supplier-edit')
                                                <a href="{{ route('supplier.edit', $item->id) }}"
                                                    class="btn btn-primary waves-effect waves-light"><i
                                                        class="mdi mdi-grease-pencil"></i></a>
                                            @endcan
                                            @can('supplier-delete')
                                                <button type="button"
                                                    class="btn btn-danger waves-effect waves-light confirmationBtn"
                                                    data-question="@lang('Are you sure to delete this supplier?')"
                                                    data-action="{{ route('supplier.delete', $item->id) }}"><i
                                                        class="mdi mdi-trash-can-outline"></i></button>
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">{{ $suppliers->links() }}</div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->

    <div id="advancePaymentModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h4>Pay <span class="supplierName"></span></h4>
                        <h6>Advance Amount: <span class="supplieradvance"></span> Tk</h6>
                        <h6>Due Amount: <span class="supplierdue"></span> Tk</h6>
                    </div>
                    <form id="paymentForm" class="px-3" action="" method="POST">
                        @csrf
                        <h4>Payment Method and Type</h4>

                        <div class="my-2">
                            <input class="form-check-input" type="radio" name="payment_mode" id="flexRadioDefault1" value="1" checked>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Pay
                            </label>
                            <input class="form-check-input ms-2" type="radio" name="payment_mode"
                                id="flexRadioDefault2" value="2">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Receive
                            </label>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Amount</label>
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
                                                <option value="{{ $item->id }}">
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

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $('input[name="type"]').change(function() {
                    const selectedValue = $(this).val();
                    if (selectedValue == 2) {
                        $('#paymentMethod').append('<option value="3">Advance</option>');
                    } else {
                        $('#paymentMethod option[value="3"]').remove();
                    }
                });

                $(document).on('click', '.paymentBtn', function() {
                    var modal = $('#advancePaymentModal');
                    let data = $(this).data();

                    modal.find('.supplierName').text(data.suppliername);
                    modal.find('.supplieradvance').text(data.supplieradvance);
                    modal.find('.supplierdue').text(data.supplierdue);

                    $('#paymentForm')[0].reset();
                    $('#bankInfo').addClass('d-none');
                    $('#bank_id').prop('required', false);
                    $('#withdrawer_name').prop('required', false);
                    $('#paymentForm').attr('action', data.action);
                    $('#paymentMethod option[value="3"]').remove();

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
            })(jQuery);
        });
    </script>
@endsection
