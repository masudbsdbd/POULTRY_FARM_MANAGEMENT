@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Product Unit', 'subtitle' => 'Products'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @can('unit-create')
                        <button type="button" class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add
                            New</button>
                            @endcan

                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($units as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">
                                    @if ($item->status == 1)
                                    <span class="badge badge-soft-primary">Activated</span>
                                    @else
                                    <span class="badge badge-soft-dark">Deactivated</span>
                                    @endif
                                </td>
                                <td class="text-end">

                        @can('unit-edit')

                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light editCatBtn"
                                        data-name="{{ $item->name }}" data-status="{{ $item->status }}"
                                        data-route="{{ route('unit.store', $item->id) }}"><i class="mdi mdi-grease-pencil"></i></button>
                                        @endcan

                        @can('unit-delete')

                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this Unit?')"
                                        data-action="{{ route('unit.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                                @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $units->links() }}</div>

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
                <form class="px-3" action="{{ route('unit.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="catename" class="form-label">Unit Name</label>
                        <input class="form-control" type="text" id="catename" name="name" required
                            placeholder="Unit Name">
                    </div>
                    <div class="mb-3 d-flex">
                        <label for="createcatstatus">Status</label>
                        <label class="switch m-0">
                            <input id="createcatstatus" checked type="checkbox" class="toggle-switch" name="status">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="submit">Create</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
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
                <h4>Edit Unit</h4>
            </div>
            <form id="editForm" class="px-3" action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="catename" class="form-label">Unit Name</label>
                    <input class="form-control" type="text" id="catename" name="name" required
                        placeholder="Unit Name">
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
                let data = $(this).data();
                let url = data.route;
                $('#editForm').attr('action', url);
                modal.find('input[name="name"]').val(data.name);

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
</script>
@endsection