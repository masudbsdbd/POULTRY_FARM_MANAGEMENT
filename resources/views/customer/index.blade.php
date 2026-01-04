@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'All Customers', 'subtitle' => 'Customers'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between {{ Route::is('customer.index') ? '' : 'mb-3' }}">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @if (Route::is('customer.index'))
                        @can('customer-create')
                        <a href="{{ route('customer.create') }}"
                            class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add New</a>
                        @endcan
                        @endif
                    </div>


                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Name</th>
                                {{-- <th class="text-center">Code</th> --}}
                                {{-- <th class="text-center">Compnay</th> --}}
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Batches</th>
                                {{-- <th class="text-center">TRN Number</th> --}}
                                <th class="text-center">Status</th>
                                <!-- <th class="text-center">Advance</th>
                                <th class="text-center">Sell Due</th> -->
                                {{-- <th class="text-center">Created At</th> --}}
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($customers as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                {{-- <td class="text-center">{{ $item->code }}</td> --}}
                                {{-- <td class="text-center">{{ $item->company }}</td> --}}
                                <td class="text-center">{{ $item->mobile }}</td>
                                <td class="text-center">{{ $item->email }}</td>
                                <td class="text-center">
                                    <a href="{{ route('customer.batches', $item->id) }}" class="btn btn-primary waves-effect waves-light">
                                        {{ $item->batches->count() }}
                                    </a>
                                </td>
                                {{-- <td class="text-center">{{ $item->trn_number }}</td> --}}
                                <td class="text-center">
                                    @if ($item->status == 1)
                                    <span class="badge badge-soft-primary">Activated</span>
                                    @else
                                    <span class="badge badge-soft-dark">Deactivated</span>
                                    @endif
                                </td>
                                {{-- <td class="text-center">{{ showDateTime($item->created_at) }}</td> --}}
                                <td class="text-end">
                                    <!-- <a title="Customer Ledger" href="{{ route('customer.ledger.sell.index', $item->id) }}"
                                        class="btn btn-danger waves-effect waves-light">
                                        <i class="mdi mdi-information"></i></a> -->
                                    @can('customer-edit')
                                    <a href="{{ route('customer.edit', $item->id) }}"
                                        class="btn btn-primary waves-effect waves-light"><i
                                            class="mdi mdi-grease-pencil"></i></a>
                                    @endcan

                                    @can('customer-delete')
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this customer?')"
                                        data-action="{{ route('customer.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                    @endcan

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $customers->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        (function($) {
            "use strict";

            $('.paymentBtn').on('click', function() {
                var modal = $('#advancePaymentModal');
                let data = $(this).data();
                modal.find('.supplierName').text(data.customername);
                modal.find('.customeradvance').text(data.customeradvance);
                modal.find('.customerdue').text(data.customerdue);
                $('#paymentForm')[0].reset();
                $('#bankInfo').addClass('d-none');
                $('#bank_id').prop('required', false);
                $('#paymentForm').attr('action', data.action);
                modal.modal('show');
            });

            $('#paymentMethod').on('change', function() {
                if ($(this).val() == 2) {
                    $('#bankInfo').removeClass('d-none');
                    $('#bank_id').prop('required', true);
                    $('#withdrawer_name').prop('required', true);
                } else {
                    $('#bankInfo').addClass('d-none');
                    $('#bank_id').prop('required', false);
                    $('#withdrawer_name').prop('required', false);
                }
            });

        })(jQuery);
    });
</script> -->
@endsection