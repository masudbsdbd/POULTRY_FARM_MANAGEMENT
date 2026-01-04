@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite([
        'node_modules/flatpickr/dist/flatpickr.min.css', 
        'node_modules/select2/dist/css/select2.min.css'
    ])
@endsection

@section('content')
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Quotations'])

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        <h4>{{ isset($quotation) ? 'Batch Code: ' . $quotation->batch->batch_code : '' }}</h4>
                    </div>

                    <form action="{{ isset($quotation) ? route('quotation.item.store', $quotation->id) : route('quotation.item.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="fw-bold text-muted">Quotation Head</label>
                                <select id="select-quotations" class="form-control" data-toggle="select2" name="quotation_id" required>
                                    <option value="">Select</option>
                                    @foreach ($quotations as $item)
                                        <option value="{{ $item->id }}" data-item="{{ $item }}" 
                                            @selected(isset($purchase) ? $purchase->quotation_id  == $item->id : false)>
                                            {{ $item->quotation_number }} - {{ $item->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold text-muted">Select Product</label>
                                <select id="select-products" class="form-control" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach ($products as $item)
                                        <option value="{{ $item->id }}" data-item="{{ $item }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <h4 class="header-title">Purchase List</h4>
                            <div class="table-responsive">
                                <table id="myTable" class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Note</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($quotation_items) && !$quotation_items->isEmpty())
                                            @foreach ($quotation_items as $item)
                                                @php $product = App\Models\Product::find($item->product_id); @endphp
                                                <tr class="tableRow" data-product="{{ $item->product_id }}">
                                                    <td>
                                                        <input type="hidden" name="products[]" value="{{ $product->id }}">
                                                        <input type="text" class="form-control" value="{{ $product->name }}" readonly required>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control description" name="description[]" value="{{ $item->description }}" placeholder="Description">
                                                    </td>
                                                    <td><input type="number" class="form-control qtyCalculation" name="qty[]" value="{{ $item->qty }}" required></td>
                                                    <td><input type="number" class="form-control unitPriceCalculation" name="unitPrice[]" value="{{ showAmount($item->unit_price,2,false) }}" required></td>
                                                    <td><input type="text" class="form-control totalPrice" name="priceTotal[]" value="{{ showAmount($item->total,2,false) }}" readonly></td>
                                                    <td><button type="button" class="btn btn-danger rowDelete"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr id="empty-row" class="{{ isset($quotation_items) && !$quotation_items->isEmpty() ? 'd-none' : '' }}">
                                            <td colspan="6" class="text-center"><h4 class="my-3">Insert Products</h4></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Total Quantity:</td>
                                            <td><span id="total-quantity">{{ isset($purchase) ? $purchase->total_qty : 0 }}</span></td>
                                            <td>Total Price:</td>
                                            <td><span id="total-price">{{ isset($purchase) ? number_format($purchase->total_price) : 0 }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" id="totalQuantity" name="totalQuantity" value="{{ isset($purchase) ? $purchase->total_qty : '' }}">
                                <input type="hidden" id="calculatedPrice" name="calculatedPrice" value="{{ isset($purchase) ? $purchase->total_price : '' }}">
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">{{ isset($quotation) ? 'Update Quotation Item' : 'Add Quotation Item' }}</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @vite([
        'resources/js/pages/form-advanced.init.js',
        'resources/js/pages/form-pickers.init.js'
    ])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let quotationId = null;

            function addRow(product) {
                $('#empty-row').addClass('d-none');
                const row = $(` 
                    <tr class="tableRow" data-product="${product.id}">
                        <td><input type="hidden" name="products[]" value="${product.id}">
                            <input type="text" class="form-control" value="${product.name}" readonly required>
                        </td>
                        <td><input type="text" class="form-control description" name="description[]" placeholder="note"></td>
                        <td><input type="number" class="form-control qtyCalculation" name="qty[]" required></td>
                        <td><input type="number" class="form-control unitPriceCalculation" name="unitPrice[]" value="${parseFloat(product.price).toFixed(2)}" required></td>
                        <td><input type="text" class="form-control totalPrice" name="priceTotal[]" readonly></td>
                        <td><button type="button" class="btn btn-danger rowDelete"><i class="mdi mdi-trash-can-outline"></i></button></td>
                    </tr>
                `);
                $("#myTable tbody").append(row);
            }

            $('#select-quotations').on('change', function() {
                quotationId = $(this).val();
            });

            $('#select-products').on('change', function() {
                if (!quotationId) { alert('Please select a quotation first!'); return; }
                const product = $(this).find(':selected').data('item');

                const duplicate = $('#myTable tbody .tableRow').filter(function() {
                    return $(this).data('product') == product.id;
                }).length;

                if (duplicate) { alert('This product is already added.'); return; }

                addRow(product);
            });

            $(document).on('click', '.rowDelete', function() {
                $(this).closest('.tableRow').remove();
                if ($('#myTable tbody .tableRow').length === 0) $('#empty-row').removeClass('d-none');
                calculateTotal();
            });

            $("#myTable").on("input", ".qtyCalculation, .unitPriceCalculation", function() {
                const row = $(this).closest('tr');
                const qty = parseFloat(row.find('.qtyCalculation').val()) || 0;
                const price = parseFloat(row.find('.unitPriceCalculation').val()) || 0;
                row.find('.totalPrice').val((qty * price).toFixed(2));
                calculateTotal();
            });

            function calculateTotal() {
                let totalQty = 0, totalPrice = 0;
                $("#myTable tbody .tableRow").each(function() {
                    totalQty += parseFloat($(this).find('.qtyCalculation').val()) || 0;
                    totalPrice += parseFloat($(this).find('.totalPrice').val()) || 0;
                });
                $('#total-quantity').text(totalQty);
                $('#total-price').text(totalPrice.toLocaleString());
                $('#totalQuantity').val(totalQty);
                $('#calculatedPrice').val(totalPrice);
            }
        });
    </script>
@endsection
