@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Damage Report'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ $pageTitle }}</h4>
                    <h4>Today ({{ $todayTime }}) </h4>

                    <form action="{{ route('damage-report.index') }}" method="get">
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

                            <div class="col-md-5">
                                <p class="fw-bold text-muted">Select Supplier</p>
                                <select id="select-customer" class="form-control" data-toggle="select2" data-width="100%"
                                    name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $item)
                                    <option value="{{ $item->id }}" @selected(request('supplier_id') ? $item->id == request('supplier_id') ?? true : false)>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->

                        <!-- Search Button -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"
                                style="margin-top: 36px;">Search</button>
                        </div>

                </div>
                </form>

                <div class="mt-3">
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Supplier </th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $totalQty = 0;
                            $totalAvgPurchasePrice = 0;
                            $totalDamagedPrice = 0;

                            @endphp
                            @foreach ($damages as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ showDateTime($item->created_at, true) }}</td>
                                <td class="text-center">{{ $item->supplier->name }}</td>
                                <td class="text-center">{{ $item->total_qty }}</td>
                                <td class="text-center">{{ showAmount($item->grand_total) }}</td>

                                <td class="text-center">
                                {{ substr($item->description, 0, 24) }}...
                                <a data-paragraph="{{ $item->description }}" href="javascript:void(0)"
                                    class="text-primary descBtn" href="">
                                    See More</a>
                                </td>
                                <td class="text-center">
                                    <button title="View" type="button" class="btn btn-primary waves-effect waves-light viewDamageProducts"
                                        data-items='@json($item->items)'
                                        data-total_amount="{{ $item->total_amount ?? 0 }}"
                                        data-supplier="{{ $item->supplier->name }}"
                                    >
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </td>

                            </tr>
                            @php
                            $totalQty += $item->total_qty;
                            $totalAvgPurchasePrice += $item->price;
                            $totalDamagedPrice += $item->grand_total;

                            @endphp
                            @endforeach
                        </tbody>
                        @if (!$damages->isEmpty())
                        <tfoot>
                            <tr style="font-size: 16px;">
                                <td class="fw-bold">Total :</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"><strong>{{ $totalQty }}</strong></td>
                                <td class="text-center"><strong>{{ showAmount($totalDamagedPrice) }} Tk</strong></td>
                                <td class="text-center"><strong></strong></td>
                                <td class="text-center"><strong></strong></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                        <div class="d-flex justify-content-end mt-3">{{ $damages->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Damage Description</h4>
                    @csrf
                    <p class="descParagraph"></p>
                    <div class="text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

<div id="damageDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Supplier - <span id="supplierName"></span></h4>
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Batch</th>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>Purchase Price</th>
                                    <th>total Price</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> <!-- end card -->
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog --> 
@endsection

@section('script')
@vite(['resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js', 'resources/js/pages/datatables.init.js'])

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

            
            $(document).on('click', '.viewDamageProducts', function() {
                var modal = $('#damageDetail');
                let data = $(this).data();
                console.log(data);
                modal.find('#supplierName').text(data.supplier);

                let tableBody = modal.find('table tbody');
                tableBody.empty();

                let totalQty = 0;
                let totalPrice = 0;

                data.items.forEach((item, index) => {
                    totalQty += parseFloat(item.qty) || 0;
                    totalPrice += parseFloat(item.total_amount) || 0;

                    // let num = parseFloat(item.total_amount);

                    let row = `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td>${item.batch.batch_code  || '-'}</td>
                            <td>${item.product.name  || '-'}</td>
                            <td>${item.qty  || '-'}</td>
                            <td>${parseFloat(item.avg_purchase_price).toFixed(2) + ' Tk'   || '-'}</td>
                            <td>${parseFloat(item.total_amount).toFixed(2) + ' Tk'  || '-'}</td>
                        </tr>

                    `;
                    tableBody.append(row);
                });

                let totalRow = `
                    <tr class="table-secondary">
                        <td colspan="1">Total:</td>
                        <td></td>
                        <td></td>
                        <td><strong>${totalQty}</strong></td>
                        <td></td>
                        <td><strong>${totalPrice.toFixed(2)} Tk</strong></td>
                    </tr>
                `;
                tableBody.append(totalRow);

                modal.modal('show');
            });

            

            $(document).on('click', '.descBtn', function() {
                    var modal = $('#descModal');
                    let data = $(this).data();
                    modal.find('.descParagraph').text(`${data.paragraph}`);
                    modal.modal('show');
                });

        })(jQuery);
    });
</script>

@endsection