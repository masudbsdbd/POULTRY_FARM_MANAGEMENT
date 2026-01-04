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
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    <form
                        action="{{ isset($supplierReturnData) ? route('supplier-return.store', $supplierReturnData->id) : route('supplier-return.store') }}"
                        method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1 fw-bold text-muted">Select Supplier</p>
                                <select id="select-suppliers" class="form-control" data-toggle="select2"
                                    data-width="100%" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $item)
                                    <option value="{{ $item->id }}" @selected(isset($supplierReturnData) ? $supplierReturnData->supplier_id == $item->id : false)>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->

                            <div class="col-md-4">
                                <p class="mb-1 fw-bold text-muted">Select Batch</p>

                                @if (isset($supplierReturnData))
                                <select id="select-batch" name="batch_id" class="form-control" data-toggle="select2"
                                    data-width="100%" required>
                                    @foreach ($purchaseBatches as $item)
                                    <option value="{{ $item->id }}
                                    "
                                        data-purchase_id="{{ json_encode($item->purchase_items[0]['purchase_id']) }}"
                                        data-purchase_items='{{ json_encode($item->purchase_items) }}'
                                        @selected(isset($supplierReturnData) ? $supplierReturnData->purchase_batch_id == $item->id : false)>{{ $item->batch_code }}</option>
                                    @endforeach
                                </select>
                                @else
                                <select id="select-batch" name="batch_id" class="form-control" data-toggle="select2"
                                    data-width="100%" required>
                                    <option value="">Select Batch</option>
                                </select>
                                @endif
                            </div> <!-- end col -->

                            <div class="col-md-4">
                                <p class="mb-1 fw-bold text-muted">Select Product</p>
                                @if (isset($supplierReturnData))
                                <select id="select-products" class="form-control" data-toggle="select2"
                                    data-width="100%">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product['id'] }}" data-name="{{ $product['name'] }}"
                                        data-price="{{ $product['price'] }}"
                                        data-quantity="{{ $product['stock_qty'] }}">
                                        {{ $product['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                                @else
                                <select id="select-products" class="form-control" data-toggle="select2"
                                    data-width="100%">
                                    <option value="">No Products Available</option>
                                </select>
                                @endif

                            </div> <!-- end col -->
                        </div>

                        <!-- Dynamic Row for Selected Product -->
                        <div class="col-lg-12 my-4">
                            <h4 class="header-title">Purchase List</h4>
                            <div class="table-responsive">
                                <table id="myTable" class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Qty</th>
                                            <th>Purchase Price</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-rows">
                                        @if (isset($supplierReturnData))
                                        @foreach ($supplierReturnItemsData as $item)
                                        @php
                                        $findProduct = App\Models\Product::find($item->product_id);
                                        $purchaseBatches = App\Models\PurchaseBatch::where(
                                        'purchase_id',
                                        $item->purchase_id,
                                        )->first()->id;
                                        $stock = App\Models\Stock::where(
                                        'product_id',
                                        $item->product_id,
                                        )
                                        ->where('purchase_batch_id', $purchaseBatches)
                                        ->pluck('stock')
                                        ->first();
                                        @endphp
                                        <tr class="product-row">
                                            <td>
                                                <input type="hidden" name="products[]"
                                                    value="{{ $item->product_id }}">
                                                <input type="hidden" name="purchase_ids[]"
                                                    value="{{ $item->purchase_id }}">
                                                <input type="text" class="form-control"
                                                    value="{{ $findProduct->name }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control qty"
                                                    name="quantities[]" value="{{ $item->return_qty }}"
                                                    min="0" max="{{ $item->available_quantity }}"
                                                    data-stock="{{ $item->available_quantity }}">
                                                <p>Total Qty: <span
                                                        id="totol_stock_qty">{{ $stock + $item->return_qty }}</span></p>
                                            </td>

                                            <td>
                                                <input type="text" class="form-control price" name="prices[]"
                                                    value="{{ showAmount($item->avg_purchase_price, 2, false) }}"
                                                    readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total"
                                                    value="{{ $item->return_qty * $item->avg_purchase_price }}"
                                                    readonly>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-danger waves-effect waves-light rowDelete">
                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <!-- If no data, show placeholder -->
                                        <tr>
                                            <td colspan="5" class="text-center">No products added yet.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total Price and Quantity -->
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row"></th>
                                        <td>
                                            <input id="totalQuantity" type="hidden" name="totalQuantity"
                                                value="{{ isset($supplierReturnData) ? $supplierReturnData->total_qty : '' }}">
                                            <h4>Total Quantity: <span id="total-quantity">
                                                    {{ isset($supplierReturnData) ? $supplierReturnData->total_qty : 0 }}
                                                </span></h4>
                                        </td>
                                        <td>
                                            <input id="calculatedPrice" type="hidden" name="calculatedPrice"
                                                value="{{ isset($supplierReturnData) ? $supplierReturnData->total_return_price : '' }}">
                                            <h4>Total Price: <span id="total-price">
                                                    {{ isset($supplierReturnData) ? number_format($supplierReturnData->total_return_price) : 0 }}
                                                </span></h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>



                        <div class="col-md-6">
                            <div class="my-3"><label class="form-label"> Amount</label>
                                <div class="input-group">
                                    <!-- Select element taking 4 columns -->
                                    <div class="col-lg-3 pe-0">
                                        <select id="paymentMethod" class="form-select rounded-0"
                                            id="example-select" name="payment_method" required>
                                            <option value="1" @selected(isset($supplierReturnData) ? $supplierReturnData->account->payment_method == 1 : false)>Cash</option>
                                            <option value="2" @selected(isset($supplierReturnData) ? $supplierReturnData->account->payment_method == 2 : false)>Bank</option>
                                        </select>
                                    </div>

                                    <!-- Other elements taking 8 columns -->
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control rounded-0" placeholder="Enter Amount"
                                                name="amount" id="amountField" readonly
                                                value="{{ isset($supplierReturnData) ? number_format($supplierReturnData->total_return_price, 2, '.', '') : '' }}"
                                                required>
                                            <div class="input-group-text">Tk</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="bankInfo"
                            class="col-lg-12 {{ isset($supplierReturnData) ? ($supplierReturnData->account->payment_method == 2 ? '' : 'd-none') : 'd-none' }}">
                            <h4>Bank Information</h4>
                            <div class="row my-3">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="example-Account" class="form-label">Select Bank</label>
                                        <select id="bank_id" class="form-select rounded-0" name="bank_id"
                                            {{ isset($supplierReturnData) ? ($supplierReturnData->account->payment_method == 2 ? 'required' : '') : '' }}>
                                            <option value="">Select Bank</option>
                                            @foreach ($banks as $item)
                                            <option value="{{ $item->id }}"
                                                data-balance="{{ $item->balance }}"
                                                @selected(isset($supplierReturnData) && $supplierReturnData->account->bankTransaction ? $item->id == $supplierReturnData->account->bankTransaction->bank_id : false)>
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
                                            value="{{ isset($supplierReturnData) && $supplierReturnData->account->bankTransaction ? $supplierReturnData->account->bankTransaction->check_no : '' }}"
                                            placeholder="Check No">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="example-withdrawer" class="form-label">Depositor Name</label>
                                        <input id="depositor_name" type="text" id="example-withdrawer"
                                            class="form-control" name="depositor_name"
                                            placeholder="Depositor Name"
                                            value="{{ isset($supplierReturnData) && $supplierReturnData->account->bankTransaction ? $supplierReturnData->account->bankTransaction->depositor_name : '' }}"
                                            {{ isset($supplierReturnData) ? ($supplierReturnData->account->payment_method == 2 ? 'required' : '') : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit"
                                class="btn btn-primary waves-effect waves-light">{{ isset($supplierReturnData) ? 'Update Supplier Return' : 'Add New Supplier Return' }}</button>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
</div> <!-- container -->
@endsection

@section('script')
@vite(['resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])

<script>
    document.addEventListener('DOMContentLoaded', function() {

        let totalQuantity = 0;
        let totalPrice = 0;

        $('#select-suppliers').on('change', function() {
            const supplierId = $(this).val();

            $('#product-rows').empty();
            $('#select-batch').empty().append('<option value="">Select Batch</option>');
            $('#select-products').empty().append('<option value="">Select Product</option>');
            updateTotal();

            if (supplierId) {
                $.ajax({
                    url: "{{ route('supplier-return.supplier.ajax', ':id') }}".replace(':id',
                        supplierId),
                    method: "GET",
                    success: function(response) {
                        let options =
                            '<option value="">Select Batch</option>';

                        response.forEach(option => {
                            if (option.purchase_items && option.purchase_items
                                .length > 0) {
                                const purchaseItems = JSON.stringify(option
                                    .purchase_items);
                                options += ` 
                                            <option 
                                                value="${option.batch_id}" 
                                                data-purchase_id="${option.purchase_items[0].purchase_id}" 
                                                data-purchase_items='${purchaseItems}'
                                            >
                                                ${option.batch_code} -- ${option.date}
                                            </option>
                                        `;
                            }
                        });

                        $('#select-batch').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching batch details:", error);
                    }
                });
            }
        });


        $('#select-batch').on('change', function() {
            $('#product-rows').empty();
            updateTotal(); // Reset totals
            const selectedOption = $('#select-batch').find('option:selected');
            const purchaseItems = selectedOption.data('purchase_items');
            // console.log(purchaseItems);

            $('#select-products').empty().append('<option value="">Select Product</option>');

            if (purchaseItems && purchaseItems.length > 0) {
                let productOptions = '';
                purchaseItems.forEach(item => {
                    productOptions += `
                        <option 
                            value="${item.product_id}" 
                            data-quantity="${item.quantity}" 
                            data-price="${item.price}"
                        >
                            ${item.product_name}
                        </option>
                    `;
                });

                $('#select-products').append(productOptions);
            }
        });

        $('#select-products').on('change', function() {
            const productId = $(this).val();
            const productName = $('#select-products option:selected').text();
            const quantity = $('#select-products option:selected').data('quantity');
            const price = parseFloat($('#select-products option:selected').data('price')).toFixed(2);

            const purchaseId = $('#select-batch option:selected').data('purchase_id');
            $('#select-products').val('');
            console.log(purchaseId);

            let productExists = false;
            $('.product-row input[name="products[]"]').each(function() {
                if ($(this).val() === productId) {
                    productExists = true;
                    return false;
                }
            });

            if (productExists) {
                alert('This product is already added to the list.');
                return;
            }

            if (productId) {
                const row = `
                        <tr class="product-row">
                            <td>
                                <input type="hidden" name="products[]" value="${productId}">
                                <input type="hidden" name="purchase_ids[]" value="${purchaseId}">
                                <input type="text" class="form-control" value="${ productName.trim() }" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control qty" name="quantities[]" value="0" min="0" max="${quantity}">
                                <span>Total Qty: ${quantity}</span>
                            </td>
                            <td>
                                <input type="text" class="form-control price" name="prices[]" value="${price}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control total" value="${price}" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger waves-effect waves-light rowDelete">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </td>
                        </tr>
                    `;

                $('#product-rows').append(row);
                updateTotal();
            }
        });

        // Handle quantity change (validate and enforce max limit)
        $(document).on('input', '.qty', function() {
            const maxQuantity = parseInt($(this).attr('max'));
            let enteredQuantity = parseInt($(this).val());
            let totalStockQty = parseInt($("#totol_stock_qty").text());

            // console.log(enteredQuantity);

            if (isNaN(enteredQuantity) || enteredQuantity < 0) {
                $(this).val(0);
                enteredQuantity = 0;
            }
            if (enteredQuantity > totalStockQty) {
                alert(
                    `You cannot select more than the available stock of ${totalStockQty} for this product.`
                );
                $(this).val(totalStockQty);
                enteredQuantity = totalStockQty;
            } else if (enteredQuantity > maxQuantity) {
                alert(
                    `You cannot select more than the maximum allowed quantity of ${maxQuantity} for this product.`
                );
                $(this).val(maxQuantity);
                enteredQuantity = maxQuantity;
            }

            if (maxQuantity === 0) {
                $(this).val(0);
                enteredQuantity = 0;
            }

            updateTotal();
        });


        // Remove row
        $(document).on('click', '.rowDelete', function() {
            $(this).closest('tr').remove();
            updateTotal();
        });

        // Update total quantity and price
        function updateTotal() {
            totalQuantity = 0;
            totalPrice = 0;

            $('.product-row').each(function() {
                const qty = parseInt($(this).find('.qty').val());
                const price = parseFloat($(this).find('.price').val());
                const total = qty * price;

                $(this).find('.total').val(total.toFixed(2));
                totalQuantity += qty;
                totalPrice += total;
            });

            $('#total-quantity').text(totalQuantity);
            $('#total-price').text(totalPrice.toFixed(2));

            $('#totalQuantity').val(totalQuantity);
            $('#calculatedPrice').val(totalPrice.toFixed(2));


            $('#amountField').val(totalPrice.toFixed(2));


        }


        let getBankOnEditPage = $('#bank_id').val();

        $('#paymentMethod').on('change', function() {
            if ($(this).val() == 2) {
                $('#bankInfo').removeClass('d-none');
                $('#bank_id').prop('required', true);
                $('#depositor_name').prop('required', true);
            } else {
                $('#bankInfo').addClass('d-none');
                $('#bank_id').prop('required', false);
                $('#depositor_name').prop('required', false);
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




    });
</script>
@endsection