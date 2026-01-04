@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => 'Assign Salary', 'subtitle' => 'Salaries'])

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <div class="row">
                            <div class="col-lg-12">

                                <form id="editForm" action="{{ route('employee.transaction.payment') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Salary Date</label>
                                        <input type="text" id="datetime-datepicker" class="form-control"
                                            placeholder="Basic datepicker"
                                            value="{{ isset($purchase) ? $purchase->purchase_date : now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}"
                                            name="salary_date" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Select Employee</label>
                                        <select id="select-employee" class="form-control" data-toggle="select2"
                                            data-width="100%" name="employee_id" required>
                                            <option value="">Select Employee</option>
                                            @foreach ($employes as $key => $employee)
                                                <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}"
                                                    data-conveyance="{{ $employee->conveyance }}">
                                                    {{ $employee->name . ' - ' . $employee->code }}</option>
                                            @endforeach
                                        </select>
                                        <div id="salary-conveyance" class="mt-1 d-none">
                                            Salary: <span id="employee-salary" class="me-2">700 Tk</span>
                                            Conveyance: <span id="employee-conveyance">50 Tk</span>
                                        </div>
                                    </div>

                                    <h4>Payment Method and Type</h4>
                                    <div class="mb-3 mt-1"><label class="form-label">Payment Amount</label>
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
                                                        class="form-control rounded-0" placeholder="Enter Amount"
                                                        name="balance">
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
                                                            <option value="{{ $item->id }}"
                                                                data-balance="{{ $item->balance }}">
                                                                {{ $item->account_no . ' - ' . $item->account_name . ' - ' . $item->bank_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="example-check" class="form-label">Check No</label>
                                                    <input type="text" id="example-check" class="form-control"
                                                        name="check_no" placeholder="Check No">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="example-withdrawer" class="form-label">Withdrawer
                                                        Name</label>
                                                    <input id="withdrawer_name" type="text" id="example-withdrawer"
                                                        class="form-control" name="withdrawer_name"
                                                        placeholder="Withdrawer Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="punishment" class="form-label">Punishment Amount (tk)</label>
                                        <input class="form-control" type="number" id="punishment" name="punishment"
                                            placeholder="Punishment Amount">
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" rows="6" name="description" placeholder="Description"></textarea>
                                    </div>

                                    <div class="mb-3 text-end">
                                        <button class="btn btn-primary" type="submit">Submit</button>
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
    @vite(['resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

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

                $('#select-employee').on('change', function() {
                    $('#salary-conveyance').removeClass('d-none');
                    let selectedOption = $(this).find(':selected');
                    let salary = parseFloat(selectedOption.data('salary')) || 0;
                    let conveyance = parseFloat(selectedOption.data('conveyance')) || 0;

                    $('#employee-salary').text(salary.toFixed(2));
                    $('#employee-conveyance').text(conveyance.toFixed(2));
                });

            })(jQuery);
        });
    </script>
@endsection
