@extends('layouts.vertical', ['title' => 'Sales'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', /* অন্যান্য */])
@endsection

@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => 'Sales', 'subtitle' => 'Poultry Sales'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="col-md-6" style="margin: 10px;">
                    <a href="{{ route('customer.manageBatch', $bathInfo->id) }}" class="btn btn-info">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">Sales Records</h4>
                        <button type="button" class="btn btn-primary createSaleBtn">Add New Sale</button>
                    </div>

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Qty/Weight</th>
                                <th>Rate</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Status</th>
                                <th>Channel</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sale->sale_date->format('d M Y') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $sale->sale_type)) }}</td>
                                    <td>
                                        {{ $sale->sale_type === 'by_weight' ? $sale->weight_kg.' kg' : $sale->quantity.' pcs' }}
                                    </td>
                                    <td>{{ number_format($sale->rate, 2) }}</td>
                                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>{{ number_format($sale->paid_amount, 2) }}</td>
                                    <td><span class="badge bg-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }}">{{ ucfirst($sale->payment_status) }}</span></td>
                                    <td>{{ ucfirst($sale->sales_channel) }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-primary editSaleBtn"
                                            data-id="{{ $sale->id }}"
                                            data-route="{{ route('poultry.sale.update', $sale->id) }}"
                                            data-sale-type="{{ $sale->sale_type }}"
                                            data-quantity="{{ $sale->quantity }}"
                                            data-weight="{{ $sale->weight_kg }}"
                                            data-rate="{{ $sale->rate }}"
                                            data-total="{{ $sale->total_amount }}"
                                            data-paid="{{ $sale->paid_amount }}"
                                            data-payment-status="{{ $sale->payment_status }}"
                                            data-sale-date="{{ $sale->sale_date }}"
                                            data-payment-date="{{ $sale->payment_date }}"
                                            data-channel="{{ $sale->sales_channel }}"
                                            data-note="{{ $sale->note }}">
                                            <i class="mdi mdi-grease-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger confirmationBtn"
                                            data-question="Are you sure to delete this sale?"
                                            data-action="{{ route('poultry.sale.destroy', $sale->id) }}">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<!-- Add Modal -->
<div id="addSaleModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-center mb-4">Create New Sale</h4>
                <form action="{{ route('poultry.sale.store') }}" method="POST" class="px-3">
                    @csrf
                    <input type="hidden" name="batch_id" value="{{ $bathInfo->id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Sale Date <span class="text-danger">*</span></label>
                            <input type="date" name="sale_date" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Sale Type <span class="text-danger">*</span></label>
                            <select name="sale_type" id="add_sale_type" class="form-control" required>
                                <option value="by_weight">By Weight (kg)</option>
                                <option value="by_piece">By Piece</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Quantity (pcs) <span class="text-danger">*</span></label>
                            <input type="number" step="1" name="quantity" id="add_quantity" class="form-control calc-total" required placeholder="e.g. 100">
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="add_weight_field">
                            <label>Weight (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="weight_kg" id="add_weight_kg" class="form-control calc-total" placeholder="e.g. 150.50">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Rate per Unit <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="rate" id="add_rate" class="form-control calc-total" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Total Amount</label>
                            <input type="number" step="0.01" name="total_amount" id="add_total_amount" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Paid Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="paid_amount" class="form-control" required value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Sales Channel</label>
                            <select name="sales_channel" class="form-control" required>
                                <option value="wholesale">Wholesale</option>
                                <option value="retail">Retail</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Note (Optional)</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal --> 
<!-- (উপরের মতোই, শুধু id দিয়ে edit_ prefix, form action edit route, @method('PUT') ) -->

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js'])

    <script>
        $(document).ready(function() {

            // Toggle Weight field only
            function toggleWeightField(prefix) {
                const saleType = $(`#${prefix}_sale_type`).val();
                const weightField = $(`#${prefix}_weight_field`);

                if (saleType === 'by_weight') {
                    weightField.removeClass('d-none');
                    $(`#${prefix}_weight_kg`).prop('required', true);
                } else {
                    weightField.addClass('d-none');
                    $(`#${prefix}_weight_kg`).prop('required', false).val(''); // optional: clear value
                }
                calculateTotal(prefix);
            }

            // Calculate Total Amount
            function calculateTotal(prefix) {
                const saleType = $(`#${prefix}_sale_type`).val();
                const rate = parseFloat($(`#${prefix}_rate`).val()) || 0;
                const quantity = parseFloat($(`#${prefix}_quantity`).val()) || 0;

                let total = quantity * rate;

                if (saleType === 'by_weight') {
                    const weight = parseFloat($(`#${prefix}_weight_kg`).val()) || 0;
                    total = weight * rate;
                }

                $(`#${prefix}_total_amount`).val(total.toFixed(2));
            }

            // Sale Type Change (Both Modals)
            $('#add_sale_type, #edit_sale_type').on('change', function() {
                const prefix = this.id.startsWith('add') ? 'add' : 'edit';
                toggleWeightField(prefix);
            });

            // Input Change (Quantity, Rate, Weight)
            $('#addSaleModal, #editSaleModal').on('input', '.calc-total', function() {
                const modalId = $(this).closest('.modal').attr('id');
                const prefix = modalId === 'addSaleModal' ? 'add' : 'edit';
                calculateTotal(prefix);
            });

            // Add Modal Open
            $('.createSaleBtn').on('click', function() {
                toggleWeightField('add');
                calculateTotal('add');
                $('#addSaleModal').modal('show');
            });

            // Edit Modal Open
            $('.editSaleBtn').on('click', function() {
                let data = $(this).data();

                $('#editForm').attr('action', data.route);
                $('#edit_sale_type').val(data.saleType);
                $('#edit_quantity').val(data.quantity);
                $('#edit_weight_kg').val(data.weight || '');
                $('#edit_rate').val(data.rate);
                $('#edit_total_amount').val(data.total);
                // ... অন্যান্য ফিল্ড

                // Toggle + Calculate after data load
                toggleWeightField('edit');
                calculateTotal('edit');

                $('#editSaleModal').modal('show');
            });

        });
    </script>
@endsection