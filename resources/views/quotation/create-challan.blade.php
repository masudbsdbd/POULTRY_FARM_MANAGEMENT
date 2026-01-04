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
                            <h4>{{ isset($quotationsInfo) ? 'Quotation: ' . $quotationsInfo->title : '' }}</h4>
                        </div>
                        <form
                            action="{{ isset($challan) ? route('challan.update', $challan->id) : route('challan.store') }}"
                            method="POST">
                            @csrf
                            <input id="quotation_id" value="{{ $quotationsInfo->id }}" type="hidden" name="quotation_id">
                            <div class="row">
                                <div class="mb-3 col-lg-4">
                                    <label class="form-label">Work Date</label>
                                    <input type="text" id="datetime-datepicker" class="form-control"
                                        placeholder="Work Date"
                                        {{-- value="{{ now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}" --}}
                                        value="{{ isset($challan) ? $challan->challan_date : now()->setTimezone('Asia/Dhaka')->format('Y-m-d H:i') }}"
                                        name="challan_date" required>
                                </div>

                                <div class="col-md-4">
                                   <div class="mb-3">
                                    <label for="invoice_number" class="form-label">Work Number</label>
                                    <input type="text" id="invoice_number" class="form-control" placeholder="Work number"
                                        name="challan_number" required
                                        value="{{ isset($challan) ? $challan->challan_number : $challanNumber }}" readonly>
                                </div>
                                </div> <!-- end col -->
                                {{-- @dump($quotationItems); --}}
                                <div class="col-md-4">
                                    <p class="mb-1 fw-bold text-muted">Select Product</p>
                                    <select data-buildings="{{ json_encode($buildings) }}" data-floors="{{ json_encode($floors) }}" id="select-products" class="form-control" data-toggle="select2"
                                        data-width="100%">
                                        <option value="">Select</option>
                                        @foreach ($quotationItems as $item)
                                            @php
                                                $item->product->name;
                                            @endphp
                                            <option value="{{ $item->id }}" data-item="{{ $item }}">
                                                {{ $item->product->name; }} {{ $item->qty - $item->used_qty }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> <!-- end col -->

                                <div class="col-lg-12">
                                    <h4 class="header-title">Purchase List</h4>
                                    <div id="deleted-product">
                                    </div>

                                    <div class="table-responsive">
                                        <table id="myTable" class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Building</th>
                                                    <th>Floor</th>
                                                    <th>Qty</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if (isset($challanItems) && !$challanItems->isEmpty())
                                                    @foreach ($challanItems as $item)
                                                        @php
                                                            $findProduct = App\Models\QuotationItem::where("quotation_id", $challan->quotation_id)->where("product_id", $item->product_id)->first();
                                                            // dd($findProduct->used_qty);
                                                            $floorBuilding = App\Models\FloorInfo::where("id", $item->floor_id)->first();
                                                            $currentFloors = App\Models\Building::with("floor")->where("id", $floorBuilding->building_id)->first()->floor;
                                                        @endphp
                                                        <tr class="tableRow">
                                                            <td>
                                                                <input type="hidden" name="products[]"
                                                                    value="{{ $item->product_id }}">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Product" value="{{ $item->product->name }}"
                                                                    readonly required>
                                                                 <textarea readonly style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">{{ $item->product->description }}</textarea>
                                                            </td>
                                                            <td>
                                                                <select class="form-select buildingSelect" name="buildings[]" required>
                                                                    <option value="">Select Floor</option>
                                                                    @foreach ($buildings as $building)
                                                                        <option data-floors='{{ json_encode($building->floor) }}' value="{{ $building->id }}"
                                                                            {{ isset($floorBuilding->building_id) && $floorBuilding->building_id == $building->id ? 'selected' : '' }}>
                                                                            {{ $building->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-select floorSelect" name="floors[]" required>
                                                                    <option value="">Select Floor</option>
                                                                    @foreach ($currentFloors as $floor)
                                                                        <option value="{{ $floor->id }}"
                                                                            {{ isset($item->floor_id) && $item->floor_id == $floor->id ? 'selected' : '' }}>
                                                                            {{ $floor->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control qtyCalculation"
                                                                    placeholder="Qty" data-available-qty="{{ intval($item->quantity) + (intval($findProduct->qty) -  + intval($findProduct->used_qty))  }}" name="qty[]"
                                                                    value="{{ $item->quantity }}" autocomplete="off"
                                                                    required>
                                                            </td>
                                                            <td>
                                                                <div class="input-group input-group-merge">
                                                                    <input type="text"
                                                                        class="form-control productPrice unitPriceCalculation"
                                                                        placeholder="Unit Price" name="unitPrice[]"
                                                                        value="{{ showAmount($item->unit_price, 2, false) }}"
                                                                        autocomplete="off" readonly required>
                                                                        <div class="input-group-text">Tk</div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control totalPrice"
                                                                    name="priceTotal[]"
                                                                    value="{{ showAmount($item->total, 2, false) }}"
                                                                    placeholder="Total" readonly>
                                                            </td>
                                                            {{-- @dd($item) --}}
                                                            <td>
                                                                <button data-product-id="{{ $item->product_id }}" type="button"
                                                                    class="btn btn-danger waves-effect waves-light rowDelete"><i
                                                                        class="mdi mdi-trash-can-outline"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                {{-- <tr id="empty-row"
                                                    class="{{ isset($purchase_items) && !$purchase_items->isEmpty() ? 'd-none' : '' }}">
                                                    <td colspan="6" class="text-center">
                                                        <h4 class="my-3">Insert Products</h4>
                                                    </td>
                                                </tr> --}}
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
                                        class="btn btn-primary waves-effect waves-light">{{ isset($challan) ? 'Update Work Progress' : 'Add Work Progress' }}</button>
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

            function addTableRow(productData, buildings) {
                $('#empty-row').addClass('d-none');

                console.log('building2', buildings);
                

                // let buildingOptions = `<option value="">Select Floor</option>`;
                // buildings.forEach(buildding => {
                //     buildingOptions += `<option data-floors="${buildding.floors}" value="${buildding.id}">${buildding.name}</option>`;
                // });

                 let buildingOptions = `<option value="">Select Building</option>`;
                buildings.forEach(building => {
                    buildingOptions += `<option data-floors='${JSON.stringify(building.floor)}' value="${building.id}">
                        ${building.name}
                    </option>`;
                });

               

            let row = $(`
                <tr class="tableRow">
                    <td>
                        <input type="hidden" name="products[]" value="${productData.product_id}">
                        <input type="text" class="form-control" placeholder="Product" value="${productData.product.name}" readonly required>
                         <textarea readonly style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">${productData.product.description}</textarea>
                    </td>
                    <td>
                        <select class="form-select buildingSelect" name="buildings[]" required>
                            ${buildingOptions}
                        </select>
                    </td>
                    <td>
                        <select class="form-select floorSelect" name="floors[]" required>
                            <option value="">Select Floor</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control qtyCalculation" placeholder="Qty" value="${parseFloat(productData.qty) - parseFloat(productData.used_qty)}" data-available-qty="${parseFloat(productData.qty) - parseFloat(productData.used_qty)}" name="qty[]" autocomplete="off" required>
                    </td>
                    <td>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control productPrice unitPriceCalculation" readOnly
                                placeholder="Unit Price" name="unitPrice[]" value="${parseFloat(productData.unit_price).toFixed(2)}" autocomplete="off" required>
                            <div class="input-group-text">Tk</div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control totalPrice" name="priceTotal[]" value="${parseFloat((productData.unit_price * (parseFloat(productData.qty) - parseFloat(productData.used_qty)))).toFixed(2)}" placeholder="Total" readonly>
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


            $(document).on('change', '.buildingSelect', function () {
                let floors = $(this).find(':selected').data('floors');
                let floorSelect = $(this).closest('tr').find('.floorSelect');

                floorSelect.empty(); // remove old options
                floorSelect.append(`<option value="">Select Floor</option>`);

                if (floors && floors.length > 0) {
                    floors.forEach(floor => {
                        floorSelect.append(`<option value="${floor.id}">${floor.name}</option>`);
                    });
                }
            });


            $(document).on("input", "[name='qty[]']", function () {
                let qty = parseInt($(this).val(), 10) || 0;
                let availableQty = parseInt($(this).data("available-qty"), 10) || 0;

                if (qty > availableQty) {
                    alert("You can't add more than " + availableQty + " qty.");
                    $(this).val(availableQty);
                }
            });

            $(document).on("change", "[name='qty[]']", function () {
                let qty = parseInt($(this).val(), 10) || 0;
                let availableQty = parseInt($(this).data("available-qty"), 10) || 0;

                if (qty < 0) {
                    alert("You can't add less then 0");
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
                
                const buildings = $(this).data('buildings');
                console.log('building', buildings);
                productData = $(this).find('option:selected').data('item');
                // console.log('sadi', productData);
                let availableQty = productData ? (productData.qty - productData.used_qty) : 0;
                if(availableQty <= 0){
                    alert("You Don't have enough product to add");
                    return;
                }

                const isDuplicate = checkDuplicateProduct(productData.product_id);
                if (isDuplicate) {
                    alert('This product is already added to the table.');
                    return;
                }

                addTableRow(productData, buildings);
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
