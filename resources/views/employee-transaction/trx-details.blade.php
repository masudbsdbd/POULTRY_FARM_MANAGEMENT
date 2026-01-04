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
                                    <p><strong>Name:</strong> {{ $employee->name }}</p>
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
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Salary Amount</th>
                                    <th class="text-center">Received Amount</th>
                                    <th class="text-center">Punishment</th>
                                    <th class="text-center">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $runningBalance = 0;
                                    $totalSalaryAmount = 0;
                                    $totalPunishment = 0;
                                    $totalReceivedAmount = 0;
                                @endphp

                                @foreach ($informations as $item)
                                    @php
                                        $runningBalance -= $item->salary_amount;
                                        $runningBalance += $item->received_amount;

                                        $totalSalaryAmount += $item->salary_amount;
                                        $totalPunishment += $item->punishment;
                                        $totalReceivedAmount += $item->received_amount;
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ showDateTime($item->salary_date) }}</td>
                                        <td class="text-center">{{ showAmount($item->salary_amount) }} Tk</td>
                                        <td class="text-center">{{ showAmount($item->received_amount) }} Tk</td>
                                        <td class="text-center">{{ showAmount($item->punishment) }} Tk</td>
                                        <td class="text-center">{{ showAmount($runningBalance) }} Tk</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total</th>
                                    <th class="text-center">{{ showAmount($totalSalaryAmount) }} Tk</th>
                                    <th class="text-center">{{ showAmount($totalReceivedAmount) }} Tk</th>
                                    <th class="text-center">{{ showAmount($totalPunishment) }} Tk</th>
                                    <th class="text-center">
                                        @if($runningBalance > 0) 
                                            Advanced: {{ showAmount($runningBalance) }} Tk
                                        @else
                                            Due: {{ showAmount(abs($runningBalance)) }} Tk
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>



                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->
    <!-- Edit Modal -->

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
    <!-- <script>
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
    </script> -->
@endsection
