@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid py-4">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Role'])

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card rounded-lg">
                <div class="card-body p-5">
                    <h4 class="header-title mb-4 ">{{ $pageTitle }}</h4>

                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($role) ? route('roles.store', $role->id) : route('roles.store') }}"
                                method="POST" class="needs-validation" novalidate>
                                @csrf
                                


                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label fw-bold text-muted">Name</label>
                                    <input id="name" type="text" class="form-control shadow-sm rounded"
                                        placeholder="Enter role name" name="name"
                                        value="{{ old('name', isset($role) ? $role->name : '') }}" required>
                                        <div class="invalid-feedback">
                                        Please provide a valid role name.
                                    </div>
                                </div> <!-- end col -->

                                <!-- Permissions -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted">Permissions</label>

                                    <div class="form-check mb-2">
                                        <input type="checkbox" id="select-all-permissions" class="form-check-input">
                                        <label class="form-check-label fw-bold" for="select-all-permissions">Select All</label>
                                    </div>


                                    @foreach($permissions as $section => $perms)
                                        <h5 class="mt-4 mb-2 fw-bold">{{ $section }}</h5> <!-- Section Name -->

                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($perms as $value)
                                                <div class="form-check">
                                                    <input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}" class="form-check-input  permission-checkbox" 
                                                        @if(isset($rolePermissions) && in_array($value->id, $rolePermissions)) checked @endif>
                                                    <label class="form-check-label">{{ $value->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach


                                </div>

                                <div class="text-end mt-3">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($role) ? 'Update Role' : 'Add New Role' }}</button>
                                </div>



                            </form>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
    <!-- end row -->
</div> <!-- container -->

<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])

<script>
    $(document).ready(function () {
        $('#select-all-permissions').on('change', function () {
            $('.permission-checkbox').prop('checked', $(this).prop('checked'));
        });

        $('.permission-checkbox').on('change', function () {
            if (!$(this).prop('checked')) {
                $('#select-all-permissions').prop('checked', false);
            } else if ($('.permission-checkbox:checked').length === $('.permission-checkbox').length) {
                $('#select-all-permissions').prop('checked', true);
            }
        });
    });
</script>

@endsection
