@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Manage Users', 'subtitle' => 'Users'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>

                        <div class="pull-right">
                            @can('user-create')
                            <a class="btn btn-primary mb-2" href="{{ route('users.create') }}"></i> Add
                                New</a>
                            @endcan
                        </div>


                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Roles</th>
                                <th class="text-end">Action</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">{{ $item->email }}</td>
                                <td class="text-center">
                                    @if(!empty($item->getRoleNames()))
                                    @foreach($item->getRoleNames() as $v)
                                    <label class="badge bg-success">{{ $v }}</label>
                                    @endforeach
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('user-edit')
                                    <a href="{{ route('users.edit', $item->id) }}"
                                        class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-grease-pencil"></i>
                                    </a>
                                    @endcan

                                    @can('user-delete')
                                    @if (!$item->hasRole('Super Admin'))
                                        <button type="button"
                                            class="btn btn-danger waves-effect waves-light confirmationBtn"
                                            data-question="@lang('Are you sure to delete this User?')"
                                            data-action="{{ route('users.destroy', $item->id) }}">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    @endif
                                @endcan

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $data->links() }}</div>

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

@endsection