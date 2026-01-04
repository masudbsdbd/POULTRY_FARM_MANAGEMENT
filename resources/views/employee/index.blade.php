@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/spectrum-colorpicker2/dist/spectrum.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/clockpicker/dist/bootstrap-clockpicker.min.css', 'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css','node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Manage Employes'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title">{{ $pageTitle }}</h4>
                            @can('employee-create')
                                <a href="{{ route('employee.create') }}"
                                    class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add New</a>
                            @endcan
                        </div>
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Designation</th>
                                    <th class="text-center">Salary</th>
                                    <th class="text-center">Conveyance</th>
                                    <th class="text-center">Mobile</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employes as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <img class="rounded"
                                                src="{{ isset($item->image) ? asset('uploads/employees/' . $item->image) : asset('uploads/dummy-product.png') }}"
                                                width="50" alt="Product Image">
                                        </td>
                                        <td class="text-center">{{ substr($item->name, 0, 25) }}</td>
                                        <td class="text-center">{{ substr($item->designation, 0, 25) }}</td>
                                        <td class="text-center">{{ showAmount($item->salary) }} Tk</td>
                                        <td class="text-center">{{ showAmount($item->conveyance) }} Tk</td>
                                        <td class="text-center">{{ $item->mobile }}</td>
                                        <td class="text-center">{{ substr($item->email, 0, 25) }}</td>
                                        <td class="text-center">
                                            @if ($item->status == 1)
                                                <span class="badge badge-soft-primary">Activated</span>
                                            @else
                                                <span class="badge badge-soft-dark">Deactivated</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                        <td class="text-end">
                                            @can('employee-edit')
                                                <a title="Edit" href="{{ route('employee.edit', $item->id) }}"
                                                    class="btn btn-primary waves-effect waves-light"><i
                                                        class="mdi mdi-grease-pencil"></i></a>
                                            @endcan

                                            <button title="Create Liability" type="button"
                                                class="btn btn-dark waves-effect waves-light liabilityBtn"
                                                data-question="@lang('Are you sure to create salary liability for this employee?')"
                                                data-action="{{ route('employee.transaction.salary.liability', $item->id) }}">
                                                <i class="mdi mdi-file-chart"></i>
                                            </button>

                                            @can('employee-delete')
                                                <button title="Delete" type="button"
                                                    class="btn btn-danger waves-effect waves-light confirmationBtn"
                                                    data-question="@lang('Are you sure to delete this employee?')"
                                                    data-action="{{ route('employee.delete', $item->id) }}"><i
                                                        class="mdi mdi-trash-can-outline"></i></button>
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end mt-3">{{ $employes->links() }}</div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->

    {{-- <!-- Edit Modal -->
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
    </div><!-- /.modal-dialog --> --}}

    <div id="liabilityModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h4>Generate Monthly Liability</h4>
                    </div>
                    <form id="liabilityForm" class="px-3" action="" method="POST">
                        @csrf
                        <div class="my-3">
                            <div class="mb-3">
                                <label class="form-label">Select Month</label>
                                <input type="text" class="form-control" data-provide="datepicker"
                                    data-date-format="MM yyyy" data-date-min-view-mode="1" name="month" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="mb-3 text-end">
                            <button class="btn btn-primary" type="submit">Create Liability</button>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/pages/form-pickers.init.js','resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $(document).on('click', '.liabilityBtn', function() {
                    var modal = $('#liabilityModal');
                    let data = $(this).data();
                    $('#liabilityForm')[0].reset();
                    $('#liabilityForm').attr('action', data.action);
                    modal.modal('show');
                });
            })(jQuery);
        });
    </script>
@endsection
