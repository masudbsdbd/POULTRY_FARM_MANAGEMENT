@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'All Batches', 'subtitle' => 'Batches'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between {{ Route::is('customer.index') ? '' : 'mb-3' }}">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        @if (Route::is('customer.batches'))
                        <a href="{{ route('customer.createBatch', $customerInfo->id) }}"
                            class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add New Batch</a>
                        @endif
                    </div>


                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Batch Name</th>
                                <th class="text-center">Batch Number</th>
                                <th class="text-center">Chicken Type</th>
                                <th class="text-center">Total Chickens</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">Close Date</th>
                                <th class="text-center">Status</th>
                                {{-- <th class="text-end">Action</th> --}}
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($batches as $batch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $batch->batch_name }}</td>
                                <td class="text-center">{{ $batch->batch_number ?? '-' }}</td>
                                <td class="text-center">{{ ucfirst($batch->chicken_type) }}</td>
                                <td class="text-center">{{ $batch->total_chickens }}</td>
                                <td class="text-center">{{ $batch->chicken_grade ?? '-' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($batch->batch_start_date)->format('d M, Y') }}</td>
                                <td class="text-center">{{ $batch->batch_close_date ? \Carbon\Carbon::parse($batch->batch_close_date)->format('d M, Y') : '-' }}</td>
                                <td class="text-center">
                                    @if($batch->status == 'active')
                                        <span class="badge badge-soft-primary">Active</span>
                                    @else
                                        <span class="badge badge-soft-dark">Inactive</span>
                                    @endif
                                </td>
                                {{-- <td class="text-end">
                                    <a href="{{ route('poultrybatch.edit', $batch->id) }}" class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-grease-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="Are you sure to delete this batch?"
                                        data-action="{{ route('poultrybatch.destroy', $batch->id) }}">
                                        <i class="mdi mdi-trash-can-outline"></i>
                                    </button>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- <div class="d-flex justify-content-end mt-3">{{ $customers->links() }}</div> --}}

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