@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => 'Create Employee', 'subtitle' => 'Employes'])

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <form
                            action="{{ isset($employee) ? route('employee.store', $employee->id) : route('employee.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="example-select" class="form-label">Product Image</label>
                                        <div class="form-group">
                                            <div class="image-upload">
                                                <div class="thumb">
                                                    <div class="avatar-preview">
                                                        <div class="profilePicPreview"
                                                            style="background-image: url({{ isset($employee) ? (isset($employee->image) ? asset('uploads/employees/' . $employee->image) : asset('uploads/default-picker.png')) : asset('uploads/default-picker.png') }})">
                                                            <button type="button" class="remove-image"><i
                                                                    class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-edit">
                                                        <input type="file" class="profilePicUpload" name="image"
                                                            id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                        <label for="profilePicUpload1"
                                                            class="btn bg--primary text-dark">@lang('Browse Image')</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Name</label>
                                        <input type="text" id="simpleinput" class="form-control" placeholder="Name"
                                            name="name" required
                                            value="{{ old('name', isset($employee) ? $employee->name : '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="compnay-name" class="form-label">Designation</label>
                                        <input type="text" id="compnay-name" class="form-control"
                                            placeholder="designation Name" name="designation" required
                                            value="{{ old('designation', isset($employee) ? $employee->designation : '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="example-nid" class="form-label">NID</label>
                                        <input type="number" id="example-nid" class="form-control" name="nid"
                                            placeholder="NID"
                                            value="{{ old('nid', isset($employee) ? $employee->nid : '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="example-mobile" class="form-label">Mobile</label>
                                        <input type="number" id="example-mobile" class="form-control" name="mobile"
                                            placeholder="Mobile" required
                                            value="{{ old('mobile', isset($employee) ? $employee->mobile : '') }}">
                                    </div>

                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="example-email" class="form-label">Email</label>
                                        <input type="email" id="example-email" name="email" class="form-control"
                                            placeholder="Email"
                                            value="{{ old('email', isset($employee) ? $employee->email : '') }}">
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="joining_date" class="form-label">Joining Date</label>
                                        <input type="date" id="joining_date" class="form-control" name="joining_date"
                                            placeholder="Joining Date" required
                                            value="{{ old('joining_date', isset($employee) ? $employee->joining_date : '') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-group">
                                        <strong>Password:</strong>
                                        <input type="password" name="password" placeholder="Password" class="form-control" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <strong>Confirm Password:</strong>
                                        <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control" autocomplete="off" required>
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="salary_amount" class="form-label">Salary Amount</label>
                                        <input type="number" id="salary_amount" class="form-control" name="salary"
                                            placeholder="Salary Amount" required min="0"
                                            value="{{ old('salary', isset($employee) ? showAmount($employee->salary, 2, false) : '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="conveyance_amount" class="form-label">Conveyance</label>
                                        <input type="number" id="conveyance_amount" class="form-control"
                                            name="conveyance" placeholder="Conveyance" min ="0"
                                            value="{{ old('conveyance', isset($employee) ? showAmount($employee->conveyance, 2, false) : '') }}">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="example-textarea" class="form-label">Address</label>
                                        <textarea class="form-control" id="example-textarea" placeholder="Address" rows="5" name="address" required> {{ old('address', isset($employee) ? $employee->address : '') }} </textarea>
                                    </div>

                                    <div class="mb-3 d-flex">
                                        <label for="status">Status</label>
                                        <label class="switch m-0">
                                            <input id="status" type="checkbox" class="toggle-switch"
                                                name="{{ isset($employee) ? 'editbrandstatus' : 'status' }}"
                                                @checked(isset($employee) ? ($employee->status == 1 ? true : false) : true)>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light">{{ isset($employee) ? 'Update Employee' : 'Add New Employee' }}</button>
                                    </div>
                                </div> <!-- end col -->
                            </div>
                        </form>
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
@endsection
