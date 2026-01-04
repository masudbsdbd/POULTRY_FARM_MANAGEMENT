@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
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
                                action="{{ isset($supplier) ? route('supplier.store', $supplier->id) : route('supplier.store')  }}"
                                method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Name</label>
                                    <input type="text" id="simpleinput" class="form-control" placeholder="Name"
                                        name="name" required
                                        value="{{ old('name', isset($supplier) ? $supplier->name : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="compnay-name" class="form-label">Company Name</label>
                                    <input type="text" id="compnay-name" class="form-control"
                                        placeholder="Company Name" name="company" required
                                        value="{{ old('company', isset($supplier) ? $supplier->company : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="example-mobile" class="form-label">Mobile</label>
                                    <input type="number" id="example-mobile" class="form-control" name="mobile"
                                        placeholder="Mobile" required
                                        value="{{ old('name', isset($supplier) ? $supplier->mobile : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="example-email" class="form-label">Email</label>
                                    <input type="email" id="example-email" name="email" class="form-control"
                                        placeholder="Email"
                                        value="{{ old('name', isset($supplier) ? $supplier->email : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="example-textarea" class="form-label">Address</label>
                                    <textarea class="form-control" id="example-textarea" placeholder="Address" rows="5" name="address" required> {{ old('address', isset($supplier) ? $supplier->address : '') }} </textarea>
                                </div>


                                @if(isset($supplier))
                                    @else
                                    <div class="col-lg-12 mb-4">
                                        <label class="form-label">Payment Type</label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="advanceToggle" checked>
                                            <label class="form-check-label" for="advanceToggle">Advance</label>
                                            </div>
                                            <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="dueToggle">
                                            <label class="form-check-label" for="dueToggle">Due</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="dueAmountOnly" class="col-lg-12 d-none">
                                        <div class="my-3">
                                            <label class="form-label">Due Amount</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" class="form-control" name="due_amount" placeholder="Enter Due Amount">
                                                <div class="input-group-text">Tk</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="my-3"><label class="form-label">Payment Amount</label>
                                            <div class="input-group">

                                                <div class="col-lg-3 pe-0">
                                                    <select id="paymentMethod" class="form-select rounded-0"
                                                        id="example-select" name="payment_method" required>
                                                        <option value="1">Cash</option>
                                                        <option value="2">Bank</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-9">
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0"
                                                            class="form-control rounded-0" placeholder="Enter Amount"
                                                            name="balance">
                                                        <div class="input-group-text">Tk</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="bankInfo" class="col-lg-12 d-none">
                                        <h4>Bank Information</h4>
                                        <div class="row my-3">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="example-Account" class="form-label">Select Bank</label>
                                                    <select id="bank_id" class="form-select rounded-0" name="bank_id">
                                                        <option value="">Select Bank</option>
                                                        @foreach ($banks as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-balance="{{ $item->balance }}">
                                                                {{ $item->account_no . ' - ' . $item->account_name . ' - ' . $item->bank_name . ' -  Balance: ' . showAmount($item->balance) . 'Tk' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="example-check" class="form-label">Check No</label>
                                                    <input type="text" id="example-check" class="form-control"
                                                        name="check_no" placeholder="Check No">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="example-depositor" class="form-label">Withdrawer
                                                        Name</label>
                                                    <input id="withdrawer_name" type="text" id="example-depositor"
                                                        class="form-control" name="withdrawer_name"
                                                        placeholder="Withdrawer Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="mb-3 d-flex">
                                    <label for="status">Status</label>
                                    <label class="switch m-0">
                                        <input id="status" type="checkbox" class="toggle-switch" name="status"
                                            @checked(isset($supplier) ? ($supplier->status == 1 ? true : false) : true)>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                
                                <div class="text-end">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($supplier) ? 'Update supplier' : 'Add New supplier' }}</button>
                                </div>
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
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#paymentMethod').on('change', function () {
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

        $('#advanceToggle').on('change', function () {
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

        $('#dueToggle').on('change', function () {
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

        $('form').on('submit', function () {
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