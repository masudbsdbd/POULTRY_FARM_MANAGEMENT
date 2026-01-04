@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Expense', 'subtitle' => 'Expense'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-end">
                        @can('expense-create')
                        <a href="{{ route('expense.create') }}" class="btn btn-primary waves-effect waves-light">Add
                            New</a>
                        @endcan
                    </div>
                    <h4 class="header-title">{{ $pageTitle }}</h4>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Employee</th>
                                <th class="text-center">Head</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Entry Type</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Effctive Amount</th>
                                <th class="text-center">Paid Amount</th>
                                <th class="text-center">Effctive Debit/Credit</th>
                                <th class="text-end">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($expenses as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ showDateTime($item->entry_date) }}</td>

                                <td class="text-center">
                                    @if (isset($item->employee_id))
                                    {{ @$item->employee->name }}
                                    @else
                                    ---
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (isset($item->expenseHead))
                                    {{ @$item->expenseHead->name }}
                                    @else
                                    --- 
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (isset($item->title))
                                    @if (strlen($item->title) < 25)
                                        {{ $item->title }}
                                        @else
                                        {{ substr($item->title, 0, 17) }}...
                                        <a data-paragraph="{{ $item->title }}" href="javascript:void(0)"
                                        class="text-primary descBtn" href="">
                                        See More</a>
                                        @endif
                                        @else
                                        ---
                                        @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->type == 0)
                                        <span class="badge badge-soft-dark">manual</span>
                                    @else
                                        <span class="badge badge-soft-dark">journal</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ showAmount($item->amount) }} </td>
                                <td class="text-center">{{ showAmount($item->effective_amount) }} </td>

                                <td class="text-center">{{ showAmount($item->paid_amount) }} </td>
                                
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
                                
                                <td class="text-center">
                                    @if($item->amount == $item->paid_amount || $item->paid_amount == $item->effective_amount)
                                    <span class="badge badge-soft-success">paid</span>
                                    @else
                                    <span class="badge badge-soft-danger">Unpaid</span>
                                    @endif                          
                                </td>

                                <td class="text-end">
                                @if($item->effective_amount == 0)
                                <button title="Due Payment" type="button"
                                    class="btn btn-success waves-effect waves-light paymentBtn {{ $item->amount != $item->paid_amount ? '' : 'disabled' }}"
                                    data-action="{{ route('expense.pay', $item->id) }}"><i
                                        class="mdi mdi-cash-multiple"></i></button>
                                    @else
                                    <button title="Due Payment" type="button"
                                    class="btn btn-success waves-effect waves-light disabled"
                                    data-action=""><i
                                        class="mdi mdi-cash-multiple"></i></button>
                                    @endif

                                        
                                    @can('expense-list')
                                    <button title="View" type="button"
                                        class="btn btn-primary waves-effect waves-light expenseDetailBtn"
                                        data-id="{{ $item->id }}"
                                        data-created_at="{{ showDateTime($item->created_at) }}"
                                        data-employee_name="{{ $item->employee->name }}"
                                        data-head_name="{{ @$item->expenseHead->name }}"
                                        data-title="{{ $item->title }}"
                                        data-amount="{{ showAmount($item->amount) }}"
                                        data-description="{{ $item->description }}">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                    @endcan

                                    {{-- @can('expense-edit')
                                    <a href="{{ route('expense.edit', $item->id) }}"
                                        class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-table-edit"></i></a>
                                    @endcan --}}
                                    @can('expense-delete')
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this expense?')"
                                        data-action="{{ route('expense.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                    @endcan


                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $expenses->links() }}</div>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->

<div id="expensePaymentModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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

<div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Expense Title</h4>
                @csrf
                <p class="descParagraph"></p>
                <div class="text-end">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<div id="sellDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header text-white">
                <h5 class="modal-title">Expense Details</h5>
                <!-- Close button removed -->
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover mb-4">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Head</th>
                                    <th>Employee</th>
                                    <th>Title</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="modalDate">---</td>
                                    <td id="modalHead">---</td>
                                    <td id="modalEmployee">---</td>
                                    <td id="modalTitle">---</td>
                                    <td id="modalAmount">---</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <h6><strong>Description:</strong></h6>
                            <p id="modalDescription">No description available.</p>
                        </div>
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

            $(document).on('click', '.descBtn', function() {
                var modal = $('#descModal');
                let data = $(this).data();
                modal.find('.descParagraph').text(`${data.paragraph}`);
                modal.modal('show');
            });



            $(document).on('click', '.expenseDetailBtn', function() {
                var modal = $('#sellDetail');

                var createdAt = $(this).data('created_at');
                var headName = $(this).data('head_name');
                var employeeName = $(this).data('employee_name');
                var title = $(this).data('title');
                var amount = $(this).data('amount');
                var description = $(this).data('description');

                // console.log(headName);

                modal.find('#modalDate').text(createdAt);
                modal.find('#modalHead').text(headName || '---');
                modal.find('#modalEmployee').text(employeeName || '---');
                modal.find('#modalTitle').text(title || '---');
                modal.find('#modalAmount').text(amount + ' Tk');
                modal.find('#modalDescription').text(description || 'No description available.');

                // Show the modal
                modal.modal('show');
            });



            $(document).on('click', '.paymentBtn', function() {
                    var modal = $('#expensePaymentModal');
                    let data = $(this).data();

                    $('#paymentForm')[0].reset();
                    $('#bankInfo').addClass('d-none');
                    $('#bank_id').prop('required', false);
                    $('#withdrawer_name').prop('required', false);
                    $('#paymentForm').attr('action', data.action);

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