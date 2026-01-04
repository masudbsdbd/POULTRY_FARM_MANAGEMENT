@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', [
            'title' => $pageTitle,
            'subtitle' => 'Manage Transaction',
        ])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <div class="border rounded p-3 mb-3 bg-light">
                            <h4 class="text-danger">Employee Details</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Name:</strong> {{ $employee->name }}
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Designation:</strong> {{ $employee->designation }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Code:</strong> {{ $employee->code }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Mobile:</strong> {{ $employee->mobile }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Address:</strong> {{ $employee->address }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Email:</strong> {{ $employee->email }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>National Id:</strong> {{ $employee->nid }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Joining Date:</strong> {{ showDateTime($employee->joining_date) }}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Salary:</strong> {{ showAmount($employee->salary) }} Tk</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Conveyance:</strong> {{ showAmount($employee->conveyance) }} Tk</p>
                                </div>
                            </div>
                        </div>

                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Month Name</th>
                                    <th class="text-center">Total Paid</th>
                                    <th class="text-center">Punishment</th>
                                    <th class="text-center">Due</th>
                                    <th class="text-center">Advance</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($informations as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ showDateName($item->month) }}</td>
                                        <td class="text-center">{{ showAmount($item->total_paid) }} Tk</td>
                                        <td class="text-center">{{ showAmount($item->punishment) }} Tk</td>
                                        <td class="text-center">{{ showAmount($item->due) }} Tk</td>
                                        <td class="text-center">{{ showAmount($item->advance) }} Tk</td>

                                        <td class="text-end">
                                            <a title="Employee Transaction History"
                                                href="{{ route('employee.transaction.details', [$item->month, $employee->id]) }}"
                                                class="btn btn-blue waves-effect waves-light">
                                                <i class="mdi mdi-information"></i></a>

                                            <button title="Delete" type="button"
                                                class="btn btn-danger waves-effect waves-light confirmationBtn"
                                                data-question="@lang('Are you sure to delete this transaction history?')"
                                                data-action="{{ route('employee.transaction.month.delete', $item->id) }}"><i
                                                    class="mdi mdi-trash-can-outline"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="fw-bold" style="font-size: 20px">Total</td>
                                    <td></td>
                                    <td class="text-center">
                                        {{ showAmount(getEmpTrSum('total_paid', $employee->id, true)) }} Tk</td>
                                    <td class="text-center">
                                        {{ showAmount(getEmpTrSum('punishment', $employee->id, true)) }} Tk</td>
                                    <td class="text-center">{{ showAmount(getEmpTrSum('due', $employee->id, true)) }} Tk
                                    </td>
                                    <td class="text-center">{{ showAmount(getEmpTrSum('advance', $employee->id, true)) }}
                                        Tk</td>
                                    <td></td>
                                </tr>
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
                    <h4 id="titleBar"></h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="received_amount" class="form-label">Payment Amount (tk) </label>
                        <input class="form-control" type="number" id="received_amount" name="received_amount" required
                            placeholder="Payment Amount">
                    </div>
                    <div class="mb-3">
                        <label for="punishment" class="form-label">Punishment Amount</label>
                        <input class="form-control" type="number" id="punishment" name="punishment"
                            placeholder="Punishment Amount">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" rows="6" name="message" placeholder="Message"></textarea>
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

                $('.editCatBtn').on('click', function() {
                    var modal = $('#editCatModal');
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
