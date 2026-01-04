@extends('layouts.vertical', ['title' => 'Stock Manage'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Manage Stock'])

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>

                    <form action="{{ route('stock.manage.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-3">
                                <p class="mb-1 fw-bold text-muted">Select Supplier</p>
                                <select id="select-suppliers" class="form-control" data-toggle="select2"
                                    data-width="100%" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <p class="mb-1 fw-bold text-muted">Select Batch</p>
                                <select id="select-batch" class="form-select" required>
                                    <option value="">Select Batch</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <p class="mb-1 fw-bold text-muted">Select Product</p>
                                <select id="select-product" class="form-select" required>
                                    <option value="">Select Product</option>
                                </select>
                            </div>

                            <!-- <div class="col-md-3">
                                <p class="mb-1 fw-bold text-muted">Adjustment Type</p>
                                <select id="select-adjustment-type" class="form-select" name="stock_status" required>
                                    <option value="">Select Adjustment Type</option>
                                    <option value="1">1 - মাল নষ্ট (Damage)</option>
                                    <option value="2">2 - মাল হারানো (Lost)</option>
                                    <option value="3">3 - মাল বেশি পাওয়া (Found)</option>
                                    <option value="4">4 - মেয়াদ শেষ (Expiry)</option>
                                    <option value="5">5 - চুরি হয়ে গেছে (Theft)</option>
                                    <option value="6">6 - হাতে বাড়ানো হয়েছে (Manual Increase)</option>
                                    <option value="7">7 - হাতে কমানো হয়েছে (Manual Decrease)</option>
                                </select>
                            </div>

                        
                        damage	           মাল নষ্ট	                  ডেবিট (খরচ)	     মাল কমে গেছে, মানে লোকসান
                        lost	           মাল হারানো	             ডেবিট (খরচ)	    মাল বের হয়ে গেছে, ক্ষতি
                        found	           মাল বেশি পাওয়া	         ক্রেডিট (আয়)	     বাড়তি মাল এসেছে, লাভ
                        expiry	           মেয়াদ শেষ	             ডেবিট (খরচ)	    মাল নষ্ট, বিক্রি হবে না
                        theft	           চুরি হয়ে গেছে	          ডেবিট (খরচ)	      মাল কমে গেছে, ক্ষতি
                        manual_increase	   হাতে বাড়ানো হয়েছে	      ক্রেডিট (আয়)	     নতুন মাল যোগ হলো
                        manual_decrease	   হাতে কমানো হয়েছে	          ডেবিট (খরচ)	    মাল কমিয়ে দেওয়া হলো 
                        -->

                        <div class="col-md-3">
                            <p class="mb-1 fw-bold text-muted">Adjustment Type</p>
                            <select id="select-adjustment-type" class="form-select" name="stock_status" required>
                                <option value="">Select Adjustment Type</option>
                                <option value="1">Damage</option>
                                <option value="2">Lost</option>
                                <option value="3">Found</option>
                                <option value="4">Expiry</option>
                                <option value="5">Theft</option>
                                <option value="6">Manual Increase</option>
                                <option value="7">Manual Decrease</option>
                            </select>
                        </div>

                        </div>

                        <br><br>

                        <div class="table-responsive">
                                <table id="myTable" class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Batch</th>
                                            <th>Qty</th>
                                            <th>Avg Purchase Price</th>
                                            <th>Total</th>
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
                                                        <button type="button"
                                                            class="btn btn-danger waves-effect waves-light rowDelete"><i
                                                                class="mdi mdi-trash-can-outline"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr id="product-input-container">
                                                <td colspan="8" class="text-center">
                                                    <h4 class="my-3">Insert Products</h4>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-end">Total Qty:</th>
                                            <th><span id="total-qty">0</span></th>
                                            <th class="text-end">Grand Total:</th>
                                            <th><span id="grand-total">0.00</span></th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>

                        </table>
                        </div><br><br>

                        
                        <div class="col-md-12 ">
                            <p class="mb-1 fw-bold text-muted">Description</p>
                            <textarea class="form-control" id="example-textarea" placeholder="Address" rows="5" name="description"
                                required>{{ isset($expense) ? $expense->description : '' }} </textarea>
                        </div> <!-- end col -->

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">Manage Stock</button>
                        </div>

                    <input type="hidden" name="total_qty" id="total_qty_input">
                    <input type="hidden" name="grand_total" id="grand_total_input">
                        
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
    $(document).ready(function () {

        $('#select-suppliers').on('change', function () {
            var supplierId = $(this).val();
            if (supplierId) {
                $.ajax({
                    url: "{{ route('stock.manage.ajax', ':id') }}".replace(':id', supplierId),
                    type: 'GET',
                    success: function (response) {
                        $('#select-batch').empty().append('<option value="">Select Batch</option>');
                        $('#select-product').empty().append('<option value="">Select Product</option>');
                        $('#myTable tbody').empty().append(`
                            <tr id="product-input-container">
                                <td colspan="8" class="text-center">
                                    <h4 class="my-3">Insert Products</h4>
                                </td>
                            </tr>
                        `);
                        response.forEach(batch => {
                            const $option = $(`<option value="${batch.id}" data-products='${JSON.stringify(batch.products)}'>${batch.batch_code}</option>`);
                            $('#select-batch').append($option);
                        });
                        updateTotals();
                    }
                });
            }
        });

        $('#select-batch').on('change', function () {
            const productsData = $('option:selected', this).data('products');
            $('#select-product').empty().append('<option value="">Select Product</option>');
            if (productsData) {
                productsData.forEach(product => {
                    $('#select-product').append(`<option value="${product.id}" data-avg_purchase_price="${product.avg_purchase_price}">
                    ${product.name} - Stock: ${product.stock}
                    </option>`);
                });
            }
        });

        $('#select-product').on('change', function () {
            const productId = $(this).val();
            const productName = $('#select-product option:selected').text();
            const batchId = $('#select-batch').val();
            const batchText = $('#select-batch option:selected').text();
            if (!productId || !batchId) return;

            const avgPurchasePrice = parseFloat($('#select-product option:selected').data('avg_purchase_price')) || 0;

            if ($(`#myTable tbody tr[data-product-id="${productId}"][data-batch-id="${batchId}"]`).length > 0) {
                alert('This product in the selected batch is already added.');
                return;
            }

            const newRow = `
                <tr class="tableRow" data-product-id="${productId}" data-batch-id="${batchId}">
                    <td><input type="hidden" name="products[]" value="${productId}"><input type="text" class="form-control" value="${productName}" readonly></td>
                    <td><input type="hidden" name="batch_id[]" value="${batchId}"><input type="text" class="form-control" value="${batchText}" readonly></td>
                    <td><input type="number" class="form-control qtyCalculation" name="qty[]" placeholder="Qty" min="0" required></td>
                    <td><input type="text" class="form-control avgPurchasePrice" name="avg_purchase_price[]" value="${avgPurchasePrice.toFixed(2)}" readonly></td>
                    <td><input type="text" class="form-control totalPrice" name="priceTotal[]" placeholder="Total" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-product-row"><i class="mdi mdi-trash-can-outline"></i></button></td>
                </tr>
            `;
            $('#product-input-container').remove();
            $('#myTable tbody').append(newRow);
            updateTotals();
            
        });

        $(document).on('input', '.qtyCalculation, .avgPurchasePrice', function () {
            const row = $(this).closest('tr');
            const qty = parseFloat(row.find('.qtyCalculation').val()) || 0;
            const price = parseFloat(row.find('.avgPurchasePrice').val()) || 0;
            const total = qty * price;
            row.find('.totalPrice').val(total.toFixed(2));
            updateTotals();
        });

        $(document).on('click', '.remove-product-row', function () {
            $(this).closest('tr').remove();
            if ($('#myTable tbody .tableRow').length === 0) {
                $('#myTable tbody').append(`
                    <tr id="product-input-container">
                        <td colspan="8" class="text-center">
                            <h4 class="my-3">Insert Products</h4>
                        </td>
                    </tr>
                `);
            }
            updateTotals();

        });


        function updateTotals() {
            let totalQty = 0;
            let grandTotal = 0.00;

            $('#myTable tbody .tableRow').each(function (){
                const qty = parseFloat($(this).find('.qtyCalculation').val()) || 0;
                const total = parseFloat($(this).find('.totalPrice').val()) || 0;
                totalQty += qty;
                grandTotal += total;
            });

            $('#total-qty').text(totalQty);
            $('#grand-total').text(grandTotal.toFixed(2));

            $('#total_qty_input').val(totalQty);
            $('#grand_total_input').val(grandTotal.toFixed(2));
        }
        updateTotals();

    });
</script>
@endsection
