@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Death List', 'subtitle' => 'Death List'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="col-md-6 col-xl-4" style="margin-left: 10px; margin-top: 10px;"> 
                    <a href="{{ route('customer.manageBatch', $bathInfo->id) }}" class="btn btn-info"><i class="mdi mdi-arrow-left"></i>back</a>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        {{-- @can('unit-create') --}}
                        <button type="button" class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add
                            New</button>
                            {{-- @endcan --}}

                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Cause of Death</th>
                                <th>Death total</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                           @foreach ($deathLists as $list)
                               <tr>
                                    <td>{{ $loop->iteration + 1}}</td>
                                    <td>{{ $list->date_of_death }}</td>
                                    <td>{{ $list->cause_of_death ?? "n/a" }}</td>
                                    <td>{{ $list->total_deaths }}</td>
                                    <td class="text-end">
                                        <button type="button"
                                            class="btn btn-primary waves-effect waves-light editCatBtn"
                                            data-route="{{ route('death.store', $list->id) }}"
                                            data-date="{{ $list->date_of_death }}"
                                            data-total="{{ $list->total_deaths }}"
                                            data-cause="{{ $list->cause_of_death }}">
                                            <i class="mdi mdi-grease-pencil"></i>
                                        </button>

                                        {{-- @if($building->floor->isEmpty()) --}}
                                            <button type="button"
                                                class="btn btn-danger waves-effect waves-light confirmationBtn"
                                                data-question="@lang('Are you sure to delete this Building?')"
                                                data-action="{{ route('death.delete', $list->id) }}">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        {{-- @endif --}}
                                    </td>
                            </tr>
                           @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="d-flex justify-content-end mt-3">{{ $buildings->links() }}</div> --}}

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->


<!-- Create Modal -->
<div id="addCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Create New Unit</h4>
                </div>
                <form class="px-3" action="{{ route('death.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="batch_id" value="{{ $bathInfo->id }}">
                    <div class="mb-3">
                        <label for="date_of_death" class="form-label">Date</label>
                        <input type="date" class="form-control" name="date_of_death" required
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="total_deaths" class="form-label">Total Death</label>
                        <input class="form-control" type="number" id="total_deaths" name="total_deaths" required
                            placeholder="Building Name">
                    </div>
                    <div class="mb-3">
                        <label for="cause_of_death" class="form-label">Cause of Death</label>
                        <input class="form-control" type="text" id="cause_of_death" name="cause_of_death" placeholder="Building Name">
                    </div>
                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Edit Modal -->
<div id="editCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Edit Death Record</h4>
                </div>

                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="edit_date_of_death" class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_date_of_death"
                            name="date_of_death" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_total_deaths" class="form-label">Total Death</label>
                        <input class="form-control" type="number" id="edit_total_deaths"
                            name="total_deaths" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_cause_of_death" class="form-label">Cause of Death</label>
                        <input class="form-control" type="text" id="edit_cause_of_death"
                            name="cause_of_death">
                    </div>

                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    (function ($) {
        "use strict";

        $('.createCatBtn').on('click', function () {
            $('#addCatModal').modal('show');
        });

        $('.editCatBtn').on('click', function () {
            let modal = $('#editCatModal');
            let data = $(this).data();

            $('#editForm').attr('action', data.route);
            $('#edit_date_of_death').val(data.date);
            $('#edit_total_deaths').val(data.total);
            $('#edit_cause_of_death').val(data.cause);

            modal.modal('show');
        });

    })(jQuery);
});
</script>

@endsection