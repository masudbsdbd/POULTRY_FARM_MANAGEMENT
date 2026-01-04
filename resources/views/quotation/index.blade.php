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

    @include('layouts.shared.page-title', ['title' => 'All Quotations', 'subtitle' => 'Quotations'])

    

    {{-- cards start --}}
     <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-heart font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $totalQuotations }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Quotations</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-shopping-cart font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">&#2547; <span data-plugin="counterup">{{ showAmount($totalQuotationAmount, 2, false) }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">All Quotation Value</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-shopping-bag font-22 avatar-title text-info"></i>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">&#2547; 
                                    <span data-plugin="counterup">{{ showAmount($totalInvoicedAmount, 2, false) }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Invoiced</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-shopping-bag font-22 avatar-title text-info"></i>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">&#2547; 
                                    @php
                                        $waitingForInvoice = intval($totalQuotationAmount) - intval($totalInvoicedAmount);
                                    @endphp
                                   <span data-plugin="counterup">{{ showAmount($waitingForInvoice, 2, false) }}</span></h3>
                                <p class="text-danger mb-1 text-truncate">Waiting for invoice</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    </div>
    {{-- cards end --}}


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="p-3">
                    <form action="{{ route('quotation.index') }}" method="get">
                        <div class="row">
                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Search Customer</p>
                                <select class="form-select" id="customer_id" name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(request('customer_id')==$customer->id)>{{ $customer->name }}</option> 
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
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
                <div class="card-body">
                    <div class="text-end mb-3">
                         @can('quotation-create')
                            <a href="{{ route('quotation.create') }}" class="btn btn-primary waves-effect waves-light">
                                Add New Quotation
                            </a>
                         @endcan
                    </div>

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Quotation Number</th>
                                <th>Title</th>
                                <th>Customer</th>
                                <th>Contract Amount</th>
                                <th>Product Used</th>
                                <th>Invoiced Amount</th>
                                <!--<th>Paid Amount</th>-->
                                <!--<th>Due Amount</th>-->
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotations as $quotation)
                                <tr>
                                    <td>{{ $loop->iteration + ($quotations->currentPage()-1)*$quotations->perPage() }}</td>
                                    <td>{{ $quotation->quotation_number }}</td>
                                    <td>{{ $quotation->title }}</td>
                                    <td>{{ $quotation->customer->name ?? '-' }}</td>
                                    <td>{{ number_format($quotation->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $progress = round(($quotation->used_product_amount / $quotation->total_amount) * 100);
                                        @endphp
                                        <div class="progress" style="height:20px; width:120px;  position: relative;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;">
                                            {{ $progress }}%
                                            </div>
                                            @if ($progress < 15 )
                                                <p style="text-align: center; position: absolute; left: 40%; font-size: 12px;">{{ $progress }}%</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $invoicedPercent = round(($quotation->invoiced_amount / $quotation->total_amount) * 100);
                                        @endphp
                                        <div class="progress" style="height:20px; width:120px;  position: relative;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $invoicedPercent }}%;">
                                            {{ $invoicedPercent }}%
                                            </div>
                                            @if ($invoicedPercent < 15 )
                                                <p style="text-align: center; position: absolute; left: 40%; font-size: 12px;">{{ $invoicedPercent }}%</p>
                                            @endif
                                        </div>
                                        {{ number_format($quotation->invoiced_amount, 2) }} tk
                                    </td>
                                    <!--<td ><span class="text-success">{{ showAmount($quotation->total_paid, 2, false) }}</span> tk</td>-->
                                    <!--<td><span class="text-danger">{{ showAmount($quotation->total_due, 2, false) }}</span> tk</td>-->
                                    <td class="text-end">
                                        {{-- <a
                                            class="btn btn-info btn-sm viewQuotation"
                                            data-quotation-number="{{ $quotation->quotation_number }}"
                                            data-title="{{ $quotation->title }}"
                                            data-customer="{{ $quotation->customer->name ?? '-' }}"
                                            data-total-amount="{{ number_format($quotation->total_amount,2) }}"
                                            data-status="{{ $quotation->status }}"
                                            data-note="{{ $quotation->notes }}"
                                            data-products='@json($quotation->items)'
                                        >
                                             <i class="mdi mdi-eye"></i>
                                        </a> --}}

                                        <a
                                            href="{{ route('quotation.view', $quotation->id) }}"
                                            class="btn btn-warning btn-sm"
                                            title="View Quotation"
                                        >
                                             <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('challan.all', $quotation->id) }}" class="btn btn-info btn-sm" title="Create Challan">
                                            Today Work done
                                        </a>
                                        <a href="{{ route('approval.all', $quotation->id) }}" class="btn btn-warning btn-sm" title="Create Challan">
                                            Approvals
                                        </a>
                                        <a href="{{ route('invoice.all', $quotation->id) }}" class="btn btn-success btn-sm" title="Create Challan">
                                            Invoices
                                        </a>
                                        @can('quotation-edit')
                                            <a href="{{ route('quotation.edit', $quotation->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('quotation-delete')
                                            <form action="{{ route('quotation.delete', $quotation->id) }}" method="POST" class="d-inline-block"  id="deleteForm_{{ $quotation->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="askPassword({{ $quotation->id }})"
                                                   >
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-start"></td>
                                <td class="text-start"></td>
                                <td class="text-start"></td>
                                <td class="text-start"></td>
                                <td class="text-start"></td>
                                <td class="text-start"></td>
                                <td class="text-start"></td>
                                <!--<td class="text-start">{{ showAmount($totalQuotationPaidAmount, 2, false) }} tk</td>-->
                                <!--<td class="text-start">{{ showAmount($totalQuotationDueAmount, 2, false) }} tk</td>-->
                                <td class="text-start"></td>
                            </tr>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-end mt-3">
                        {{ $quotations->links() }}
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
    function askPassword(quotationId) {
        const password = prompt("Please enter your password to confirm deletion:");
        if (!password) return;
        const form = document.getElementById(`deleteForm_${quotationId}`);
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "password";
        input.value = password;
    
        form.appendChild(input);
        form.submit();
    }
</script>
@endsection
