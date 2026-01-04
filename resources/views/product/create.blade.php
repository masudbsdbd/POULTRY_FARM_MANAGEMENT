@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Product'])

    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($product) ? route('product.store', $product->id) : route('product.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="example-select" class="form-label">Product Image</label>
                                            <div class="form-group">
                                                <div class="image-upload">
                                                    <div class="thumb">
                                                        <div class="avatar-preview">
                                                            <div class="profilePicPreview"
                                                                style="background-image: url({{ isset($product) ? (isset($product->image) ? asset('uploads/products/' . $product->image) : asset('uploads/default-picker.png')) : asset('uploads/default-picker.png') }})">
                                                                <button type="button" class="remove-image"><i
                                                                        class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="avatar-edit">
                                                            <input type="file" class="profilePicUpload"
                                                                name="image" id="profilePicUpload1"
                                                                accept=".png, .jpg, .jpeg">
                                                            <label for="profilePicUpload1"
                                                                class="btn bg--primary text-dark">@lang('Browse Image')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Name</label>
                                    <input type="text" id="simpleinput" class="form-control"
                                        placeholder="Name" name="name" value="{{ old('name', isset($product) ? $product->name : '') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Unit</label>
                                    <select class="form-select" name="unit_id" required>
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $key => $unit)
                                        <option value="{{ $unit->id }}"
                                            @selected(isset($product) ? ($unit->id == $product->unit_id ? true : false) : false)>
                                            {{ $unit->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Product Price</label>
                                    <input type="number" id="price" class="form-control" placeholder="price"
                                        name="price" min="0" step=".01"
                                        value="{{ old('price', isset($product) ? number_format($product->price, 2, '.', '') : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="example-textarea" class="form-label">Description</label>
                                    <textarea class="form-control" id="example-textarea" placeholder="description" rows="5" name="description">{{ old('price', isset($product) ? $product->description : '') }}</textarea>
                                </div>

                                <div class="mb-3 d-flex">
                                    <label for="status">Status</label>
                                    <label class="switch m-0">
                                        <input id="status" type="checkbox" class="toggle-switch" name="status"
                                            @checked(isset($product) ? ($product->status == 1 ? true : false) : true)>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="text-end">
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">{{ isset($product) ? 'Update Product' : 'Add New Product' }}</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        $('#catSelect').on('change', function() {
            catid = $(this).val();
            $.ajax({
                url: "{{ route('subcategory.ajax') }}",
                method: 'GET',
                data: {
                    id: catid
                },
                dataType: 'json',
                success: function(response) {
                    let optionHtml = '';
                    $('#subCatSelect').empty();

                    if (response.length === 0) {
                        $('#subCatSelect').append(
                            '<option value="">Sub Category not found</option>');
                    } else {
                        $('#subCatSelect').append(
                            '<option value="">Select Sub Category</option>');
                        response.forEach(option => {
                            optionHtml +=
                                `<option value="${option.id}">${option.name}</option>`;
                        });
                    }

                    $('#subCatSelect').append(optionHtml);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>
@endsection