@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => 'Create Receivable', 'subtitle' => 'Receivable'])

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ route('accounts.receivable.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="example-select" class="form-label">Account's Head</label>
                                                <select class="form-select" name="head" required>
                                                    <option value="">Select Head</option>
                                                    @foreach ($heads as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">Receivable Amount</label>
                                                <input type="number" id="simpleinput" class="form-control"
                                                    placeholder="Receivable Amount" name="receivable_amount" step="any" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="example-textarea" class="form-label">Description</label>
                                        <textarea class="form-control" id="example-textarea" placeholder="description" rows="5" name="description">{{ old('price', isset($product) ? $product->description : '') }}</textarea>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Add
                                            Account's Receivable</button>
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
