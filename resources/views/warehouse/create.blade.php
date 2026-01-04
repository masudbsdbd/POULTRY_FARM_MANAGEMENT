@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => 'Manage Warehouse Create', 'subtitle' => 'Manage Warehouse'])

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <form
                                    action="{{ route('warehouse.manage.store') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="example-select" class="form-label">Product</label>
                                            <select class="form-select" id="select-product" name="product_id" required>
                                                <option value="">Select Product</option>
                                                    @foreach ($uniqueStocks as $item)
                                                        <option value="{{ $item->product_id }}">{{ $item->product->name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="example-select" class="form-label">Batch</label>
                                            <select class="form-select" name="batch_id" id="select-batch">
                                                <option value="">Select Batch</option>
                                            </select>
                                        </div>
                                    </div>


                                        <div class="row align-items-end">
                                            <!-- From Warehouse -->
                                            <div class="col-md-5">
                                                <div class="mb-4">
                                                    <label class="form-label">From Warehouse</label>
                                                    <select class="form-select" name="warehouse_id_from" id="select-warehouse">
                                                        <option value="">Select Warehouse</option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- to text in center -->
                                            <div class="col-md-2 text-center d-flex justify-content-center align-items-center" style="height: 100%;">
                                                <span class="fw-bold mb-3">to</span>
                                            </div>

                                            <!-- To Warehouse -->
                                            <div class="col-md-5">
                                                <div class="mb-4">
                                                    <label class="form-label">To Warehouse</label>
                                                    <select class="form-select" name="warehouse_id_to" id="select-to-warehouse">
                                                        <option value="">Select Warehouse</option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    

                                    </div>

                                    <div class="text-end">
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light">Manage Create</button>
                                    </div>
                                </form>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </div> <!-- container -->

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])

    <script>
        $(document).ready(function () {
            let stockData = [];

            $('#select-product').on('change', function () {
                var productId = $(this).val();

                if (productId) {
                    $.ajax({
                        url: "{{ route('warehouse.manage.ajax', ':id') }}".replace(':id', productId),
                        type: 'GET',
                        success: function (response) {
                            stockData = response;
                            $('#select-batch').empty().append('<option value="">Select Batch</option>');
                            $('#select-warehouse').val('').prop('disabled', true);

                            response.forEach(stock => {
                                $('#select-batch').append(`<option value="${stock.stock_id}">${stock.batch_code}</option>`);
                            });
                        }
                    });
                } else {
                    $('#select-batch').empty().append('<option value="">Select Batch</option>');
                    $('#select-warehouse').val('').prop('disabled', true); 
                }
            });

            $('#select-batch').on('change', function () {
                const stockId = $(this).val();
                const matched = stockData.find(s => s.stock_id == stockId);

                if (matched) {
                    $('#select-warehouse').val(matched.warehouse_id).prop('disabled', true);

                    const fromWarehouseId = matched.warehouse_id;

                    let toWarehouseSelect = $('#select-to-warehouse');
                    toWarehouseSelect.empty().append('<option value="">Select Warehouse</option>');

                    @foreach ($warehouses as $warehouse)
                        toWarehouseSelect.append(`<option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>`);
                    @endforeach

                    toWarehouseSelect.find(`option[value="${fromWarehouseId}"]`).remove();
                } else {
                    $('#select-warehouse').val('').prop('disabled', true);

                    let toWarehouseSelect = $('#select-to-warehouse');
                    toWarehouseSelect.empty().append('<option value="">Select Warehouse</option>');

                    @foreach ($warehouses as $warehouse)
                        toWarehouseSelect.append(`<option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>`);
                    @endforeach
                }
            });


        });

</script>
@endsection
