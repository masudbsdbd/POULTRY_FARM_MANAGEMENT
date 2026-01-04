@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => $pageTitle])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @can('sell-return-create')
                        <a href="{{ route('customer-return.create') }}" class="mb-2 btn btn-primary waves-effect waves-light">Add
                            New</a>
                        @endcan
                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-center">Total Return Price</th>
                                <th class="text-center">Date</th>
                                {{--<th class="text-center">Status</th>
                                <th class="text-end">Action</th>--}}
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($customerReturns as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ @$item->customer->name }}</td>
                                <td class="text-center">
                                    {{ $item->total_qty }} units
                                </td>
                                <td class="text-center">{{ showAmount($item->total_return_price) }} Tk</td>
                                <td class="text-center">{{ showDateTime($item->entry_date, true) }}</td>
                                {{--<td class="text-center">
                                    @if ($item->status == 0)
                                    <span class="badge badge-soft-dark">Pending</span>
                                    @else
                                    <span class="badge badge-soft-success">Completed</span>
                                    @endif
                                </td>--}}
                                {{--<td class="text-end">
                                    @can('sell-return-edit')
                                    <a href="{{ route('customer-return.edit', $item->id) }}"
                                class="btn btn-primary waves-effect waves-light">
                                <i class="mdi mdi-grease-pencil"></i></a>
                                @endcan
                                @can('sell-return-delete')
                                <button type="button"
                                    class="btn btn-danger waves-effect waves-light confirmationBtn"
                                    data-question="@lang('Are you sure to delete this sell?')"
                                    data-action="{{ route('customer-return.delete', $item->id) }}"><i
                                        class="mdi mdi-trash-can-outline"></i></button>
                                @endcan

                                </td>--}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $customerReturns->links() }}</div>

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