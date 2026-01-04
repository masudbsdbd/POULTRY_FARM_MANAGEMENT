@extends('layouts.vertical', ['title' => 'Datatables'])

@section('css')
@vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css', 'node_modules/mohithg-switchery/dist/switchery.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Floor', 'subtitle' => 'Floors'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="p-3">
                    <form action="{{ route('floor.index') }}" method="get">
                        <div class="row">
                            <div class="col-md-2">
                                <p class="fw-bold text-muted">Search Building</p>
                                <select class="form-select" id="building_id" name="building_id">
                                    <option value="">Select Building</option>
                                    @foreach ($buildings as $building)
                                        <option {{ isset($building_id) && $building_id == $building->id ? 'selected' : '' }} value="{{ $building->id }}">
                                            {{ $building->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100" style="margin-top: 36px;">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="header-title">{{ $pageTitle }}</h4>
                        {{-- @can('unit-create') --}}
                        <button type="button" class="mb-2 btn btn-primary waves-effect waves-light createCatBtn">Add
                            New</button>
                            {{-- @endcan --}}

                    </div>
                    <table id="basic-datatables" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Building Name</th>
                                <th class="text-center">Name</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($floors as $floor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ isset($floor->building) ? $floor->building->name : "N/A" }}</td>
                                <td class="text-center">{{ $floor->name }}</td>
                                
                                <td class="text-end">


                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light editCatBtn"
                                        data-name="{{ $floor->name }}"
                                        data-building-id="{{ $floor->building_id }}"
                                        data-route="{{ route('floor.store', $floor->id) }}"><i class="mdi mdi-grease-pencil"></i></button>


                                    @if($floor->challanItems->isEmpty())
                                    <button type="button"
                                        class="btn btn-danger waves-effect waves-light confirmationBtn"
                                        data-question="@lang('Are you sure to delete this Floor?')"
                                        data-action="{{ route('floor.delete', $floor->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">{{ $floors->links() }}</div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->


<!-- Create Modal -->
<div id="addCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <h4>Create New Floor</h4>
                </div>
                <form class="px-3" action="{{ route('floor.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="building_id" id="select-buildings" class="form-control" data-toggle="select2"
                                        data-width="100%">
                            <option value="">Select</option>
                            @foreach ($buildings as $item)
                                {{-- @php
                                    $item->name;
                                @endphp --}}
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="catename" class="form-label">Floor Name</label>
                        <input class="form-control" type="text" id="catename" name="name" required
                            placeholder="Floor Name">
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
<div id="editCatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Edit Floor</h4>
            </div>
            <form id="editForm" class="px-3" action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="catename" class="form-label">Floor Name</label>
                    <select name="building_id" class="form-control" data-toggle="select2"
                                        data-width="100%">
                            <option value="">Select</option>
                            @foreach ($buildings as $item)
                                <option {{ isset($building_id) && $building_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                </div>
                <div class="mb-3">
                    <input class="form-control" type="text" id="catename" name="name" required
                        placeholder="Floor Name">
                </div>
                <div class="mb-3 text-end">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
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
                var modal = $('#addCatModal');
                modal.modal('show');
            });

            $('.editCatBtn').on('click', function() {
                var modal = $('#editCatModal');
                let data = $(this).data();
                let url = data.route;
                $('#editForm').attr('action', url);
                modal.find('input[name="name"]').val(data.name);
                
                // make selected building
                modal.find('select[name="building_id"]').val(data.buildingId); 


                if (data.status == 1) {
                    modal.find('input[name="editcatstatus"]').prop('checked', true);
                }

                if (data.status == 0) {
                    modal.find('input[name="editcatstatus"]').prop('checked', false);
                }
                modal.modal('show');
            });
        })(jQuery);
    });
</script>
@endsection