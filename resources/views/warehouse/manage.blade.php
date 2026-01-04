@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite([
        'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
        'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
        'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
        'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
        'node_modules/mohithg-switchery/dist/switchery.min.css',
        'node_modules/select2/dist/css/select2.min.css',
        'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        'node_modules/clockpicker/dist/bootstrap-clockpicker.min.css',
        'node_modules/spectrum-colorpicker2/dist/spectrum.min.css',
        'node_modules/flatpickr/dist/flatpickr.min.css',
        'node_modules/select2/dist/css/select2.min.css'

    ])
@endsection


@section('content')
<div class="container-fluid">
    @include('layouts.shared.page-title', [
        'title' => 'Manage Warehouse',
        'subtitle' => 'Manage Warehouse',
    ])

    <a href="{{ route('warehouse.manage.create') }}" class="mb-2 btn btn-primary waves-effect waves-light">Manage</a>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    {{--<h5>
                        @if(request('date'))
                        Search Date: ( {{ request('date') }} )
                        @elseif(request('range'))
                        Search Date: ( {{ request('range') }} )
                        @else

                        @endif

                    </h5>
                    <form action="{{ route('warehouse.manage') }}" method="get">
                        <div class="row">
                            <!-- Start Date Field -->
                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Search Type</p>
                                <select class="form-select" id="type-select" name="type">
                                    <option value="">Select Type</option>
                                    <option value="1" @selected(request('type')=='1' )>Single Date</option>
                                    <option value="2" @selected(request('type')=='2' )>Date Range</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="basicDatepicker {{ request('range') ? 'd-none' : '' }}">
                                    <p class="fw-bold text-muted">Select Date</p>
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="date" id="basic-datepicker" class="form-control"
                                            placeholder="Select Date" value="{{ old('date', request('date')) }}">
                                        <div class="input-group-text clear-btn" style="cursor: pointer;">X</div>
                                    </div>
                                </div>


                                <div class="rangeDatepicker {{ request('range') ? '' : 'd-none' }}">
                                    <p class="fw-bold text-muted">Choose Date Range</p>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="range-datepicker" class="form-control" name="range"
                                            placeholder="Ex. 2018-10-03 to 2018-10-10"
                                            value="{{ old('date', request('range')) }}">
                                        <div class="input-group-text clear-btn" style="cursor: pointer;">X</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Select Warehouse</p>
                                <select id="select-warehouse" class="form-control" data-toggle="select2"
                                    data-width="100%" name="warehouse_id">
                                    <option value="">Select Warehouse</option>
                                    @foreach ($warehouseNames as $item)
                                    <option value="{{ $item->id }}" @selected(request('warehouse_id') ? $item->id == request('warehouse_id') ?? true : false)>{{ $item->warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->

                            <!-- Search Button -->
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100"
                                    style="margin-top: 36px;">Search</button>
                            </div>

                        </div>
                    </form><br>--}}

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Manager</th>
                                <th class="text-center">Stock</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouses as $key => $warehouse)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-center">{{ $warehouse->warehouse_name }}</td>
                                    <td class="text-center">{{ $warehouse->warehouse_code ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $warehouse->user->name ?? "N/A" }}</td>
                                    <td class="text-center">{{ $warehouse->stocks->sum('stock') }}</td>
                                    <td class="text-end">
                                        <button title="View" type="button" class="btn btn-primary waves-effect waves-light wareHouseProducts"
                                            data-stocks="{{ $warehouse->stocks }}"
                                            data-name="{{ $warehouse->warehouse_name }}"
                                            data-code="{{ $warehouse->warehouse_code }}">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $warehouses->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
</div>

<div id="productDetails" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">From : <span id="warehouseName"></span> (<span id="warehouseCode"></span>)</h4>
                        <h5>
                            @if(request('date'))
                                Search Date: ( {{ request('date') }} )
                            @elseif(request('range'))
                                Search Date: ( {{ request('range') }} )
                            @endif
                        </h5>
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Batch</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Purchase Stock</th>
                                    <th class="text-center">Avg Purchase Price</th>
                                    <th class="text-center">Available Stock</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])


    <script>

            $(document).on('click', '.clear-btn', function() {
            $('.basicDatepicker input').val('');
            $('.rangeDatepicker input').val('');
            $('#type-select').val('');
        });

        $('#type-select').on('change', function() {
            const selectedValue = $(this).val();

            if (selectedValue == '') {
                $('.basicDatepicker input').val('');
                $('.rangeDatepicker input').val('');
                $('.basicDatepicker input').prop('disabled', true);
                $('.rangeDatepicker input').prop('disabled', true);
            } else if (selectedValue == '1') {
                $('.basicDatepicker').removeClass('d-none');
                $('.basicDatepicker input').prop('disabled', false);

                $('.rangeDatepicker').addClass('d-none');
                $('.rangeDatepicker input').val('');
            } else if (selectedValue == '2') {
                $('.rangeDatepicker').removeClass('d-none');
                $('.rangeDatepicker input').prop('disabled', false);

                $('.basicDatepicker').addClass('d-none');
                $('.basicDatepicker input').val('');
            }
        });


        $(document).on('click', '.wareHouseProducts', function () {

            var modal = $('#productDetails');
            let data = $(this).data();

            modal.find('#warehouseName').text(data.name);
            modal.find('#warehouseCode').text(data.code);

            let tableBody = modal.find('table tbody');
            tableBody.empty();

            let totalPurchaseQty = 0;
            let totalStock = 0;

            data.stocks.forEach((stock, index) => {
                totalPurchaseQty += Number(stock.purchase_qty) || 0;
                totalStock += Number(stock.stock) || 0;

                let row = `
                    <tr>
                        <th scope="row">${index + 1}</th>
                        <td class="text-center">${stock.batch?.batch_code || '-'}</td>
                        <td class="text-center">${stock.product_name || '-'}</td>
                        <td class="text-center">${stock.purchase_qty || 0}</td>
                        <td class="text-center">${parseFloat(stock.avg_purchase_price).toFixed(2) + ' Tk' || '-'}</td>
                        <td class="text-center">${stock.stock || 0}</td>
                    </tr>
                `;
                tableBody.append(row);
            });

            // Add total row
            let totalRow = `
                <tr class="fw-bold table-warning">
                    <td colspan="3" class="text-end">Total:</td>
                    <td class="text-center">${totalPurchaseQty}</td>
                    <td></td>
                    <td class="text-center">${totalStock}</td>
                </tr>
            `;
            tableBody.append(totalRow);

            modal.modal('show');
        });
    </script>
@endsection
