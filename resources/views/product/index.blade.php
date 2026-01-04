@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'All Products', 'subtitle' => 'Products'])
    <style>
        .description-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @can('product-create')
                        <a href="{{ route('product.create') }}" class="mb-2 btn btn-primary waves-effect waves-light">Add
                            New</a>
                        @endcan

                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Image</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    <img class="rounded"
                                        src="{{ isset($item->image) ? asset('uploads/products/' . $item->image) : asset('uploads/dummy-product.png') }}"
                                        width="50" alt="Product Image">
                                </td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center description-cell">
                                    {{ $item->description }}
                                </td>
                                <td class="text-center">{{ number_format($item->price, 2, '.', '') }} Tk</td>
                                <td class="text-center">
                                    @if ($item->status == 1)
                                    <span class="badge badge-soft-primary">Activated</span>
                                    @else
                                    <span class="badge badge-soft-dark">Deactivated</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    {{-- <!-- @can('product-list') --}}

                                    <a href="{{ route('product.print.barcode', $item->id) }}" target="_blank"
                                        class="btn btn-dark waves-effect waves-light">
                                        <i class="fas fa-barcode"></i>
                                    </a>
                                    {{-- @endcan --}}
                                    {{-- @can('product-edit') --> --}}



                                    <a href="{{ route('product.edit', $item->id) }}"
                                        class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-grease-pencil"></i></a>
                                    {{-- @endcan --}}

                                    {{-- @can('product-delete') --}}
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this product?')"
                                        data-action="{{ route('product.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                    {{-- @endcan --}}


                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $products->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->


<x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])
@endsection