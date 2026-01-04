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
                            action="{{ isset($approvalInfo) ? route('invoice.update', $approvalInfo->id) : route('approval.store') }}"
                            method="POST" enctype="multipart/form-data">
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
                                        <label for="approvalNumber" class="form-label">Invoice Number</label>
                                        <input type="text" id="approvalNumber" name="approval_number" class="form-control" placeholder="Enter title" readonly
                                            value="{{ old('invoice_number', isset($approvalInfo) ? $approvalInfo->invoice_number : $approvalNumber) }}">
                                    </div>
                                <div class="col-md-4">
                                    {{-- @dd($approvalItems["15"]) --}}
                                    <p class="mb-1 fw-bold text-muted">Select Product</p>
                                    <select id="select-products" class="form-control" data-toggle="select2"
                                        data-width="100%">
                                        <option value="">Select</option>
                                        @foreach ($approvalItems as $productId => $item)
                                            @php
                                                $productId;
                                               $quotationProdInfo = \App\Models\Product::where('id', $productId)->first();
                                            @endphp
                                            <option value="{{ $productId }}" data-item="{{ $item }}">
                                                {{ $quotationProdInfo->name; }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> <!-- end col -->
                                {{-- @dd($approvalItems) --}}
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
                                                    <th style="width: 10%;">notes</th>
                                                    <th style="width: 15%;">Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @if(isset($approvalItems) && !$approvalItems->isEmpty())
                                                    @foreach($approvalItems as $floorId => $items)
                                                        <tr class="tableRow bg-light">
                                                            <td colspan="6" class="fw-bold text-center">
                                                                @php
                                                                    $floor = App\Models\FloorInfo::with('building')->where("id", $floorId)->first() ?? 'Unknown floor';
                                                                @endphp
                                                                Building: {{ $floor->building->name }} | Floor: {{ $floor->name }}
                                                            </td>
                                                        </tr>

                                                        @foreach($items as $item)
                                                            <tr class="tableRow">
                                                                <td>
                                                                    <input type="hidden" name="products[]" value="{{ $item['product_id'] }}">
                                                                    <input type="hidden" name="floor_id[]" value="{{ $item['floor_id'] }}">
                                                                    <textarea readonly style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">{{ $item['product_name'] }}</textarea>
                                                                </td>
                                                                <td>
                                                                    <input placeholder="notes" type="text"
                                                                        class="form-control description"
                                                                        name="item_notes[]"
                                                                        value="">
                                                                </td>
                                                                <td>
                                                                    <input type="number"
                                                                        class="form-control qtyCalculation"
                                                                        placeholder="Qty"
                                                                        data-available-qty="{{ $item['pending_qty'] }}"
                                                                        name="qty[]"
                                                                        value="{{ $item['pending_qty'] }}"
                                                                        autocomplete="off" required>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @endif --}}

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
                                        class="btn btn-primary waves-effect waves-light">Create Approval</button>
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


            $('#select-products').on('change', function() {
                let productArray = $(this).find('option:selected').data('item'); // এটা array of floors
                if (!productArray || productArray.length === 0) {
                    alert("No floor-wise data found for this product.");
                    return;
                }

                let productId = productArray[0].product_id;
                let productName = productArray[0].product_name;

                // ডুপ্লিকেট চেক
                if (isDuplicateProduct(productId)) {
                    alert('This product is already added.');
                    return;
                }

                addProductSection(productArray, productId, productName);
            });

            function isDuplicateProduct(productId) {
                return $(`.product-section[data-product-id="${productId}"]`).length > 0;
            }

            function addProductSection(productArray, productId, productName) {
                $('#empty-row').addClass('d-none');

                // 🔹 পুরো product section wrap করার জন্য div/tbody তৈরি
                let section = $(`<tbody class="product-section" data-product-id="${productId}"></tbody>`);

                // 🔹 Header Row
                let headerRow = $(`
                    <tr class="bg-primary text-white">
                        <td colspan="4" class="fw-bold text-center text-white">
                            Product: ${productName}
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-light btn-sm text-danger productDelete" data-product-id="${productId}">
                                <i class="mdi mdi-delete"></i> Remove Product
                            </button>
                        </td>
                    </tr>
                `);
                section.append(headerRow);

                // 🔹 প্রতিটা floor-wise row
                productArray.forEach(item => {
                    console.log('item', item.floor.name);
                    let row = $(`
                        <tr class="floor-row">
                            <td>
                                <input type="hidden" name="products[]" value="${item.product_id}">
                                <input type="hidden" name="floor_id[]" value="${item.floor_id}">
                                <input type="text" class="form-control" value="${item.product_name}" readonly>
                            </td>
                            <td>
                                Building: ${item.floor.building.name} </br> Floor: ${item.floor.name}
                            </td>
                            <td>
                                <input type="number" class="form-control qtyCalculation"
                                    name="qty[]" value="${item.pending_qty}"
                                    data-available-qty="${item.pending_qty}"
                                    required>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="notes[]" placeholder="Notes">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm singleRowDelete">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    section.append(row);
                });

                // 🔹 Section append করি টেবিলে
                $("#myTable").append(section);
            }

            // 🔹 পুরো product section delete
            $(document).on('click', '.productDelete', function() {
                let productId = $(this).data('product-id');
                if (confirm("Are you sure you want to remove this product and all its floors?")) {
                    $(`.product-section[data-product-id="${productId}"]`).remove();
                }
            });

            // 🔹 শুধু একক floor row delete
            $(document).on('click', '.singleRowDelete', function() {
                $(this).closest('tr').remove();
            });




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

            $("#myTable tbody").on("change", ".qtyCalculation", function() {
                let productQty = $(this).val();
                let unitPrice = $(this).closest('tr').find('.productPrice').val();
                let sum = productQty * unitPrice;
                $(this).closest('tr').find('.totalPrice').val(sum);
                calculateTotal();
            });
        });
    </script>
@endsection
