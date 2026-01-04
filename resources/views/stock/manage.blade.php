@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/spectrum-colorpicker2/dist/spectrum.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/clockpicker/dist/bootstrap-clockpicker.min.css', 'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css','node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Manage Stock'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title">{{ $pageTitle }}</h4>
                                <a href="{{ route('stock.manage.create') }}"
                                    class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add New</a>
                        </div>
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($manageStocks as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                        <td class="text-center">{{ $item->supplier->name }}</td>
                                        <td class="text-center">{{ $item->total_qty }} </td>
                                        <td class="text-center">
                                            @if (strlen($item->description) < 25)
                                                {{ $item->description }}
                                            @else
                                                {{ substr($item->description, 0, 24) }}...
                                                <a data-paragraph="{{ $item->description }}" href="javascript:void(0)"
                                                    class="text-primary descBtn" href="">
                                                    See More</a>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if ($item->stock_status == 1)
                                                <span class="badge badge-soft-secondary">Damage</span>
                                            @elseif ($item->stock_status == 2)
                                                <span class="badge badge-soft-secondary">Lost</span>
                                            @elseif ($item->stock_status == 3)
                                                <span class="badge badge-soft-secondary">Found</span>
                                            @elseif ($item->stock_status == 4)
                                                <span class="badge badge-soft-secondary">Expiry</span>
                                            @elseif ($item->stock_status == 5)
                                                <span class="badge badge-soft-secondary">Theft</span>
                                            @elseif ($item->stock_status == 6)
                                                <span class="badge badge-soft-secondary">Manual Increase</span>
                                            @elseif ($item->stock_status == 7)
                                                <span class="badge badge-soft-secondary">Manual Decrease</span>
                                            @else
                                                <span class="badge badge-soft-secondary">Unknown</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ showAmount($item->grand_total) }}
                                        </td>


                                        <td class="text-end">

                                            <button title="View" type="button" class="btn btn-primary waves-effect waves-light manageStockItems"
                                                data-items='@json($item->items)'
                                                data-total_amount="{{ $item->total_amount ?? 0 }}"
                                                data-supplier="{{ $item->supplier->name }}"
                                            >
                                                <i class="mdi mdi-eye"></i>
                                            </button>

                                        {{--<a title="Edit" href="{{ route('employee.edit', $item->id) }}"
                                            class="btn btn-primary waves-effect waves-light"><i
                                                class="mdi mdi-grease-pencil"></i></a>
                                        <button title="Delete" type="button"
                                            class="btn btn-danger waves-effect waves-light confirmationBtn"
                                            data-question="@lang('Are you sure to delete this employee?')"
                                            data-action="{{ route('employee.delete', $item->id) }}"><i
                                                class="mdi mdi-trash-can-outline"></i></button>--}}

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">{{ $manageStocks->links() }}</div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->


    <div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h4> Description</h4>
                @csrf
                <p class="descParagraph"></p>
                <div class="text-end">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->



<div id="manageItemDetails" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Supplier : <span id="supplierName"></span></h4>
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
                                    <th>Batch </th>
                                    <th>Product </th>
                                    <th>Qty</th>
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
    @vite(['resources/js/pages/form-pickers.init.js','resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $(document).on('click', '.descBtn', function() {
                    var modal = $('#descModal');
                    let data = $(this).data();
                    modal.find('.descParagraph').text(`${data.paragraph}`);
                    modal.modal('show');
                });


                $(document).on('click', '.manageStockItems', function () {
                    var modal = $('#manageItemDetails');
                    let data = $(this).data();

                    let items = data.items;
                    modal.find('#supplierName').text(data.supplier);
                    let tableBody = modal.find('table tbody');
                    tableBody.empty();

                    if (items && items.length > 0) {
                        items.forEach((item, index) => {
                            let totalPrice = parseFloat(item.qty) * parseFloat(item.avg_purchase_price);
                            let row = `
                                <tr>
                                    <th scope="row">${index + 1}</th>
                                    <td>${item.batch?.batch_code || '-'}</td>
                                    <td>${item.product?.name || '-'}</td>
                                    <td>${item.qty}</td>
                                    <td>${parseFloat(item.avg_purchase_price).toFixed(2)} Tk</td>
                                    <td>${totalPrice.toFixed(2)} Tk</td>
                                </tr>
                            `;
                            tableBody.append(row);
                        });
                    }

                    $('#hidden_total_amount').val(data.total_amount || 0);

                    modal.modal('show');
                });




            })(jQuery);
        });
    </script>
@endsection
