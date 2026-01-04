@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => $pageTitle])

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <form action="{{ isset($expense) ? route('expense.store', $expense->id) : route('expense.store') }}"
                            method="POST">
                            @csrf
                            <div class="row">

                                <div class="mb-3 col-lg-12">
                                    <p class="mb-1 fw-bold text-muted">Select Expense Head</p>
                                    <select id="select-employees" class="form-control" data-toggle="select2"
                                        data-width="100%" name="expense_head_id" required>
                                        <option value="">Select</option>
                                        @foreach ($expenseHeads as $item)
                                            <option value="{{ $item->id }}" @selected(isset($expense) ? $expense->expense_head_id == $item->id : false)>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> <!-- end col -->

                                <div class="mb-3 col-lg-6">
                                    <label class="form-label"> Date</label>
                                    <input type="text" id="datetime-datepicker" class="form-control"
                                        placeholder="Basic datepicker"
                                        value="{{ isset($expense) ? $expense->entry_date : now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}"
                                        name="entry_date" required>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-muted">Select Employee</p>
                                    <select id="select-employees" class="form-control" data-toggle="select2"
                                        data-width="100%" name="employee_id" required>
                                        <option value="">Select</option>
                                        @foreach ($employees as $item)
                                            <option value="{{ $item->id }}" @selected(isset($expense) ? $expense->employee_id == $item->id : false)>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> <!-- end col -->

                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label fw-bold text-muted">Title</label>
                                    <input id="title" type="text" class="form-control shadow-sm rounded"
                                        placeholder="Enter title" name="title"
                                        value="{{ isset($expense) ? $expense->title : '' }}" required>
                                </div> <!-- end col -->

                                {{-- <div class="col-md-12 d-flex align-items-center">
                                    <label for="pending_status" class="fw-bold text-muted me-2">Set as Pending</label>
                                    <div class="form-check form-switch">
                                        <input id="pending_status" type="checkbox" class="form-check-input toggle-switch" 
                                            name="pending_status">
                                    </div>
                                </div> <!-- end col -->
                                


                                <div class="col-md-12">
                                    <div class="my-3"><label class="form-label"> Amount</label>
                                        <div class="input-group">
                                            <!-- Select element taking 4 columns -->
                                            <div class="col-lg-3 pe-0">
                                                <select id="paymentMethod" class="form-select rounded-0" id="example-select"
                                                    name="payment_method" required>
                                                    <option value="1" @selected(isset($expense) ? $expense->account->payment_method == 1 : false)>Cash</option>
                                                    <option value="2" @selected(isset($expense) ? $expense->account->payment_method == 2 : false)>Bank</option>
                                                </select>
                                            </div>

                                            <!-- Other elements taking 8 columns -->
                                            <div class="col-lg-9">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control rounded-0" placeholder="Enter Amount"
                                                        name="amount"
                                                        value="{{ isset($expense) ? number_format($expense->amount, 2, '.', '') : '' }}"
                                                        required>
                                                    <div class="input-group-text">Tk</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-md-12 d-flex align-items-center">
                                    <label for="pending_status" class="fw-bold text-muted me-2">Set as Pending</label>
                                    <div class="form-check form-switch">
                                        <input id="pending_status" type="checkbox" class="form-check-input toggle-switch"
                                            name="pending_status"

                                            @checked(isset($expense) ? ($expense->pending_status == 1 ? true : false) : false)
                                            
                                            
                                            >
                                    </div>
                                </div> <!-- end col -->

                                <!-- Amount Field -->
                                {{-- @if (isset($expense->account)) --}}
                                    <div class="col-md-12" id="amountField">
                                        <div class="my-3">
                                            <label class="form-label">Amount</label>
                                            <div class="input-group">
                                                <div class="col-lg-3 pe-0">
                                                    <select id="paymentMethod" class="form-select rounded-0"
                                                        name="payment_method">
                                                        <option value="1" @selected(isset($expense) && isset($expense->account) && $expense->account->payment_method == 1)>Cash</option>
                                                        <option value="2" @selected(isset($expense) && isset($expense->account) && $expense->account->payment_method == 2)>Bank</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0"
                                                            class="form-control rounded-0" placeholder="Enter Amount"
                                                            name="amount"
                                                            value="{{ isset($expense) ? number_format($expense->amount, 2, '.', '') : '' }}">
                                                        <div class="input-group-text">Tk</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {{-- @endif --}}

                                <!-- New Input Field (hidden by default) -->
                                <div class="col-md-12  {{ isset($expense->account) ? 'd-none' : '' }}" id="newInputField">
                                    <div class="my-3">
                                        <label class="form-label">Amount</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control rounded-0" placeholder="Enter Amount"
                                                name="pending_amount"

                                                 value="{{ isset($expense) ? number_format($expense->amount, 2, '.', '') : '' }}"
                                                
                                                >
                                            <div class="input-group-text">Tk</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- @if (isset($expense->account)) --}}
                                    <div id="bankInfo"
                                        class="col-lg-12 {{ isset($expense) ? (isset($expense->account) ? ($expense->account->payment_method == 2 ? '' : 'd-none') : 'd-none') : 'd-none' }}">
                                        <h4>Bank Information</h4>
                                        <div class="row my-3">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="example-Account" class="form-label">Select Bank</label>
                                                    <select id="bank_id" class="form-select rounded-0" name="bank_id"
                                                        {{ isset($expense) ? isset($expense->account) ? ($expense->account->payment_method == 2 ? 'required' : '') : '' : '' }}>
                                                        <option value="">Select Bank</option>
                                                        @foreach ($banks as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-balance="{{ $item->balance }}"
                                                                @selected(isset($expense) && isset($expense->account->bankTransaction->bank_id) ? $item->id == $expense->account->bankTransaction->bank_id : false)>
                                                                {{ $item->account_no . ' - ' . $item->account_name . ' - ' . $item->bank_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="example-check" class="form-label">Check No</label>
                                                    <input type="text" id="example-check" class="form-control"
                                                        name="check_no"
                                                        value="{{ isset($expense) && isset($expense->account) ? ($expense->account->bankTransaction ? $expense->account->bankTransaction->check_no : '') : ''}}"
                                                        placeholder="Check No">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="withdrawer_name" class="form-label">Withdrawer Name</label>
                                                    <input id="withdrawer_name" type="text" class="form-control" name="withdrawer_name"
                                                        placeholder="Withdrawer Name"
                                                        value="{{ isset($expense) && isset($expense->account) && isset($expense->account->bankTransaction) ? $expense->account->bankTransaction->withdrawer_name : '' }}"
                                                        {{ isset($expense) && isset($expense->account) && $expense->account->payment_method == 2 ? 'required' : '' }}>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                {{-- @endif --}}





                                <div class="col-md-12 ">
                                    <p class="mb-1 fw-bold text-muted">Description</p>
                                    <textarea class="form-control" id="example-textarea" placeholder="Address" rows="5" name="description"
                                        required>{{ isset($expense) ? $expense->description : '' }} </textarea>
                                </div> <!-- end col -->





                                <div class="text-end mt-3">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($expense) ? 'Update Expense' : 'Add New Expense' }}</button>
                                </div>

                            </div>
                        </form>
                        <!-- end row-->

                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let getBankOnEditPage = $('#bank_id').val();

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

            $('#bank_id').on('change', function() {
                let data = $(this).find(':selected').data();
                let total_price = $('input[name="amount"]').val();

                // console.log(getBankOnEditPage);

                if (total_price > data.balance && $(this).val() != getBankOnEditPage) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Insufficient balance in this bank.'
                    })

                    if (getBankOnEditPage !== "" && getBankOnEditPage !== null) {
                        $(this).val(getBankOnEditPage);
                    }
                }
            });
            // $(document).ready(function() {
            //     $('#pending_status').change(function() {
            //         var amountField = $('#amountField');
            //         var newInputField = $('#newInputField');
            //         var amountInput = $('input[name="amount"]');

            //         if ($(this).is(':checked')) {
            //             $('#bankInfo').addClass('d-none');
            //             amountField.addClass('d-none');
            //             newInputField.removeClass('d-none');
            //             amountInput.removeAttr(
            //                 'required');
            //         } else {

            //             amountField.removeClass('d-none');
            //             newInputField.addClass('d-none');
            //             amountInput.attr('required',
            //                 true);
            //         }
            //     });

            //     if ($('#pending_status').is(':checked')) {
            //         $('#amountField').addClass('d-none');
            //         $('#newInputField').removeClass('d-none');
            //         $('input[name="amount"]').removeAttr('required');
            //     } else {
            //         $('#amountField').removeClass('d-none');
            //         $('#newInputField').addClass('d-none');
            //         $('input[name="amount"]').attr('required', true);
            //     }
            // });

            $('#pending_status').change(function() {
                var amountField = $('#amountField');
                var newInputField = $('#newInputField');
                var amountInput = $('input[name="amount"]');
                var paymentMethod = $('#paymentMethod');

                if ($(this).is(':checked')) {
                    $('#bankInfo').addClass('d-none');
                    amountField.addClass('d-none');
                    newInputField.removeClass('d-none');
                    amountInput.removeAttr('required');

                    paymentMethod.prop('disabled', true);
                    paymentMethod.val('');
                } else {
                    amountField.removeClass('d-none');
                    newInputField.addClass('d-none');
                    amountInput.attr('required', true);

                    paymentMethod.prop('disabled', false);
                    paymentMethod.val(1);
                }
            });

            // Ensure correct state on page load
            if ($('#pending_status').is(':checked')) {
                $('#amountField').addClass('d-none');
                $('#newInputField').removeClass('d-none');
                $('input[name="amount"]').removeAttr('required');
                $('#paymentMethod').prop('disabled', true).val('');
            } else {
                $('#amountField').removeClass('d-none');
                $('#newInputField').addClass('d-none');
                $('input[name="amount"]').attr('required', true);
                $('#paymentMethod').prop('disabled', false);
            }




        });
    </script>
@endsection
