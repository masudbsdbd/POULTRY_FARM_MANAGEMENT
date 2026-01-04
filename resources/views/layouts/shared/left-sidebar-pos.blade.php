<!-- Left Sidebar Start -->
 <!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

<!-- jQuery (Toastr needs it) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<div class="app-menu d-flex flex-column" style="min-height: 100vh; width:1500px;">
    <div class="container-fluid flex-grow-1"><br>
        <!-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif -->

        @if(session('print') && session('sellRecord') && is_iterable(session('sellRecord')))
    <div id="print-content" style="display: none; font-family: Arial, sans-serif; font-size: 10px; width: 58mm; text-align: center;">
        @php
            $firstRecord = session('sellRecord')[0] ?? null;
        @endphp
        <div style="margin-bottom: 5px;">
            <strong>{{ \Carbon\Carbon::now()->format('d M Y, h:i A') }} , </strong>
            <strong>{{ \Carbon\Carbon::now()->format('l') }}</strong>

        </div>
        @if($firstRecord && isset($firstRecord->sell->customer->name))
            <div style="margin-bottom: 5px;">
                <strong>Customer: {{ $firstRecord->sell->customer->name }}</strong>
            </div>
        @endif
        <h3 style="margin: 5px 0;">Sell Details</h3>
        <table style="width: 100%; margin: 0 auto;">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('sellRecord') as $sell)
                    <tr>
                        <td>{{ $sell->product->name }}</td>
                        <td>{{ showAmount($sell->sell_price) }}</td>
                        <td>{{ $sell->sell_qty }}</td>
                        <td>{{ showAmount($sell->total_amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Amount:</strong></td>
                    <td>
                        <strong>
                            {{ showAmount(session('sellRecord')->sum('total_amount')) }}
                        </strong>
                    </td>
                </tr>
            </tfoot>

        </table>
    </div>

    <script type="text/javascript">
        window.onload = function () {
            const content = document.getElementById('print-content');
            if (content) {
                const printWindow = window.open('', '', 'width=300,height=600');
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Print</title>
                            <style>
                                body {
                                    font-size: 20px;
                                    font-family: Arial, sans-serif;
                                    width: 58mm;
                                    margin: 0;
                                    padding: 5px;
                                    text-align: center;
                                }
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin: 0 auto;
                                }
                                th, td {
                                    padding: 2px;
                                }
                                th {
                                    font-weight: bold;
                                }
                                tr {
                                    line-height: 1.4;
                                }
                                tbody td {
                                    font-size: 22px; 
                                }
                            </style>
                        </head>
                        <body>${content.innerHTML}</body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
            }
        };
    </script>
@endif





        <div class="col-md-12">
            <input type="text" id="barcode-input-new" placeholder="Search product by Barcode" class="form-control" />
        </div>

        <form action="{{ route('sell.store') }}" method="POST">
            @csrf

            <input type="hidden" name="from_pos" value="1">
            <div class="mb-3">
                <label for="example-select" class="form-label"></label>
                <select id="select-suppliers" data-toggle="select2" data-width="100%" name="customer_id" required>
                    <option value="">Select Customer</option>
                    @foreach ($customers as $item)

                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="payment_method" value="1">

            <!-- Product Search -->
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="simpleinput" class="form-control" placeholder="Search product by name" autocomplete="off">
                    </div>

                </div>


                <ul id="suggestions" class="list-group"
                    style="display:none; position: absolute; z-index: 1000; width: 100%;"></ul>
                <table id="entries-table" class="table mt-3">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="entries-container"></tbody>
                </table>
            </div>

            <div id="product-data" style="display:none;">
            </div>
    </div>

    <!-- Footer (Sticky) -->
    <div id="totalTable" class="mt-auto" style="width:100%; padding: 5px; color: #000; background: #FFF;">
        <table>
            <tbody>
                <tr>
                    <td style="padding: 10px 10px;border-top: 1px solid #DDD; width: 50%;">Items :</td>
                    <td class="text-right"
                        style="padding: 5px 10px;font-size: 14px; font-weight:bold;border-top: 1px solid #DDD; width: 50%;">
                        <span id="footer-items">0</span>
                    </td>


                </tr>
                <tr>
                    <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #333; font-weight:bold; background:#333; color:#FFF;"
                        colspan="2">
                        Total Payment
                    </td>
                    <td class="text-right"
                        style="padding: 5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #333; font-weight:bold; background:#333; color:#FFF;"
                        colspan="2">
                    </td>
                    <td class="text-right"
                        style="padding: 5px 10px;font-size: 14px; font-weight:bold;border-top: 1px solid #DDD; width: 50%;">
                        <span id="total">0.00</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Submit Button -->
        <div class="mt-3">
            <button style="width: 100%;" class="btn btn-success">Payment</button>
        </div>
        </form>




    </div>
</div>
<!-- Left Sidebar End -->


<!-- jQuery Script for Product Suggestions and Table Update -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // let productData = "{{$productData}}";
    // let productData = "{{$productData}}";
    let productData = @json($productData);

    // console.log(productData);


    $(document).ready(function() {
        const inputField = $('#simpleinput');
        const suggestionsContainer = $('#suggestions');
        const entriesContainer = $('#entries-container');
        const footerItems = $('#footer-items');
        const footerTotal = $('#total');


        inputField.on('input', function() {
            const searchTerm = $.trim(inputField.val());

            if (searchTerm.length > 0) {
                $.ajax({
                    url: "{{ route('pos.get-products-suggestions') }}",
                    method: 'GET',
                    data: {
                        term: searchTerm
                    },
                    success: function(data) {
                        suggestionsContainer.empty();

                        if (data.length > 0) {
                            suggestionsContainer.show();
                            data.forEach(function(product) {
                                const stockQty = product.stock_item ? product
                                    .stock_item.stock : 0;
                                const suggestionItem = $('<li></li>')
                                    .addClass(
                                        'list-group-item list-group-item-action')
                                    .text(`${product.name} (Stock: ${stockQty})`)
                                    .on('click', function() {
                                        addProductToTable(product);
                                        inputField.val('');
                                        suggestionsContainer.hide();
                                    });
                                suggestionsContainer.append(suggestionItem);
                            });
                        } else {
                            suggestionsContainer.hide();
                        }
                    },
                    error: function() {
                        suggestionsContainer.hide();
                    }
                });
            } else {
                suggestionsContainer.hide();
            }
        });
        // ================================
        $("#barcode-input-new").on("change", function() {
            // console.log("scannedBarcode");

            let scannedBarcode = $(this).val().toUpperCase();
            $(this).val("");
            // console.log(scannedBarcode);

            if (productData[scannedBarcode]) {
                let product = productData[scannedBarcode];
                // console.log(product);

                addProductToTable(product);
            } else {
                alert("Product not found!");
            }
        });

        // function addProductToTable(product) {
        //     $("#product-table tbody").append(`
        //     <tr>
        //         <td>${product.name}</td>
        //         <td>${product.price}</td>
        //         <td>${product.stock}</td>
        //     </tr>
        // `);
        // }
        // ================================

        // ============================================
        // document.getElementById('barcode-input').addEventListener('keypress', function(event) {
        //     if (event.key === 'Enter') {
        //         event.preventDefault();
        //         const barcode = this.value;

        //         $.ajax({
        //             url: "{{ route('pos.get-products-suggestions') }}",
        //             type: 'GET',
        //             data: {
        //                 barcode: barcode
        //             },
        //             success: function(data) {
        //                 if (data.length > 0) {
        //                     const product = data[0];
        //                     addProductToTable(product);
        //                     $('#barcode-input').val('');
        //                 } else {
        //                     alert('Product not found!');
        //                 }
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error('Error:', error);
        //                 alert('Error while fetching product.');
        //             }
        //         });
        //     }
        // });


        // ============================================


        function addProductToTable(product) {

            let exists = false;
            $('#entries-container tr').each(function() {
                if ($(this).find('.entry-text').text() === product.name) {
                    exists = true;
                }
            });

            if (exists) {
                alert("Product already added to the table!");
                return;
            }

            const entryRow = $('<tr></tr>');
            const entryTextCell = $('<td></td>').addClass('entry-text').text(product.name);
            // const entryTextCell2 = $('<td></td>')
            //     .addClass('entry-text-2')
            //     .text(parseFloat(product.price).toFixed(0));

                const entryTextCell2 = $('<td></td>').addClass('entry-text-2');

                const priceInput = $('<input>')
                    .attr({
                        type: 'number',
                        min: 0
                    })
                    .addClass('form-control form-control-sm')
                    .val(parseFloat(product.price).toFixed(0));

                entryTextCell2.append(priceInput);


            const entryId = $('<td></td>').addClass('entry-id').text(product.id).attr('hidden', true);
            const entryDate = $('<td></td>')
                .addClass('entry-date')
                .text(new Date().toLocaleString()).attr('hidden', true);

            // ===================================================
            // const avgPurchasePrice = product.stocks.reduce((total, stock) => total + parseFloat(stock
            //     .avg_purchase_price), 0) / product.stocks.length;

            // const avgPurchasePriceCell = $('<td></td>').addClass('avg-purchase-price-sell').text(
            //     avgPurchasePrice.toFixed(2)).attr('hidden', true);
            // console.log(avgPurchasePrice);

            // const batchSelectCell = $('<td></td>').addClass('batch-cell');
            // // const batchSelect = $('<select></select>').addClass('form-control form-control-sm');

            // const batchSelect = $('<select></select>')
            //     .addClass('form-control form-control-sm')
            //     .data('current-batch-id', product.stocks[0]?.purchase_batch_id || '').css('width', '130px');


            // product.stocks.forEach(function(stock) {
            //     const batchOption = $('<option></option>')
            //         .val(stock.purchase_batch_id)
            //         .text(`${stock.purchase_batch.batch_code} - Stock: ${stock.stock}`);
            //     batchSelect.append(batchOption);
            // });

            // ===================================================

            // ============================
         
            const availableStocks = product.stocks.filter(stock => parseFloat(stock.stock) > 0);
            // console.log(availableStocks);


            // const avgPurchasePrice = availableStocks.reduce(
            //     (total, stock) => total + parseFloat(stock.avg_purchase_price),
            //     0
            // ) / (availableStocks.length || 1); // prevent divide by 0

            // console.log(avgPurchasePrice);

            const avgPurchasePrice = availableStocks.length > 0 ? parseFloat(availableStocks[0].avg_purchase_price) : 0;
            // console.log(avgPurchasePrice);


            
            const avgPurchasePriceCell = $('<td></td>')
                .addClass('avg-purchase-price-sell')
                .text(avgPurchasePrice.toFixed(2))
                .attr('hidden', true);

            const batchSelectCell = $('<td></td>').addClass('batch-cell');

            const batchSelect = $('<select></select>')
                .addClass('form-control form-control-sm')
                .data('current-batch-id', availableStocks[0]?.purchase_batch_id || '')
                .css('width', '130px');

            // Append only available batches
            availableStocks.forEach(function(stock) {
                const batchOption = $('<option></option>')
                    .val(stock.purchase_batch_id)
                    .text(`${stock.purchase_batch.batch_code} - Stock: ${stock.stock}`);
                batchSelect.append(batchOption);
            });

            // ============================

            batchSelectCell.append(batchSelect);

            const qtyCell = $('<td></td>').addClass('qty-cell');
            const qtyInput = $('<input>').attr('type', 'number').addClass('form-control form-control-sm').val(0)
                .attr('min', 1);
            qtyCell.append(qtyInput);

            const subtotalCell = $('<td></td>').addClass('subtotal-cell').text('0.00');

            entryRow.append(entryTextCell);
            entryRow.append(entryTextCell2);
            entryRow.append(entryId);
            entryRow.append(batchSelectCell);
            entryRow.append(qtyCell);
            entryRow.append(subtotalCell);
            entryRow.append(entryDate);
            entryRow.append(avgPurchasePriceCell);

            const clearButtonCell = $('<td></td>');
            const clearButton = $('<button></button>')
                .addClass('btn btn-danger btn-sm')
                .text('X')
                .on('click', function() {
                    entryRow.remove();
                    updateFooter();
                });
            clearButtonCell.append(clearButton);
            entryRow.append(clearButtonCell);

            entriesContainer.append(entryRow);

            qtyInput.on('input', function() {
                const qtyValue = qtyInput.val();
                const selectedBatchId = batchSelect.val();
                const selectedBatch = product.stocks.find(stock => stock.purchase_batch_id ==
                    selectedBatchId);
                const availableStock = selectedBatch ? selectedBatch.stock : 0;

                if (qtyValue > availableStock) {
                    alert('Out of stock!');
                    qtyInput.val(0);
                    // qtyInput.val(availableStock);
                    return;
                }
                const price = parseFloat(priceInput.val());
                // console.log(price);
                updateSubtotal(entryRow, price, qtyInput.val());
            });

            // Price input handler (new)
            priceInput.on('input', function() {
            const price = parseFloat(priceInput.val()) || 0;
            const qty = parseFloat(qtyInput.val()) || 0;
            updateSubtotal(entryRow, price, qty);
            });



            batchSelect.on('change', function() {
                const newBatchId = $(this).val();
                const currentBatchId = $(this).data('current-batch-id');


                const productId = $(this).closest('tr').find('.entry-id').text();

                // console.log(productId);

                let isDuplicateBatch = false;
                let sum = 0;
                $('#entries-container tr').each(function() {

                    const rowProductId = $(this).find('.entry-id').text();
                    const rowBatchId = $(this).find('.batch-cell select').val();

                    // console.log(rowProductId);
                    if (rowProductId == productId && rowBatchId == newBatchId) {
                        isDuplicateBatch = true;
                        sum++;
                    }

                });

                if (sum <= 1) {
                    if (newBatchId !== currentBatchId) {
                        addNewProductRow(product, newBatchId);
                        $(this).val(currentBatchId);
                    }

                    updateFooter();

                } else {
                    // console.log('duplicate');
                    alert("Already selected this product with the same batch!");
                    $(this).val(currentBatchId);

                }
                // if (newBatchId !== currentBatchId) {
                //     // Add a new row for the selected batch
                //     addNewProductRow(product, newBatchId);
                //     $(this).val(currentBatchId); 
                // }

                // updateFooter();
            });


            updateFooter();
        }


        function addNewProductRow(product, selectedBatchId = null) {
            const entryRow = $('<tr></tr>').attr('data-product-id', product.id).attr('data-batch-id',
                selectedBatchId || '');
            const entryTextCell = $('<td></td>').addClass('entry-text').text(product.name);
            // const entryTextCell2 = $('<td></td>').addClass('entry-text-2').text(product.price);
            // const entryTextCell2 = $('<td></td>')
            //     .addClass('entry-text-2')
            //     .text(parseFloat(product.price).toFixed(0));

            const entryTextCell2 = $('<td></td>').addClass('entry-text-2');

            const priceInput = $('<input>')
                .attr({
                    type: 'number',
                    min: 0
                })
                .addClass('form-control form-control-sm')
                .val(parseFloat(product.price).toFixed(0));

            entryTextCell2.append(priceInput);

            const entryId = $('<td></td>').addClass('entry-id').text(product.id).attr('hidden', true);
            const entryDate = $('<td></td>')
                .addClass('entry-date')
                .text(new Date().toLocaleString()).attr('hidden', true);

            // ================================================

            let avgPurchasePrice = null;  
            product.stocks.forEach(stock => {
                if (stock.purchase_batch_id == selectedBatchId) {
                    avgPurchasePrice = parseFloat(stock.avg_purchase_price);
                }
            });

            if (avgPurchasePrice !== null) {
                const avgPurchasePriceCell = $('<td></td>')
                    .addClass('avg-purchase-price-sell')
                    .text(avgPurchasePrice.toFixed(2))
                    .attr('hidden', true);
            } else {
                console.log("No matching batch found.");
            }
            // ------
            
            // const avgPurchasePrice = product.stocks.reduce((total, stock) => total + parseFloat(stock
            //     .avg_purchase_price), 0) / product.stocks.length;
            const avgPurchasePriceCell = $('<td></td>').addClass('avg-purchase-price-sell').text(
                avgPurchasePrice.toFixed(2)).attr('hidden', true);

            // Batch selection
            const batchSelectCell = $('<td></td>').addClass('batch-cell');
            // const batchSelect = $('<select></select>').addClass('form-control form-control-sm');
            // const batchSelect = $('<select></select>')
            //     .addClass('form-control form-control-sm')
            //     .data('current-batch-id', product.stocks[0]?.purchase_batch_id || ''); // Default to the first batch ID


            const batchSelect = $('<select></select>')
                .addClass('form-control form-control-sm')
                .data('current-batch-id', selectedBatchId || (product.stocks[0]?.purchase_batch_id || ''));

            product.stocks.forEach(function(stock) {
                const batchOption = $('<option></option>')
                    .val(stock.purchase_batch_id)
                    .text(`${stock.purchase_batch.batch_code} - Stock: ${stock.stock}`);;

                if (selectedBatchId && selectedBatchId == stock.purchase_batch_id) {
                    batchOption.prop('selected', true);
                }

                batchSelect.append(batchOption);
            });

            // ============================================
            // ============================
            // const availableStocks = product.stocks.filter(stock => parseFloat(stock.stock) > 0);
            // const avgPurchasePrice = availableStocks.reduce(
            //     (total, stock) => total + parseFloat(stock.avg_purchase_price),
            //     0
            // ) / (availableStocks.length || 1); // prevent divide by 0

            // const avgPurchasePriceCell = $('<td></td>')
            //     .addClass('avg-purchase-price-sell')
            //     .text(avgPurchasePrice.toFixed(2))
            //     .attr('hidden', true);

            // const batchSelectCell = $('<td></td>').addClass('batch-cell');

            // const batchSelect = $('<select></select>')
            //     .addClass('form-control form-control-sm')
            //     .data('current-batch-id', availableStocks[0]?.purchase_batch_id || '')
            //     .css('width', '130px');

            // // Append only available batches
            // availableStocks.forEach(function(stock) {
            //     const batchOption = $('<option></option>')
            //         .val(stock.purchase_batch_id)
            //         .text(`${stock.purchase_batch.batch_code} - Stock: ${stock.stock}`);
            //     batchSelect.append(batchOption);
            // });

            // ============================

            batchSelectCell.append(batchSelect);

            // Quantity input
            const qtyCell = $('<td></td>').addClass('qty-cell');
            const qtyInput = $('<input>').attr('type', 'number').addClass('form-control form-control-sm').val(0)
                .attr('min', 1);
            qtyCell.append(qtyInput);

            // Subtotal cell
            const subtotalCell = $('<td></td>').addClass('subtotal-cell').text('0.00');

            entryRow.append(entryTextCell);
            entryRow.append(entryTextCell2);
            entryRow.append(entryId);
            entryRow.append(batchSelectCell);
            entryRow.append(qtyCell);
            entryRow.append(subtotalCell);
            entryRow.append(entryDate);
            entryRow.append(avgPurchasePriceCell);

            // Clear button
            const clearButtonCell = $('<td></td>');
            const clearButton = $('<button></button>')
                .addClass('btn btn-danger btn-sm')
                .text('X')
                .on('click', function() {
                    entryRow.remove();
                    updateFooter();
                });
            clearButtonCell.append(clearButton);
            entryRow.append(clearButtonCell);

            entriesContainer.append(entryRow);

            // Quantity input change handler
            qtyInput.on('input', function() {
                const qtyValue = qtyInput.val();
                const selectedBatchId = batchSelect.val();
                const selectedBatch = product.stocks.find(stock => stock.purchase_batch_id ==
                    selectedBatchId);
                const availableStock = selectedBatch ? selectedBatch.stock : 0;

                if (qtyValue > availableStock) {
                    alert('Out of stock!');
                    qtyInput.val(0);
                    return; 
                }

                const price = parseFloat(priceInput.val());
                // console.log(price);
                updateSubtotal(entryRow, price, qtyInput.val());

                // updateSubtotal(entryRow, product.price, qtyInput.val());
            });

            // Price input handler (new)
            priceInput.on('input', function() {
            const price = parseFloat(priceInput.val()) || 0;
            const qty = parseFloat(qtyInput.val()) || 0;
            updateSubtotal(entryRow, price, qty);
            });

            // Batch change handler 
            batchSelect.on('change', function() {
                const newBatchId = $(this).val();
                const currentBatchId = $(this).data(
                    'current-batch-id');

                // ===================
                const productId = $(this).closest('tr').find('.entry-id').text();

                // console.log(productId);

                let isDuplicateBatch = false;
                let sum = 0;
                $('#entries-container tr').each(function() {

                    const rowProductId = $(this).find('.entry-id').text();
                    const rowBatchId = $(this).find('.batch-cell select').val();

                    // console.log(rowProductId);

                    if (rowProductId == productId && rowBatchId == newBatchId) {
                        isDuplicateBatch = true;
                        sum++;

                    }


                });

                if (sum <= 1) {

                    if (newBatchId !== currentBatchId) {
                        addNewProductRow(product, newBatchId);

                        $(this).val(currentBatchId);
                    }

                    updateFooter();

                } else {
                    // console.log('duplicate');
                    alert("Already selected this product with the same batch!");
                    $(this).val(currentBatchId);

                }
                // if (newBatchId !== currentBatchId) {
                //     // Add a new row for the selected batch
                //     addNewProductRow(product, newBatchId);
                //     $(this).val(currentBatchId); 
                // }

                // updateFooter();
            });

            updateFooter();
        }

        function updateSubtotal(row, price, qty) {
            const subtotalCell = row.find('.subtotal-cell');
            const subtotal = price * qty;
            subtotalCell.text(subtotal.toFixed(2));
            updateFooter();
        }

        function updateFooter() {
            let totalQty = 0;
            let totalAmount = 0;
            // console.log("ssss");
            $('#entries-container .qty-cell input').each(function() {
                const qty = parseInt($(this).val()) || 0;
                // const price = parseFloat($(this).closest('tr').find('.entry-text-2').text()) || 0;
                const price = parseFloat($(this).closest('tr').find('.entry-text-2 input').val()) || 0;
                // console.log(price,qty);
                totalQty += qty;
                totalAmount += price * qty;
            });

            footerItems.text(totalQty);
            footerTotal.text(totalAmount.toFixed(2));
        }

        $('form').on('submit', function() {
            let index = 0;

            $('#product-data').empty();

            $('#entries-container tr').each(function() {
                const entryId = $(this).find('.entry-id').text();
                const productName = $(this).find('.entry-text').text();
                // const productPrice = $(this).find('.entry-text-2').text();
                const productPrice = $(this).find('.entry-text-2 input').val();
                const batchId = $(this).find('.batch-cell select').val();
                const quantity = $(this).find('.qty-cell input').val();
                const subtotal = $(this).find('.subtotal-cell').text();
                const entryDate = $(this).find('.entry-date').text();
                const avgPurchasePrice = $(this).find('.avg-purchase-price-sell').text();

                const priceTotal = $(this).find('.subtotal-cell').text();

                const discount = $(this).find('.entry-discount').text();
                const installment = $(this).find('.entry-installment').text();
                const transactionType = 1;
                const balance = $('#total').text();
                const status = 1;

                $('#product-data').append(`
                <input type="hidden" name="sell_date" value="${entryDate}">
                <input type="hidden" name="products[${index}]" value="${entryId}">
                <input type="hidden" name="batch_id[${index}]" value="${batchId}">
                <input type="hidden" name="sell_price[${index}]" value="${productPrice}">
                <input type="hidden" name="qty[${index}]" value="${quantity}">
                <input type="hidden" name="priceTotal[${index}]" value="${priceTotal}">
                <input type="hidden" name="avg_purchase_price[${index}]" value="${avgPurchasePrice}">
                <input type="hidden" name="discounts[${index}]" value="${discount}">
                <input type="hidden" name="status" value="${status}">
                <input type="hidden" name="discount" value="${discount}">
                <input type="hidden" name="installment" value="${installment}">
                <input type="hidden" name="transaction_type" value="${transactionType}"> <!-- Static transaction type -->
                <input type="hidden" name="balance" value="${balance}">
            `);

                index++;
            });

            const totalQuantity = $('#footer-items').text();
            const calculatedPrice = $('#total').text();

            $('#product-data').append(`
            <input type="hidden" name="totalQuantity" value="${totalQuantity}">
            <input type="hidden" name="calculatedPrice" value="${calculatedPrice}">
        `);
        });


        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if(session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if(session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif


    });
</script>