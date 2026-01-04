@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css', 'node_modules/spectrum-colorpicker2/dist/spectrum.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/clockpicker/dist/bootstrap-clockpicker.min.css', 'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css','node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => $pageTitle])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h4>Purchase Report List</h4>
                        <h4>Today ({{ $todayTime }}) </h4>
                        <h5>
                            @if(request('date'))
                            Search Date: ( {{ request('date') }} )
                            @elseif(request('range'))
                            Search Date: ( {{ request('range') }} )
                            @else

                            @endif

                        </h5>
                    </div>
                    <form action="{{ route('purchase-report.index') }}" method="get">
                        <div class="row">
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
                            {{--<div class="col-lg-3 mt-2">
                                <label class="form-label">Select Start Date</label>
                                <input type="text" name="start_date" id="basic-datepicker" class="form-control" placeholder="Select Start Date">
                            </div>
                            <div class="col-lg-3 mt-2">
                                <label class="form-label">Select End Date</label>
                                <input type="text" name="end_date" id="basic-datepicker-2" class="form-control" placeholder="Select End Date">
                            </div>--}}
                            <div class="col-lg-3 mt-2"">
                                <label class=" form-label">Select Supplier</label>
                                <select id="select-product" class="form-control" data-toggle="select2" data-width="100%"
                                    name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $item)
                                    <option value="{{ $item->id }}" @selected(request('supplier_id') ? $item->id == request('supplier_id') ?? true : false)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100" style="margin-top: 39px;">Search</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4">
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Batch</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">Total Purchase Qty</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalQty = 0;
                                @endphp
                                @foreach ($purchases as $item)

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->batch->batch_code }}</td>
                                    <td class="text-center">{{ $item->created_at }}</td>
                                    <td class="text-center">{{ $item->supplier->name }}</td>
                                    <td class="text-center">{{ $item->total_qty }}</td>
                                    <td class="text-end">
                                        <button title="View" type="button" class="btn btn-primary waves-effect waves-light purchaseProductsBtn" data-items="{{ $item->items }}" data-damages="{{ $item->items->sum('damage_qty') ?? 0 }}"
                                            data-return_qty="{{ $item->items->sum('return_qty') ?? 0 }}"
                                            data-customer_return_qty="{{ $item->items->sum('customer_return_qty') ?? 0 }}"
                                            data-stock="{{ $item->items->sum('stock') ?? 0 }}"
                                            data-total_amount="{{ $item->items->sum('total_amount') ?? 0 }}"
                                            data-avg_purchase_price="{{ $item->items->sum('avg_purchase_price') ?? 0 }}"
                                            data-purchase_batch_code="{{ $item->batch->batch_code }}"
                                            data-stock="{{ $item->stock->stock }}"
                                            ><i class="mdi mdi-eye"></i></button>
                                    </td>
                                </tr>
                                @php
                                $totalQty += $item->total_qty;
                                @endphp
                                @endforeach
                            </tbody>
                            @if (!$purchases->isEmpty())
                            <tfoot>
                                <tr style="font-size: 16px;">
                                    <td class="fw-bold">Total :</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><strong></strong></td>
                                    <td class="text-center"><strong></strong></td>
                                    <td class="text-center"><strong>{{ $totalQty }}</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                            @endif

                        </table>
                        <div class="d-flex justify-content-end mt-3">{{ $purchases->links() }}</div>
                    </div>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
</div> <!-- container -->

<div id="purchaseProductDetails" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Purchase from : <span id="purchaseName"></span></h4>
                        <h5>
                            @if(request('date'))
                            Search Date: ( {{ request('date') }} )
                            @elseif(request('range'))
                            Search Date: ( {{ request('range') }} )
                            @else

                            @endif
                        </h5>
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Product </th>
                                    <th>Stock</th>
                                    <th>Purchase Price</th>
                                    <th>Supplier Name</th>
                                    <th>Damage Qty</th>
                                    <th>Return Qty</th>
                                    <th>Customer Return Qty</th>
                                    <th>Purchase Qty</th>
                                    <th>Available Stock</th>
                                    <th>Avg Purchase Price</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> <!-- end card -->
            </div>
        </div>
    </div><!-- /.modal-dialog -->

    <x-confirmation-modal></x-confirmation-modal>

    @endsection

    @section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";


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

                $(document).on('click', '.purchaseProductsBtn', function() {
                    var modal = $('#purchaseProductDetails');
                    let data = $(this).data();
                    console.log(data);


                    modal.find('#purchaseName').text(data.purchase_batch_code);
                    let tableBody = modal.find('table tbody');
                    tableBody.empty();

                    data.items.forEach((item, index) => {
                        let row = `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td>${item.product.name || '-'}</td>
                            <td>${item.stock || 0}</td>
                            <td>${parseFloat(item.price).toFixed(2) + ' Tk' ||  '-'}</td>
                            <td>${item.supplier_name || '-'}</td>
                            <td>${item.damage_qty || 0}</td>
                            <td>${item.return_qty || 0}</td>
                            <td>${item.customer_return_qty || 0}</td>
                            <td>${item.qty || '-'}</td>
                            <td>${item.stock || '-'}</td>
                            <td>${parseFloat(item.avg_purchase_price).toFixed(2) + ' Tk' || 0}</td>
                            <td>${item.qty && item.avg_purchase_price ? (item.qty * parseFloat(item.avg_purchase_price)).toFixed(2) + ' Tk' : '0.00 Tk'}</td>

                        </tr>
                    `;
                        // console.log(item.return_qty);

                        tableBody.append(row);
                    });

                    modal.modal('show');
                });

            })(jQuery);
        });
    </script>
    @endsection