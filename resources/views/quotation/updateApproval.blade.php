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
                            action="{{ route('approval.update.db', $approvalInfo->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input id="quotation_id" value="{{ $quotationsInfo->id }}" type="hidden" name="quotation_id">
                            <div class="row justify-content-center">
                            
                        </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-lg-10 mb-3">
                                        <label for="approval_date" class="form-label">Approval Date</label>
                                        <input type="date" id="approval_date" name="approval_date" class="form-control" required
                                            value="{{ old('approval_date', $approvalInfo->approval_date ?? now()->setTimezone('Asia/Dhaka')->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col-lg-10 mb-3">
                                        <label for="approvalNumber" class="form-label">Approval Number</label>
                                        <input type="text" id="approvalNumber" name="approval_number" class="form-control" placeholder="Enter title" readonly
                                            value="{{ old('approval_number', isset($approvalInfo) ? $approvalInfo->approval_number : $approvalNumber) }}">
                                    </div>
                                    
                            
                                    {{-- @dump($quotationItems); --}}
                                    {{-- <div class="col-md-10">
                                        <p class="mb-1 fw-bold text-muted">Select Product</p>
                                        <select id="select-products" class="form-control" data-toggle="select2"
                                            data-width="100%">
                                            <option value="">Select</option>
                                            @foreach ($quotationItems as $item)
                                                @php
                                                    $item->product->name;
                                                @endphp
                                                <option value="{{ $item->id }}" data-item="{{ $item }}">
                                                    {{ $item->product->name; }} {{ ($item->used_qty - $item->invoiced_qty) > 0 ? $item->used_qty - $item->invoiced_qty : 0 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> <!-- end col --> --}}
                                   
                                </div>
                                <div class="col-md-4">
                                    {{-- <div class="col-md-3 "> --}}
                                        <div class="mb-3">
                                            <label for="example-select" class="form-label">Project diagram</label>
                                            <div class="form-group">
                                                <div class="image-upload">
                                                    <div class="thumb">
                                                        <div class="avatar-preview" style="height: 290px;">
                                                            <div class="profilePicPreview"
                                                                style="height: 290px; background-image: url({{ isset($approvalInfo) ? (isset($approvalInfo->diagram_image) ? asset('uploads/approval/' . $approvalInfo->diagram_image) : asset('uploads/default-picker.png')) : asset('uploads/default-picker.png') }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                                                <button type="button" class="remove-image"><i
                                                                        class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="avatar-edit">
                                                            <input type="file" class="profilePicUpload"
                                                                name="diagram_image" id="profilePicUpload1"
                                                                accept=".png, .jpg, .jpeg">
                                                            <label for="profilePicUpload1"
                                                                class="btn bg--primary text-dark">@lang('Browse Image')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- </div> --}}
                                </div>
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
                                                    {{-- <th style="width: 10%;">notes</th> --}}
                                                    <th style="width: 15%;">Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    @foreach($approvalItems as $floorId => $items)
                                                    {{-- @dd($items) --}}
                                                        {{-- Floor Header Row --}}
                                                        <tr class="tableRow bg-light">
                                                            <td colspan="6" class="fw-bold text-center">
                                                                @php
                                                                    $floor = App\Models\FloorInfo::with('building')->where("id", $floorId)->first();
                                                                    $floorName = $floor->name ?? 'Unknown floor';
                                                                    $buildingName = $floor->building->name ?? 'Unknown building';
                                                                    // dd($floor);
                                                                @endphp
                                                                {{ $buildingName }} | {{ $floorName }}
                                                            </td>
                                                        </tr>

                                                        {{-- Products for this floor --}}
                                                        @foreach($items as $item)
                                                        {{-- @dd($item) --}}
                                                            {{-- @php
                                                                $findProduct = App\Models\QuotationItem::where("quotation_id", $approvalInfo->quotation_id)
                                                                    ->where("product_id", $item['product_id'])
                                                                    ->first();
                                                            @endphp --}}
                                                            <tr class="tableRow">
                                                                <td>
                                                                    <input type="hidden" name="products[]" value="{{ $item['product_id'] }}">
                                                                    <input type="hidden" name="floor_id[]" value="{{ $item['floor_id'] }}">
                                                                    <textarea readonly style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">{{ $item->product->description }}</textarea>
                                                                </td>
                                                                {{-- <td>
                                                                    <input placeholder="notes" type="text"
                                                                        class="form-control description"
                                                                        name="item_notes[]"
                                                                        value="">
                                                                </td> --}}
                                                                <td>
                                                                    <input type="number"
                                                                        class="form-control qtyCalculation"
                                                                        placeholder="Qty"
                                                                        data-available-qty="{{ $item->available_qty }}"
                                                                        name="qty[]"
                                                                        value="{{ $item['approved_qty'] }}"
                                                                        autocomplete="off" required>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach

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
                                    
                                    <div class="col-md-12 mt-3">
                                        <p class="mb-1 fw-bold text-muted">Notes</p>
                                        <textarea class="form-control" id="notes" placeholder="notes" rows="5"
                                            name="notes">{{ isset($approvalInfo) ? $approvalInfo->notes : '' }}</textarea>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">Update Approval</button>
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

                if (qty < 0) {
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
                console.log('sadi', productData);
                let availableQty = productData ? (productData.qty - productData.invoiced_qty) : 0;
                if(availableQty <= 0){
                    alert("You Don't have enough product to add");
                    return;
                }

                const isDuplicate = checkDuplicateProduct(productData.product_id);
                if (isDuplicate) {
                    alert('This product is already added to the table.');
                    return;
                }

                addTableRow(productData);
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
