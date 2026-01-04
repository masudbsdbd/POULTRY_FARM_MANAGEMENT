@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Investors', 'subtitle' => 'Investors'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-end">
                             @can('investor-create')
                        <a href="{{ route('investor.create') }}" class="btn btn-primary waves-effect waves-light">Add
                            New</a>
                            @endcan

                    </div>
                    <h4 class="header-title">{{ $pageTitle }}</h4>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Amount</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($investors as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ showDateTime($item->entry_date) }}</td>

                                <td class="text-center">
                                    {{ $item->name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->email }}
                                </td>

                                <td class="text-center">{{ showAmount($item->amount) }} Tk</td>

                                <td class="text-end">
                                    @can('investor-edit')

                                    <a href="{{ route('investor.edit', $item->id) }}"
                                        class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-table-edit"></i></a>
                                        @endcan
                                    @can('investor-delete')
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this Investor?')"
                                        data-action="{{ route('investor.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>
                                        @endcan



                                </td>


                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->

<div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Expense Title</h4>
                @csrf
                <p class="descParagraph"></p>
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
@vite(['resources/js/app.js', 'resources/js/pages/datatables.init.js', 'resources/js/pages/form-advanced.init.js'])

@endsection