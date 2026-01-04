@extends('layouts.vertical', ['title' => 'Create Quotation'])

@section('css')
@vite([
    'node_modules/flatpickr/dist/flatpickr.min.css',
    'node_modules/select2/dist/css/select2.min.css',
    'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
    'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
    'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
    'node_modules/mohithg-switchery/dist/switchery.min.css'
])
@endsection

@section('content')
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Quotations'])

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    <form action="{{ isset($quotation) ? route('quotation.store', $quotation->id) : route('quotation.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="row">
                                    @if (isset($quotation))
                                        <div class="col-lg-12 mb-3">
                                            <label for="quotation_number" class="form-label">Quotation Number</label>
                                            <input type="text" id="quotation_number" name="quotation_number" class="form-control"
                                                placeholder="Enter quotation number" required readonly
                                                value="{{ old('quotation_number', $quotation->quotation_number ?? '') }}">
                                        </div>
                                    @endif
                                    <div class="col-lg-12 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter title"
                                            value="{{ old('title', $quotation->title ?? '') }}" required>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="customer_id" class="form-label">Select Customer</label>
                                        <select class="form-select" name="customer_id" required data-toggle="select2">
                                            <option value="">Choose one</option>
                                            @foreach ($customers as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('customer_id', $quotation->customer_id ?? '') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="quotation_date" class="form-label">Quotation Date</label>
                                        <input type="date" id="quotation_date" name="quotation_date" class="form-control" required
                                            value="{{ old('quotation_date', $quotation->quotation_date ?? now()->setTimezone('Asia/Dhaka')->format('Y-m-d')) }}">
                                            
                                    </div>
                                    {{-- <div class="col-lg-12 mb-3">
                                        <label for="customer_id" class="form-label">Select Customer</label>
                                        <select class="form-select" name="customer_id" required data-toggle="select2">
                                            <option value="">Choose one</option>
                                            @foreach ($customers as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('customer_id', $quotation->customer_id ?? '') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    {{-- <div class="col-lg-12 mb-3">
                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                        <input type="date" id="expiry_date" name="expiry_date" class="form-control"
                                            value="{{ old('expiry_date', $quotation->expiry_date ?? '') }}">
                                    </div> --}}

                                    <div class="col-lg-12 mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea id="notes" name="notes" class="form-control" rows="6" placeholder="Enter notes">{{ old('notes', $quotation->notes ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                {{-- <div class="col-lg-12 mb-3">
                                    <label for="project_name" class="form-label">Project Name</label>
                                    <input type="text" id="project_name" name="project_name" class="form-control" placeholder="Enter title"
                                        value="{{ old('title', $quotation->project_name ?? '') }}" required>
                                </div> --}}
                                <div class="col-lg-12 mb-3">
                                    <label for="floor_info" class="form-label">Project No.</label>
                                    <input type="text" id="floor_info" name="floor_info" class="form-control" placeholder="Enter Project No."
                                        value="{{ old('title', $quotation->floor_info ?? '') }}" required>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="example-select" class="form-label">Project diagram</label>
                                        <div class="form-group">
                                            <div class="image-upload">
                                                <div class="thumb">
                                                    <div class="avatar-preview" style="height: 290px;">
                                                        <div class="profilePicPreview"
                                                            style="height: 290px; background-image: url({{ isset($quotation) ? (isset($quotation->diagram_image) ? asset('uploads/quotations/' . $quotation->diagram_image) : asset('uploads/default-picker.png')) : asset('uploads/default-picker.png') }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
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
                                </div>

                                
                            </div>
                        </div>

                        <div class="row">

                            
                        </div>

                        <!-- Purchase List Section -->
                        <div class="col-lg-12 mb-3 mt-4">
                            <h4 class="header-title">Product List</h4>

                            <!-- Product Select -->
                            <div class="mb-3">
                                <label for="select-products" class="form-label">Select Product</label>
                                <select id="select-products" class="form-select" data-toggle="select2">
                                    <option value="">Choose Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-item='@json($product)'>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="deleted-product">
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;">Product Name</th>
                                            <th style="width: 15%;">Note</th>
                                            <th style="width: 10%;">Qty</th>
                                            <th style="width: 10%;">Unit Price</th>
                                            <th style="width: 10%;">Unit</th>
                                            <th style="width: 10%;">Total</th>
                                            <th style="width: 5%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($quotation) && $quotation->items->count())
                                            @foreach($quotation->items as $item)
                                                <tr class="tableRow" data-product="{{ $item->product_id }}">
                                                    <td>
                                                        <input type="hidden" name="products[]" value="{{ $item->product_id }}">
                                                        <input type="text" class="form-control" value="{{ $item->product->name }}" readonly required>
                                                        <textarea readonly style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">{{ $item->product->description }}</textarea>
                                                    </td>
                                                    <td><input placeholder="notes" type="text" class="form-control description" name="description[]" value="{{ $item->description }}"></td>
                                                    <td><input type="number" class="form-control qtyCalculation" name="qty[]" value="{{ $item->qty }}" required></td>
                                                    <td><input readonly type="number" class="form-control unitPriceCalculation" 
                                                        name="unitPrice[]" value="{{ showAmount($item->unit_price, 2, false) }}" required></td>

                                                    <td>
                                                        {{-- <div class="">
                                                            <select class="form-select" name="unit_id" readonly required>
                                                                <option value="">Select Unit</option>
                                                                @foreach ($units as $key => $unit)
                                                                    <option value="{{ $unit->id }}"
                                                                        @selected(isset($item->product) ? ($unit->id == $item->product->unit_id ? true : false) : false)>
                                                                        {{ $unit->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div> --}}
                                                        <input type="text" class="form-control qtyCalculation" name="unit[]" value="{{ $item->product->unit->name }}" required>
                                                    </td>

                                                    <td><input type="text" class="form-control totalPrice" 
                                                        name="priceTotal[]" value="{{ $item->total }}" readonly></td>

                                                    <td><button data-product-id="{{ $item->product_id }}" type="button" class="btn btn-danger rowDelete"><i class="mdi mdi-trash-can-outline"></i></button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr id="empty-row">
                                                <td colspan="6" class="text-center"><h4 class="my-3">Insert Products</h4></td>
                                            </tr>
                                        @endif

                                        <button data-units="{{ $units }}" type="button" class="btn btn-primary waves-effect waves-light" id="CreateNewProductBtn">
                                            Craete New Product
                                        </button>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Total Quantity:</td>
                                            <td><span id="total-quantity">0</span></td>
                                            <td>Total Price:</td>
                                            <td><span id="total-price">0.00</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" id="totalQuantity" name="totalQuantity" value="0">
                                <input type="hidden" id="calculatedPrice" name="calculatedPrice" value="0">
                            </div>
                        </div>

                        <!-- Status field at the bottom -->
                        <div class="col-lg-12 mb-3 d-flex align-items-center">
                            <label for="status" class="form-label me-3">Status</label>
                            <label class="switch m-0">
                                <input id="status" type="checkbox" class="toggle-switch" name="status"
                                    @checked(isset($quotation) ? ($quotation->status == 1 ? true : false) : true)>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                {{ isset($quotation) ? 'Update Quotation' : 'Add New Quotation' }}
                            </button>
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
    'resources/js/pages/form-pickers.init.js',
    'resources/js/pages/datatables.init.js',
    'resources/js/app.js'
])
<script>
document.addEventListener('DOMContentLoaded', function() {

    function addRow(product) {
        $('#empty-row').addClass('d-none');
        const row = $(` 
            <tr class="tableRow" data-product="${product.id}">
                <td><input type="hidden" name="products[]" value="${product.id}">
                    <input type="text" class="form-control" value="${product.name}" readonly required>
                    <textarea style="margin-top: 5px;" class="form-control" rows="2" placeholder="Long Description">${product.description}</textarea>
                </td>
                <td><input type="text" class="form-control description" name="description[]" placeholder="note"></td>
                <td><input type="number" class="form-control qtyCalculation" name="qty[]" required></td>
                <td><input type="number" class="form-control unitPriceCalculation" name="unitPrice[]" value="${parseFloat(product.price).toFixed(2)}" required></td>
                <td><input type="text" class="form-control" name="units[]" value="${product.unit.name}" readonly></td>
                <td><input type="text" class="form-control totalPrice" name="priceTotal[]" readonly></td>
                <td><button type="button" class="btn btn-danger rowDelete"><i class="mdi mdi-trash-can-outline"></i></button></td>
            </tr>
        `);
        $("#myTable tbody").append(row);
    }


    $("#CreateNewProductBtn").on("click", function() {
    const units = $(this).data('units');

    let unitOptions = `<option value="">Select Unit</option>`;
    units.forEach(unit => {
        unitOptions += `<option value="${unit.id}">${unit.name}</option>`;
    });

    const newProductRow = $(`
        <tr class="tableRow">
            <td>
                <input type="text" class="form-control" placeholder="product sort name" name="new_products_name[]" required>
                <textarea style="margin-top: 5px;" class="form-control" name="new_products_description[]" rows="2" placeholder="Long Description"></textarea>
            </td>
            <td><input type="text" class="form-control description" name="new_notes[]" placeholder="note"></td>
            <td><input type="number" class="form-control qtyCalculation" name="new_qty[]" required></td>
            <td><input type="number" class="form-control unitPriceCalculation" name="new_unitPrice[]" required></td>
            <td>
                <select class="form-select" name="new_units[]" required>
                    ${unitOptions}
                </select>
            </td>
            <td><input type="text" class="form-control totalPrice" name="new_priceTotal[]" readonly></td>
            <td>
                <button type="button" class="btn btn-danger rowDelete">
                    <i class="mdi mdi-trash-can-outline"></i>
                </button>
            </td>
        </tr>
    `);

    $("#myTable tbody").append(newProductRow);
});


    $('#select-products').on('change', function() {
        const product = $(this).find(':selected').data('item');
        if (!product) return;

        const duplicate = $('#myTable tbody .tableRow').filter(function() {
            return $(this).data('product') == product.id;
        }).length;

        if (duplicate) { alert('This product is already added.'); return; }
        console.log(product);
        addRow(product);
        $(this).val('').trigger('change'); // reset select
        calculateTotal();
    });
    
    
    $(document).on("change", "[name='qty[]']", function () {
        let qty = parseInt($(this).val(), 10) || 0;
        
    
        if (qty <= 0) {
            alert("You can't add less then 1 qty.");
            $(this).val(1);
        }
    });
    
    
    $(document).on("change", "[name='new_qty[]']", function () {
        let qty = parseInt($(this).val(), 10) || 0;
        
    
        if (qty <= 0) {
            alert("You can't add less then 1 qty.");
            $(this).val(1);
        }
    });

    $(document).on('click', '.rowDelete', function() {
        let productId = $(this).data('product-id');
        $("#deleted-product").append(`<input type="hidden" name="deleltedProduct[]" value="${productId}">`)
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
        $('#total-price').text(totalPrice.toFixed(2));
        $('#totalQuantity').val(totalQty);
        $('#calculatedPrice').val(totalPrice);
    }

    // Page load এ total recalc (edit mode)
    calculateTotal();

});
</script>
@endsection
