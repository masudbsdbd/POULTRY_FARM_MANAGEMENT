@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Purchase'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        <a href="{{ route('supplier-return.create') }}"
                            class="mb-2 btn btn-primary waves-effect waves-light">Add
                            New</a>
                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Purchase Batch</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-center">Total Return Price</th>
                                <th class="text-center">Date</th>
                                {{--<th class="text-center">Action</th>--}}
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($supplierReturns as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ @$item->supplier->name }}</td>
                                <td class="text-center">{{ @$item->purchaseBatch->batch_code }}</td>
                                <td class="text-center">
                                    @php
                                    $productCount = $item->total_qty;
                                    @endphp
                                    {{ $productCount }}
                                    {{ $productCount == 0 || $productCount == 1 ? 'Unit' : 'Units' }}
                                </td>
                                <td class="text-center">{{ showAmount($item->total_return_price) }} Tk</td>
                                <td class="text-center">{{ showDateTime($item->entry_date, true) }}</td>
                                {{--<td class="text-center">
                                    <a href="{{ route('supplier-return.edit', $item->id) }}"
                                class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-grease-pencil"></i></a>
                                <button type="button"
                                    class="btn btn-danger waves-effect waves-light confirmationBtn"
                                    data-question="@lang('Are you sure to delete this sell?')"
                                    data-action="{{ route('supplier-return.delete', $item->id) }}"><i
                                        class="mdi mdi-trash-can-outline"></i></button>
                                </td>--}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $supplierReturns->links() }}</div>

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