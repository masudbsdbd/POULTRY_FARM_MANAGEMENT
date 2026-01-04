@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/selectize/dist/css/selectize.bootstrap3.css', 'node_modules/mohithg-switchery/dist/switchery.min.css', 'node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/multiselect/css/multi-select.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', [
            'title' => isset($pageTitle) ? $pageTitle : '',
            'subtitle' => 'Purchase',
        ])

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                            <h4>{{ isset($quotationsInfo) ? 'Quotation: ' . $quotationsInfo->title : '' }}</h4>
                        </div>
                        <form
                            action="{{ isset($invoiceInfo) ? route('invoice.update', $invoiceInfo->id) : route('invoice.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <input id="quotation_id" value="{{ $quotationsInfo->id }}" type="hidden" name="quotation_id">
                            <div class="row justify-content-center">

                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <div class="col-lg-10 mb-3">
                                        <label for="invoice_date" class="form-label">Invoice Date</label>
                                        <input type="date" id="invoice_date" name="invoice_date" class="form-control"
                                            required
                                            value="{{ old('invoice_date', $invoiceInfo->invoice_date ?? now()->setTimezone('Asia/Dhaka')->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col-md-10 mt-3">
                                        <p class="mb-1 fw-bold text-muted">Select Product</p>
                                        <select id="select-products" class="form-control" data-toggle="select2"
                                            data-width="100%">
                                            <option value="">Select</option>
                                            @foreach ($quotationItems as $item)
                                                @php
                                                    $item->product->name;
                                                @endphp
                                                <option value="{{ $item->id }}" data-item="{{ $item }}">
                                                    {{ $item->product->name }}
                                                    {{ $item->approved_qty - $item->invoiced_qty > 0 ? $item->approved_qty - $item->invoiced_qty : 0 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> <!-- end col -->


                                </div>
                                <div class="col-md-6">
                                    <div class="col-lg-10 mb-3">
                                        <label for="invoiceNumber" class="form-label">Invoice Number</label>
                                        <input type="text" id="invoiceNumber" name="invoice_number" class="form-control"
                                            placeholder="Enter title" readonly
                                            value="{{ old('invoice_number', isset($invoiceInfo) ? $invoiceInfo->invoice_number : $invoiceNumber) }}">
                                    </div>


                                    <div class="col-lg-10">
                                        <label for="vat" class="form-label">Vat %</label>
                                        <input type="number" id="vat" name="vat" class="form-control"
                                            placeholder="Enter Vat"
                                            value="{{ old('vat', isset($invoiceInfo) ? $invoiceInfo->vat : 0) }}">
                                    </div>
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
                                                <th style="width: 35%;">Product Name</th>
                                                <th style="width: 10%;">Floors</th>
                                                <th style="width: 10%;">notes</th>
                                                <th style="width: 10%;">Qty</th>
                                                <th style="width: 10%;">Unit Price</th>
                                                <th style="width: 10%;">Percentage %</th>
                                                <th style="width: 10%;">Total</th>
                                                <th style="width: 10%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if (isset($invoiceItems) && !$invoiceItems->isEmpty())
                                                @foreach ($invoiceItems as $item)
                                                    @php
                                                        $findProduct = App\Models\QuotationItem::where(
                                                            'quotation_id',
                                                            $invoiceInfo->quotation_id,
                                                        )
                                                            ->where('product_id', $item->product_id)
                                                            ->first();
                                                        $invFloors = json_decode($item->floors);

                                                        // $availableQty = max(
                                                        //     0,
                                                        //     $item->approved_qty - $item->invoiced_qty,
                                                        // );
                                                        $tempQuotId = $invoiceInfo->quotation_id;
                                                        $nnnn = App\Models\QuotationItem::with(['product'])
                                                            ->where('quotation_id', $invoiceInfo->quotation_id)
                                                            ->where('product_id', $item->product_id)
                                                            ->get()
                                                            ->each(function ($item) use ($tempQuotId) {
                                                                // Manual query for approval items with floors
                                                                $approvalItems = App\Models\approval_items::with('floor:id,name')
                                                                    ->where('product_id', $item->product_id)
                                                                    ->whereHas('approval', function ($query) use (
                                                                        $tempQuotId,
                                                                    ) {
                                                                        $query->where('quotation_id', $tempQuotId);
                                                                    })
                                                                    ->get();

                                                                $floors = $approvalItems
                                                                    ->pluck('floor')
                                                                    ->unique()
                                                                    ->values();

                                                                // Directly add floors as property to the model object
                                                                $item->floors = $floors;
                                                            });
                                                        $floorNames = json_decode($nnnn[0]->floors);
                                                    @endphp

                                                    <tr class="tableRow">
                                                        <td>
                                                            <input type="hidden" name="products[]"
                                                                value="{{ $item->product_id }}">
                                                            <input type="text" class="form-control" placeholder="Product"
                                                                value="{{ $item->product->name }}" readonly required>
                                                            <textarea readonly style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">{{ $item->product->description }}</textarea>
                                                        </td>
                                                        <td>
                                                            <select name="floors[{{ $item->product_id }}][]"
                                                                class="form-control select2-multiple" data-toggle="select2"
                                                                data-width="100%" multiple="multiple"
                                                                data-placeholder="Choose ...">
                                                                @foreach ($floorNames as $floor)
                                                                    @php
                                                                        $tempFloor = App\Models\FloorInfo::with('building')->where("id", $floor->id)->first();
                                                                       $nnnamme = $tempFloor->building->name . ' | ' . $tempFloor->name;
                                                                    @endphp
                                                                    <option
                                                                        {{ in_array($floor->id, $invFloors) ? 'selected' : '' }}
                                                                        value="{{ $floor->id }}">
                                                                        {{ $nnnamme }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input placeholder="notes" type="text"
                                                                class="form-control description" name="item_notes[]"
                                                                value="{{ $item->description }}"></td>
                                                        <td>
                                                            <input readonly type="text"
                                                                class="form-control dummyQtyCalculation"
                                                                placeholder="dummyDisplayQty"
                                                                value="{{ $item->display_qty }}" name="dummyDisplayQty[]"
                                                                data-available-qty="{{ intval($item->quantity) + (intval($findProduct->approved_qty) - intval($findProduct->invoiced_qty)) }}"
                                                                autocomplete="off" required>
                                                            <input type="hidden" class="form-control qtyCalculation"
                                                                placeholder="Qty"
                                                                data-available-qty="{{ intval($item->quantity) + (intval($findProduct->approved_qty) - intval($findProduct->invoiced_qty)) }}"
                                                                name="qty[]" value="{{ $item->quantity }}"
                                                                autocomplete="off" required>
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
                                                            <input readonly type="text"
                                                                class="form-control percentageCalculation"
                                                                placeholder="Percentage" value="{{ $item->percentage }}"
                                                                name="percentage[]" autocomplete="off" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control totalPrice"
                                                                name="priceTotal[]"
                                                                value="{{ showAmount($item->total, 2, false) }}"
                                                                placeholder="Total" readonly>
                                                        </td>
                                                        {{-- @dd($item) --}}
                                                        <td>
                                                            <button data-product-id="{{ $item->product_id }}"
                                                                type="button"
                                                                class="btn btn-danger waves-effect waves-light rowDelete"><i
                                                                    class="mdi mdi-trash-can-outline"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                {{-- ✅ New Invoice --}}
                                                @foreach ($quotationItems as $item)
                                                    @php
                                                        $availableQty = max(
                                                            0,
                                                            $item->approved_qty - $item->invoiced_qty,
                                                        );
                                                        $tempQuotId = $quotationsInfo->id;
                                                        $nnnn = App\Models\QuotationItem::with(['product'])
                                                            ->where('quotation_id', $quotationsInfo->id)
                                                            ->where('product_id', $item->product_id)
                                                            ->get()
                                                            ->each(function ($item) use ($tempQuotId) {
                                                                // Manual query for approval items with floors
                                                                $approvalItems = App\Models\approval_items::with('floor:id,name')
                                                                    ->where('product_id', $item->product_id)
                                                                    ->whereHas('approval', function ($query) use (
                                                                        $tempQuotId,
                                                                    ) {
                                                                        $query->where('quotation_id', $tempQuotId);
                                                                    })
                                                                    ->get();

                                                                $floors = $approvalItems
                                                                    ->pluck('floor')
                                                                    ->unique()
                                                                    ->values();

                                                                // Directly add floors as property to the model object
                                                                $item->floors = $floors;
                                                            });
                                                        $floors = $nnnn[0]->floors;
                                                        // dd($floors);

                                                      $tempFloor =  App\Models\FloorInfo::with('building')->where("id", $floors[0]->id)->first();

                                                    //   dd($tempFloor->building->name . ' | ' . $tempFloor->name);
                                                    @endphp
                                                    @if ($availableQty > 0)
                                                        <tr class="tableRow">
                                                            <td>
                                                                <input type="hidden" name="products[]"
                                                                    value="{{ $item->product_id }}">
                                                                <input type="text" class="form-control"
                                                                    value="{{ $item->product->name }}" readonly required>
                                                                <textarea readonly class="form-control mt-2" rows="2">{{ $item->product->description }}</textarea>
                                                            </td>
                                                            <td>
                                                                <select name="floors[{{ $item->product_id }}][]"
                                                                    class="form-control select2-multiple" multiple>
                                                                    @foreach ($floors as $floor)
                                                                    @php
                                                                        $tempFloor = App\Models\FloorInfo::with('building')->where("id", $floor->id)->first();
                                                                       $nnnamme = $tempFloor->building->name . ' | ' . $tempFloor->name;
                                                                    @endphp
                                                                        <option selected value="{{ $floor->id }}">
                                                                            {{ $nnnamme }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control description"
                                                                    name="item_notes[]" placeholder="notes"></td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control dummyQtyCalculation"
                                                                    placeholder="dummyDisplayQty"
                                                                    value="{{ $availableQty }}" name="dummyDisplayQty[]"
                                                                    data-available-qty="{{ $availableQty }}"
                                                                    autocomplete="off" required>
                                                                <input type="hidden" class="form-control qtyCalculation"
                                                                    name="qty[]" value="{{ $availableQty }}"
                                                                    data-available-qty="{{ $availableQty }}" required>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control productPrice unitPriceCalculation"
                                                                        name="unitPrice[]"
                                                                        value="{{ showAmount($item->unit_price, 2, false) }}"
                                                                        readonly>
                                                                    <div class="input-group-text">Tk</div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control percentageCalculation"
                                                                    placeholder="Percentage" value="100"
                                                                    name="percentage[]" autocomplete="off" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control totalPrice"
                                                                    name="priceTotal[]"
                                                                    value="{{ showAmount($item->unit_price * $availableQty, 2, false) }}"
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <button data-product-id="{{ $item->product_id }}"
                                                                    type="button" class="btn btn-danger rowDelete"><i
                                                                        class="mdi mdi-trash-can-outline"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endif
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

                                                {{-- <td>
                                                        <input id="commissionField" step="0.01" min="0"
                                                            max="100" type="number" class="form-control"
                                                            placeholder="Enter Commission (%)"
                                                            value="{{ isset($purchase) ? number_format($purchase->commission, 2, '.', '') : '' }}"
                                                            name="commission">
                                                    </td> --}}
                                                {{-- <td>
                                                        <input id="discountField" step="0.01" min="0"
                                                            type="number" class="form-control"
                                                            placeholder="Enter Discount"
                                                            value="{{ isset($purchase) ? number_format($purchase->discount, 2, '.', '') : '' }}"
                                                            name="discount">
                                                    </td> --}}
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <p class="mb-1 fw-bold text-muted">Notes</p>
                                    <textarea class="form-control" id="notes" placeholder="notes" rows="5" name="notes">{{ isset($incomeList) ? $incomeList->details : '' }}</textarea>
                                </div>
                            </div>

                            @if (isset($purchase))
                                {{-- <div class="col-lg-6">
                                        <h4 class="mt-3">Total Amount</h4>
                                        <div class="my-3"><label class="form-label">Payment Amount</label>
                                            <div class="input-group">
                                                <input id="totalAmount" type="number" step="0.01" min="0"
                                                    class="form-control rounded-0" placeholder="Enter Amount"
                                                    value="{{ isset($purchase) ? showAmount($purchase->total_price, 2, false) : '' }}"
                                                    readonly>
                                                <div class="input-group-text">Tk</div>
                                            </div>
                                        </div>
                                    </div> --}}
                            @else
                                {{-- <div class="col-lg-6">
                                        <h4>Payment Method and Type</h4>
                                        <div class="my-3"><label class="form-label">Payment Amount</label>
                                            <div class="input-group">

                                                <div class="col-lg-3 pe-0">
                                                    <select id="paymentMethod" class="form-select rounded-0"
                                                        id="example-select" name="payment_method" required>
                                                        <option value="1">Cash</option>
                                                        <option value="2">Bank</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-9">
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0"
                                                            class="form-control rounded-0" placeholder="Enter Amount"
                                                            name="balance">
                                                        <div class="input-group-text">Tk</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}


                                {{-- <div id="bankInfo" class="col-lg-12 d-none">
                                        <h4>Bank Information</h4>
                                        <div class="row my-3">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="example-Account" class="form-label">Select Bank</label>
                                                    <select id="bank_id" class="form-select rounded-0" name="bank_id">
                                                        <option value="">Select Bank</option>
                                                        @foreach ($banks as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-balance="{{ $item->balance }}">
                                                                {{ $item->account_no . ' - ' . $item->account_name . ' - ' . $item->bank_name . ' -  Balance: ' . showAmount($item->balance) . 'Tk' }}
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
                                                    <label for="example-withdrawer" class="form-label">Withdrawer
                                                        Name</label>
                                                    <input id="withdrawer_name" type="text" id="example-withdrawer"
                                                        class="form-control" name="withdrawer_name"
                                                        placeholder="Withdrawer Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                            @endif

                            <div class="text-end mt-3">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ isset($invoiceInfo) ? 'Update Invoice' : 'Add Invoice' }}</button>
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
    @vite(['resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js', 'resources/js/pages/form-advanced.init.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2-multiple').select2({
                placeholder: "Choose ...",
                width: '100%'
            });
            setTimeout(() => {
                calculateTotal();
            }, 1000);

            let productData;
            let count = 1;
            let originalTotalPrice = 0;
            let getBankOnEditPage = $('#bank_id').val();

            function initSelect2(row) {
                $(row).find('.select2-multiple').select2({
                    placeholder: "Choose ...",
                    width: '100%'
                });
            }

            function addTableRow(productData) {
                $('#empty-row').addClass('d-none');

                // console.log('sadi', );
                // let floorsInfo = productData.floors.map(item => item.floor);
                let floorsInfo = productData.floors;
                console.log('sadi-11', productData);

                let floorOptions = `<option value="">Select Floor</option>`;
                floorsInfo.forEach(floor => {
                    // console.log('sadi', floor);
                    floorOptions += `<option selected value="${floor.id}">${floor.name}</option>`;
                });

                let row = $(`
                <tr class="tableRow">
                    <td>
                        <input type="hidden" name="products[]" value="${productData.product_id}">
                        <input type="text" class="form-control" placeholder="Product" value="${productData.product.name}" readonly required>
                        <textarea readonly style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">${productData.product.description}</textarea>
                    </td>
                    <td>
                        <select name="floors[${productData.product_id}][]" class="form-control select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" data-placeholder="Choose ...">
                            ${floorOptions}
                        </select>
                    </td>
                    <td><input placeholder="notes" type="text" class="form-control description" name="item_notes[]" value=""></td>
                    <td>
                        <input type="text" class="form-control dummyQtyCalculation" placeholder="dummyDisplayQty" value="${parseFloat(productData.approved_qty) - parseFloat(productData.invoiced_qty) > 0 ? parseFloat(productData.approved_qty) - parseFloat(productData.invoiced_qty) : 0}" data-available-qty="${parseFloat(productData.approved_qty) - parseFloat(productData.invoiced_qty)}" name="dummyDisplayQty[]" autocomplete="off">
                        <input type="hidden" class="form-control qtyCalculation" placeholder="Qty" value="${parseFloat(productData.approved_qty) - parseFloat(productData.invoiced_qty) > 0 ? parseFloat(productData.approved_qty) - parseFloat(productData.invoiced_qty) : 0}" data-available-qty="${parseFloat(productData.approved_qty) - parseFloat(productData.invoiced_qty)}" name="qty[]" autocomplete="off" required>
                    </td>
                    <td>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control productPrice unitPriceCalculation" readOnly
                                placeholder="Unit Price" name="unitPrice[]" value="${parseFloat(productData.unit_price).toFixed(2)}" autocomplete="off" required>
                            <div class="input-group-text">Tk</div>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control percentageCalculation" placeholder="Percentage" value="100" name="percentage[]" autocomplete="off" required>
                    </td>
                    <td>
                        <input type="text" class="form-control totalPrice" name="priceTotal[]" value="${parseFloat((productData.unit_price * (parseFloat(productData.approved_qty) - parseFloat(productData.invoiced_qty)))).toFixed(2)}" placeholder="Total" readonly>
                    </td>
                    <td>
                        <button  data-product-id="${productData.product_id}" type="button" class="btn btn-danger waves-effect waves-light rowDelete">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </button>
                    </td>
                </tr>
            `);

                $("#myTable tbody").append(row);
                initSelect2(row);

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

            // $(document).on("input", "[name='qty[]']", function () {
            //     let $row = $(this).closest("tr");
            //     let qty = parseInt($(this).val(), 10) || 0;
            //     let availableQty = parseInt($(this).data("available-qty"), 10) || 0;

            //     if (qty > availableQty) {
            //         alert("You can't add more than " + availableQty + " qty.");
            //         qty = availableQty;
            //         $(this).val(qty);
            //     }

            //     // unit price বের করো
            //     let unitPrice = parseFloat($row.find(".productPrice").val()) || 0;

            //     // total হিসাব করো
            //     let total = (qty * unitPrice).toFixed(2);

            //     // row এর totalPrice input এ বসাও
            //     $row.find(".totalPrice").val(total);
            // });

            $(document).on("input", "[name='dummyDisplayQty[]']", function() {
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




            // $(document).on("change", "[name='qty[]']", function () {
            //     let qty = parseInt($(this).val(), 10) || 0;
            //     let availableQty = parseInt($(this).data("available-qty"), 10) || 0;

            //     if (qty <= 0) {
            //         alert("You can't add less then 0");
            //         $(this).val(availableQty);
            //         window.location.reload();
            //     }
            // });

            $(document).on("change", "[name='dummyDisplayQty[]']", function() {
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
                console.log('sadi', productData);
                let availableQty = productData ? (productData.qty - productData.invoiced_qty) : 0;
                if (availableQty <= 0) {
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
                $("#deleted-product").append(
                    `<input type="hidden" name="deleltedProduct[]" value="${productId}">`)
                // console.log('deleteproduct', productId);
                const rowCount = $("#myTable tbody tr").length - 1;
                // console.log(rowCount);
                if (rowCount == 1) {
                    $('#empty-row').removeClass('d-none');
                }
                $(this).closest('.tableRow').remove();
                calculateTotal();
            });

            // $("#myTable tbody").on("change", ".qtyCalculation", function() {
            //     let productQty = $(this).val();
            //     let unitPrice = $(this).closest('tr').find('.productPrice').val();
            //     let dummyDisplayQty = $(this).closest('tr').find('.dummyQtyCalculation').val(productQty);
            //     let sum = productQty * unitPrice;
            //     $(this).closest('tr').find('.totalPrice').val(sum);
            //     calculateTotal();
            // });

            $("#myTable tbody").on("change", ".dummyQtyCalculation", function() {
                let productQty = $(this).val();
                let unitPrice = $(this).closest('tr').find('.productPrice').val();
                let dummyDisplayQty = $(this).closest('tr').find('.qtyCalculation').val(productQty);
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

            $("#myTable tbody").on("input", ".percentageCalculation", function() {
                let percentage = parseFloat($(this).val()) || 0;
                let productQtyCount = parseFloat($(this).closest('tr').find('.dummyQtyCalculation')
                .val()) || 0;
                let unitPrice = parseFloat($(this).closest('tr').find('.unitPriceCalculation').val()) || 0;

                // Base total
                let baseTotal = unitPrice * productQtyCount;

                // Discounted total (keeping percentage of baseTotal)
                let discountedTotal = (baseTotal * percentage) / 100;

                // New qty
                let newQty = (unitPrice > 0) ? (discountedTotal / unitPrice) : 0;

                // Update fields
                $(this).closest('tr').find('.totalPrice').val(discountedTotal.toFixed(2));
                // $(this).closest('tr').find('.dummyQtyCalculation').val(newQty);
                $(this).closest('tr').find('.qtyCalculation').val(newQty.toFixed(3));

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
