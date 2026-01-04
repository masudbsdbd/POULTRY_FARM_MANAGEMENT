@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css','node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => 'Create Asset', 'subtitle' => 'Asset'])

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ $pageTitle }}</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ isset($assetHead) ? route('asset.head.store', $assetHead->id) : route('asset.head.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">Head Name</label>
                                                <input type="text" id="simpleinput" class="form-control" placeholder="Name"
                                                    name="name" 
                                                value="{{ isset($assetHead) ? $assetHead->name : '' }}">

                                            </div>

                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="example-textarea" class="form-label">Description</label>
                                        <textarea class="form-control" id="example-textarea" placeholder="Description" rows="5" name="description" required>{{ isset($assetHead) ? $assetHead->description : '' }}</textarea>
                                    </div>
                                    <div class="text-end">
                                            <button type="submit"
                                            class="btn btn-primary waves-effect waves-light">{{ isset($assetHead) ? 'Update Head' : 'Add Head' }}</button>
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
    @vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js', 'resources/js/pages/form-pickers.init.js'])

@endsection
