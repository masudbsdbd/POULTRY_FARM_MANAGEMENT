@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Settings'])

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ route('setting.general.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="companyname" class="form-label">Company Name</label>
                                                        <input type="text" id="companyname" class="form-control"
                                                            placeholder="Company Name" name="company_name" required
                                                            value="{{ $generals->company_name }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="ownername" class="form-label">Owner Name</label>
                                                        <input type="text" id="ownername" class="form-control"
                                                            placeholder="Company Oner Name" name="owner_name" required
                                                            value="{{ $generals->owner_name }}">
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="trn_number" class="form-label">TRN Number</label>
                                                        <input type="text" id="trn_number" class="form-control"
                                                            placeholder="TRN Number" name="trn_number" required
                                                            value="{{ $generals->trn_number }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="contactone" class="form-label">Primary Contact
                                                            Number</label>
                                                        <input type="number" id="contactone" class="form-control"
                                                            placeholder="Primary Contact Number"
                                                            name="primary_contact_number" required
                                                            value="{{ $generals->primary_contact_number }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="contacttwo" class="form-label">Alternate Contact
                                                            Number</label>
                                                        <input type="number" id="contacttwo" class="form-control"
                                                            placeholder="Alternate Contact Number"
                                                            name="alternate_contact_number" required
                                                            value="{{ $generals->alternate_contact_number }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="emailone" class="form-label">Primary Email
                                                            Address</label>
                                                        <input type="email" id="emailone" class="form-control"
                                                            placeholder="Primary Email Address" name="primary_email_address"
                                                            required value="{{ $generals->primary_email_address }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="emailtwo" class="form-label">Alternate Email
                                                            Address</label>
                                                        <input type="email" id="emailtwo" class="form-control"
                                                            placeholder="Alternate Email Address"
                                                            name="alternate_email_address" required
                                                            value="{{ $generals->alternate_email_address }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="url" class="form-label">Website URL</label>
                                                        <input type="url" id="url" class="form-control"
                                                            placeholder="Website URL" name="website_url" required
                                                            value="{{ $generals->website_url }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="pagination" class="form-label">Table Pagination
                                                            Count</label>
                                                        <select id="pagination" class="form-select"
                                                            name="pagination" required>
                                                            <option value="10" @selected($generals->pagination == 10)>
                                                                10
                                                            </option>
                                                            <option value="20" @selected($generals->pagination == 20)>
                                                                20
                                                            </option>
                                                            <option value="30" @selected($generals->pagination == 30)>
                                                                30
                                                            </option>
                                                            <option value="40" @selected($generals->pagination == 40)>
                                                                40
                                                            </option>
                                                            <option value="50" @selected($generals->pagination == 50)>
                                                                50
                                                            </option>
                                                            <option value="60" @selected($generals->pagination == 60)>
                                                                60
                                                            </option>
                                                            <option value="70" @selected($generals->pagination == 70)>
                                                                70
                                                            </option>
                                                            <option value="80" @selected($generals->pagination == 80)>
                                                                80
                                                            </option>
                                                            <option value="90" @selected($generals->pagination == 90)>
                                                                90
                                                            </option>
                                                            <option value="100" @selected($generals->pagination == 100)>
                                                                100
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="subcatselect" class="form-label">BS
                                                            Module</label>
                                                        <select id="subcatselect" class="form-select"
                                                            name="bs_module" required>
                                                            <option value="0" @selected($generals->bs_module == 0)>
                                                                Deactivated
                                                            </option>
                                                            <option value="1" @selected($generals->bs_module == 1)>
                                                                Activated
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div> -->
                                            
                                                <!-- <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="subcatselect" class="form-label">Subcategy
                                                            Module</label>
                                                        <select id="subcatselect" class="form-select"
                                                            name="subcategory_module" required>
                                                            <option value="0" @selected($generals->subcategory_module == 0)>
                                                                Deactivated
                                                            </option>
                                                            <option value="1" @selected($generals->subcategory_module == 1)>
                                                                Activated
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div> -->

                                                <!-- <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="brandselect" class="form-label">Brand
                                                            Module</label>
                                                        <select id="brandselect" class="form-select" name="brand_module"
                                                            required>
                                                            <option value="0" @selected($generals->brand_module == 0)>
                                                                Deactivated
                                                            </option>
                                                            <option value="1" @selected($generals->brand_module == 1)>
                                                                Activated
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div> -->

                                                <!-- <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="barcodeselect" class="form-label">Product
                                                            Barcode</label>
                                                        <select id="barcodeselect" class="form-select" name="barcode"
                                                            required>
                                                            <option value="0" @selected($generals->barcode == 0)>
                                                                Deactivated
                                                            </option>
                                                            <option value="1" @selected($generals->barcode == 1)>
                                                                Activated
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div> -->

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">Address</label>
                                                        <input type="text" id="address" class="form-control"
                                                            placeholder="Address" name="address" required
                                                            value="{{ $generals->address }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h4 class="header-title mb-3">Logo and Favicon</h4>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="example-select" class="form-label">Profile Image</label>
                                                        <div class="form-group">
                                                            <div class="image-upload">
                                                                <div class="thumb">
                                                                    <div class="avatar-preview">
                                                                        <div class="profilePicPreview"
                                                                            style="background-image: url({{ asset('uploads/profile/' . $generals->user_image) }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                                                            <button type="button" class="remove-image"><i
                                                                                    class="fa fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="avatar-edit">
                                                                        <input type="file" class="profilePicUpload"
                                                                            name="user_image" id="profilePicUpload2"
                                                                            accept=".png, .jpg, .jpeg">
                                                                        <label for="profilePicUpload2"
                                                                            class="btn bg--primary text-dark">@lang('Profile Image')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="example-select" class="form-label">Logo</label>
                                                        <div class="form-group">
                                                            <div class="image-upload">
                                                                <div class="thumb">
                                                                    <div class="avatar-preview">
                                                                        <div class="profilePicPreview"
                                                                            style="background-image: url({{ asset('uploads/logo/' . $generals->logo) }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                                                            <button type="button" class="remove-image"><i
                                                                                    class="fa fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="avatar-edit">
                                                                        <input type="file" class="profilePicUpload"
                                                                            name="logo" id="profilePicUpload1"
                                                                            accept=".png, .jpg, .jpeg">
                                                                        <label for="profilePicUpload1"
                                                                            class="btn bg--primary text-dark">@lang('Browse Logo')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="example-select" class="form-label">Favicon</label>
                                                        <div class="form-group">
                                                            <div class="image-upload">
                                                                <div class="thumb">
                                                                    <div class="avatar-preview">
                                                                        <div class="profilePicPreview"
                                                                            style="background-image: url({{ asset('uploads/favicon/' . $generals->favicon) }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                                                            <button type="button" class="remove-image"><i
                                                                                    class="fa fa-times"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="avatar-edit">
                                                                        <input type="file" class="profilePicUpload"
                                                                            name="favicon" id="profilePicUpload3"
                                                                            accept=".png, .jpg, .jpeg">
                                                                        <label for="profilePicUpload3"
                                                                            class="btn bg--primary text-dark">@lang('Browse Favicon')</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="text-end">
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light">Save</button>
                                    </div>
                                </form>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div><!-- end col -->
        </div>
        <!-- end row-->
    </div> <!-- container -->


    <!-- Create Modal -->
    <div id="addCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h4>Create New Customer Type</h4>
                    </div>
                    <form class="px-3" action="{{ route('setting.customer.type.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="catename" class="form-label">Type Name</label>
                            <input class="form-control" type="text" id="catename" name="name" required
                                placeholder="Type Name">
                        </div>
                        <div class="mb-3 d-flex">
                            <label for="createcatstatus">Status</label>
                            <label class="switch m-0">
                                <input id="createcatstatus" type="checkbox" class="toggle-switch" name="status">
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
                    <h4>Edit Customer Type</h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="catename" class="form-label">Type Name</label>
                        <input class="form-control" type="text" id="catename" name="name" required
                            placeholder="Type Name">
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
