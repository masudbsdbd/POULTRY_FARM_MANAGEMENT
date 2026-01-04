@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => isset($pageTitle) ? $pageTitle : '', 'subtitle' => 'Purchase'])

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                            {{-- <h4>{{ isset($quotationsInfo) ? 'Quotation: ' . $quotationsInfo->title : '' }}</h4> --}}
                        </div>
                        <form
                            {{-- action="{{ isset($invoiceInfo) ? route('invoice.update', $invoiceInfo->id) : route('payment.store') }}" --}}
                            action="{{ route('payment.store') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{ $invoiceInfo->id }}">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                <label for="payment_date" class="form-label">Payment Date</label>
                                <input type="date" id="payment_date" name="payment_date" class="form-control" required
                                    value="{{ old('payment_date', $paymentInfo->payment_date ?? now()->setTimezone('Asia/Dhaka')->format('Y-m-d')) }}">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" name="payment_method" required data-toggle="select2">
                                    <option value="">Choose one</option>
                                    <option selected value="1">Cash</option>
                                    <option value="2">Bank</option>
                                    <option value="3">Cheque</option>
                                </select>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="reference_no" class="form-label">Reference</label>
                                <input type="text" id="reference_no" name="reference_no" class="form-control" placeholder="Reference"
                                    value="{{ old('reference_no', $paymentInfo->reference_no ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold text-muted">Select Product</p>
                                <select data-floors="{{ json_encode($floors) }}" id="select-products" class="form-control" data-toggle="select2"
                                    data-width="100%">
                                    <option value="">Select</option>
                                    @foreach ($invoiceItems as $item)
                                        @php
                                            $item->product->name;
                                        @endphp
                                        <option value="{{ $item->id }}" data-item="{{ $item }}">
                                            {{ $item->product->name; }} {{ ($item->quantity - $item->paid_qty) > 0 ? $item->quantity - $item->paid_qty : 0 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->
                                
                             </div> <!-- end col -->
                                

                                <div class="col-lg-12">
                                    {{-- <h4 class="header-title">Purchase List</h4> --}}
                                    <div id="deleted-product">
                                    </div>

                                    <div class="table-responsive">
                                        <table id="myTable" class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40%;">Product Name</th>
                                                    <th style="width: 10%;">notes</th>
                                                    <th style="width: 15%;">Qty</th>
                                                    <th style="width: 10%;">Unit Price</th>
                                                    <th style="width: 10%;">Total</th>
                                                    <th style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoiceItems as $item)
                                                    @php
                                                        $availableQty = max(0, $item->quantity - $item->paid_qty);
                                                    @endphp
                                                    @if ($availableQty > 0.001)
                                                        <tr class="tableRow">
                                                            <td>
                                                                <input type="hidden" name="products[]" value="{{ $item->product_id }}">
                                                                <input type="text" class="form-control" value="{{ $item->product->name }}" readonly required>
                                                                <textarea readonly style="margin-top: 5px;" class="form-control" rows="2">
                                                                    {{ $item->product->description }}
                                                                </textarea>
                                                            </td>
                                                            <td>
                                                                <input placeholder="notes" type="text" class="form-control description" name="item_notes[]" value="">
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    class="form-control qtyCalculation"
                                                                    placeholder="Qty"
                                                                    value="{{ $availableQty }}"
                                                                    data-available-qty="{{ $availableQty }}"
                                                                    name="qty[]"
                                                                    autocomplete="off"
                                                                    required>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-merge">
                                                                    <input type="number"
                                                                        class="form-control productPrice unitPriceCalculation"
                                                                        readonly
                                                                        placeholder="Unit Price"
                                                                        name="unitPrice[]"
                                                                        value="{{ $item->unit_price }}"
                                                                        autocomplete="off"
                                                                        required>
                                                                    <div class="input-group-text">Tk</div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control totalPrice"
                                                                    name="priceTotal[]"
                                                                    value="{{ $item->unit_price * $availableQty }}"
                                                                    placeholder="Total"
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <button data-product-id="{{ $item->product_id }}"
                                                                        type="button"
                                                                        class="btn btn-danger waves-effect waves-light rowDelete">
                                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
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
                                                            value="{{ isset($totalProductQty) ? $totalProductQty : '' }}">
                                                        <h4>Total Quantity: <span
                                                                id="total-quantity">{{ isset($totalProductQty) ? $totalProductQty : 0 }}</span>
                                                        </h4>
                                                    </td>
                                                    <td>
                                                        <input id="calculatedPrice" type="hidden" name="calculatedPrice"
                                                            value="{{ isset($totalProductPrice) ? $totalProductPrice : '' }}">
                                                        <h4>Total Price: <span
                                                                id="total-price">{{ isset($totalProductPrice) ? number_format($totalProductPrice) : 0 }}</span>
                                                        </h4>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <p class="mb-1 fw-bold text-muted">Notes</p>
                                        <textarea class="form-control" id="notes" placeholder="notes" rows="5"
                                            name="notes">{{ isset($incomeList) ? $incomeList->details : '' }}</textarea>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">Create Payment</button>
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
            let productData;
            let count = 1;
            let originalTotalPrice = 0;
            let getBankOnEditPage = $('#bank_id').val();

            setTimeout(() => {
                calculateTotal();
            }, 1000);

            function addTableRow(productData, floors) {
                $('#empty-row').addClass('d-none');

            
                let floorOptions = `<option value="">Select Floor</option>`;
                floors.forEach(floor => {
                    floorOptions += `<option value="${floor.id}">${floor.name}</option>`;
                });

            let row = $(`
                <tr class="tableRow">
                    <td>
                        <input type="hidden" name="products[]" value="${productData.product_id}">
                        <input type="text" class="form-control" placeholder="Product" value="${productData.product.name}" readonly required>
                        <textarea readonly style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">${productData.product.description}</textarea>
                    </td>
                    <td><input placeholder="notes" type="text" class="form-control description" name="item_notes[]" value=""></td>
                    <td>
                        <input type="number" class="form-control qtyCalculation" placeholder="Qty" value="${parseFloat(productData.quantity) - parseFloat(productData.paid_qty) > 0 ? parseFloat(productData.quantity) - parseFloat(productData.paid_qty) : 0}" data-available-qty="${parseFloat(productData.quantity) - parseFloat(productData.paid_qty)}" name="qty[]" autocomplete="off" required>
                    </td>
                    <td>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control productPrice unitPriceCalculation" readOnly
                                placeholder="Unit Price" name="unitPrice[]" value="${parseFloat(productData.unit_price).toFixed(2)}" autocomplete="off" required>
                            <div class="input-group-text">Tk</div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control totalPrice" name="priceTotal[]" value="${parseFloat((productData.unit_price * (parseFloat(productData.quantity) - parseFloat(productData.paid_qty)))).toFixed(2)}" placeholder="Total" readonly>
                    </td>
                    <td>
                        <button  data-product-id="${productData.product_id}" type="button" class="btn btn-danger waves-effect waves-light rowDelete">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </button>
                    </td>
                </tr>
            `);

            $("#myTable tbody").append(row);

            // এখন select box-এ option গুলো বসাই
            let select = row.find('.warehouse');

                
                count++;
            }


            // $(document).on("input", "[name='qty[]']", function () {
            //     let qty = parseInt($(this).val(), 10) || 0;
            //     let availableQty = parseInt($(this).data("available-qty"), 10) || 0;

            //     if (qty > availableQty) {
            //         alert("You can't add more than " + availableQty + " qty.");
            //         $(this).val(availableQty);
            //     }
            // });

            $(document).on("input", "[name='qty[]']", function () {
                let $row = $(this).closest("tr");
                let qty = parseInt($(this).val(), 10) || 0;
                let availableQty = parseInt($(this).data("available-qty"), 10) || 0;

                if (qty > availableQty) {
                    alert("You can't add more than " + availableQty + " qty.");
                    qty = availableQty;
                    $(this).val(qty);
                }

                // unit price বের করো
                let unitPrice = parseFloat($row.find(".productPrice").val()) || 0;

                // total হিসাব করো
                let total = (qty * unitPrice).toFixed(2);

                // row এর totalPrice input এ বসাও
                $row.find(".totalPrice").val(total);
            });

            
            

            $(document).on("change", "[name='qty[]']", function () {
                let qty = parseInt($(this).val(), 10) || 0;
                let availableQty = parseInt($(this).data("available-qty"), 10) || 0;

                if (qty <= 0) {
                    alert("You can't add less then 0");
                    $(this).val(availableQty);
                    window.location.reload();
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
                const floors = $(this).data('floors');
                console.log('sadi', productData);
                let availableQty = productData ? (productData.quantity - productData.paid_qty) : 0;
                if(availableQty <= 0){
                    alert("You Don't have enough product to add");
                    return;
                }

                const isDuplicate = checkDuplicateProduct(productData.product_id);
                if (isDuplicate) {
                    alert('This product is already added to the table.');
                    return;
                }

                addTableRow(productData, floors);
                calculateTotal();
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
                let productId = $(this).data('product-id');
                $("#deleted-product").append(`<input type="hidden" name="deleltedProduct[]" value="${productId}">`)
                // console.log('deleteproduct', productId);
                const rowCount = $("#myTable tbody tr").length - 1;
                // console.log(rowCount);
                if (rowCount == 1) {
                    $('#empty-row').removeClass('d-none');
                }
                $(this).closest('.tableRow').remove();
                calculateTotal();
            });

            $("#myTable tbody").on("change", ".qtyCalculation", function() {
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
