@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Income Head', 'subtitle' => 'Income Head'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        <a href="{{ route('income.list.create') }}" class="mb-2 btn btn-primary waves-effect waves-light">Add
                            New</a>
                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Name</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($incomeLists as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="text-center">{{ $item->name }}</td>

                                <td class="text-end">
                                    <button title="View" type="button"
                                        class="btn btn-success waves-effect waves-light expenseHeadBtn"
                                        data-id="{{ $item->id }}"
                                        data-created_at="{{ showDateTime($item->created_at) }}"
                                        data-name="{{ $item->name }}"
                                        data-details="{{ $item->details }}">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                    <a href="{{ route('income.list.edit', $item->id) }}"
                                        class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-grease-pencil"></i></a>
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this Income List?')"
                                        data-action="{{ route('income.list.delete', $item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i></button>


                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $incomeLists->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->


<div id="sellDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header text-white">
                <h5 class="modal-title">Income List Details</h5>
                <!-- Close button removed -->
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover mb-4">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="modalDate">---</td>
                                    <td id="modalName">---</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <h6><strong>Description:</strong></h6>
                            <p id="modalDescription">No description available.</p>
                        </div>
                    </div>
                </div> <!-- end card -->
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



            $(document).on('click', '.expenseHeadBtn', function() {
                var modal = $('#sellDetail');

                var createdAt = $(this).data('created_at');
                var name = $(this).data('name');
                var details = $(this).data('details');

                modal.find('#modalDate').text(createdAt);
                modal.find('#modalName').text(name);
                modal.find('#modalDescription').text(details || 'No description available.');

                modal.modal('show');
            });




        })(jQuery);

    });
</script>
@endsection