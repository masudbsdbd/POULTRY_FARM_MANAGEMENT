@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
        'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
        'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
        'node_modules/mohithg-switchery/dist/switchery.min.css'
    ])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Expense', 'subtitle' => 'Poultry Expense'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="col-md-6 col-xl-4" style="margin-left: 10px; margin-top: 10px;">
                    <a href="{{ route('customer.manageBatch', $bathInfo->id) }}" class="btn btn-info">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle ?? 'Poultry Expenses' }}</h4>
                        <button type="button" class="mb-2 btn btn-primary waves-effect waves-light createExpenseBtn">
                            Add New Expense
                        </button>
                    </div>

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Total Amount</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $expense->expense_date }}</td>
                                    <td>{{ $expense->expense_title }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $expense->category)) }}</td>
                                    <td>{{ $expense->quantity }}</td>
                                    <td>{{ $expense->unit }}</td>
                                    <td>{{ number_format($expense->total_amount, 2) }}</td>
                                    <td class="text-end">
                                        <button type="button"
                                                class="btn btn-primary waves-effect waves-light editExpenseBtn"
                                                data-id="{{ $expense->id }}"
                                                data-route="{{ route('poultry.expense.update', $expense->id) }}"
                                                data-title="{{ $expense->expense_title }}"
                                                data-invoice="{{ $expense->invoice_number }}"
                                                data-category="{{ $expense->category }}"
                                                data-feed-name="{{ $expense->feed_name }}"
                                                data-feed-type="{{ $expense->feed_type }}"
                                                data-medicine-name="{{ $expense->medicine_name }}"
                                                data-transaction="{{ $expense->transaction_type }}"
                                                data-quantity="{{ $expense->quantity }}"
                                                data-unit="{{ $expense->unit }}"
                                                data-price="{{ $expense->price }}"
                                                data-total="{{ $expense->total_amount }}"
                                                data-description="{{ $expense->description }}"
                                                data-date="{{ $expense->expense_date }}">
                                            <i class="mdi mdi-grease-pencil"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-danger waves-effect waves-light confirmationBtn"
                                                data-question="Are you sure to delete this expense?"
                                                data-action="{{ route('poultry.expense.destroy', $expense->id) }}">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div>
<!-- container -->

<!-- Create Modal -->
<div id="addExpenseModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Create New Expense</h4>
                </div>
                <form class="px-3" action="{{ route('poultry.expense.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_id" value="{{ $bathInfo->id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expense_date" class="form-label">Expense Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="expense_date" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expense_title" class="form-label">Expense Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="expense_title" placeholder="e.g. Feed Purchase, Medicine Cost, Labor Payment" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="invoice_number" class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" value="{{ $invNumber }}" name="invoice_number" placeholder="e.g. INV-2025-001 (optional)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control" name="category" id="add_category" required>
                                <option value="">Select Category</option>
                                <option value="feed">Feed</option>
                                <option value="medicine">Medicine</option>
                                <option value="transportation">Transportation</option>
                                <option value="bedding">Bedding</option>
                                <option value="labor">Labor</option>
                                <option value="utilities">Utilities</option>
                                <option value="death_loss">Death Loss</option>
                                <option value="miscellaneous">Miscellaneous</option>
                                <option value="bad_debt">Bad Debt</option>
                                <option value="bio_security">Bio Security</option>
                                <option value="chickens">Chickens</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Feed Fields -->
                    <div id="feed_fields" class="d-none">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="feed_name">Feed Name</label>
                                <input type="text" class="form-control" name="feed_name" placeholder="e.g. Starter Feed, Layer Mash">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="feed_type">Feed Type</label>
                                <select class="form-control" name="feed_type">
                                    <option value="">Select Type</option>
                                    <option value="pre_starter">Pre Starter</option>
                                    <option value="starter">Starter</option>
                                    <option value="grower">Grower</option>
                                    <option value="finisher">Finisher</option>
                                    <option value="pre_layer">Pre Layer</option>
                                    <option value="layer">Layer</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Medicine Field -->
                    <div id="medicine_field" class="d-none mb-3">
                        <label for="medicine_name">Medicine Name</label>
                        <input type="text" class="form-control" name="medicine_name" placeholder="e.g. Vitamin Supplement, Antibiotic">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="transaction_type">Transaction Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="transaction_type" required>
                                <option value="cash">Cash</option>
                                <option value="due">Due</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="quantity">Quantity <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" name="quantity" placeholder="e.g. 50" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="unit">Unit <span class="text-danger">*</span></label>
                            <select class="form-control" name="unit" required>
                                <option value="bag">Bag</option>
                                <option value="piece">Piece</option>
                                <option value="kg">Kg</option>
                                <option value="litre">Litre</option>
                                <option value="gram">Gram</option>
                                <option value="bottle">Bottle</option>
                                <option value="pack">Pack</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price">Price per Unit <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control " name="price" placeholder="e.g. 2500.00" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="total_amount" placeholder="e.g. 125000.00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description">Description (Optional)</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Add any additional notes or details about this expense..."></textarea>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Create Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editExpenseModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Edit Expense</h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_expense_date" class="form-label">Expense Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_expense_date" name="expense_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_expense_title" class="form-label">Expense Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_expense_title" name="expense_title" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_invoice_number">Invoice Number</label>
                            <input type="text" class="form-control" id="edit_invoice_number" name="invoice_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_category">Category <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="feed">Feed</option>
                                <option value="medicine">Medicine</option>
                                <option value="transportation">Transportation</option>
                                <option value="bedding">Bedding</option>
                                <option value="labor">Labor</option>
                                <option value="utilities">Utilities</option>
                                <option value="death_loss">Death Loss</option>
                                <option value="miscellaneous">Miscellaneous</option>
                                <option value="bad_debt">Bad Debt</option>
                                <option value="bio_security">Bio Security</option>
                                <option value="chickens">Chickens</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div id="edit_feed_fields" class="d-none">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Feed Name</label>
                                <input type="text" class="form-control" id="edit_feed_name" name="feed_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Feed Type</label>
                                <select class="form-control" id="edit_feed_type" name="feed_type">
                                    <option value="">Select Type</option>
                                    <option value="pre_starter">Pre Starter</option>
                                    <option value="starter">Starter</option>
                                    <option value="grower">Grower</option>
                                    <option value="finisher">Finisher</option>
                                    <option value="pre_layer">Pre Layer</option>
                                    <option value="layer">Layer</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="edit_medicine_field" class="d-none mb-3">
                        <label>Medicine Name</label>
                        <input type="text" class="form-control" id="edit_medicine_name" name="medicine_name">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Transaction Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_transaction_type" name="transaction_type" required>
                                <option value="cash">Cash</option>
                                <option value="due">Due</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Quantity <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control" id="edit_quantity" name="quantity" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Unit <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_unit" name="unit" required>
                                <option value="bag">Bag</option>
                                <option value="piece">Piece</option>
                                <option value="kg">Kg</option>
                                <option value="litre">Litre</option>
                                <option value="gram">Gram</option>
                                <option value="bottle">Bottle</option>
                                <option value="pack">Pack</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Price per Unit <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="edit_price" name="price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Total Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="edit_total_amount" name="total_amount" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Description (Optional)</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Update Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            (function ($) {
                "use strict";

                // Function to toggle conditional fields (feed/medicine)
                function toggleConditionalFields(selectElement, feedDiv, medicineDiv) {
                    const category = selectElement.val();
                    if (category === 'feed') {
                        feedDiv.removeClass('d-none');
                        medicineDiv.addClass('d-none');
                    } else if (category === 'medicine') {
                        medicineDiv.removeClass('d-none');
                        feedDiv.addClass('d-none');
                    } else {
                        feedDiv.addClass('d-none');
                        medicineDiv.addClass('d-none');
                    }
                }

                // Auto-calculate Total Amount = Quantity * Price
                function calculateTotalAmount(quantityInput, priceInput, totalInput) {
                    const qty = parseFloat(quantityInput.val()) || 0;
                    const price = parseFloat(priceInput.val()) || 0;
                    const total = qty * price;
                    totalInput.val(total.toFixed(2));
                }

                // Add Modal: Auto Calculate Total Amount
                const addQty = $('#addExpenseModal input[name="quantity"]');
                const addPrice = $('#addExpenseModal input[name="price"]');
                const addTotal = $('#addExpenseModal input[name="total_amount"]');

                addQty.on('input', function () {
                    calculateTotalAmount(addQty, addPrice, addTotal);
                });

                addPrice.on('input', function () {
                    calculateTotalAmount(addQty, addPrice, addTotal);
                });

                // Edit Modal: Auto Calculate Total Amount
                const editQty = $('#edit_quantity');
                const editPrice = $('#edit_price');
                const editTotal = $('#edit_total_amount');

                editQty.on('input', function () {
                    calculateTotalAmount(editQty, editPrice, editTotal);
                });

                editPrice.on('input', function () {
                    calculateTotalAmount(editQty, editPrice, editTotal);
                });

                // Add Modal: Category change
                $('#add_category').on('change', function() {
                    toggleConditionalFields($(this), $('#feed_fields'), $('#medicine_field'));
                });

                // Edit Modal: Category change
                $('#edit_category').on('change', function() {
                    toggleConditionalFields($(this), $('#edit_feed_fields'), $('#edit_medicine_field'));
                });

                // Open Create Modal
                $('.createExpenseBtn').on('click', function () {
                    $('#addExpenseModal').modal('show');
                });

                // Open Edit Modal
                $('.editExpenseBtn').on('click', function () {
                    let data = $(this).data();

                    $('#editForm').attr('action', data.route);

                    $('#edit_expense_date').val(data.date);
                    $('#edit_expense_title').val(data.title);
                    $('#edit_invoice_number').val(data.invoice);
                    $('#edit_category').val(data.category);
                    $('#edit_feed_name').val(data.feedName);
                    $('#edit_feed_type').val(data.feedType);
                    $('#edit_medicine_name').val(data.medicineName);
                    $('#edit_transaction_type').val(data.transaction);
                    $('#edit_quantity').val(data.quantity);
                    $('#edit_unit').val(data.unit);
                    $('#edit_price').val(data.price);
                    $('#edit_total_amount').val(data.total);
                    $('#edit_description').val(data.description);

                    // Trigger conditional fields visibility
                    toggleConditionalFields($('#edit_category'), $('#edit_feed_fields'), $('#edit_medicine_field'));

                    // Trigger initial calculation in edit modal
                    calculateTotalAmount(editQty, editPrice, editTotal);

                    $('#editExpenseModal').modal('show');
                });

            })(jQuery);
        });
    </script>
@endsection