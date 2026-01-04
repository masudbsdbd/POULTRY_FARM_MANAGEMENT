@extends('layouts.vertical', ['title' => 'Datatables'])

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

    @include('layouts.shared.page-title', ['title' => $pageTitle ?? "All Invoices", 'subtitle' => 'Quotations'])
    

   

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                 <div class="p-3">
                    <form action="{{ route('challan.used_history') }}" method="get">
                        <div class="row g-3">
                            {{-- <div class="col-md-2">
                                <p class="fw-bold text-muted">Payment Method</p>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value="">Select Method</option>
                                        <option value="1" @selected(request('payment_method')== 1)>Cash</option> 
                                        <option value="2" @selected(request('payment_method')== 2)>Bank</option> 
                                        <option value="3" @selected(request('payment_method')== 3)>Cheque</option> 
                                </select>
                            </div> --}}

                            <div class="col-md-3">
                                <p class="fw-bold text-muted">Search Quotation</p>
                                <select class="form-select" id="quotation_id" name="quotation_id">
                                    <option value="">Select Quotation</option>
                                    @foreach ($quotations as $quotation)
                                        <option value="{{ $quotation->id }}" @selected(request('quotation_id')==$quotation->id)>{{ $quotation->quotation_number }}</option> 
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <p class="fw-bold text-muted">Search Work Number</p>
                                <select class="form-select" id="challan_id" name="challan_id">
                                    <option value="">Select Work Number</option>
                                    @foreach ($challans as $challan)
                                        <option value="{{ $challan->id }}" @selected(request('challan_id')==$challan->id)>{{ $challan->challan_number }}</option> 
                                    @endforeach
                                </select>
                            </div>

                            {{-- select product --}}
                            <div class="col-md-3">
                                <p class="fw-bold text-muted">Search Product</p>
                                <select class="form-select" id="product_id" name="product_id">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected(request('product_id')==$product->id)>{{ $product->name }}</option> 
                                    @endforeach
                                </select>                                
                            </div>

                            {{-- floors --}}
                            <div class="col-md-3">
                                <p class="fw-bold text-muted">Search Floor</p>
                                <select class="form-select" id="floor_id" name="floor_id">
                                    <option value="">Select Floor</option>
                                    @foreach ($floors as $floor)
                                        <option value="{{ $floor->id }}" @selected(request('floor_id')==$floor->id)>{{ $floor->name }}</option> 
                                    @endforeach
                                </select>                                
                            </div>

                            <div class="col-md-3">
                                <p class="fw-bold text-muted">Search Type</p>
                                <select class="form-select" id="type-select" name="type">
                                    <option value="">Select Type</option>
                                    <option value="1" @selected(request('type')=='1')>Single Date</option>
                                    <option value="2" @selected(request('type')=='2')>Date Range</option>
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
                                <button type="submit" class="btn btn-primary w-100" style="margin-top: 36px;">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Quotation Number</th>
                                <th>Work Number</th>
                                <th>Product Name</th>
                                <th>Floor</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($challanItems))
                                @foreach($challanItems as $key => $challan)
                                {{-- @dd($challan) --}}
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $challan->challan_date }}</td>
                                    <td>{{ $challan->quotation_number }}</td>
                                    <td>{{ $challan->challan_number }}</td>
                                    <td>{{ $challan->product->name }}</td>
                                    <td>{{ $challan->floor->name }}</td>
                                    <td>{{ $challan->quantity }}</td>
                                    <td>{{ $challan->unit_price }}</td>
                                    <td>{{ $challan->total }}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            {{-- @if (isset($payments) && $payments->count() > 0) --}}
                                {{-- <tr>
                                    <td colspan="4" class="">
                                        <div class="d-flex justify-content-center" style="margin-left: 20px;"> --}}
                                            {{-- <span class=" fw-bold">{{ showAmount($totalPaid, 2, false) }} tk</span> --}}
                                        {{-- </div>
                                    </td>
                                    <td colspan="2" class="text-center"></td>
                                </tr> --}}
                            {{-- @endif --}}
                        </tfoot>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-end mt-3">
                        {{  isset($challanItems) ? $challanItems->links() : '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<x-confirmation-modal></x-confirmation-modal>

{{-- Quotation View Modal --}}
<div class="modal fade" id="quotationViewModal" tabindex="-1" aria-labelledby="quotationViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quotationViewModalLabel">Quotation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6"><strong>Quotation Number:</strong> <span id="modalQuotationNumber"></span></div>
                    <div class="col-md-6"><strong>Customer:</strong> <span id="modalCustomer"></span></div>
                    <div class="col-md-6"><strong>Contract Amount:</strong> <span id="modalTotalAmount"></span></div>
                    <div class="col-md-6"><strong>Status:</strong> <span id="modalStatus"></span></div>
                    <div class="col-md-12"><strong>Note:</strong> <span id="modalNote"></span></div>
                </div>

                <h5>Products</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="modalProductsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Note</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- JS populate --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js','resources/js/pages/datatables.init.js'])
<script>
$(document).ready(function() {
    $('.viewQuotation').on('click', function() {
        var button = $(this);

        $('#quotationViewModalLabel').text(button.data('title'));
        $('#modalQuotationNumber').text(button.data('quotation-number'));
        $('#modalCustomer').text(button.data('customer'));
        $('#modalTotalAmount').text(button.data('total-amount'));
        $('#modalNote').text(button.data('note'));

        var status = button.data('status');
        if(status == 1){
            $('#modalStatus').html('<span class="badge bg-primary">Activated</span>');
        } else {
            $('#modalStatus').html('<span class="badge bg-secondary">Inactive</span>');
        }

        // Populate products
        var products = button.data('products');
        var tbody = $('#modalProductsTable tbody');
        tbody.empty();

        if(products && products.length > 0){
            products.forEach(function(item, index){
                tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.product.name}</td>
                        <td>${item.description || '-'}</td>
                        <td>${item.qty}</td>
                        <td>${parseFloat(item.unit_price).toFixed(2)}</td>
                        <td>${parseFloat(item.total).toFixed(2)}</td>
                    </tr>
                `);
            });
        } else {
            tbody.append('<tr><td colspan="6" class="text-center">No products found</td></tr>');
        }

        $('#quotationViewModal').modal('show');
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function ($) {
            "use strict";

            $(document).on('click', '.clear-btn', function () {
                $('.basicDatepicker input').val('');
                $('.rangeDatepicker input').val('');
                $('#type-select').val('');
            });

            $('#type-select').on('change', function () {
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
        })(jQuery);
    });
</script>
@endsection
