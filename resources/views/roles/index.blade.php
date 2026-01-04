@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Manage Roles', 'subtitle' => 'Users'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @can('role-create')

                        <a href="{{ route('roles.create') }}"
                            class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add New</a>
                        @endcan

                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Name</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->name }}</td>

                                <td class="text-end">
                                    <button title="View" type="button"
                                        class="btn btn-primary waves-effect waves-light roleDetailBtn"
                                        onclick="showRoleDetailModal(this)"
                                        data-role-name="{{ $item->name }}"
                                        data-permissions='@json($item->permissions)'>
                                        <i class="mdi mdi-eye"></i>
                                    </button>


                                    {{-- @if ($item->name !== 'Super Admin') --}}
                                    @can('role-edit')
                                    <a href="{{ route('roles.edit', $item->id) }}"
                                        class="btn btn-blue waves-effect waves-light"><i
                                            class="mdi mdi-grease-pencil"></i></a>
                                    @endcan

                                    @can('role-delete')
                                    <form method="POST" action="{{ route('roles.destroy', $item->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger waves-effect waves-light">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </form>
                                    {{-- @endif --}}
                                    @endcan


                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $roles->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->


<div id="permissionDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Role Details for <span id="customerName"></span></h4>
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center"> Permission Name</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
<script>
    function showRoleDetailModal(button) {
        let roleName = $(button).data('role-name');
        let permissions = $(button).data('permissions');

        if (!roleName || !permissions) {
            console.error('Data or role name is undefined');
            return;
        }

        let modal = $('#permissionDetail');
        modal.find('#customerName').text(roleName);

        let tableBody = modal.find('table tbody');
        tableBody.empty();

        permissions.forEach((permission, index) => {
            let row = `
            <tr>
                <td>${index + 1}</td>
                <td class="text-center">${permission.name}</td>
            </tr>`;
            tableBody.append(row);
        });

        modal.modal('show');
    }
</script>
@endsection