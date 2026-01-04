@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Subcategories', 'subtitle' => 'Products'])

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
                                <th class="text-center">Category Name</th>
                                <th class="text-center">Number of Products</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($subcategories as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">{{ $item->category->name }}</td>
                                <td class="text-center">
                                    @php
                                    $productCount = $item->products->count();
                                    @endphp
                                    {{ $productCount }} {{ $productCount == 0 || $productCount == 1 ? 'Unit' : 'Units' }}
                                </td>
                                <td class="text-center">
                                    @if ($item->status == 1)
                                    <span class="badge badge-soft-primary">Activated</span>
                                    @else
                                    <span class="badge badge-soft-dark">Deactivated</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('sub-category-edit')
                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light editSubCatBtn"
                                        data-name="{{ $item->name }}" data-catid="{{ $item->category->id }}"
                                        data-status="{{ $item->status }}"
                                        data-route="{{ route('subcategory.store', $item->id) }}"><i
                                            class="mdi mdi-grease-pencil"></i></button>
                                    @endcan
                                    @can('sub-category-delete')
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this subcategory?')"
                                        data-action="{{ route('subcategory.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                    @endcan

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $subcategories->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->


<!-- Create Modal -->
<div id="addSubCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Create New Subcategory</h4>
                </div>
                <form class="px-3" action="{{ route('subcategory.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="subcatename" class="form-label">Select Category</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">Choose one</option>
                            @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subcatename" class="form-label">Subcategory Name</label>
                        <input class="form-control" type="text" id="subcatename" name="name" required
                            placeholder="Subcategory Name">
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
                    <h4>Edit Subcategory</h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="subcatename" class="form-label">Select Category</label>
                        <select id="subCatSelect" class="form-select" name="category_id" required>
                            <option value="">Choose one</option>
                            @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subcatename" class="form-label">Subcategory Name</label>
                        <input class="form-control" type="text" id="subcatename" name="name" required
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
                var modal = $('#addSubCatModal');
                modal.modal('show');
            });

            $('.editSubCatBtn').on('click', function() {
                var modal = $('#editSubCatModal');
                let data = $(this).data();
                // console.log(data);
                let url = data.route;
                $('#editForm').attr('action', url);

                modal.find('input[name="name"]').val(data.name);

                $('#subCatSelect').val(data.catid);

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