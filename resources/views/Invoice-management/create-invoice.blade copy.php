@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Create Supplier', 'subtitle' => 'Supplier'])

    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ route('invoice.store') }}"
                                {{-- action="{{ isset($supplier) ? route('supplier.store', $supplier->id) : route('supplier.store')  }}" --}}
                                method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">Invoice Number</label>
                                    <input type="text" id="invoice_number" class="form-control" placeholder="invoice number"
                                        name="invoice_number" required
                                        value="{{ $invoiceNumber }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <p class="mb-1 fw-bold text-muted">Select Qutotaion</p>
                                    <select id="quotation_id" class="form-control" data-toggle="select2"
                                        data-width="100%" name="quotation_id" required>
                                        <option value="">Select Qutotaion</option>
                                        @foreach ($quotations as $quotation)
                                        <option value="{{ $quotation->id }}">{{ $quotation->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Invoice Date</label>
                                    <input type="text" id="datetime-datepicker" class="form-control"
                                        placeholder="Invoice Date"
                                        value="{{ now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}"
                                        {{-- value="{{ isset($purchase) ? $purchase->purchase_date : now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}" --}}
                                        name="invoice_date" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Due Date (Optional)</label>
                                    <input type="text" id="datetime-datepicker2" class="form-control"
                                        placeholder="Basic datepicker"
                                        value="Due Date"
                                        {{-- value="{{ isset($purchase) ? $purchase->purchase_date : now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}" --}}
                                        name="due_date" required>
                                </div>


                                <div class="mb-3">
                                    <label for="example-textarea" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" placeholder="Address" rows="5" name="notes"></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($supplier) ? 'Update supplier' : 'Create Invoice' }}</button>
                                </div>
                                {{-- {{ old('address', isset($supplier) ? $supplier->address : '') }} --}}
                            </form>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
    <!-- end row -->
</div> <!-- container -->

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#paymentMethod').on('change', function() {
            if ($(this).val() == '2') {
                $('#bankInfo').removeClass('d-none');
                $('#bank_id').prop('required', true).prop('disabled', false);
                $('#depositor_name').prop('required', true).prop('disabled', false);
            } else {
                $('#bankInfo').addClass('d-none');
                $('#bank_id').prop('required', false).prop('disabled', true);
                $('#depositor_name').prop('required', false).prop('disabled', true);
            }
        });

        $('#advanceToggle').on('change', function() {
            if ($(this).is(':checked')) {
                $('#dueToggle').prop('checked', false);

                $('input[name="due_amount"]').val('').prop('disabled', true);
                $('input[name="balance"]').prop('required', true);

                $('#dueAmountOnly').addClass('d-none');
                $('#paymentMethod').closest('.col-lg-12').removeClass('d-none');

                $('#bankInfo').addClass('d-none');
                $('#bank_id').prop('required', false).prop('disabled', true);
                $('#depositor_name').prop('required', false).prop('disabled', true);
            }
        });

        $('#dueToggle').on('change', function() {
            if ($(this).is(':checked')) {
                $('#advanceToggle').prop('checked', false);

                // $('input[name="balance"]').val('');
                $('input[name="balance"]').val('').prop('required', false);
                $('#paymentMethod').val('1').trigger('change');
                $('#bank_id').val('');
                $('input[name="check_no"]').val('');
                $('input[name="depositor_name"]').val('');

                // $('input[name="due_amount"]').prop('disabled', false);
                $('input[name="due_amount"]').prop('disabled', false).prop('required', true);
                $('#dueAmountOnly').removeClass('d-none');
                $('#paymentMethod').closest('.col-lg-12').addClass('d-none');
                $('#bankInfo').addClass('d-none');
            }
        });

        $('form').on('submit', function() {
            if ($('#bankInfo').hasClass('d-none')) {
                $('#bank_id').prop('disabled', true);
                $('#depositor_name').prop('disabled', true);
            }

            if ($('#dueAmountOnly').hasClass('d-none')) {
                $('input[name="due_amount"]').prop('disabled', true);
            }
        });

        $('#advanceToggle').trigger('change');
    });
</script>
@endsection