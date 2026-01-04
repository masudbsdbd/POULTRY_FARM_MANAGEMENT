@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', [
            'title' => "Employee's Transaction",
            'subtitle' => 'Manage Transaction',
        ])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-2">{{ $pageTitle }}</h4>
                            @php
                                $totalSalary    = $transactions->sum('total_salary');
                                $totalReceived  = $transactions->sum('total_received');
                                $totalPunishment = $transactions->sum('total_punishment');
                            @endphp

                            <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Mobile</th>
                                        <th class="text-center">Net Salary</th>
                                        <th class="text-center">Conveyance</th>
                                        <th class="text-center">Total Salary</th>
                                        <th class="text-center">Total Received</th>
                                        <th class="text-center">Punishment</th>
                                        <th class="text-center">Current Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->employee->name }}</td>
                                            <td class="text-center">{{ $item->employee->mobile }}</td>
                                            <td class="text-center">{{ showAmount($item->employee->salary) }} Tk</td>
                                            <td class="text-center">{{ showAmount($item->employee->conveyance) }} Tk</td>
                                            <td class="text-center">{{ showAmount($item->total_salary) }} Tk</td>
                                            <td class="text-center">{{ showAmount($item->total_received) }} Tk</td>
                                            <td class="text-center">{{ showAmount($item->total_punishment) }} Tk</td>
                                            <td class="text-center">
                                                @php
                                                    $advanceDue = $item->total_received - $item->total_salary;
                                                @endphp

                                                @if($advanceDue == 0)
                                                    <span class="text-muted fw-bold">Settled</span>
                                                @elseif($advanceDue > 0)
                                                    <span class="text-success fw-bold">Advance: {{ $advanceDue }} Tk</span>
                                                @else
                                                    <span class="text-danger fw-bold">Due: {{ abs($advanceDue) }} Tk</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a title="Employee Transaction History"
                                                href="{{ route('employee.transaction.salary.trx.details', $item->employee->id) }}"
                                                class="btn btn-blue waves-effect waves-light">
                                                    <i class="mdi mdi-information"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="fw-bold text-center" style="font-size: 18px">Total:</th>
                                        <th class="text-center fw-bold">{{ showAmount($transactions->sum('total_salary')) }} Tk</th>
                                        <th class="text-center fw-bold">{{ showAmount($transactions->sum('total_received')) }} Tk</th>
                                        <th class="text-center fw-bold">{{ showAmount($transactions->sum('total_punishment')) }} Tk</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>

                        <div class="d-flex justify-content-end mt-3">{{ $transactions->links() }}</div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->

    <!-- Edit Modal -->
    <div id="employeePaymentModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 id="titleBar"></h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="received_amount" class="form-label">Payment Amount (tk)</label>
                        <input class="form-control" type="number" id="received_amount" name="received_amount"
                            placeholder="Payment Amount">
                    </div>
                    <div class="mb-3">
                        <label for="punishment" class="form-label">Punishment Amount (tk)</label>
                        <input class="form-control" type="number" id="punishment" name="punishment"
                            placeholder="Punishment Amount">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="6" name="description" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
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
                $('.paymentBtn').on('click', function() {
                    var modal = $('#employeePaymentModal');
                    var titleBar = $('#titleBar');
                    let data = $(this).data();
                    let url = data.route;
                    titleBar.text("Employee Payment Record for " + data.name);
                    $('#editForm').attr('action', url);
                    modal.find('input[name="punishment"]').val(data.punishment);
                    modal.modal('show');
                });
            })(jQuery);
        });
    </script>
@endsection
