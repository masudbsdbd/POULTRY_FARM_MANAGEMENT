@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Purchase'])

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                            <h4>{{ isset($quotationsInfo) ? 'Quotation: ' . $quotationsInfo->title : '' }}</h4>
                        </div>
                        <form
                            action="{{ route('invoice.store') }}"
                            {{-- action="{{ isset($purchase) ? route('purchase.store', $purchase->id) : route('purchase.store') }}" --}}
                            method="POST">
                            @csrf
                            <input id="quotation_id" value="{{ $quotationsInfo->id }}" type="hidden" name="quotation_id">
                            <div class="row">
                                <div class="mb-3 col-lg-4">
                                    <label class="form-label">Invoice Date</label>
                                    <input type="text" id="datetime-datepicker" class="form-control"
                                        placeholder="Invoice Date"
                                        value="{{ now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}"
                                        {{-- value="{{ isset($purchase) ? $purchase->purchase_date : now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}" --}}
                                        name="invoice_date" required>
                                </div>

                                <div class="col-md-4">
                                   <div class="mb-3">
                                    <label for="invoice_number" class="form-label">Invoice Number</label>
                                    <input type="text" id="invoice_number" class="form-control" placeholder="invoice number"
                                        name="invoice_number" required
                                        value="{{ $invoiceNumber }}" readonly>
                                </div>
                                </div> <!-- end col -->
                                {{-- @dump($quotationsInfo); --}}
                                <div class="col-md-4">
                                    <p class="mb-1 fw-bold text-muted">Select Product</p>
                                    <select id="select-products" class="form-control" data-toggle="select2"
                                        data-width="100%">
                                        <option value="">Select</option>
                                        @foreach ($quotationItems as $item)
                                            @php
                                                $item->product->name;
                                            @endphp
                                            <option value="{{ $item->id }}" data-item="{{ $item }}">
                                                {{ $item->product->name; }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> <!-- end col -->

                                <div class="col-lg-12">
                                    <h4 class="header-title">Purchase List</h4>

                                    <div class="table-responsive">
                                        <table id="myTable" class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Qty</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if (isset($purchase_items) && !$purchase_items->isEmpty())
                                                    @foreach ($purchase_items as $item)
                                                        @php
                                                            $findProduct = App\Models\Product::find($item->product_id);
                                                        @endphp
                                                        <tr class="tableRow">
                                                            <td>
                                                                <input type="hidden" name="products[]"
                                                                    value="{{ $findProduct->id }}">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Product" value="{{ $findProduct->name }}"
                                                                    readonly required>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control qtyCalculation"
                                                                    placeholder="Qty" data-available-qty="{{ parseInt($item->qty) - parseInt($item->used_qty) }}" name="qty[]"
                                                                    value="{{ $item->qty }}" autocomplete="off"
                                                                    required>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-merge">
                                                                    <input type="text"
                                                                        class="form-control productPrice unitPriceCalculation"
                                                                        placeholder="Unit Price" name="unitPrice[]"
                                                                        value="{{ showAmount($item->price, 2, false) }}"
                                                                        autocomplete="off" required>
                                                                    <div class="input-group-text">Tk /
                                                                        {{ $findProduct->unit->name }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control totalPrice"
                                                                    name="priceTotal[]"
                                                                    value="{{ showAmount($item->total_amount, 2, false) }}"
                                                                    placeholder="Total" readonly>
                                                            </td>
                                                            {{-- @dd($item) --}}
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect waves-light rowDelete"><i
                                                                        class="mdi mdi-trash-can-outline"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <tr id="empty-row"
                                                    class="{{ isset($purchase_items) && !$purchase_items->isEmpty() ? 'd-none' : '' }}">
                                                    <td colspan="6" class="text-center">
                                                        <h4 class="my-3">Insert Products</h4>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Row outside the table -->
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row"></th>
                                                    <td>
                                                        <input id="totalQuantity" type="hidden" name="totalQuantity"
                                                            value="{{ isset($purchase) ? $purchase->total_qty : '' }}">
                                                        <h4>Total Quantity: <span
                                                                id="total-quantity">{{ isset($purchase) ? $purchase->total_qty : 0 }}</span>
                                                        </h4>
                                                    </td>
                                                    <td>
                                                        <input id="calculatedPrice" type="hidden" name="calculatedPrice"
                                                            value="{{ isset($purchase) ? $purchase->total_price : '' }}">
                                                        <h4>Total Price: <span
                                                                id="total-price">{{ isset($purchase) ? number_format($purchase->total_price) : 0 }}</span>
                                                        </h4>
                                                    </td>
                                                    
                                                    <td>
                                                        <input id="commissionField" step="0.01" min="0"
                                                            max="100" type="number" class="form-control"
                                                            placeholder="Enter Commission (%)"
                                                            value="{{ isset($purchase) ? number_format($purchase->commission, 2, '.', '') : '' }}"
                                                            name="commission">
                                                    </td>
                                                    <td>
                                                        <input id="discountField" step="0.01" min="0"
                                                            type="number" class="form-control"
                                                            placeholder="Enter Discount"
                                                            value="{{ isset($purchase) ? number_format($purchase->discount, 2, '.', '') : '' }}"
                                                            name="discount">
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if (isset($purchase))
                                    <div class="col-lg-6">
                                        <h4 class="mt-3">Total Amount</h4>
                                        <div class="my-3"><label class="form-label">Payment Amount</label>
                                            <div class="input-group">
                                                <input id="totalAmount" type="number" step="0.01" min="0"
                                                    class="form-control rounded-0" placeholder="Enter Amount"
                                                    value="{{ isset($purchase) ? showAmount($purchase->total_price, 2, false) : '' }}"
                                                    readonly>
                                                <div class="input-group-text">Tk</div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-6">
                                        <h4>Payment Method and Type</h4>
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
                                                    <label for="example-withdrawer" class="form-label">Withdrawer
                                                        Name</label>
                                                    <input id="withdrawer_name" type="text" id="example-withdrawer"
                                                        class="form-control" name="withdrawer_name"
                                                        placeholder="Withdrawer Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="text-end mt-3">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($purchase) ? 'Update Purchase' : 'Add Invoice' }}</button>
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

        let warehouses = @json($warehouses);
        // console.log('sadi', warehouses);
        
        document.addEventListener('DOMContentLoaded', function() {
            let productData;
            let count = 1;
            let originalTotalPrice = 0;
            let getBankOnEditPage = $('#bank_id').val();

            function addTableRow(productData) {
                $('#empty-row').addClass('d-none');

                /* $("#myTable tbody").append(
                    `
                        <tr class="tableRow">
                            <td>
                                <input type="hidden" name="products[]" value="${productData.id}">
                                <input type="text" class="form-control" placeholder="Product" value="${productData.name}" readonly required>
                            </td>
                            <td>
                                <input type="number" class="form-control qtyCalculation" placeholder="Qty" name="qty[]" autocomplete="off" required>
                            </td>
                            <td>
                                <div class="input-group input-group-merge">
                                    <input type="number" class="form-control productPrice unitPriceCalculation"
                                        placeholder="Unit Price" name="unitPrice[]" value="${parseFloat(productData.price).toFixed(2)}"  autocomplete="off" required>
                                    <div class="input-group-text">Tk / ${productData.unit.name}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control totalPrice" name="priceTotal[]" placeholder="Total" readonly>
                            </td>
                            <td>
                                <button type="button"
                                    class="btn btn-danger waves-effect waves-light rowDelete"><i
                                        class="mdi mdi-trash-can-outline"></i></button>
                            </td>
                        </tr>
                `
                ) */

            let row = $(`
                <tr class="tableRow">
                    <td>
                        <input type="hidden" name="products[]" value="${productData.id}">
                        <input type="text" class="form-control" placeholder="Product" value="${productData.product.name}" readonly required>
                    </td>
                    <td>
                        <input type="number" class="form-control qtyCalculation" placeholder="Qty" value="${parseInt(productData.qty) - parseInt(productData.used_qty)}" data-available-qty="${parseInt(productData.qty) - parseInt(productData.used_qty)}" name="qty[]" autocomplete="off" required>
                    </td>
                    <td>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control productPrice unitPriceCalculation" readOnly
                                placeholder="Unit Price" name="unitPrice[]" value="${parseFloat(productData.unit_price).toFixed(2)}" autocomplete="off" required>
                            <div class="input-group-text">Tk / ${productData.product.name}</div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control totalPrice" name="priceTotal[]" value="${parseFloat(productData.total).toFixed(2)}" placeholder="Total" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger waves-effect waves-light rowDelete">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </button>
                    </td>
                </tr>
            `);

            $("#myTable tbody").append(row);

            // এখন select box-এ option গুলো বসাই
            let select = row.find('.warehouse');

            // warehouses.forEach(function(warehouse) {
            //     select.append(`<option value="${warehouse.id}">${warehouse.warehouse_name}</option>`);
            // });
                
                count++;
            }


            $(document).on("input", "[name='qty[]']", function () {
    let qty = parseInt($(this).val(), 10) || 0;
    let availableQty = parseInt($(this).data("available-qty"), 10) || 0;

    if (qty > availableQty) {
        alert("You can't add more than " + availableQty + " qty.");
        $(this).val(availableQty);
    }
});


            $('#select-suppliers').on('change', function() {
                let data = $(this).find(':selected').data();

                if (data.item.advance > 0) {
                    $('#supplierAdvanceParagraph').removeClass('d-none');
                    $('#supplierAdvance').text(parseFloat(data.item.advance).toFixed(2) + ' Tk');
                } else {
                    $('#supplierAdvanceParagraph').addClass('d-none');
                    $('#supplierAdvance').text(0 + ' Tk');
                }
            });

            $('#select-products').on('change', function() {
                productData = $(this).find('option:selected').data('item');
                console.log('sadi', productData);

                const isDuplicate = checkDuplicateProduct(productData.id);
                if (isDuplicate) {
                    alert('This product is already added to the table.');
                    return;
                }

                addTableRow(productData);
            });

            function checkDuplicateProduct(productId) {
                let isDuplicate = false;

                $('#myTable tbody .tableRow').each(function() {
                    const existingProductId = $(this).find('input[name="products[]"]').val();

                    if (existingProductId == productId) {
                        isDuplicate = true;
                        return false;
                    }
                });

                return isDuplicate;
            }

            $(document).on('click', '.rowDelete', function() {
                const rowCount = $("#myTable tbody tr").length - 1;
                console.log(rowCount);
                if (rowCount == 1) {
                    $('#empty-row').removeClass('d-none');
                }
                $(this).closest('.tableRow').remove();
                calculateTotal();
            });

            $("#myTable tbody").on("input", ".qtyCalculation", function() {
                let productQty = $(this).val();
                let unitPrice = $(this).closest('tr').find('.productPrice').val();
                let sum = productQty * unitPrice;
                $(this).closest('tr').find('.totalPrice').val(sum);
                calculateTotal();
            });

            $("#myTable tbody").on("input", ".unitPriceCalculation", function() {
                let productUnitPrice = $(this).val();
                let productQtyCount = $(this).closest('tr').find('.qtyCalculation').val();
                let sum = productUnitPrice * productQtyCount;
                $(this).closest('tr').find('.totalPrice').val(sum);
                calculateTotal();
            });

            function calculateTotal(commission = 0, discount = 0) {
                let totalQty = 0;
                let totalPrice = 0;

                $("#myTable tbody .tableRow").each(function() {
                    let qty = parseFloat($(this).find(".qtyCalculation").val()) || 0;
                    let price = parseFloat($(this).find(".totalPrice").val()) || 0;

                    totalQty += qty;
                    totalPrice += price;
                });

                originalTotalPrice = totalPrice;
                totalPrice = originalTotalPrice - (commission + discount);

                $("#totalQuantity").val(totalQty);
                $("#calculatedPrice").val(totalPrice);
                $("#totalAmount").val(totalPrice);

                $("#total-quantity").text(totalQty.toLocaleString());
                $("#total-price").text(totalPrice.toLocaleString());
            }

            $('#commissionField, #discountField').on('input', function() {
                const commissionPercentage = parseFloat($('#commissionField').val()) || 0;
                const commission = (commissionPercentage * originalTotalPrice) / 100;
                const discount = parseFloat($('#discountField').val()) || 0;
                calculateTotal(commission, discount);
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

            $('#bank_id').on('change', function() {
                let data = $(this).find(':selected').data();
                let total_price = $('input[name="balance"]').val();

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

        });
    </script>
@endsection
