@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Journal Head', 'subtitle' => 'B/S Accounts'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @can('sub-category-create')
                        <button type="button" class="mb-2 btn btn-primary waves-effect waves-light createSubCatBtn">Add
                            New</button>
                        @endcan

                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Main Account Type</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($bsType as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">
                                    @if ($item->main_type == 1)
                                    <span class="badge badge-soft-dark">Assets</span>
                                    @elseif($item->main_type == 2)
                                    <span class="badge badge-soft-dark">Libilities</span>
                                    @elseif($item->main_type == 3)
                                    <span class="badge badge-soft-dark">Equity</span>
                                    @elseif($item->main_type == 4)
                                    <span class="badge badge-soft-dark">Expense</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    @can('sub-category-edit')
                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light editSubCatBtn"
                                        data-name="{{ $item->name }}" data-catid="{{ $item->main_type }}"
                                        data-status="{{ $item->status }}"
                                        data-route="{{ route('bs.account.journal.head.store', $item->id) }}"><i
                                            class="mdi mdi-grease-pencil"></i></button>

                                    @endcan
                                    @can('sub-category-delete')

                                    @endcan

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $bsType->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->


<!-- Create Modal -->
<div id="addBsTypeModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Create New Head</h4>
                </div>
                <form class="px-3" action="{{ route('bs.account.journal.head.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="headName" class="form-label">Main Account Type</label>
                        <select class="form-select" name="main_type" required>
                            <option value="" readonly>Choose one</option>
                            <option value="1">Assets</option>
                            <option value="2">Libilities</option>
                            <option value="3">Equity</option>
                            <option value="4">Expense</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="headName" class="form-label">Journal Head Name</label>
                        <input class="form-control" type="text" id="headName" name="name" required
                            placeholder="Head Name">
                    </div>
                    <div class="mb-3 d-flex">
                        <label for="createsubcatstatus">Status</label>
                        <label class="switch m-0">
                            <input id="createsubcatstatus" checked type="checkbox" class="toggle-switch" name="status">
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
<div id="editSubCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Edit Head</h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="headName" class="form-label">Main Account Type</label>
                        <select id="headSelect" class="form-select" name="main_type" required>
                            <option value="" readonly>Choose one</option>
                            <option value="1">Assets</option>
                            <option value="2">Libilities</option>
                            <option value="3">Equity</option>
                            <option value="4">Expense</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="headName" class="form-label">Journal Head Name</label>
                        <input class="form-control" type="text" id="headName" name="name" required
                            placeholder="Subcategory Name">
                    </div>
                    <div class="mb-3 d-flex">
                        <label for="editsubcatstatus">Status</label>
                        <label class="switch m-0">
                            <input id="editsubcatstatus" type="checkbox" class="toggle-switch"
                                name="editsubcatstatus">
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
</div><!-- /.modal -->


<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        (function($) {
            "use strict";

            $('.createSubCatBtn').on('click', function() {
                var modal = $('#addBsTypeModal');
                modal.modal('show');
            });

            $('.editSubCatBtn').on('click', function() {
                var modal = $('#editSubCatModal');
                let data = $(this).data();
                // console.log(data);
                let url = data.route;
                $('#editForm').attr('action', url);

                modal.find('input[name="name"]').val(data.name);

                $('#headSelect').val(data.catid);

                if (data.status == 1) {
                    modal.find('input[name="editsubcatstatus"]').prop('checked', true);
                }

                if (data.status == 0) {
                    modal.find('input[name="editsubcatstatus"]').prop('checked', false);
                }
                modal.modal('show');
            });
        })(jQuery);
    });
</script>
@endsection