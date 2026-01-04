@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => $pageTitle])

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <form action="{{ isset($sell) ? route('sell.store', $sell->id) : route('sell.store') }}"
                            method="POST">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label class="form-label">Sell Date</label>
                                    <input type="text" id="datetime-datepicker" class="form-control"
                                        placeholder="Basic datepicker"
                                        value="{{ isset($purchase) ? $purchase->purchase_date : now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}"
                                        name="sell_date" required>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-muted">Select Customer</p>
                                    <select id="select-customers" class="form-control" data-toggle="select2"
                                        data-width="100%" name="customer_id" required>
                                        <option value="">Select</option>
                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}" data-item="{{ $item }}"
                                                @selected(isset($sell) ? $sell->customer_id == $item->id : false)>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p id="customerAdvanceParagraph"
                                        class="mt-1 {{ isset($sell) ? ($sell->customer->advance > 0 ? '' : 'd-none') : 'd-none' }}">
                                        Current Advance Balance : <span
                                            id="customerAdvance">{{ isset($sell) ? showAmount($sell->customer->advance) . ' Tk' : 0 }}</span>
                                    </p>
                                </div> <!-- end col -->

                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-muted">Select Product</p>
                                    <select id="select-products" class="form-control" data-toggle="select2"
                                        data-width="100%">
                                        <option value="">Select</option>
                                        @foreach ($products as $item)
                                            @php
                                                $item->unit->name;
                                            @endphp
                                            <option value="{{ $item->id }}" data-item="{{ $item }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-2">Total Product Stock is : <span id="injectProductStock">0</span></p>

                                </div> <!-- end col -->

                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-muted">Select Batch</p>
                                    <select id="select-batch" class="form-select">
                                        <option value="" @selected(isset($sell) ? $sell->customer_id == $item->id : false)>Select Batch</option>
                                    </select>
                                    <p class="mt-2">Total Product in this batch : <span
                                            id="injectProductsInBatch">0</span></p>
                                </div> <!-- end col -->

                                <div class="col-lg-12 my-4">
                                    <h4 class="header-title">Sell Item List</h4>

                                    <div class="table-responsive">
                                        <table id="myTable" class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Batch</th>
                                                    <th>Qty</th>
                                                    <th>Unit Sell Price</th>
                                                    <th>Avg Purchase Price</th>
                                                    <th>Total</th>
                                                    <th>Discount (%)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if (isset($sellRecords) && !$sellRecords->isEmpty())
                                                    @foreach ($sellRecords as $item)
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
                                                                <input type="hidden" class="form-control"
                                                                    placeholder="Batch Code" name="batch_id[]"
                                                                    value="{{ $item->purchase_batch_id }}"
                                                                    autocomplete="off" readonly>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Batch Code"
                                                                    value="{{ $item->purchaseBatch->batch_code }}"
                                                                    autocomplete="off" readonly>
                                                            </td>
                                                            <td>
                                                                <input class="get_stock" type="hidden"
                                                                    value="{{ $item->purchaseBatch->stock->stock + $item->sell_qty }}">
                                                                <input type="number" class="form-control qtyCalculation"
                                                                    placeholder="Qty" name="qty[]"
                                                                    value="{{ $item->sell_qty }}" autocomplete="off"
                                                                    required>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-merge">
                                                                    <input type="number" class="form-control productPrice"
                                                                        placeholder="Unit Sell Price" name="sell_price[]"
                                                                        value="{{ number_format($item->sell_price, 2, '.', '') }}">
                                                                    <div class="input-group-text">Tk /
                                                                        {{ $findProduct->unit->name }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-merge">
                                                                    <input type="text"
                                                                        class="form-control avgPurchasePrice"
                                                                        placeholder="Avg Purchase Price"
                                                                        name="avg_purchase_price[]"
                                                                        value="{{ number_format($item->avg_purchase_price, 2, '.', '') }}"
                                                                        readonly>
                                                                    <div class="input-group-text">Tk /
                                                                        {{ $findProduct->unit->name }} </span>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <input type="hidden" class="form-control productTotal"
                                                                    value="{{ $item->sell_qty * $item->sell_price }}">
                                                                <input type="text" class="form-control totalPrice"
                                                                    name="priceTotal[]"
                                                                    value="{{ number_format($item->total_amount, 2, '.', '') }}"
                                                                    placeholder="Total" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    class="form-control calculateDiscount"
                                                                    value="{{ number_format($item->discount, 2, '.', '') }}"
                                                                    name="discounts[]" placeholder="Discount (%)">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect waves-light rowDelete"><i
                                                                        class="mdi mdi-trash-can-outline"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr id="empty-row">
                                                        <td colspan="8" class="text-center">
                                                            <h4 class="my-3">Insert Products</h4>
                                                        </td>
                                                    </tr>
                                                @endif
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
                                                            value="{{ isset($sell) ? $sell->total_qty : '' }}">
                                                        <h4>Total Quantity: <span
                                                                id="total-quantity">{{ isset($sell) ? $sell->total_qty : 0 }}</span>
                                                        </h4>
                                                    </td>
                                                    <td>
                                                        <input id="calculatedPrice" type="hidden" name="calculatedPrice"
                                                            value="{{ isset($sell) ? $sell->total_price : '' }}">
                                                        <h4>Total Price: <span
                                                                id="total-price">{{ isset($sell) ? showAmount($sell->total_price) : 0 }}</span>
                                                        </h4>
                                                    </td>
                                                    <td>
                                                        <input id="discountField" step="0.01" min="0"
                                                            type="number" class="form-control" placeholder="Less Amount"
                                                            value="{{ isset($sell) ? showAmount($sell->discount) : '' }}"
                                                            name="discount">
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if (isset($sell))
                                    <div class="col-lg-6">
                                        <h4 class="mt-3">Total Amount</h4>
                                        <div class="my-3"><label class="form-label">Payment Amount</label>
                                            <div class="input-group">
                                                <input id="totalSellAmount" type="number" step="0.01" min="0"
                                                    class="form-control rounded-0" placeholder="Enter Amount"
                                                    value="{{ isset($sell) ? showAmount($sell->total_price, 2, false) : '' }}"
                                                    readonly>
                                                <div class="input-group-text">Tk</div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <h4 class="mt-3">Payment Amount and Type</h4>
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Receipt Amount</label>
                                                <div class="input-group">
                                                    <!-- Select element taking 4 columns -->
                                                    <div class="col-lg-3 pe-0">
                                                        <select id="paymentMethod" class="form-select rounded-0"
                                                            id="example-select" name="payment_method">
                                                            <option value="1">Cash</option>
                                                            <option value="2">Bank</option>
                                                        </select>
                                                    </div>

                                                    <!-- Other elements taking 8 columns -->
                                                    <div class="col-lg-9">
                                                        <div class="input-group">
                                                            <input type="number" step="0.01" min="0"
                                                                class="form-control rounded-0" placeholder="Enter Amount"
                                                                name="balance"
                                                                value="{{ isset($sell) ? number_format($sell->payment_received, 2, '.', '') : '' }}">
                                                            <div class="input-group-text">Tk</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="bankInfo"
                                        class="col-lg-12 d-none">
                                        <h4>Bank Information</h4>
                                        <div class="row my-3">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="example-Account" class="form-label">Select Bank</label>
                                                    <select id="bank_id" class="form-select rounded-0" name="bank_id">
                                                        <option value="">Select Bank</option>
                                                        @foreach ($banks as $item)
                                                            <option value="{{ $item->id }}">
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
                                                        name="check_no" placeholder="Check No">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="example-depositor" class="form-label">Depositor
                                                        Name</label>
                                                    <input id="depositor_name" type="text" id="example-depositor"
                                                        class="form-control" name="depositor_name"
                                                        placeholder="Depositor Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="text-end mt-3">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($sellRecords) ? 'Update Sell' : 'Add New Sell' }}</button>
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
            let supplierValue = '';
            let productData;
            let count = 1;
            let originalTotalPrice = 0;
            let selectedProduct;

            $('#select-batch').on('change', function() {
                const productValue = $('#select-products').val();
                const batchValue = $('#select-batch').val();


                if (productValue && batchValue) {
                    if (selectedProduct) {
                        const isDuplicate = checkDuplicateProduct(selectedProduct.id, batchValue);

                        if (isDuplicate) {
                            alert('This product with the selected batch is already added to the table.');
                            return;
                        }

                        const batch_code = $(this).find('option:selected').data('batch_code');
                        const avg_purchase_price = $(this).find('option:selected').data(
                            'avg_purchase_price');
                        const stock = $(this).find('option:selected').data('stock');
                        const items = $(this).find('option:selected').data('items');

                        if (stock) {
                            $('#injectProductsInBatch').text(stock);
                        } else {
                            $('#injectProductsInBatch').text(0);
                        }

                        if (stock == 0) {
                            alert('Sorry, the stock is currently empty.');
                            return;
                        }

                        let designArr = [batch_code, $(this).val(), parseFloat(
                            avg_purchase_price).toFixed(2), stock];

                        items.forEach(option => {
                            if (selectedProduct.id == option.product_id) {
                                designArr.push(parseFloat(option.price).toFixed(2));
                            }

                        });

                        insertTableRow(selectedProduct, designArr);
                    }
                }
            });

            function checkDuplicateProduct(productId, batchId) {
                let isDuplicate = false;

                $('#myTable tbody .tableRow').each(function() {
                    const existingProductId = $(this).find('input[name="products[]"]').val();
                    const existingBatchId = $(this).find('input[name="batch_id[]"]').val();

                    if (existingProductId == productId && existingBatchId == batchId) {
                        isDuplicate = true;
                        return false;
                    }
                });

                return isDuplicate;
            }

            function insertTableRow(productData, arr) {
                $('#empty-row').addClass('d-none');

                $("#myTable tbody").append(
                    `
                    <tr class="tableRow">
                        <td>
                            <input type="hidden" name="products[]" value="${productData.id}">
                            <input type="text" class="form-control" placeholder="Product" value="${productData.name}" readonly required>
                        </td>
                        <td>
                            <input type="hidden" name="batch_id[]" value="${arr[1]}">
                            <input type="text" class="form-control" placeholder="Batch Code" value="${arr[0]}" autocomplete="off" readonly>
                        </td>
                        <td>
                            <input class="get_stock" type="hidden" value="${arr[3]}">
                            <input type="number" class="form-control qtyCalculation" placeholder="Qty" name="qty[]" autocomplete="off" required>
                        </td>
                        <td>
                            <div class="input-group input-group-merge">
                                <input type="number" class="form-control productPrice"
                                    placeholder="Unit Sell Price" name="sell_price[]" value="${arr[4]}" >
                                <div class="input-group-text">Tk / ${productData.unit.name}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control avgPurchasePrice"
                                    placeholder="Avg Purchase Price" name="avg_purchase_price[]" value="${arr[2]}" readonly>
                                <div class="input-group-text">Tk / ${productData.unit.name}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="hidden" class="form-control productTotal" value="">
                            <input type="text" class="form-control totalPrice" name="priceTotal[]" placeholder="Total" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control calculateDiscount" name="discounts[]" placeholder="Discount (%)">
                        </td>
                        <td>
                            <button type="button"
                                class="btn btn-danger waves-effect waves-light rowDelete"><i
                                    class="mdi mdi-trash-can-outline"></i></button>
                        </td>
                    </tr>
            `
                )
                count++;
            }

            $('#select-customers').on('change', function() {
                let data = $(this).find(':selected').data();
                // console.log(data);

                if (data.item.advance > 0) {
                    $('#customerAdvanceParagraph').removeClass('d-none');
                    $('#customerAdvance').text(parseFloat(data.item.advance).toFixed(2) + ' Tk');
                } else {
                    $('#customerAdvanceParagraph').addClass('d-none');
                    $('#customerAdvance').text(0 + ' Tk');
                }
            });

            $('#select-products').on('change', function() {
                productData = $(this).find('option:selected').data('item');
                if (productData.stock_item) {
                    $('#injectProductStock').text(productData.stock_item.stock);
                } else {
                    $('#injectProductStock').text(0);
                }

                selectedProduct = productData;
                if (productData) {
                    const url = `{{ route('sell.batch.ajax', ':id') }}`.replace(':id', productData
                        .id);

                    $.ajax({
                        url: url,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            let optionHtml = '';
                            $('#select-batch').empty();
                            $('#select-batch').append('<option value="">Select Batch</option>');

                            response.forEach(option => {
                                const $option = $(`
                                    <option value="${option.batch.id}" 
                                            data-stock="${option.stock}" 
                                            data-batch_code="${option.batch.batch_code}" 
                                            data-avg_purchase_price="${option.avg_purchase_price}">
                                        ${option.batch.batch_code} Stock - ${option.stock}
                                    </option>
                                `);

                                $option.data('items', option.batch.purchase.items);
                                $('#select-batch').append($option);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            });


            $(document).on('click', '.rowDelete', function() {
                count--;
                if (count == 1) {
                    $('#empty-row').removeClass('d-none');
                }
                $(this).closest('.tableRow').remove();
                calculateTotal();
            });

            $("#myTable tbody").on("input", ".qtyCalculation", function() {
                let existStock = parseInt($(this).closest('tr').find('.get_stock').val(), 10);

                let productQty = parseInt($(this).val(), 10);
                if (productQty > existStock) {
                    alert('Stock Limit Exceeded!');
                    $(this).val(existStock);
                    productQty = existStock;
                }

                let unitPrice = $(this).closest('tr').find('.productPrice').val();
                let sum = productQty * unitPrice;
                $(this).closest('tr').find('.productTotal').val(sum);
                $(this).closest('tr').find('.totalPrice').val(sum);
                $(this).closest('tr').find('.calculateDiscount').val('');
                calculateTotal();
            });

            $("#myTable tbody").on("input", ".productPrice", function() {
                let getQty = $(this).closest('tr').find('.qtyCalculation').val();
                let currentPrice = $(this).closest('tr').find('.avgPurchasePrice').val();
                let updatedPrice = $(this).val();

                if (getQty === "") {
                    alert('Please insert Qty first!');
                    $(this).val(currentPrice);
                    return false;
                }

                let totalPrice = getQty * updatedPrice;
                $(this).closest('tr').find('.productTotal').val(totalPrice);
                $(this).closest('tr').find('.totalPrice').val(totalPrice);
                $(this).closest('tr').find('.calculateDiscount').val('');
                calculateTotal();
            });

            $("#myTable tbody").on("input", ".calculateDiscount", function() {
                let closestQty = $(this).closest('tr').find('.qtyCalculation').val();
                let discountParcentage = $(this).val();

                if (closestQty === "") {
                    alert('Please insert Qty first!');
                    $(this).val("");
                    return false;
                }

                let totalPrice = $(this).closest('tr').find('.productTotal').val();
                // console.log(totalPrice);
                let discountAmount = (discountParcentage * totalPrice) / 100;
                let discountPrice = totalPrice - discountAmount;
                $(this).closest('tr').find('.totalPrice').val(discountPrice);
                calculateTotal();
            });

            function calculateTotal() {
                let totalQty = 0;
                let totalPrice = 0;

                $("#myTable tbody .tableRow").each(function() {
                    let qty = parseFloat($(this).find(".qtyCalculation").val()) || 0;
                    let price = parseFloat($(this).find(".totalPrice").val()) || 0;

                    totalQty += qty;
                    totalPrice += price;
                });

                $("#totalQuantity").val(totalQty);
                $("#calculatedPrice").val(totalPrice);

                $("#total-quantity").text(totalQty.toLocaleString());
                $("#total-price").text(totalPrice.toLocaleString());

                return totalPrice;
            }

            $('#discountField').on('input', function() {
                const discount = parseFloat($('#discountField').val()) || 0;
                const subtotalAmount = calculateTotal();
                const subtractedAmount = subtotalAmount - discount;

                $("#calculatedPrice").val(subtractedAmount);
                $("#total-price").text(subtractedAmount.toLocaleString());
            });

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
        });
    </script>
@endsection
