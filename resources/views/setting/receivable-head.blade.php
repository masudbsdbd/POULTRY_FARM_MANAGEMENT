@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title', ['title' => $pageTitle, 'subtitle' => 'Settings'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title">{{ $pageTitle }}</h4>
                            <button type="button" class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add
                                New</button>
                        </div>
                        <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($heads as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">
                                            {{-- {{ $item->description }} --}}

                                            @if (strlen($item->description) < 25)
                                                {{ $item->description }}
                                            @else
                                                {{ substr($item->description, 0, 24) }}...
                                                <a data-paragraph="{{ $item->description }}" href="javascript:void(0)"
                                                    class="text-primary descBtn" href="">
                                                    See More</a>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button"
                                                class="btn btn-primary waves-effect waves-light editHeadBtn"
                                                data-name="{{ $item->name }}"
                                                data-description="{{ $item->description }}"
                                                data-route="{{ route('payable.store', $item->id) }}"><i
                                                    class="mdi mdi-grease-pencil"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">{{ $heads->links() }}</div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div> <!-- container -->


    <!-- Create Modal -->
    <div id="addHeadModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h4>Create New Accounts Receivable Head</h4>
                    </div>
                    <form class="px-3" action="{{ route('receivable.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="catename" class="form-label">Head Name</label>
                            <input class="form-control" type="text" id="catename" name="name" required
                                placeholder="Type Name">
                        </div>

                        <div class="mb-3">
                            <label for="catename" class="form-label">Description</label>
                            <textarea name="description" rows="5" class=" form-control"></textarea>
                        </div>

                        <div class="mb-3 text-end">
                            <button class="btn btn-primary" type="submit">Create</button>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Edit Modal -->
    <div id="editHeadModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Edit Customer Type</h4>
                </div>
                <form id="editForm" class="px-3" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="catename" class="form-label">Head Name</label>
                        <input class="form-control" type="text" id="catename" name="name" required
                            placeholder="Type Name">
                    </div>

                    <div class="mb-3">
                        <label for="catename" class="form-label">Description</label>
                        <textarea id="headDesc" name="description" rows="5" class=" form-control"></textarea>
                    </div>

                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

    <div id="descModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Description</h4>
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

                $('.createCatBtn').on('click', function() {
                    var modal = $('#addHeadModal');
                    modal.modal('show');
                });

                $(document).on('click', '.descBtn', function() {
                    var modal = $('#descModal');
                    let data = $(this).data();
                    modal.find('.descParagraph').text(`${data.paragraph}`);
                    modal.modal('show');
                });

                $('.editHeadBtn').on('click', function() {
                    var modal = $('#editHeadModal');
                    let data = $(this).data();
                    console.log(data);
                    let url = data.route;
                    $('#editForm').attr('action', url);
                    modal.find('input[name="name"]').val(data.name);
                    $('#headDesc').val(data.description);

                    modal.modal('show');
                });
            })(jQuery);
        });
    </script>
@endsection
