@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css', 'node_modules/select2/dist/css/select2.min.css', 'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', [
            'title' => $pageTitle,
            'subtitle' => $pageTitle,
        ])

        <div class="card">
            <div class="card-body">
            <div class="text-end">
                <a href="{{ route('asset.head.create') }}" class="btn btn-primary waves-effect waves-light">Add
                    New</a>
            </div>
                <h4 class="header-title">{{ $pageTitle }}</h4>


                <p>
                    @if (request('date'))
                        Search Date: ( {{ request('date') }} )
                    @elseif(request('range'))
                        Search Date: ( {{ request('range') }} )
                    @endif
                </p>

                <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th class="text-center">SL</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Asset Name</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        @php
                            $totalAssetAmount = 0;
                        @endphp
                        @foreach ($assetHeads as $item)
                            @php
                                $totalAssetAmount += $item->purchase_price;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ showDateTime($item->created_at) }}</td>

                                <td class="text-center">
                                    {{ $item->name }} 
                                </td>

                                <td class="text-center">
                                    {{ $item->description }}
                                </td>

                                <td class="text-end">

                                    <a href="{{ route('asset.head.edit', $item->id) }}"
                                        class="btn btn-blue waves-effect waves-light"><i
                                            class="mdi mdi-grease-pencil"></i></a>

                                        <button type="button"
                                            class="btn btn-danger waves-effect waves-light confirmationBtn"
                                            data-question="@lang('Are you sure to delete this sell?')"
                                            data-action="{{ route('asset.head.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
                <div class="d-flex justify-content-end mt-3">{{ $assetHeads->links() }}</div>

            </div> <!-- end card body-->
        </div> <!-- end card -->
        <!-- end row-->
    </div> <!-- container -->


    <div id="purchaseDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2">Account's Payable Description</h4>
                            <p id="accPayableDesc"></p>
                        </div>
                    </div> <!-- end card -->
                    <div class="text-end">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@section('script')
    @vite(['resources/js/app.js', 'resources/js/pages/form-pickers.init.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])

@endsection
