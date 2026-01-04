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

    @include('layouts.shared.page-title', ['title' => 'Quotation Items', 'subtitle' => 'Quotations'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="text-end mb-3">
                        <a href="{{ route('quotation.item.create') }}" class="btn btn-primary waves-effect waves-light">
                            Add New
                        </a>
                    </div>

                    <h4 class="header-title">{{ $pageTitle }}</h4>

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Quotation</th>
                                <th>Customer</th>
                                <th>Quotation Date</th>
                                <th>Expiry Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
@php $sl = 1; @endphp
@foreach ($items as $quotationId => $quotationItems)
    <tr class="bg-light">
        <td colspan="8"><strong>Quotation #{{ $quotationItems->first()->quotation->quotation_number ?? '--' }}</strong></td>
    </tr>
    @foreach ($quotationItems as $item)
        <tr>
            <td>{{ $sl++ }}</td>
            <td>{{ $item->quotation->quotation_number ?? '--' }}</td>
            <td>{{ $item->quotation->customer->name ?? '--' }}</td>
            <td>{{ showDateTime($item->quotation->quotation_date) }}</td>
            <td>{{ $item->quotation->expiry_date ? showDateTime($item->quotation->expiry_date) : '--' }}</td>
            <td>{{ showAmount($item->total_amount, 2) }} Tk</td>
            <td>
                @if ($item->status == 1)
                    <span class="badge badge-soft-primary">Activated</span>
                @else
                    <span class="badge badge-soft-dark">Inactive</span>
                @endif
            </td>
            <td class="text-end">
                <!-- Action buttons -->
            </td>
        </tr>
    @endforeach
@endforeach

<div class="d-flex justify-content-end mt-3">{{ $items->links() }}</div>

                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">{{ $items->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>

<!-- Quotation View Modal -->
<div class="modal fade" id="quotationViewModal" tabindex="-1" aria-labelledby="quotationViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quotationViewModalLabel">Quotation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered mb-3">
                    <tr>
                        <th>Quotation Number</th>
                        <td id="modalQuotationNumber"></td>
                    </tr>
                    <tr>
                        <th>Customer</th>
                        <td id="modalCustomer"></td>
                    </tr>
                    <tr>
                        <th>Quotation Date</th>
                        <td id="modalQuotationDate"></td>
                    </tr>
                    <tr>
                        <th>Expiry Date</th>
                        <td id="modalExpiryDate"></td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td id="modalTotalAmount"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="modalStatus"></td>
                    </tr>
                </table>
                <div>
                    <h6>Note:</h6>
                    <p id="modalNote" class="border p-2 bg-light rounded"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
<script>
$(document).ready(function() {
    $('.viewQuotation').on('click', function() {
        var button = $(this);
        var quotationNumber = button.data('quotation-number');
        var customer = button.data('customer');
        var title = button.data('title');
        $('#quotationViewModalLabel').text(title);
        $('#modalQuotationNumber').text(quotationNumber);
        $('#modalCustomer').text(customer);
        $('#modalQuotationDate').text(button.data('quotation-date'));
        $('#modalExpiryDate').text(button.data('expiry-date'));
        $('#modalTotalAmount').text(button.data('total-amount'));
        var status = button.data('status');
        if(status == 1){
            $('#modalStatus').html('<span class="badge badge-soft-primary">Activated</span>');
        } else {
            $('#modalStatus').html('<span class="badge badge-soft-dark">Inactive</span>');
        }
        $('#modalNote').text(button.data('note'));
        $('#quotationViewModal').modal('show');
    });
});
</script>
@endsection
