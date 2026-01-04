@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Create Damage', 'subtitle' => 'Damage'])

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <form action="{{ isset($damage) ? route('damage.store', $damage->id) : route('damage.store') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" id="purchase_id" name="purchase_id"
                        value="{{ isset($damage) ? $damage->purchase_id : '' }}">
                    <input type="hidden" id="supplier_id" name="supplier_id"
                        value="{{ isset($damage) ? $damage->supplier_id : '' }}">
                    <input type="hidden" id="purchase_batch_id" name="purchase_batch_id"
                        value="{{ isset($damage) ? $damage->purchase_batch_id : '' }}">

                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title mb-3">{{ $pageTitle }}</h4>

                            <!-- <div class="mb-3 d-flex justify-content-end">
                                <label for="damage_status" class="me-2">Damage Status</label>
                                <label class="switch m-0">
                                    <input id="damage_status" type="checkbox" class="toggle-switch" name="damage_status"
                                        @checked(isset($damage) ? ($damage->damage_status == 1 ? true : false) : true)>
                                    <span class="slider round"></span>
                                </label>
                            </div> -->

                            <input type="hidden" id="purchase_id" name="damage_status" value="1">

                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="select-products" class="form-label">Select Product</label>
                                            <select id="select-products" class="form-control" data-toggle="select2"
                                                data-width="100%" name="product_id" required>
                                                <option value="">Select</option>
                                                @foreach ($products as $item)
                                                <option value="{{ $item->id }}" data-item="{{ $item }}"
                                                    {{ isset($damage) && $damage->product_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <p class="mb-1 fw-bold text-muted">Select Batch</p>
                                            @if (isset($damage))
                                            <!-- Dropdown is disabled in edit mode -->
                                            <select id="select-batch" class="form-select" required>
                                                @foreach ($stocks as $item)
                                                <option value="{{ $item['batch_id'] }}"
                                                    data-supplier_id="{{ $item['supplier_id'] }}"
                                                    data-purchase_id="{{ $item['purchase_id'] }}"
                                                    data-avg_purchase_price="{{ $item['avg_purchase_price'] }}"
                                                    data-total_qty="{{ $item['stock'] }}"
                                                    data-unit_name="{{ $item['unit_name'] }}"
                                                    data-purchase_batch_id="{{ $item['purchase_batch_id'] }}"
                                                    {{ isset($damage) && $damage->purchase_batch_id == $item['purchase_batch_id'] ? 'selected' : '' }}>
                                                    {{ $item['batch_code'] }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @else
                                            <!-- Dropdown is enabled in create mode -->
                                            <select id="select-batch" class="form-select" required>
                                                <option value="">Select Batch</option>
                                            </select>
                                            @endif
                                        </div>

                                    </div>
                                </div>


                                <div class="mb-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Damage Date</label>
                                            <input type="text" id="datetime-datepicker" class="form-control"
                                                placeholder="Basic datepicker"
                                                value="{{ isset($damage) ? $damage->entry_date : now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}"
                                                name="entry_date" required>
                                        </div>

                                        <!-- Damage Qty Input -->
                                        <div class="col-md-4 mb-3">
                                            <label for="example-number" class="form-label">Damage Qty</label>
                                            <input class="form-control" id="example-number" type="number"
                                                name="qty" min="0"
                                                value="{{ isset($damage) ? $damage->qty : '' }}" required>
                                        </div>

                                        <!-- Unit Price Input -->
                                        <div class="col-md-4 mb-3">
                                            <label for="unit-price" class="form-label">Avg Unit Price</label>
                                            <div class="input-group">
                                                <input type="text" id="unit-price" name="avg_purchase_price"
                                                    class="form-control productPrice" placeholder="Unit Price"
                                                    value="{{ isset($damage) ? showAmount($damage->price, 2, false) : '' }}" readonly required>
                                                <span class="input-group-text">Tk / unit</span>
                                                <!-- This will be updated dynamically -->
                                            </div>
                                        </div>


                                        <!-- Total Qty Input -->
                                        <div class="col-md-4 mb-3">
                                            <label for="total-qty" class="form-label">Total Qty</label>
                                            <div class="input-group">
                                                <input type="text" id="total-qty" class="form-control totalQty"
                                                    name="total_qty" placeholder="Total Qty"
                                                    value="{{ isset($damage) ? $damage->total_qty : '' }}" readonly>
                                                <span class="input-group-text">unit</span>
                                                <!-- This will be dynamically updated -->
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="total-damage-price" class="form-label">Total Damage Product
                                                Price</label>
                                            <div class="input-group">
                                                <input type="text" id="total-damage-price"
                                                    class="form-control totalDamagePrice" name="total_damage_price"
                                                    placeholder="Total Price"
                                                    value="{{ isset($damage) ? showAmount($damage->total_damage_price, 2, false) : '' }}" readonly>
                                                <span class="input-group-text">Tk</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="example-textarea" class="form-label">Reason</label>
                                    <textarea class="form-control" id="example-textarea" placeholder="Address" rows="5" name="description"
                                        required>{{ isset($damage) ? $damage->description : '' }} </textarea>
                                </div>

                                <!-- <div class="mb-3 d-flex">
                                    <label for="toggle-fields">Show Supplier Conversation</label>
                                    <label class="switch m-0">
                                        <input id="toggle-fields" type="checkbox"
                                            @checked(isset($damage) && !empty($damage->conversation))>
                                        <span class="slider round"></span>
                                    </label>
                                </div> -->




                                <!-- <div id="additional-fields" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status-dropdown" class="form-label">Status</label>
                                                <select id="status-dropdown" name="status" class="form-control">
                                                    <option value="0" @selected(isset($damage) && $damage->status == 0)>Pending</option>
                                                    <option value="1" @selected(isset($damage) && $damage->status == 1)>Replacement</option>
                                                    <option value="2" @selected(isset($damage) && $damage->status == 2)>Repair</option>
                                                    <option value="3" @selected(isset($damage) && $damage->status == 3)>Decline/Cancel</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div id="extra-date-field" class="mb-3 d-none">
                                                <label class="form-label">Replacement / Repair Date</label>
                                                <input type="text" id="basic-datepicker" class="form-control"
                                                    placeholder="Basic datepicker" name="replacement_repair_date"
                                                    value="{{ isset($damage) ? $damage->replacement_repair_date : '' }} " required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="example-textarea" class="form-label">Conversation</label>
                                                <textarea class="form-control" id="example-textarea" placeholder="Conversation" rows="5" name="conversation"
                                                    required> {{ isset($damage) ? $damage->conversation : '' }} </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="text-end">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($damage) ? 'Update Damage' : 'Add New Damage' }}</button>
                                </div>


                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- end card-body -->

                </form>

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
        $(document).ready(function() {

            if ($('#toggle-fields').is(':checked')) {
                $('#additional-fields').removeClass('d-none');
            }

            function calculateTotalDamagePrice() {
                const damageQty = parseFloat($('#example-number').val()) || 0;
                const unitPrice = parseFloat($('input[name="avg_purchase_price"]').val()) || 0;
                const totalDamagePrice = damageQty * unitPrice;

                $('#total-damage-price').val(totalDamagePrice.toFixed(2));
            }

            $('#example-number').on('input', function() {
                const damageQty = parseFloat($(this).val()) || 0;
                const totalQty = parseFloat($('input[name="total_qty"]').val()) || 0;

                if (damageQty > totalQty) {
                    alert("Damage Quantity cannot exceed Total Quantity!");
                    $(this).val(totalQty);
                }

                calculateTotalDamagePrice();
            });

            $('#select-products').on('change', function() {
                $('#select-batch').empty().append('<option value="">Select Batch</option>');
                $('input[name="avg_purchase_price"]').val('');
                $('input[name="total_qty"]').val('');
                $('#example-number').val('');
                $('#total-damage-price').val('');
                $('.input-group-text').text('Tk / unit');
                $('#total-qty').siblings('.input-group-text').text('unit');

                const productId = $(this).val();

                if (productId) {
                    $.ajax({
                        url: "{{ route('damage.batch.ajax', ':id') }}".replace(':id',
                            productId),
                        method: "GET",
                        success: function(response) {
                            let optionHtml = '';
                            response.forEach(option => {
                                optionHtml += `
                            <option 
                                value="${option.batch_id}" 
                                data-supplier_id="${option.supplier_id}" 
                                data-batch_code="${option.batch_code}" 
                                data-avg_purchase_price="${option.avg_purchase_price}" 
                                data-total_qty="${option.stock}" 
                                data-purchase_id="${option.purchase_id}" 
                                data-unit_name="${option.unit_name}"
                                data-purchase_batch_id="${option.purchase_batch_id}">  <!-- Added data-purchase_batch_id -->
                                ${option.batch_code}
                            </option>`;
                            });

                            $('#select-batch').append(optionHtml);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching batch details:", error);
                        }
                    });
                } else {
                    console.log("No product selected");
                }
            });


            $('#select-batch').on('change', function() {
                const selectedOption = $('#select-batch').find('option:selected');

                const purchaseId = selectedOption.data('purchase_id') || null;
                const supplierId = selectedOption.data('supplier_id') || null;
                const avgPurchasePrice = selectedOption.data('avg_purchase_price') || '';
                const totalQty = selectedOption.data('total_qty') || '';
                const unitName = selectedOption.data('unit_name') || 'unit';
                const purchaseBatchId = selectedOption.data('purchase_batch_id') || null;
                console.log(purchaseId);

                $('#purchase_id').val(purchaseId);
                $('#supplier_id').val(supplierId);
                $('#purchase_batch_id').val(purchaseBatchId);

                $('input[name="avg_purchase_price"]').val(parseFloat(avgPurchasePrice).toFixed(2));
                $('input[name="total_qty"]').val(totalQty);
                $('.input-group-text').text(`Tk / ${unitName}`);
                $('#total-qty').siblings('.input-group-text').text(unitName);

                $('#example-number').val(0);

                calculateTotalDamagePrice();
            });

            $('#toggle-fields').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#additional-fields').removeClass('d-none');
                } else {
                    $('#additional-fields').find(':input').val('');
                    $('#additional-fields').find(':input').prop('checked', false);
                    $('#status-dropdown').val('0');
                    $('#extra-date-field').addClass('d-none');
                    $('#additional-fields').addClass('d-none');
                }
            });

            $('#status-dropdown').on('change', function() {
                const selectedValue = $(this).val();

                if (selectedValue === '1' || selectedValue === '2') {
                    $('#extra-date-field').removeClass('d-none');
                } else {
                    $('#extra-date-field').addClass('d-none');
                }
            });

            if ($('#status-dropdown').val() === '1' || $('#status-dropdown').val() === '2') {
                $('#extra-date-field').removeClass('d-none');
            } else {
                $('#extra-date-field').addClass('d-none');
            }



        });
    });
</script>
@endsection