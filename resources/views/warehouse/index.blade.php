@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', [
            'title' => 'Warehouses',
            'subtitle' => 'Warehouse List',
        ])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <button type="button" class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add
                            New</button>
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Address</th>
                                    <th class="text-center">Manager</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach ($warehouses as $key => $warehouse)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">{{ $warehouse->warehouse_code ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $warehouse->warehouse_name }}</td>
                                        <td class="text-center">{{ $warehouse->warehouse_address ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            {{ $warehouse->manager_name ?? "N/A" }}
                                        </td>
                                        <td class="text-center">{{ $warehouse->warehouse_phone ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $warehouse->warehouse_email ?? 'N/A' }}</td>
                                        <td class="text-end">
                                            {{-- edit button --}}
                                            <button class="btn btn-blue waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#EditWarehouseModal_{{ $warehouse->id }}">
                                                <i class="mdi mdi-grease-pencil"></i>
                                            </button>

                                            {{-- delete button --}}
                                            <button class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#DeleteWarehouseModal_{{ $warehouse->id }}">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>

                                            {{-- delete warehouse start --}}
                                            <div id="DeleteWarehouseModal_{{ $warehouse->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="text-center mt-2 mb-4">
                                                                <h4>Are You Sure You Want To Perform This Action?</h4>
                                                            </div>
                                                            <form class="px-3" action="{{ route('warehouse.destroy', $warehouse->id) }}" method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                
                                                                <div class="mb-3 text-end">
                                                                    <button class="btn btn-danger" type="submit">Create</button>
                                                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                            {{-- delete warehouse end --}}


                                            <!-- Update Warehouse Modal Start -->
                                            <div id="EditWarehouseModal_{{ $warehouse->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="text-center mt-2 mb-4">
                                                                <h4>Create New Category</h4>
                                                            </div>
                                                            <form class="px-3" action="{{ route('warehouse.update', $warehouse->id) }}" method="POST">
                                                                @method('PUT')
                                                                @csrf
                                                                {{-- warehouse code --}}
                                                                <div class="mb-3">
                                                                    <label for="catename" class="form-label">Warehouse Code</label>
                                                                    <input class="form-control" type="text" id="warehouse_code" name="warehouse_code" value="{{ $warehouse->warehouse_code }}"
                                                                        placeholder="Code">
                                                                </div>

                                                                {{-- Warehouse Name --}}
                                                                <div class="mb-3">
                                                                    <label for="catename" class="form-label">Warehouse Name</label>
                                                                    <input class="form-control" type="text" id="warehouse_name" name="warehouse_name" required
                                                                    value="{{ $warehouse->warehouse_name }}" placeholder="Name">
                                                                </div>

                                                                {{-- Warehouse Address --}}
                                                                <div class="mb-3">
                                                                    <label for="catename" class="form-label">Warehouse Address</label>
                                                                    <textarea class="form-control" id="warehouse_address" name="warehouse_address"
                                                                    value="{{ $warehouse->warehouse_address }}" placeholder="Address"></textarea>
                                                                </div>
                                                                
                                                                {{-- Warehouse Manager --}}
                                                                <div class="mb-3">
                                                                    <label class="form-label">Warehouse Manager</label>
                                                                    <select id="warehouse_manager" class="form-control"
                                                                        data-width="100%" name="warehouse_manager">
                                                                        <option value="">Select Manager</option>
                                                                        @foreach ($users as $key => $user)
                                                                            <option {{ $user->id == $warehouse->warehouse_manager ? 'selected' : '' }} value="{{ $user->id }}">
                                                                                {{ $user->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                {{-- Warehouse Phone --}}
                                                                <div class="mb-3">
                                                                    <label for="catename" class="form-label">Warehouse Phone</label>
                                                                    <input class="form-control" type="text" id="warehouse_phone" name="warehouse_phone"
                                                                    value="{{ $warehouse->warehouse_phone }}" placeholder="Phone">
                                                                </div>

                                                                {{-- Warehouse Email --}}
                                                                <div class="mb-3">
                                                                    <label for="catename" class="form-label">Warehouse Email</label>
                                                                    <input class="form-control" type="text" id="warehouse_email" name="warehouse_email"
                                                                    value="{{ $warehouse->warehouse_email }}" placeholder="Email">
                                                                </div>
                                                                
                                                                {{-- <div class="mb-3 d-flex">
                                                                    <label for="warehouse_status">Status</label>
                                                                    <label class="switch m-0">
                                                                        <input id="warehouse_status" type="checkbox" class="toggle-switch" {{ $warehouse->warehouse_status == 1 ? 'checked' : '' }}>
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </div> --}}
                                                                <div class="mb-3 text-end">
                                                                    <button class="btn btn-primary" type="submit">Update</button>
                                                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                            {{-- Update Warehouse Modal End --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">{{ $warehouses->links() }}</div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->


    <!-- Create Warehouse Modal -->
    <div id="addWarehouseModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h4>Create New Category</h4>
                    </div>
                    <form class="px-3" action="{{ route('warehouse.store') }}" method="POST">
                        @csrf
                        {{-- warehouse code --}}
                        <div class="mb-3">
                            <label for="catename" class="form-label">Warehouse Code</label>
                            <input class="form-control" type="text" id="warehouse_code" name="warehouse_code"
                                placeholder="Code">
                        </div>

                        {{-- Warehouse Name --}}
                        <div class="mb-3">
                            <label for="catename" class="form-label">Warehouse Name</label>
                            <input class="form-control" type="text" id="warehouse_name" name="warehouse_name" required
                                placeholder="Name">
                        </div>

                        {{-- Warehouse Address --}}
                        <div class="mb-3">
                            <label for="catename" class="form-label">Warehouse Address</label>
                            <textarea class="form-control" id="warehouse_address" name="warehouse_address"
                                placeholder="Address"></textarea>
                        </div>
                        
                        {{-- Warehouse Manager --}}
                        <div class="mb-3">
                            <label class="form-label">Warehouse Manager</label>
                            <select id="warehouse_manager" class="form-control"
                                data-width="100%" name="warehouse_manager">
                                <option value="">Select Manager</option>
                                @foreach ($users as $key => $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Warehouse Phone --}}
                        <div class="mb-3">
                            <label for="catename" class="form-label">Warehouse Phone</label>
                            <input class="form-control" type="text" id="warehouse_phone" name="warehouse_phone"
                                placeholder="Phone">
                        </div>

                        {{-- Warehouse Email --}}
                        <div class="mb-3">
                            <label for="catename" class="form-label">Warehouse Email</label>
                            <input class="form-control" type="text" id="warehouse_email" name="warehouse_email"
                                placeholder="Email">
                        </div>
                        
                        {{-- <div class="mb-3 d-flex">
                            <label for="warehouse_status">Status</label>
                            <label class="switch m-0">
                                <input id="warehouse_status" type="checkbox" value="1" class="toggle-switch" name="warehouse_status" checked>
                                <span class="slider round"></span>
                            </label>
                        </div> --}}
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
                    <h4>Edit Category</h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="catename" class="form-label">Category Name</label>
                        <input class="form-control" type="text" id="catename" name="name" required
                            placeholder="Category Name">
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
                    var modal = $('#addWarehouseModal');
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
