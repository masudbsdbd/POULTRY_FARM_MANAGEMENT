@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite([
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


    {{-- dashboard cards --}}
    @php
        $totalDueAmount = intval($InvoiceInfo->total_amount) - intval($totalPaid);
    @endphp
    <div class="row g-3">
         <div class="col-md-6 col-xl-4">
            <a href="{{ route('invoice.all', $InvoiceInfo->quotation_id) }}" class="btn btn-info"><i class="mdi mdi-arrow-left"></i>back</a>
        </div>
        <div class="col-md-6 col-xl-4"></div><div class="col-md-6 col-xl-4"></div>
        
        <div class="col-md-6 col-xl-4">
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
                                <h3 class="text-dark mt-1">&#2547; <span data-plugin="counterup">{{ showAmount($InvoiceInfo->total_amount, 2, false) }}</span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Invoice Price</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-4">
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
                                <h3 class="text-dark mt-1">&#2547; <span data-plugin="counterup">{{ showAmount($totalPaid, 2, false) }}</span></h3>
                                <p class="text-success mb-1 text-truncate">Total Paid Amount</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-4">
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
                                    <span data-plugin="counterup">{{ showAmount($totalDueAmount, 2, false) }}</span></h3>
                                <p class="text-danger mb-1 text-truncate">Total Due Amount</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    </div>
    {{-- end dashboard cards --}}
    

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <span style="font-size: 18px;" class="badge 
                        {{ $InvoiceInfo->status == 'paid' ? 'bg-success' : ($InvoiceInfo->status == 'partially_paid' ? 'bg-warning' : ($InvoiceInfo->status == 'cancelled' ? 'bg-secondary' : 'bg-danger')) }}">
                            {{ $InvoiceInfo->status }}
                    </span>

                    @can('payment-create')
                        <div class="text-end mb-3">
                            @if($totalDueAmount > 0)
                                <a href="{{ route('payment.create', $InvoiceInfo->id) }}" class="btn btn-primary waves-effect waves-light">
                                    Add Payment
                                </a>
                            @else
                                <a title="Balance is not available to create a payment." href="#" style="cursor: not-allowed;" class="btn btn-secondary waves-effect waves-light">
                                    Add Payment
                                </a>
                            @endif
                        </div>
                    @endcan

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>reference_no</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($payments))
                                @foreach($payments as $key => $payment)
                                <tr>
                                    {{-- <td>{{ $loop->iteration + ($quotations->currentPage()-1)*$quotations->perPage() }}</td> --}}
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ number_format($payment->amount, 2) }} tk</td>
                                    <td>{{ paymentMethod($payment->payment_method)  }}</td>
                                    <td>{{ $payment->reference_no ?? "N/A" }}</td>
                                    <td class="text-end">
                                        <a
                                            href="{{ route('payment.payment_items', [$payment->id, $InvoiceInfo->id]) }}"
                                            class="btn btn-warning btn-sm"
                                            title="View Quotation"
                                            >
                                             <i class="mdi mdi-eye"></i>
                                        </a>
                                        
                                        @can('payment-delete')
                                            <form action="{{ route('payment.delete', $payment->id) }}" method="POST" class="d-inline-block" id="paymentForm_{{$payment->id}}" >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                    onclick="askPassword({{$payment->id}})">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            @if (isset($payments) && $payments->count() > 0)
                                <tr>
                                    <td colspan="4" class="">
                                        <div class="d-flex justify-content-center" style="margin-left: 20px;">
                                            <span class=" fw-bold">{{ showAmount($totalPaid, 2, false) }} tk</span>
                                        </div>
                                    </td>
                                    <td colspan="2" class="text-center"></td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-end mt-3">
                        {{  isset($invoices) ? $invoices->links() : '' }}
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
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js'])

<script>
 function askPassword(paymentId){
        const password = prompt("Please enter your password to confirm deletion:");
        if (!password) return;
        const form = document.getElementById(`paymentForm_${paymentId}`);
        const input = document.createElement("input");
        input.type="hidden";
        input.name="password";
        input.value=password;

        form.appendChild(input);
        form.submit();
    }
    
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
@endsection
