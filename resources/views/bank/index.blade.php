@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Manage Bank Info'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @can('bank-create')
                        <a href="{{ route('bank.create') }}"
                            class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add New</a>
                        @endcan
                    </div>

                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Account No</th>
                                <th class="text-center">Balance</th>
                                <th class="text-center">Account Name</th>
                                <th class="text-center">Bank Name</th>
                                <th class="text-center">Branch Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        @php
                        $finalBalance = 0;
                        @endphp
                        <tbody>
                            @foreach ($banks as $item)
                            @php
                            $finalBalance += $item->balance;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->account_no }}</td>
                                <td class="text-center">{{ showAmount($item->balance) }}</td>
                                <td class="text-center">{{ $item->account_name }}</td>
                                <td class="text-center">{{ $item->bank_name }}</td>
                                <td class="text-center">{{ $item->branch_name }}</td>
                                <td class="text-center">
                                    @if ($item->status == 1)
                                    <span class="badge badge-soft-primary">Activated</span>
                                    @else
                                    <span class="badge badge-soft-dark">Deactivated</span>
                                    @endif
                                </td>

                                <td class="text-end">

                                <a href="{{ route('bank.individual.trx', $item->id) }}" title="View"
                                    class="btn btn-success waves-effect waves-light expenseHeadBtn">
                                        <i class="mdi mdi-eye"></i>
                                </a>


                                    @can('bank-edit')
                                    <a class="btn btn-primary waves-effect waves-light"
                                        href="{{ route('bank.edit', $item->id) }}"><i
                                            class="mdi mdi-grease-pencil"></i></a>
                                    @endcan
                                    @can('bank-delete')
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this customer?')"
                                        data-action="{{ route('bank.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @if (!$banks->isEmpty())
                        <tfoot>
                            <tr style="font-size: 16px;">
                                <td class="fw-bold">Total:</td>
                                <td></td>
                                <td class="text-center"><strong>{{ showAmount($finalBalance) }} Tk</strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $banks->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->
<!-- Edit Modal -->

{{-- <div id="editCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Edit Bank Info</h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="account_name" class="form-label">Account Name</label>
                        <input class="form-control" type="text" id="account_name" name="account_name" required
                            placeholder="Account Name">
                    </div>
                    <div class="mb-3">
                        <label for="account_no" class="form-label">Account Number</label>
                        <input class="form-control" type="text" id="account_no" name="account_no" required
                            placeholder="Account Number">
                    </div>
                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input class="form-control" type="text" id="bank_name" name="bank_name" required
                            placeholder="Bank Name">
                    </div>
                    <div class="mb-3">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input class="form-control" type="text" id="branch_name" name="branch_name" required
                            placeholder="Branch Name">
                    </div>
                    <div class="mb-3 d-flex">
                        <label for="status">Status</label>
                        <label class="switch m-0">
                            <input id="status" type="checkbox" class="toggle-switch" name="editcatstatus">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog --> --}}

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
{{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $('.createCatBtn').on('click', function() {
                    var modal = $('#addCatModal');
                    modal.modal('show');
                });

                $('.editCatBtn').on('click', function() {
                    var modal = $('#editCatModal');
                    let data = $(this).data();
                    let url = data.route;
                    console.log(data);
                    $('#editForm').attr('action', url);
                    modal.find('input[name="account_name"]').val(data.account_name);
                    modal.find('input[name="account_no"]').val(data.account_no);
                    modal.find('input[name="bank_name"]').val(data.bank_name);
                    modal.find('input[name="branch_name"]').val(data.branch_name);
                    if (data.status == 1) {
                        modal.find('input[name="editcatstatus"]').prop('checked', true);
                    }

                    if (data.status == 0) {
                        modal.find('input[name="editcatstatus"]').prop('checked', false);
                    }
                    modal.modal('show');
                });
            })(jQuery);
        });
    </script> --}}
@endsection