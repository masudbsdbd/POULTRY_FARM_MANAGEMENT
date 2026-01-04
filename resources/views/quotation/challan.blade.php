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

    @include('layouts.shared.page-title', ['title' => "Quotation : " . $quotationsInfo->title, 'subtitle' => 'Quotations'])

    {{-- dashboard cards --}}
    <div class="row g-3">   
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('quotation.index') }}" class="btn btn-info"><i class="mdi mdi-arrow-left"></i>back</a>
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
                                <h3 class="text-dark mt-1">&#2547; <span data-plugin="counterup"><span id="total-quot-amount">{{ showAmount($quotationsInfo->total_amount, 2, false) }}</span></span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Quatation Price</p>
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
                                <h3 class="text-dark mt-1">&#2547; <span data-plugin="counterup"><span id="total-invoiced-amount">{{ showAmount($quotationsInfo->used_product_amount, 2, false) }}</span></span></h3>
                                <p class="text-muted mb-1 text-truncate">Total Used Product Amount</p>
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
                                    <span data-plugin="counterup"><span id="total-not-invoiced-amount">{{ showAmount($quotationsInfo->total_amount - $quotationsInfo->used_product_amount, 2, false) }}</span></span></h3>
                                <p class="text-danger mb-1 text-truncate">Not Used Product Amount</p>
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
                    <div class="text-end mb-3">
                        @can('work-create')
                            <a href="{{ route('challan.create', $quotationId) }}" class="btn btn-primary waves-effect waves-light">
                                Add Work Progress
                            </a>
                        @endcan
                    </div>
                    <h1></h1>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Work Number</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Product Used</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($challans as $key => $challan)
                                <tr>
                                    {{-- <td>{{ $loop->iteration + ($challans->currentPage()-1)*$challans->perPage() }}</td> --}}
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $challan->challan_number }}</td>
                                    <td>{{ $challan->challan_date }}</td>
                                    <td>{{ $challan->total_amount }}</td>
                                    <td class="">
                                        @php
                                            $usedPercent = round(($challan->total_amount / $quotationsInfo->total_amount) * 100);
                                        @endphp
                                       
                                        <div class="progress" style="height:20px; width:120px; position: relative;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $usedPercent }}%;">
                                            {{ $usedPercent }}%
                                            </div>
                                            @if ($usedPercent < 10 )
                                                <p style="text-align: center; position: absolute; left: 40%; font-size: 12px;">{{ $usedPercent }}%</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        @can('work-edit')
                                            <a href="{{ route('challan.edit', $challan->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        @endcan
                                         <a target="_blank" href="{{ route('challan.download', $challan->id) }}" class="btn btn-primary btn-sm" title="Print Invoice">
                                            <i styl class="mdi mdi-printer"></i>
                                        </a>
                                        @can('work-delete')
                                            <form action="{{ route('challan.delete', $challan->id) }}" method="POST" class="d-inline-block" id="deleteForm_{{ $challan->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                    onclick="askPassword({{$challan->id}})">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-end mt-3">
                        {{ $challans->links() }}
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
function askPassword(challanId){
        const password = prompt("Please enter your password to confirm deletion:");
        if (!password) return;
        const form = document.getElementById(`deleteForm_${challanId}`);
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
