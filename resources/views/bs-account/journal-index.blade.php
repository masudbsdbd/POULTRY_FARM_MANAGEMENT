@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => 'Journals', 'subtitle' => 'Journals'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title">{{ $pageTitle }}</h4>
                            @can('product-create')
                                <a href="{{ route('bs.account.journal.create') }}"
                                    class="mb-2 btn btn-primary waves-effect waves-light">Add
                                    New</a>
                            @endcan

                        </div>
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>

                                    <th class="text-center">Date</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Debit/Credit</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Description</th>
                                 {{--   <th class="text-end">Action</th>--}}
                                </tr>
                            </thead>


                            <tbody>
                                @foreach ($allJournalEntry as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ showDateTime($item->entry_date, true) }}</td>
                                        <td class="text-center">{{ $item->code }}</td>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">
                                            @if ($item->type == 1)
                                            <span class="badge badge-soft-dark">Assets</span>
                                            @elseif($item->type == 2)
                                            <span class="badge badge-soft-dark">Libilities</span>
                                            @elseif($item->type == 3)
                                            <span class="badge badge-soft-dark">Equity</span>
                                            @elseif($item->type == 4)
                                            <span class="badge badge-soft-dark">Expense</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->debit_or_credit == 'debit')
                                                <span class="badge badge-soft-danger">Debit</span>
                                            @else
                                                <span class="badge badge-soft-success">Credit</span>    
                                            @endif
                                        </td>
                                        <td class="text-center">{{ showAmount($item->amount) }} Tk</td>
                                        <td class="text-center">
                                            
                                        {{ substr($item->description, 0, 24) }}...
                                        <a data-paragraph="{{ $item->description }}" href="javascript:void(0)"
                                            class="text-primary descBtn" href="">
                                            See More</a>
                                        </td>
                                       {{-- <td class="text-end">
                                            @can('product-edit')
                                                <a href="{{ route('product.edit', $item->id) }}"
                                                    class="btn btn-primary waves-effect waves-light">
                                                    <i class="mdi mdi-grease-pencil"></i></a>
                                            @endcan

                                            @can('product-delete')
                                                <button type="button"
                                                    class="btn btn-danger waves-effect waves-light confirmationBtn"
                                                    data-question="@lang('Are you sure to delete this product?')"
                                                    data-action="{{ route('product.delete', $item->id) }}"><i
                                                        class="mdi mdi-trash-can-outline"></i></button>
                                            @endcan

                                        </td>--}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    <div class="d-flex justify-content-end mt-3">{{ $allJournalEntry->links() }}</div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->

        <div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Account Description</h4>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
                "use strict";

                $(document).on('click', '.descBtn', function() {
                    var modal = $('#descModal');
                    let data = $(this).data();
                    modal.find('.descParagraph').text(`${data.paragraph}`);
                    modal.modal('show');
                });

            })(jQuery);
        });
    </script>   
@endsection
