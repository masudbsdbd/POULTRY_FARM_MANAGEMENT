@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Income', 'subtitle' => 'Income'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-end">
                        @can('income-create')
                        <a href="{{ route('income.create') }}" class="btn btn-primary waves-effect waves-light">Add
                            New</a>
                        @endcan
                    </div>
                    <h4 class="header-title">{{ $pageTitle }}</h4>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Head</th>
                                <th class="text-center">Entry Type</th>
                                <th class="text-center">Effective Type</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Effective Amount</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incomes as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ showDateTime($item->entry_date) }}</td>
                                <td class="text-center">{{ $item->incomeList->name ?? '--' }}</td>
                                <td class="text-center">
                                    @if ($item->entry_type == 0)
                                        <span class="badge badge-soft-dark">manual</span>
                                    @else
                                        <span class="badge badge-soft-dark">journal</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                @if(isset($item->debit_or_credit))
                                @if($item->debit_or_credit == 'debit')
                                <span class="badge badge-soft-danger">Debit</span>
                                @else
                                <span class="badge badge-soft-success">Credit</span>
                                @endif
                                @else
                                ---
                                @endif
                                </td>

                                <td class="text-center">{{ showAmount($item->amount) }} Tk</td>
                                <td class="text-center">{{ showAmount($item->effective_amount) }} </td>

                                <td class="text-end">
                                    @can('income-list')
                                    <button title="View" type="button"
                                        class="btn btn-success waves-effect waves-light expenseDetailBtn"
                                        data-id="{{ $item->id }}"
                                        data-created_at="{{ showDateTime($item->created_at) }}"
                                        data-title="{{ @$item->incomeList->name }}"
                                        data-amount="{{ showAmount($item->amount) }}"
                                        data-description="{{ $item->description }}">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                    @endcan
                                    @can('income-edit')
                                    @if($item->entry_type == 0)
                                    <a href="{{ route('income.edit', $item->id) }}"
                                        class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-table-edit"></i></a>
                                    @else
                                    @endif

                                    @endcan
                                    @can('income-delete')
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this Income?')"
                                        data-action="{{ route('income.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $incomes->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->

<div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Expense Title</h4>
                @csrf
                <p class="descParagraph"></p>
                <div class="text-end">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<div id="sellDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header text-white">
                <h5 class="modal-title">Income Details</h5>
                <!-- Close button removed -->
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover mb-4">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="modalDate">---</td>
                                    <td id="modalTitle">---</td>
                                    <td id="modalAmount">---</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <h6><strong>Description:</strong></h6>
                            <p id="modalDescription">No description available.</p>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->


<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])

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



            $(document).on('click', '.expenseDetailBtn', function() {
                var modal = $('#sellDetail');

                var createdAt = $(this).data('created_at');
                var title = $(this).data('title');
                var amount = $(this).data('amount');
                var description = $(this).data('description');

                modal.find('#modalDate').text(createdAt);
                modal.find('#modalTitle').text(title);
                modal.find('#modalAmount').text(amount + ' Tk');
                modal.find('#modalDescription').text(description || 'No description available.');

                // Show the modal
                modal.modal('show');
            });




        })(jQuery);

    });
</script>
@endsection