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
                    <form action="{{ isset($customerRetun) ? route('customer-return.store', $customerRetun->id) : route('customer-return.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1 fw-bold text-muted">Select Customer</p>
                                <select id="select-customers" class="form-control" data-toggle="select2" data-width="100%" name="" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $item)
                                    <option value="{{ $item->id }}" @selected(isset($customerRetun) ? $customerRetun->customer_id == $item->id : false)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->
                            <div class="col-md-4">
                                <p class="mb-1 fw-bold text-muted">Select Product</p>
                                @if(isset($customerRetun) && isset($products) && count($products) > 0)
                                <select id="select-products" class="form-control" data-toggle="select2" data-width="100%" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product['product_id'] }}"
                                        data-batch_details="{{ json_encode($product['batch_details']) }}"
                                        data-sell_id="{{ $item['batchDetails'][0]['sell_id'] ?? 'N/A' }}"
                                        @selected(isset($customerRetun) ? $customerRetun->base_product_id == $product['product_id'] : false)
                                        >
                                        {{ $product['product_name'] }}
                                    </option>
                                    @endforeach
                                </select>
                                @else
                                <select id="select-products" class="form-control" data-toggle="select2" data-width="100%" required>
                                    <option value="">No Products Available</option>
                                </select>
                                @endif
                            </div>



                            <div class="col-md-4">
                                <p class="mb-1 fw-bold text-muted">Select Batch</p>

                                <input type="hidden" id="batches_id" name="batches_id[]" value="{{ isset($customerRetun) ? json_encode($batches) : '[]' }}">
                                @if(isset($customerRetun))
                                <select id="select-batch" name="batch_id" class="form-control" data-toggle="select2" data-width="100%">
                                    <option value="">Select Batch</option>

                                    @foreach($batchData as $item)

                                    <option value="{{ $item['batch_id'] }}"
                                        data-sell_qty="{{ $item['sell_qty'] }}"
                                        data-avg_sell_price="{{ $item['batchDetails'][0]['avg_sell_price'] ?? 'N/A' }}"
                                        data-purchase_id="{{ $item['batchDetails'][0]['purchase_id'] ?? 'N/A' }}"
                                        data-sell_id="{{ $item['batchDetails'][0]['sell_id'] ?? 'N/A' }}"
                                        data-created_at="{{ $item['created_at'] }}">
                                        {{ $item['batch_name'] }} -- {{ $item['created_at'] }} -- Sell Qty: {{ $item['sell_qty'] }}
                                    </option>
                                    @endforeach
                                </select>

                                @else
                                <select id="select-batch" name="batch_id" class="form-control" data-toggle="select2" data-width="100%">
                                    <option value="">Select Batch</option>
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

                                            <th>Product</th>
                                            <th>Batch</th>
                                            <th>Return Qty</th>
                                            <th>Return Price</th>
                                            <th>Sold Price</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-rows">
                                        @if (isset($customerRetun))

                                        <input type="hidden" name="customer_id" value="{{ $customerRetun->customer_id }}">

                                        @endif

                                        @if (isset($customerRetun))
                                        @foreach ($customerReturnItems as $item)
                                        @php
                                        $findProduct = App\Models\Product::find($item->product_id);

                                        $findBatch= App\Models\PurchaseBatch::find($item->purchase_batch_id);

                                        $customerReturn = App\Models\CustomerReturn::where('id',$item->customer_return_id)->first();

                                        $sellRecord = App\Models\SellRecord::where('sell_id', $item->sell_id)
                                        ->where('purchase_batch_id', $item->purchase_batch_id)
                                        ->where('product_id', $item->product_id)
                                        ->first();


                                        $sellQty = $sellRecord ? $sellRecord->sell_qty : 0;
                                        $sellCreatedAt = $sellRecord ? $sellRecord->created_at : 0;


                                        $iterationCount = $loop->count;
                                        @endphp
                                        <tr class="product-row">
                                            <td>
                                                <input type="hidden" name="base_product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                                <input type="hidden" name="sell_id[]" value="{{ $item->sell_id }}">

                                                <input type="hidden" name="purchase_id[]" value="{{ $item->purchase_id }}">
                                                {{ $findProduct->name }}
                                            </td>
                                            <td>

                                                <input type="hidden" name="batch_id[]" value="{{ $item->purchase_batch_id }}">
                                                <input type="hidden" name="created_at[]" value="{{ $sellCreatedAt }}">


                                                {{ $findBatch->batch_code }} -- {{ $sellCreatedAt }} -- Sell Qty: {{ $sellQty }}
                                            <td>
                                                <input type="number" class="form-control qty" name="quantities[]"
                                                    value="{{ $item->return_qty }}" min="0" max="{{ $item->available_quantity }}"
                                                    data-stock="{{ $item->available_quantity }}">
                                                <p>Total Sell Qty: <span id="totol_stock_qty">{{$sellQty + $item->return_qty}}</span></p>
                                            </td>


                                            <td>
                                                <input type="text" class="form-control price" name="return_prices[]" value="{{ showAmount($item->retun_sell_price, 2, false) }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control price" name="avg_sell_prices[]" value="{{ showAmount($item->avg_sell_price, 2, false) }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total" value="{{  showAmount($item->retun_total_sell_price, 2, false) }}" readonly>
                                            </td>
                                            <td>
                                                <button type="button" value="{{$loop->iteration - 1}}" class="btn btn-danger waves-effect waves-light rowDelete">
                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <!-- If no data, show placeholder -->
                                        <tr>
                                            <td colspan="8" class="text-center">No products added yet.</td>
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
                                            <input id="totalQuantity" type="hidden" name="totalQuantity" value="{{ isset($customerRetun) ? $customerRetun->total_qty : '' }}">
                                            <h4>Total Quantity: <span id="total-quantity">
                                                    {{ isset($customerRetun) ? $customerRetun->total_qty : 0 }}
                                                </span></h4>
                                        </td>
                                        <td>
                                            <input id="calculatedPrice" type="hidden" name="calculatedPrice" value="{{ isset($customerRetun) ? $customerRetun->total_return_price : '' }}">
                                            <h4>Total Price: <span id="total-price">
                                                    {{ isset($customerRetun) ? number_format($customerRetun->total_return_price) : 0 }}
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
                                            <option value="1" @selected(isset($customerRetun) ? $customerRetun->account->payment_method == 1 : false)>Cash</option>
                                            <option value="2" @selected(isset($customerRetun) ? $customerRetun->account->payment_method == 2 : false)>Bank</option>
                                        </select>
                                    </div>

                                    <!-- Other elements taking 8 columns -->
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control rounded-0" placeholder="Enter Amount"
                                                name="amount" id="amountField" readonly
                                                value="{{ isset($customerRetun) ? number_format($customerRetun->total_return_price, 2, '.', '') : '' }}"
                                                required>
                                            <div class="input-group-text">Tk</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="bankInfo"
                            class="col-lg-12 {{ isset($customerRetun) ? ($customerRetun->account->payment_method == 2 ? '' : 'd-none') : 'd-none' }}">
                            <h4>Bank Information</h4>
                            <div class="row my-3">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="example-Account" class="form-label">Select Bank</label>
                                        <select id="bank_id" class="form-select rounded-0" name="bank_id"
                                            {{ isset($customerRetun) ? ($customerRetun->account->payment_method == 2 ? 'required' : '') : '' }}>
                                            <option value="">Select Bank</option>
                                            @foreach ($banks as $item)
                                            <option value="{{ $item->id }}"
                                                data-balance="{{ $item->balance }}"
                                                @selected(isset($customerRetun) && $customerRetun->account->bankTransaction ? $item->id == $customerRetun->account->bankTransaction->bank_id : false)>
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
                                            value="{{ isset($customerRetun) && $customerRetun->account->bankTransaction ? $customerRetun->account->bankTransaction->check_no : '' }}"
                                            placeholder="Check No">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="example-withdrawer" class="form-label">Withdrawer Name</label>
                                        <input id="withdrawer_name" type="text" id="example-withdrawer"
                                            class="form-control" name="withdrawer_name"
                                            placeholder="Withdrawer Name"
                                            value="{{ isset($customerRetun) && $customerRetun->account->bankTransaction ? $customerRetun->account->bankTransaction->withdrawer_name : '' }}"
                                            {{ isset($customerRetun) ? ($customerRetun->account->payment_method == 2 ? 'required' : '') : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">{{ isset($customerRetun) ? 'Update Customer Return' : 'Add New Customer Return' }}</button>
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
        let count = "{{ $iterationCount ?? 0 }}";

        $('#select-customers').on('change', function() {
            const customerId = $(this).val();



            $('#product-rows').empty();
            updateTotal();



            if (customerId) {
                $.ajax({
                    url: "{{ route('customer-return.customer.ajax', ':id') }}".replace(':id', customerId),
                    method: "GET",
                    success: function(response) {
                        let options = '<option value="">Select Product</option>';

                        response.forEach(option => {
                            if (option.batch_details && option.batch_details.length > 0) {
                                const batchDetails = JSON.stringify(option.batch_details);
                                options += ` 
                            <option 
                                value="${option.product_id}" 
                                data-batch_details='${batchDetails}'>
                                ${option.product_name}
                            </option>
                        `;
                            }
                        });

                        $('#select-products').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching batch details:", error);
                    }
                });
            }
        });


        $('#select-products').on('change', function() {
            $('#product-rows').empty();
            updateTotal();
            const selectedOption = $('#select-products').find('option:selected');
            const batchDetails = selectedOption.data('batch_details');
            // console.log(batchDetails);

            $('#batches_id').val(JSON.stringify([]));

            $('#select-batch').empty().append('<option value="">Select Batch</option>');

            if (batchDetails && batchDetails.length > 0) {
                let batchOptions = '';
                batchDetails.forEach(item => {
                    batchOptions += `
                        <option 
                            value="${item.batch_id}" 
                            data-sell_qty="${item.sell_qty}" 
                            data-avg_purchase_price="${item.avg_purchase_price}" 
                            data-avg_sell_price="${item.avg_sell_price}" 
                            data-purchase_id="${item.purchase_id}" 
                            data-sell_id="${item.sell_id}" 
                            data-created_at="${item.created_at}">
                            ${item.batch_code} -- ${item.created_at} -- Sell Qty: ${item.sell_qty} 
                        </option>
                    `;
                });

                $('#select-batch').append(batchOptions);
            }
        });

        $('#select-batch').on('change', function() {
            let batches = JSON.parse($('#batches_id').val());

            const batchId = $(this).val();

            batches.push(parseInt(batchId));
            $('#batches_id').val(JSON.stringify(batches));

            const productId = $('#select-products option:selected').val();
            const batchName = $('#select-batch option:selected').text();
            const sellQty = $('#select-batch option:selected').data('sell_qty');
            const avgSellPrice = parseFloat($('#select-batch option:selected').data('avg_sell_price')).toFixed(2);

            const purchaseId = $('#select-batch option:selected').data('purchase_id');
            const productName = $('#select-products option:selected').text();
            const customerId = $('#select-customers').val();

            const sellIds = $('#select-batch option:selected').data('sell_id');
            const avgPurchasePrices = $('#select-batch option:selected').data('avg_purchase_price');

            // console.log(avgPurchasePrices);



            const batchCode = $('#select-batch option:selected').val();
            const batchDate = $('#select-batch option:selected').data('created_at');
            let batchExists = false;
            $('.product-row').each(function() {
                const existingBatchCode = $(this).find('input[name="batch_id[]"]').val();
                const existingBatchDate = $(this).find('input[name="created_at[]"]').val();
                console.log(existingBatchDate);
                if (existingBatchCode === batchCode && existingBatchDate === batchDate) {
                    batchExists = true;
                    return false;
                }
            });

            if (batchExists) {
                alert('This batch with the same date has already been added to the table. Please choose a different batch or date.');
                return;
            }


            // console.log(avgSellPrice);
            $('#select-batch').val('');

            if (batchId) {
                const row = `
            <tr class="product-row">
                    <input type="hidden" name="base_product_id" value="${productId}">

                <td>${productName}</td>
                <td>
                    <input type="hidden" name="customer_id" value="${customerId}">
                    <input type="hidden" name="product_id[]" value="${productId}">
                    <input type="hidden" name="batch_id[]" value="${batchId}">
                    <input type="hidden" name="created_at[]" value="${batchDate}">
                    <input type="hidden" name="purchase_id[]" value="${purchaseId}">
                    <input type="hidden" name="sell_id[]" value="${sellIds}">
                    <input type="hidden" name="avg_purchase_price[]" value="${avgPurchasePrices}">
                    ${batchName}
                </td>
                <td>
                    <input 
                        type="number" 
                        class="form-control qty" 
                        name="quantities[]" 
                        value="0" 
                        min="0" 
                        max="${sellQty}">
                    <span>Total Sell Qty: ${sellQty}</span>
                </td>
                <td>
                    <input type="number" class="form-control price" name="return_prices[]" value="${avgSellPrice}" min="0">
                </td>
                <td>
                    <input type="text" class="form-control avg_sell_prices" name="avg_sell_prices[]" value="${avgSellPrice}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control total" value="0" readonly>
                </td>
                <td>
                    <button type="button" value="${count}" class="btn btn-danger waves-effect waves-light rowDelete">
                        <i class="mdi mdi-trash-can-outline"></i>
                    </button>
                </td>
            </tr>
        `;

                $('#product-rows').append(row);
                updateTotal();
            }
            count++;
        });

        // Handle quantity or price change to update the total
        // $(document).on('input', '.qty, .price', function() {
        //     updateTotal();
        // });


        $(document).on('input', '.qty', function() {
            const maxQuantity = parseInt($(this).attr('max'));
            let enteredQuantity = parseInt($(this).val());
            let totalStockQty = parseInt($("#totol_stock_qty").text());

            // console.log(totalStockQty);

            if (isNaN(enteredQuantity) || enteredQuantity < 0) {
                $(this).val(0);
                enteredQuantity = 0;
            }
            if (enteredQuantity > totalStockQty) {
                alert(`You cannot select more than the available stock of ${totalStockQty} for this product.`);
                $(this).val(totalStockQty);
                enteredQuantity = totalStockQty;
            } else if (enteredQuantity > maxQuantity) {
                alert(`You cannot select more than the maximum allowed quantity of ${maxQuantity} for this product.`);
                $(this).val(maxQuantity);
                enteredQuantity = maxQuantity;
            }

            if (maxQuantity === 0) {
                $(this).val(0);
                enteredQuantity = 0;
            }

            updateTotal();
        });


        $(document).on('click', '.rowDelete', function() {
            var buttonValue = $(this).val();
            let batches = JSON.parse($('#batches_id').val());

            batches.splice(buttonValue, 1);

            // console.log('Removing index:', buttonValue);
            $('#batches_id').val(JSON.stringify(batches));
            $(this).closest('tr').remove();

            $('#myTable .rowDelete').each(function(index) {
                $(this).val(index);
            });



            $(this).closest('tr').remove();
            updateTotal();
        });
        $(document).on('input', '.qty, .price', function() {
            const row = $(this).closest('tr');
            const qty = parseFloat(row.find('.qty').val()) || 0;
            const price = parseFloat(row.find('.price').val()) || 0;
            const total = qty * price;

            // console.log(price);

            row.find('.total').val(total.toFixed(2));

            updateTotal();
        });


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


        function updateTotal() {
            totalQuantity = 0;
            totalPrice = 0;

            $('.product-row').each(function() {
                const qty = parseInt($(this).find('.qty').val()) || 0;
                const price = parseFloat($(this).find('.price').val()) || 0;
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





    });
</script>



@endsection