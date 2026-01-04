@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', [
            'title' => $pageTitle,
            'subtitle' => 'Manage Transactions Info',
        ])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-2">{{ $pageTitle }}</h4>


                        <div class="border rounded p-3 mb-3 bg-light">
                            <h4 class="text-danger">Bank Details</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Name:</strong> {{ !empty($bankInfo->bank_name) ? $bankInfo->bank_name : '-' }}
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Opening Date:</strong> {{ !empty($bankInfo->entry_date) ? $bankInfo->entry_date : '-' }}
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Branch:</strong> {{ !empty($bankInfo->branch_name) ? $bankInfo->branch_name : '-' }}
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Account Holder:</strong> {{ !empty($bankInfo->account_name) ? $bankInfo->account_name : '-' }}
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Account Number:</strong> {{ !empty($bankInfo->account_no) ? $bankInfo->account_no : '-' }}
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Balance:</strong> {{ !empty(showAmount($bankInfo->balance)) ? showAmount($bankInfo->balance) : '-' }} Tk
                                </div>

                            </div>
                        </div>




                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Account No</th>
                                    <th class="text-center">Account Name</th>
                                    <th class="text-center">Bank Name</th>
                                    <th class="text-center">Deposit / Withdray By</th>
                                    <th class="text-center">Debit(TK)</th>
                                    <th class="text-center">Credit(TK)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end">Description</th>
                                </tr>
                            </thead>


                            <tbody>
                                @php
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                       
                                @endphp
                                @foreach ($bankTransactions as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ @$item->bank->account_no }}</td>
                                        <td class="text-center">{{ @$item->bank->account_name }}</td>
                                        <td class="text-center">{{ @$item->bank->bank_name }}</td>
                                        <td class="text-center">
                                            {{ $item->depositor_name == '' ? $item->withdrawer_name : $item->depositor_name }}
                                        </td>
                                        <td class="text-center">{{ showAmount($item->debit) }}</td>
                                        <td class="text-center">{{ showAmount($item->credit) }}</td>
                                        <td class="text-center">
                                            @if ($item->status == 1)
                                                <span class="badge badge-soft-danger">Debit</span>
                                            @else
                                                <span class="badge badge-soft-success">Credit</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if (strlen($item->description) < 25)
                                                {{ $item->description }}
                                            @else
                                                {{ substr($item->description, 0, 24) }}...
                                                <a data-paragraph="{{ $item->description }}" href="javascript:void(0)"
                                                    class="text-primary descBtn" href="">
                                                    See More</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $totalDebit += $item->debit;
                                        $totalCredit += $item->credit;
                                    @endphp
                                @endforeach
                                @if (!$bankTransactions->isEmpty())
                            <tfoot>
                                <tr style="font-size: 16px;">
                                    <td class="fw-bold">Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><strong>{{ showAmount($totalDebit) }} Tk</strong></td>
                                    <td class="text-center"><strong>{{ showAmount($totalCredit) }} Tk</strong></td>
                                    <td></td>
                                    <td class="text-end"><strong>Grand Total: {{ showAmount($totalCredit - $totalDebit) }} Tk</strong></td>
                                </tr>
                            </tfoot>
                            @endif
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->
    <!-- Edit Modal -->
    <div id="editCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Bank Transactions Description</h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <p id="modal_description"></p>
                    </div>
                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Account Description</h4>
                    @csrf
                    <p class="descParagraph"></p>
                    <div class="text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
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

                $('.createCatBtn').on('click', function() {
                    var modal = $('#addCatModal');
                    modal.modal('show');
                });

                $('.editCatBtn').on('click', function() {
                    var modal = $('#editCatModal');
                    var description = $('#modal_description');
                    let data = $(this).data();
                    description.text(data.description);
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
